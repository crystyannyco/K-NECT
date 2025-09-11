<?php
/**
 * Verify Google Calendar sync fix
 */

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\EventModel;

class VerifyGoogleCalendarFix extends BaseCommand
{
    protected $group       = 'app';
    protected $name        = 'event:verify-gcal-fix';
    protected $description = 'Verify that published events have Google Calendar IDs';

    public function run(array $params)
    {
        $eventModel = new EventModel();
        
        // Get all published events
        $publishedEvents = $eventModel
            ->where('status', 'Published')
            ->findAll();

        CLI::write('Published Events Status:', 'yellow');
        CLI::write('========================', 'yellow');
        
        foreach ($publishedEvents as $event) {
            $status = $event['google_event_id'] ? '✅ Synced' : '❌ Not Synced';
            $googleId = $event['google_event_id'] ?: 'NULL';
            
            CLI::write("Event ID: {$event['event_id']} | Title: {$event['title']} | Status: $status | Google ID: $googleId");
        }
        
        $syncedCount = count(array_filter($publishedEvents, fn($e) => !empty($e['google_event_id'])));
        $totalCount = count($publishedEvents);
        
        CLI::write('', 'white');
        CLI::write("Summary: $syncedCount/$totalCount published events are synced with Google Calendar", 'green');
        
        if ($syncedCount === $totalCount) {
            CLI::write('🎉 ALL PUBLISHED EVENTS ARE SYNCED WITH GOOGLE CALENDAR!', 'green');
        } else {
            CLI::write('⚠️  Some events still need to be synced. Run "php spark event:retry-gcal-sync"', 'red');
        }
    }
}
?>
