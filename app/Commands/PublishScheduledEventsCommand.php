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
        
        // Build query based on recipient scope and roles
        $query = $userModel->select('user.*, barangay.name as barangay_name')
                          ->join('barangay', 'barangay.barangay_id = user.barangay_id', 'left');
        
        // Handle recipient scope for superadmin
        if ($event['barangay_id'] == 0 && $event['sms_recipient_scope']) {
            if ($event['sms_recipient_scope'] === 'specific_barangays') {
                $selectedBarangays = json_decode($event['sms_recipient_barangays'], true) ?? [];
                if (!empty($selectedBarangays)) {
                    $query->whereIn('user.barangay_id', $selectedBarangays);
                }
            }
            // For 'all_barangays', no additional filter needed
        } else {
            // For regular events, only include users from the same barangay
            $query->where('user.barangay_id', $event['barangay_id']);
        }
        
        // Filter by roles
        if (!empty($recipientRoles)) {
            $roleConditions = [];
            foreach ($recipientRoles as $role) {
                switch ($role) {
                    case 'all_officials':
                        $roleConditions[] = "user.position LIKE '%SK%'";
                        break;
                    case 'chairperson':
                        $roleConditions[] = "user.position LIKE '%Chairperson%'";
                        break;
                    case 'secretary':
                        $roleConditions[] = "user.position LIKE '%Secretary%'";
                        break;
                    case 'treasurer':
                        $roleConditions[] = "user.position LIKE '%Treasurer%'";
                        break;
                    case 'kk_members':
                        $roleConditions[] = "user.position LIKE '%KK%' OR user.position LIKE '%Katipunan%'";
                        break;
                }
            }
            if (!empty($roleConditions)) {
                $query->where('(' . implode(' OR ', $roleConditions) . ')');
            }
        }
        
        return $query->findAll();
    }
    
    private function formatSmsMessage($event)
    {
        $startDate = (new \DateTime($event['start_datetime']))->format('F d, Y');
        $startTime = (new \DateTime($event['start_datetime']))->format('h:i A');
        $endTime = (new \DateTime($event['end_datetime']))->format('h:i A');
        
        $message = "NEW EVENT: {$event['title']}\n";
        $message .= "Date: {$startDate}\n";
        $message .= "Time: {$startTime} - {$endTime}\n";
        $message .= "Location: {$event['location']}\n";
        $message .= "Description: {$event['description']}";
        
        return $message;
    }
}
