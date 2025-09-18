<?php
namespace App\Controllers;

use CodeIgniter\Controller;
use Google_Client;
use Google_Service_Calendar;
use Google_Service_Oauth2;

class GoogleCalendarController extends Controller
{
    private function getClient()
    {
        $client = new \Google_Client();
        // Use the service account JSON file
        $client->setAuthConfig(APPPATH . 'ThirdParty/google/analog-antler-417007-74dbc064d902.json');
        $client->setScopes([\Google_Service_Calendar::CALENDAR]);
        $client->setAccessType('offline');
        $client->setSubject('calendar-sync@analog-antler-417007.iam.gserviceaccount.com');
        
        // Configure HTTP client to handle SSL properly
        $httpClient = new \GuzzleHttp\Client([
            'verify' => false, // Disable SSL verification for development
            'timeout' => 30,
            'connect_timeout' => 10,
        ]);
        $client->setHttpClient($httpClient);
        
        return $client;
    }

    // Validate calendar access and permissions
    public function validateCalendarAccess($calendarId)
    {
        try {
            $client = $this->getClient();
            $service = new \Google_Service_Calendar($client);
            
            // Try to get calendar info to validate access
            $calendar = $service->calendars->get($calendarId);
            log_message('debug', 'Calendar access validated for: ' . $calendarId . ' - ' . $calendar->getSummary());
            return true;
        } catch (\Google_Service_Exception $e) {
            log_message('error', 'Calendar access validation failed for ' . $calendarId . ': ' . $e->getMessage());
            log_message('error', 'Error details: ' . json_encode($e->getErrors()));
            return false;
        } catch (\Exception $e) {
            log_message('error', 'Calendar access validation error for ' . $calendarId . ': ' . $e->getMessage());
            return false;
        }
    }

    // Service account: no connect/callback needed

    // Service account: addEvent not needed for user session

    // Add a new event to Google Calendar and return the Google event ID
    public function addEventToGoogleCalendar($calendarId, $eventData)
    {
        $maxRetries = 3;
        $retryCount = 0;
        
        while ($retryCount < $maxRetries) {
            try {
                $client = $this->getClient();
                log_message('debug', 'Calendar service initialized for: ' . $calendarId . ' (attempt ' . ($retryCount + 1) . ')');
                log_message('debug', 'Event data: ' . json_encode($eventData));
                
                $service = new \Google_Service_Calendar($client);
                $event = new \Google_Service_Calendar_Event($eventData);
                
                $createdEvent = $service->events->insert($calendarId, $event);
                log_message('debug', 'Event created successfully with ID: ' . $createdEvent->getId());
                return $createdEvent->getId();
            } catch (\Google_Service_Exception $e) {
                log_message('error', 'Google Calendar API service error (add) - attempt ' . ($retryCount + 1) . ': ' . $e->getMessage());
                log_message('error', 'Error details: ' . json_encode($e->getErrors()));
                $retryCount++;
                if ($retryCount < $maxRetries) {
                    sleep(2); // Wait 2 seconds before retry
                }
            } catch (\Exception $e) {
                log_message('error', 'Google Calendar API general error (add) - attempt ' . ($retryCount + 1) . ': ' . $e->getMessage());
                log_message('error', 'Stack trace: ' . $e->getTraceAsString());
                $retryCount++;
                if ($retryCount < $maxRetries) {
                    sleep(2); // Wait 2 seconds before retry
                }
            }
        }
        
        log_message('error', 'Failed to add event to Google Calendar after ' . $maxRetries . ' attempts');
        return null;
    }

    // Update an existing Google Calendar event
    public function updateGoogleCalendarEvent($calendarId, $googleEventId, $eventData)
    {
        $maxRetries = 3;
        $retryCount = 0;
        
        while ($retryCount < $maxRetries) {
            try {
                $client = $this->getClient();
                $service = new \Google_Service_Calendar($client);
                
                log_message('debug', 'Updating Google Calendar event: ' . $googleEventId . ' in calendar: ' . $calendarId . ' (attempt ' . ($retryCount + 1) . ')');
                
                $event = $service->events->get($calendarId, $googleEventId);
                foreach ($eventData as $key => $value) {
                    $event->$key = $value;
                }
                $service->events->update($calendarId, $googleEventId, $event);
                log_message('debug', 'Event updated successfully: ' . $googleEventId);
                return true;
            } catch (\Google_Service_Exception $e) {
                log_message('error', 'Google Calendar API service error (update) - attempt ' . ($retryCount + 1) . ': ' . $e->getMessage());
                log_message('error', 'Error details: ' . json_encode($e->getErrors()));
                $retryCount++;
                if ($retryCount < $maxRetries) {
                    sleep(2); // Wait 2 seconds before retry
                }
            } catch (\Exception $e) {
                log_message('error', 'Google Calendar API general error (update) - attempt ' . ($retryCount + 1) . ': ' . $e->getMessage());
                $retryCount++;
                if ($retryCount < $maxRetries) {
                    sleep(2); // Wait 2 seconds before retry
                }
            }
        }
        
        log_message('error', 'Failed to update event in Google Calendar after ' . $maxRetries . ' attempts');
        return false;
    }

    // Delete a Google Calendar event
    public function deleteGoogleCalendarEvent($calendarId, $googleEventId)
    {
        $client = $this->getClient();
        $service = new \Google_Service_Calendar($client);
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