<?php
namespace App\Controllers;

use CodeIgniter\Controller;
use Google\Client;
use Google\Service\Calendar;
use Google\Service\Calendar\Event as GoogleCalendarEvent;

class GoogleCalendarController extends Controller
{
    private function getClient()
    {
    $client = new Client();
        // Use the service account JSON file
        $client->setAuthConfig(APPPATH . 'ThirdParty/google/analog-antler-417007-74dbc064d902.json');
    $client->setScopes([Calendar::CALENDAR]);
        $client->setAccessType('offline');
        $client->setSubject('calendar-sync@analog-antler-417007.iam.gserviceaccount.com');
        return $client;
    }

    // Service account: no connect/callback needed

    // Service account: addEvent not needed for user session

    // Add a new event to Google Calendar and return the Google event ID
    public function addEventToGoogleCalendar($calendarId, $eventData)
    {
        try {
            $client = $this->getClient();
            log_message('debug', 'Calendar service initialized for: ' . $calendarId);
            log_message('debug', 'Event data: ' . json_encode($eventData));
            
            $service = new Calendar($client);
            $event = new GoogleCalendarEvent($eventData);
            
            $createdEvent = $service->events->insert($calendarId, $event);
            log_message('debug', 'Event created successfully with ID: ' . $createdEvent->getId());
            return $createdEvent->getId();
        } catch (\Exception $e) {
            log_message('error', 'Google Calendar API error (add): ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            return null;
        }
    }

    // Update an existing Google Calendar event
    public function updateGoogleCalendarEvent($calendarId, $googleEventId, $eventData)
    {
        $client = $this->getClient();
        $service = new Calendar($client);
        try {
            $event = $service->events->get($calendarId, $googleEventId);
            foreach ($eventData as $key => $value) {
                $event->$key = $value;
            }
            $service->events->update($calendarId, $googleEventId, $event);
            return true;
        } catch (\Exception $e) {
            log_message('error', 'Google Calendar API error (update): ' . $e->getMessage());
            return false;
        }
    }

    // Delete a Google Calendar event
    public function deleteGoogleCalendarEvent($calendarId, $googleEventId)
    {
        $client = $this->getClient();
        $service = new Calendar($client);
        try {
            $service->events->delete($calendarId, $googleEventId);
            return true;
        } catch (\Exception $e) {
            log_message('error', 'Google Calendar API error (delete): ' . $e->getMessage());
            return false;
        }
    }

    // Service account: logout not needed
} 