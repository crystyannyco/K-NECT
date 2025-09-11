<?php
/**
 * Retry Google Calendar sync for existing event
 * This script will attempt to sync event ID 38 to Google Calendar
 */

// This is a manual fix script to be run via spark command
namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Controllers\GoogleCalendarController;
use App\Models\EventModel;
use App\Models\BarangayModel;

class RetryGoogleCalendarSync extends BaseCommand
{
    protected $group       = 'app';
    protected $name        = 'event:retry-gcal-sync';
    protected $description = 'Retry Google Calendar sync for events that are Published but missing google_event_id';

    public function run(array $params)
    {
        $eventModel = new EventModel();
        
        // Find events that are Published but don't have google_event_id
        $eventsToSync = $eventModel
            ->where('status', 'Published')
            ->where('google_event_id IS NULL OR google_event_id = ""')
            ->findAll();

        if (empty($eventsToSync)) {
            CLI::write('No events found that need Google Calendar sync.', 'green');
            return;
        }

        CLI::write('Found ' . count($eventsToSync) . ' event(s) to sync with Google Calendar.', 'yellow');

        $googleCalendar = new GoogleCalendarController();
        $barangayModel = new BarangayModel();
        
        foreach ($eventsToSync as $event) {
            CLI::write("Syncing event: {$event['title']} (ID: {$event['event_id']})", 'blue');
            
            try {
                // Get calendar ID
                if ($event['barangay_id'] == 0) {
                    $calendarId = 'knect.system@gmail.com';
                } else {
                    $barangay = $barangayModel->find($event['barangay_id']);
                    $calendarId = $barangay ? $barangay['google_calendar_id'] : 'knect.system@gmail.com';
                }
                
                // Prepare event data for Google Calendar
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
                
                CLI::write("Calendar ID: $calendarId");
                CLI::write("Event Data: " . json_encode($googleEventData));
                
                // Add to Google Calendar
                $googleEventId = $googleCalendar->addEventToGoogleCalendar($calendarId, $googleEventData);
                
                if ($googleEventId) {
                    // Update the event with Google Event ID
                    $eventModel->update($event['event_id'], ['google_event_id' => $googleEventId]);
                    CLI::write("✅ SUCCESS! Google Event ID: $googleEventId", 'green');
                } else {
                    CLI::write("❌ FAILED to sync event to Google Calendar", 'red');
                }
                
            } catch (\Exception $e) {
                CLI::write("❌ ERROR: " . $e->getMessage(), 'red');
            }
            
            CLI::write('---');
        }
        
        CLI::write('Google Calendar sync retry completed!', 'green');
    }
}
?>
