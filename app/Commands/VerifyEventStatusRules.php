<?php
/**
 * Check all event statuses to verify Google Calendar sync rules
 */

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\EventModel;

class VerifyEventStatusRules extends BaseCommand
{
    protected $group       = 'app';
    protected $name        = 'event:verify-status-rules';
    protected $description = 'Verify that only Published events have Google Calendar IDs (Draft and Scheduled should not)';

    public function run(array $params)
    {
        $eventModel = new EventModel();
        
        // Get all events grouped by status
        $allEvents = $eventModel->findAll();
        $eventsByStatus = [];
        
        foreach ($allEvents as $event) {
            $status = $event['status'];
            if (!isset($eventsByStatus[$status])) {
                $eventsByStatus[$status] = [];
            }
            $eventsByStatus[$status][] = $event;
        }
        
        CLI::write('🔍 GOOGLE CALENDAR SYNC RULES VERIFICATION', 'yellow');
        CLI::write('==========================================', 'yellow');
        CLI::write('Rule: Only PUBLISHED events should have Google Calendar IDs', 'blue');
        CLI::write('Rule: DRAFT and SCHEDULED events should NOT have Google Calendar IDs', 'blue');
        CLI::write('');
        
        $totalValid = 0;
        $totalInvalid = 0;
        
        foreach (['Draft', 'Scheduled', 'Published'] as $status) {
            if (!isset($eventsByStatus[$status])) {
                CLI::write("📂 {$status} Events: 0 events", 'cyan');
                continue;
            }
            
            $events = $eventsByStatus[$status];
            $validCount = 0;
            $invalidCount = 0;
            
            CLI::write("📂 {$status} Events: " . count($events) . " events", 'cyan');
            
            foreach ($events as $event) {
                $hasGoogleId = !empty($event['google_event_id']);
                $shouldHaveGoogleId = ($status === 'Published');
                
                if ($hasGoogleId === $shouldHaveGoogleId) {
                    $validCount++;
                    $icon = $shouldHaveGoogleId ? '✅' : '⚪';
                    $googleIdText = $hasGoogleId ? $event['google_event_id'] : 'None';
                } else {
                    $invalidCount++;
                    $icon = '❌';
                    $googleIdText = $hasGoogleId ? $event['google_event_id'] : 'None';
                    CLI::write("  $icon Event ID: {$event['event_id']} | {$event['title']} | Google ID: $googleIdText", 'red');
                }
            }
            
            if ($status === 'Published') {
                CLI::write("  ✅ Valid (With Google ID): $validCount", 'green');
                CLI::write("  ❌ Invalid (Missing Google ID): $invalidCount", $invalidCount > 0 ? 'red' : 'green');
            } else {
                CLI::write("  ⚪ Valid (No Google ID): $validCount", 'green');
                CLI::write("  ❌ Invalid (Has Google ID): $invalidCount", $invalidCount > 0 ? 'red' : 'green');
            }
            
            $totalValid += $validCount;
            $totalInvalid += $invalidCount;
            CLI::write('');
        }
        
        // Summary
        CLI::write('📊 SUMMARY', 'yellow');
        CLI::write('=========', 'yellow');
        CLI::write("✅ Events following rules: $totalValid", 'green');
        CLI::write("❌ Events violating rules: $totalInvalid", $totalInvalid > 0 ? 'red' : 'green');
        
        if ($totalInvalid === 0) {
            CLI::write('🎉 ALL EVENTS ARE FOLLOWING GOOGLE CALENDAR SYNC RULES!', 'green');
            CLI::write('✓ Draft events: No Google Calendar sync', 'white');
            CLI::write('✓ Scheduled events: No Google Calendar sync', 'white'); 
            CLI::write('✓ Published events: Synced with Google Calendar', 'white');
        } else {
            CLI::write('⚠️  Some events are violating the Google Calendar sync rules!', 'red');
            CLI::write('Please check the invalid events listed above.', 'red');
        }
    }
}
?>
