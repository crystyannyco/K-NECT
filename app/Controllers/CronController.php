<?php
namespace App\Controllers;

use App\Models\EventModel;
use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class CronController extends BaseController
{
    /**
     * Secure endpoint for publishing scheduled events
     * This can be called by external cron services or webhooks
     * 
     * Access: GET/POST /cron/publish-events?token=YOUR_SECRET_TOKEN
     */
    public function publishScheduledEvents()
    {
        // Security token check
        $providedToken = $this->request->getGet('token') ?? $this->request->getPost('token');
        $expectedToken = env('CRON_SECRET_TOKEN', 'your-secret-token-here');
        
        if (empty($providedToken) || $providedToken !== $expectedToken) {
            log_message('warning', '[CRON API] Unauthorized access attempt from IP: ' . $this->request->getIPAddress());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Unauthorized access'
            ])->setStatusCode(401);
        }
        
        try {
            // Execute the publish command
            $command = \CodeIgniter\Config\Services::commands();
            $result = $command->run('events:publish-scheduled', []);
            
            // Get events that were processed
            $eventModel = new EventModel();
            $recentlyPublished = $eventModel->where('publish_date >=', date('Y-m-d H:i:s', strtotime('-5 minutes')))
                                          ->where('status', 'Published')
                                          ->findAll();
            
            $scheduledEvents = $eventModel->where('status', 'Scheduled')
                                        ->where('scheduling_enabled', 1)
                                        ->where('scheduled_publish_datetime <=', date('Y-m-d H:i:s'))
                                        ->countAllResults();
            
            log_message('info', '[CRON API] Published ' . count($recentlyPublished) . ' events. ' . $scheduledEvents . ' events still scheduled.');
            
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Scheduled events check completed',
                'published_count' => count($recentlyPublished),
                'pending_scheduled' => $scheduledEvents,
                'timestamp' => date('Y-m-d H:i:s')
            ]);
            
        } catch (\Exception $e) {
            log_message('error', '[CRON API] Error publishing scheduled events: ' . $e->getMessage());
            
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to publish scheduled events',
                'error' => $e->getMessage(),
                'timestamp' => date('Y-m-d H:i:s')
            ])->setStatusCode(500);
        }
    }
    
    /**
     * Health check endpoint for monitoring
     * Access: GET /cron/health
     */
    public function health()
    {
        $eventModel = new EventModel();
        
        // Check for events that should have been published but weren't
        $overdueEvents = $eventModel->where('status', 'Scheduled')
                                   ->where('scheduling_enabled', 1)
                                   ->where('scheduled_publish_datetime <', date('Y-m-d H:i:s', strtotime('-5 minutes')))
                                   ->countAllResults();
        
        $upcomingScheduled = $eventModel->where('status', 'Scheduled')
                                       ->where('scheduling_enabled', 1)
                                       ->where('scheduled_publish_datetime >', date('Y-m-d H:i:s'))
                                       ->countAllResults();
        
        $health = [
            'status' => $overdueEvents > 0 ? 'warning' : 'healthy',
            'timestamp' => date('Y-m-d H:i:s'),
            'overdue_events' => $overdueEvents,
            'upcoming_scheduled' => $upcomingScheduled,
            'message' => $overdueEvents > 0 ? 
                "Warning: {$overdueEvents} events are overdue for publishing" : 
                'All scheduled events are processing normally'
        ];
        
        return $this->response->setJSON($health);
    }
    
    /**
     * Debug endpoint to check scheduled events details
     * Access: GET /cron/debug-events
     */
    public function debugEvents()
    {
        $eventModel = new EventModel();
        $events = $eventModel->where('scheduling_enabled', 1)
                            ->where('status', 'Scheduled')
                            ->findAll();
        
        $debug = [
            'current_server_time' => date('Y-m-d H:i:s'),
            'timezone' => date_default_timezone_get(),
            'events' => []
        ];
        
        foreach($events as $event) {
            $scheduledTime = strtotime($event['scheduled_publish_datetime']);
            $currentTime = time();
            
            $debug['events'][] = [
                'event_id' => $event['event_id'],
                'title' => $event['title'],
                'status' => $event['status'],
                'scheduling_enabled' => $event['scheduling_enabled'],
                'scheduled_publish_datetime' => $event['scheduled_publish_datetime'],
                'start_datetime' => $event['start_datetime'], // This is what shows in UI
                'current_time' => date('Y-m-d H:i:s'),
                'is_due_for_publishing' => $scheduledTime <= $currentTime,
                'time_until_publish' => $scheduledTime - $currentTime,
                'minutes_until_publish' => round(($scheduledTime - $currentTime) / 60, 2)
            ];
        }
        
        return $this->response->setJSON($debug);
    }
}