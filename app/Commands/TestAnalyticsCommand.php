<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\AnalyticsModel;

class TestAnalyticsCommand extends BaseCommand
{
    protected $group       = 'Debug';
    protected $name        = 'analytics:test';
    protected $description = 'Test analytics queries and data';

    public function run(array $params)
    {
        $analyticsModel = new AnalyticsModel();
        
        CLI::write('Testing Event Summary Query:', 'yellow');
        
        // Test the raw query directly
        $db = \Config\Database::connect();
        
        CLI::write('Testing Database Connection and Queries:', 'yellow');
        
        // Test database connection
        try {
            $db = \Config\Database::connect();
            CLI::write('Database connection successful');
            
            // Test very simple query
            $result = $db->query("SELECT 1 as test");
            CLI::write('Query result type: ' . gettype($result));
            if ($result) {
                $testData = $result->getRowArray();
                CLI::write('Simple test query result: ' . ($testData['test'] ?? 'FAILED'));
            } else {
                CLI::write('Query result is false/null');
            }
            
        } catch (\Exception $e) {
            CLI::write('Database connection error: ' . $e->getMessage(), 'red');
            return;
        }
        
        // Test table structure
        try {
            $result = $db->query("DESCRIBE event");
            $columns = $result->getResultArray();
            CLI::write('Event table columns:');
            foreach ($columns as $column) {
                CLI::write("  " . $column['Field'] . " (" . $column['Type'] . ")");
            }
        } catch (\Exception $e) {
            CLI::write('Error describing event table: ' . $e->getMessage(), 'red');
        }
        
        // Test simpler query first
        try {
            $simpleQuery = "SELECT COUNT(*) as total_events FROM event WHERE status = 'Published'";
            CLI::write('Executing query: ' . $simpleQuery);
            $result = $db->query($simpleQuery);
            CLI::write('Query executed, getting row...');
            $data = $result->getRowArray();
            CLI::write('Simple Event Count:');
            if ($data) {
                CLI::write("  Published events: " . $data['total_events']);
            } else {
                CLI::write("  No data returned from simple query");
            }
        } catch (\Exception $e) {
            CLI::write('Simple Query Error: ' . $e->getMessage(), 'red');
        }
        
        // Test the complex query step by step
        $eventQuery = "
            SELECT 
                COUNT(CASE WHEN e.status = 'Published' THEN 1 END) as total_published_events,
                COUNT(CASE WHEN e.status = 'Draft' THEN 1 END) as total_draft_events,
                COUNT(CASE WHEN e.status = 'Scheduled' THEN 1 END) as total_scheduled_events,
                COUNT(DISTINCT a.user_id) as total_unique_participants,
                COUNT(a.attendance_id) as total_attendances,
                ROUND(AVG(
                    CASE WHEN a.`time-out_am` IS NOT NULL 
                    THEN TIMESTAMPDIFF(MINUTE, a.`time-in_am`, a.`time-out_am`) 
                    WHEN a.`time-out_pm` IS NOT NULL 
                    THEN TIMESTAMPDIFF(MINUTE, a.`time-in_pm`, a.`time-out_pm`)
                    END
                ), 2) as avg_attendance_duration
            FROM event e
            LEFT JOIN attendance a ON e.event_id = a.event_id
            WHERE 1=1
        ";
        
        try {
            $result = $db->query($eventQuery);
            if ($result) {
                $eventData = $result->getRowArray();
                CLI::write('Direct Event Query Results:');
                if ($eventData && is_array($eventData)) {
                    foreach ($eventData as $key => $value) {
                        CLI::write("  $key: " . ($value ?? 'NULL'));
                    }
                } else {
                    CLI::write('  Query succeeded but no data returned');
                    CLI::write('  Result type: ' . gettype($eventData));
                }
            } else {
                CLI::write('  Query result is false/null');
            }
        } catch (\Exception $e) {
            CLI::write('Direct Event Query Error: ' . $e->getMessage(), 'red');
        }
        
        // Now test the model method
        try {
            $eventSummary = $analyticsModel->getEventSummary();
            CLI::write('AnalyticsModel Event Summary Results:');
            if ($eventSummary && is_array($eventSummary)) {
                foreach ($eventSummary as $key => $value) {
                    CLI::write("  $key: " . ($value ?? 'NULL'));
                }
            } else {
                CLI::write('  No data returned or invalid format');
                CLI::write('  Data type: ' . gettype($eventSummary));
                CLI::write('  Data value: ' . var_export($eventSummary, true));
            }
        } catch (\Exception $e) {
            CLI::write('Event Summary Error: ' . $e->getMessage(), 'red');
        }
        
        CLI::write(''); // Empty line
        CLI::write('Testing Document Summary Query:', 'yellow');
        
        try {
            $documentSummary = $analyticsModel->getDocumentSummary();
            CLI::write('Document Summary Results:');
            if ($documentSummary && is_array($documentSummary)) {
                foreach ($documentSummary as $key => $value) {
                    CLI::write("  $key: " . ($value ?? 'NULL'));
                }
            } else {
                CLI::write('  No data returned or invalid format');
            }
        } catch (\Exception $e) {
            CLI::write('Document Summary Error: ' . $e->getMessage(), 'red');
        }
        
        // Check table existence and basic data
        CLI::write(''); // Empty line
        CLI::write('Checking table data:', 'yellow');
        
        $db = \Config\Database::connect();
        
        $tables = [
            'event' => 'SELECT COUNT(*) as count, status FROM event GROUP BY status',
            'attendance' => 'SELECT COUNT(*) as count FROM attendance',
            'documents' => 'SELECT COUNT(*) as count, approval_status FROM documents GROUP BY approval_status',
            'audit_logs' => 'SELECT COUNT(*) as count, action FROM audit_logs WHERE action = "download" GROUP BY action'
        ];
        
        foreach ($tables as $table => $query) {
            try {
                CLI::write("$table table:", 'green');
                $result = $db->query($query);
                $data = $result->getResultArray();
                if ($data) {
                    foreach ($data as $row) {
                        $rowString = '';
                        foreach ($row as $key => $value) {
                            $rowString .= "$key: $value, ";
                        }
                        CLI::write("  " . rtrim($rowString, ', '));
                    }
                } else {
                    CLI::write("  No data found");
                }
                CLI::write(''); // Empty line
            } catch (\Exception $e) {
                CLI::write("$table Error: " . $e->getMessage(), 'red');
            }
        }
    }
}
