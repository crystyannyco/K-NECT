<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Controllers\GoogleCalendarController;
use App\Models\BarangayModel;

class TestGoogleCalendarConnection extends BaseCommand
{
    protected $group = 'gcal';
    protected $name = 'gcal:test';
    protected $description = 'Test Google Calendar API connection and sync functionality';

    public function run(array $params)
    {
        CLI::write('Testing Google Calendar API connection...', 'yellow');
        CLI::newLine();

        $googleCalendar = new GoogleCalendarController();
        $barangayModel = new BarangayModel();

        // Test city-wide calendar first
        CLI::write('Testing City-wide calendar (knect.system@gmail.com)...', 'white');
        $cityWideCalendarId = 'knect.system@gmail.com';
        $cityWideAccess = $googleCalendar->validateCalendarAccess($cityWideCalendarId);
        
        if ($cityWideAccess) {
            CLI::write('✓ City-wide calendar access: OK', 'green');
        } else {
            CLI::write('✗ City-wide calendar access: FAILED', 'red');
        }
        CLI::newLine();

        // Test a few barangay calendars
        $barangays = $barangayModel->findAll();
        $testCount = 0;
        $maxTests = 5; // Test first 5 barangays

        foreach ($barangays as $barangay) {
            if ($testCount >= $maxTests) break;
            
            CLI::write("Testing Barangay {$barangay['name']} calendar...", 'white');
            $calendarAccess = $googleCalendar->validateCalendarAccess($barangay['google_calendar_id']);
            
            if ($calendarAccess) {
                CLI::write("✓ Barangay {$barangay['name']}: OK", 'green');
            } else {
                CLI::write("✗ Barangay {$barangay['name']}: FAILED", 'red');
            }
            
            $testCount++;
        }

        CLI::newLine();
        CLI::write('Google Calendar connection test completed.', 'yellow');
    }
}