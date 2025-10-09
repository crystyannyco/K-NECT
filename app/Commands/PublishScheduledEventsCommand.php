<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\EventModel;
use App\Models\UserModel;
use App\Models\BarangayModel;
use App\Controllers\GoogleCalendarController;

class PublishScheduledEventsCommand extends BaseCommand
{
    protected $group       = 'Events';
    protected $name        = 'events:publish-scheduled';
    protected $description = 'Publish scheduled events and send SMS notifications';

    public function run(array $params)
    {
        $eventModel = new EventModel();
        $userModel = new UserModel();
        
        // Find events that are scheduled and due for publishing
        $scheduledEvents = $eventModel->where('status', 'Scheduled')
                                     ->where('scheduling_enabled', 1)
                                     ->where('scheduled_publish_datetime <=', date('Y-m-d H:i:s'))
                                     ->findAll();
        
        if (empty($scheduledEvents)) {
            CLI::write('No scheduled events to publish.', 'yellow');
            return;
        }
        
        CLI::write('Found ' . count($scheduledEvents) . ' scheduled events to publish.', 'green');
        
        foreach ($scheduledEvents as $event) {
            try {
                // Update event status to Publishing
                $eventModel->update($event['event_id'], ['status' => 'Publishing']);
                
                // Publish the event (sync to Google Calendar)
                $this->publishEvent($event, $eventModel);
                
                // Send SMS notifications if enabled
                if ($event['sms_notification_enabled']) {
                    $this->sendSmsNotifications($event, $userModel);
                }
                
                // Update event status to Published
                $eventModel->update($event['event_id'], [
                    'status' => 'Published',
                    'publish_date' => date('Y-m-d H:i:s')
                ]);
                
                CLI::write("Successfully published event: {$event['title']}", 'green');
                
            } catch (\Exception $e) {
                // Update event status to Failed
                $eventModel->update($event['event_id'], ['status' => 'Failed']);
                CLI::error("Failed to publish event: {$event['title']} - " . $e->getMessage());
            }
        }
    }
    
    private function publishEvent($event, $eventModel)
    {
        // Sync to Google Calendar
        if ($event['barangay_id'] == 0) {
            $calendarId = 'knect.system@gmail.com';
        } else {
            $barangayModel = new BarangayModel();
            $barangay = $barangayModel->find($event['barangay_id']);
            if (is_object($barangay)) {
                $barangay = $barangay->toArray();
            }
            $calendarId = $barangay ? $barangay['google_calendar_id'] : 'knect.system@gmail.com';
        }

        $startDT = new \DateTime($event['start_datetime'], new \DateTimeZone('Asia/Manila'));
        $endDT = new \DateTime($event['end_datetime'], new \DateTimeZone('Asia/Manila'));
        $startRFC = $startDT->format('c');
        $endRFC = $endDT->format('c');

        $googleEventData = [
            'summary' => $event['title'],
            'description' => $event['description'],
            'location' => $event['location'],
            'start' => ['dateTime' => $startRFC, 'timeZone' => 'Asia/Manila'],
            'end' => ['dateTime' => $endRFC, 'timeZone' => 'Asia/Manila'],
        ];

        log_message('info', "[GCAL SYNC] Publishing scheduled event to Google Calendar. CalendarID: {$calendarId} | Event: {$event['title']}");
        
        $googleCalendar = new GoogleCalendarController();
        $googleEventId = $googleCalendar->addEventToGoogleCalendar($calendarId, $googleEventData);

        if ($googleEventId) {
            $eventModel->update($event['event_id'], ['google_event_id' => $googleEventId]);
            log_message('info', "[GCAL SYNC] Success! Google Event ID: {$googleEventId}");
            CLI::write("Event synced to Google Calendar: {$event['title']}", 'green');
        } else {
            log_message('error', "[GCAL SYNC] FAILED to sync event to Google Calendar. CalendarID: {$calendarId} | Event: {$event['title']}");
            CLI::error("Failed to sync event to Google Calendar: {$event['title']}");
            throw new \Exception('Failed to sync event to Google Calendar');
        }
    }
    
    private function sendSmsNotifications($event, $userModel)
    {
        $recipients = $this->getSmsRecipients($event, $userModel);
        
        if (empty($recipients)) {
            CLI::write('No SMS recipients found for event: ' . $event['title'], 'yellow');
            return;
        }
        
        $message = $this->formatSmsMessage($event);
        
        foreach ($recipients as $recipient) {
            if (!empty($recipient['phone_number'])) {
                try {
                    send_sms($recipient['phone_number'], $message);
                    CLI::write("SMS sent to: {$recipient['phone_number']}", 'green');
                } catch (\Exception $e) {
                    CLI::error("Failed to send SMS to {$recipient['phone_number']}: " . $e->getMessage());
                }
            }
        }
    }
    
    private function getSmsRecipients($event, $userModel)
    {
        $recipients = [];
        $recipientRoles = json_decode($event['sms_recipient_roles'], true) ?? [];
        
        // Separate City-Level Officials from Barangay-Level roles
        $cityLevelRoles = [];
        $barangayLevelRoles = [];
        
        foreach ($recipientRoles as $role) {
            if (in_array($role, [
                'all_pederasyon_officials', 
                'pederasyon_officers',
                'pederasyon_members',
                'pederasyon_president', 
                'pederasyon_vice_president',
                'pederasyon_secretary', 
                'pederasyon_treasurer', 
                'pederasyon_auditor',
                'pederasyon_pro', 
                'pederasyon_sergeant'
            ])) {
                $cityLevelRoles[] = $role;
            } else {
                $barangayLevelRoles[] = $role;
            }
        }
        
        // 1. Handle City-Level Officials (Pederasyon) - No barangay filtering
        if (!empty($cityLevelRoles)) {
            $cityQuery = $userModel->select('user.*, address.barangay as barangay_id, barangay.name as barangay_name')
                                  ->join('address', 'address.user_id = user.id', 'left')
                                  ->join('barangay', 'barangay.barangay_id = address.barangay', 'left');
            
            $cityRoleConditions = [];
            foreach ($cityLevelRoles as $role) {
                switch ($role) {
                    case 'all_pederasyon_officials':
                        $cityRoleConditions[] = "((user.user_type = 3 AND user.ped_position IS NOT NULL) OR (user.user_type = 2 AND user.position = 1))";
                        break;
                    case 'pederasyon_officers':
                        $cityRoleConditions[] = "(user.user_type = 3 AND user.ped_position IS NOT NULL)";
                        break;
                    case 'pederasyon_members':
                        $cityRoleConditions[] = "(user.user_type = 2 AND user.position = 1)";
                        break;
                    case 'pederasyon_president':
                        $cityRoleConditions[] = "(user.user_type = 3 AND user.ped_position = 1)";
                        break;
                    case 'pederasyon_vice_president':
                        $cityRoleConditions[] = "(user.user_type = 3 AND user.ped_position = 2)";
                        break;
                    case 'pederasyon_secretary':
                        $cityRoleConditions[] = "(user.user_type = 3 AND user.ped_position = 3)";
                        break;
                    case 'pederasyon_treasurer':
                        $cityRoleConditions[] = "(user.user_type = 3 AND user.ped_position = 4)";
                        break;
                    case 'pederasyon_auditor':
                        $cityRoleConditions[] = "(user.user_type = 3 AND user.ped_position = 5)";
                        break;
                    case 'pederasyon_pro':
                        $cityRoleConditions[] = "(user.user_type = 3 AND user.ped_position = 6)";
                        break;
                    case 'pederasyon_sergeant':
                        $cityRoleConditions[] = "(user.user_type = 3 AND user.ped_position = 7)";
                        break;
                }
            }
            
            if (!empty($cityRoleConditions)) {
                $cityRoleQuery = '(' . implode(' OR ', $cityRoleConditions) . ')';
                $cityQuery->where($cityRoleQuery);
            }
            
            $cityQuery->where('user.phone_number IS NOT NULL')
                      ->where('user.phone_number !=', '')
                      ->where('user.is_active', 1)
                      ->where('user.status', 2);
            
            $cityResults = $cityQuery->findAll();
            
            foreach ($cityResults as $user) {
                $recipients[$user['id']] = $user;
            }
        }
        
        // 2. Handle Barangay-Level Officials - Apply barangay filtering
        if (!empty($barangayLevelRoles)) {
            $barangayQuery = $userModel->select('user.*, address.barangay as barangay_id, barangay.name as barangay_name')
                                      ->join('address', 'address.user_id = user.id', 'left')
                                      ->join('barangay', 'barangay.barangay_id = address.barangay', 'left');
            
            // Apply barangay filtering for Barangay-Level roles
            if ($event['barangay_id'] == 0 && isset($event['sms_recipient_scope']) && $event['sms_recipient_scope']) {
                if ($event['sms_recipient_scope'] === 'specific_barangays') {
                    $selectedBarangays = json_decode($event['sms_recipient_barangays'], true) ?? [];
                    if (!empty($selectedBarangays)) {
                        $barangayQuery->whereIn('address.barangay', $selectedBarangays);
                    }
                }
            } else {
                $barangayQuery->where('address.barangay', $event['barangay_id']);
            }
            
            $barangayRoleConditions = [];
            foreach ($barangayLevelRoles as $role) {
                switch ($role) {
                    case 'all_sk_officials':
                    case 'all_officials':
                        $barangayRoleConditions[] = "(user.user_type = 2 OR user.user_type = 3)";
                        break;
                    case 'sk_chairperson':
                    case 'chairperson':
                        $barangayRoleConditions[] = "((user.user_type = 2 AND user.position = 1) OR user.user_type = 3)";
                        break;
                    case 'sk_secretary':
                    case 'secretary':
                        $barangayRoleConditions[] = "(user.user_type = 2 AND user.position = 2)";
                        break;
                    case 'sk_treasurer':
                    case 'treasurer':
                        $barangayRoleConditions[] = "(user.user_type = 2 AND user.position = 3)";
                        break;
                    case 'sk_members':
                        // SK Councilor: user_type=2 AND position=4
                        $barangayRoleConditions[] = "(user.user_type = 2 AND user.position = 4)";
                        break;
                    case 'kk_members':
                        $barangayRoleConditions[] = "(user.user_type = 1)";
                        break;
                }
            }
            
            if (!empty($barangayRoleConditions)) {
                $barangayRoleQuery = '(' . implode(' OR ', $barangayRoleConditions) . ')';
                $barangayQuery->where($barangayRoleQuery);
            }
            
            $barangayQuery->where('user.phone_number IS NOT NULL')
                          ->where('user.phone_number !=', '')
                          ->where('user.is_active', 1)
                          ->where('user.status', 2);
            
            $barangayResults = $barangayQuery->findAll();
            
            foreach ($barangayResults as $user) {
                $recipients[$user['id']] = $user;
            }
        }
        
        return array_values($recipients);
    }
    
    private function formatSmsMessage($event)
    {
        $startDateTime = new \DateTime($event['start_datetime']);
        $endDateTime = new \DateTime($event['end_datetime']);
        
        $startDate = $startDateTime->format('F d, Y');
        $endDate = $endDateTime->format('F d, Y');
        $startTime = $startDateTime->format('h:i A');
        $endTime = $endDateTime->format('h:i A');
        
        $message = "NEW EVENT: {$event['title']}\n";
        
        // Show date range if event spans multiple days
        if ($startDate !== $endDate) {
            $message .= "Start: {$startDate} at {$startTime}\n";
            $message .= "End: {$endDate} at {$endTime}\n";
        } else {
            // Same day event
            $message .= "Date: {$startDate}\n";
            $message .= "Time: {$startTime} - {$endTime}\n";
        }
        
        $message .= "Location: {$event['location']}\n";
        $message .= "Description: {$event['description']}";
        
        return $message;
    }
}
