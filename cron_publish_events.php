<?php
/**
 * Cron job script to publish scheduled events
 * This script should be called by the server's cron job system every minute
 * 
 * For Hostinger cPanel, add this to your cron jobs:
 * Command: /usr/local/bin/php /home/yourdomain/public_html/cron_publish_events.php
 * Timing: * * * * * (every minute)
 */

// Set the path to your CodeIgniter application
$appPath = __DIR__;

// Include CodeIgniter bootstrap
require_once $appPath . '/vendor/autoload.php';

// Bootstrap CodeIgniter
$app = \CodeIgniter\Config\Services::codeigniter();
$app->initialize();

// Set environment to production for cron jobs
putenv('CI_ENVIRONMENT=production');

try {
    // Get the command runner
    $command = \CodeIgniter\Config\Services::commands();
    
    // Run the publish scheduled events command
    $result = $command->run('events:publish-scheduled', []);
    
    // Log the result
    log_message('info', '[CRON] Published scheduled events - Result: ' . ($result ? 'Success' : 'No events to process'));
    
    // Output for cron log (optional)
    echo date('Y-m-d H:i:s') . " - Scheduled events check completed\n";
    
} catch (\Exception $e) {
    // Log any errors
    log_message('error', '[CRON] Error publishing scheduled events: ' . $e->getMessage());
    echo date('Y-m-d H:i:s') . " - Error: " . $e->getMessage() . "\n";
    exit(1);
}

exit(0);