<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class CheckSMSLogsCommand extends BaseCommand
{
    protected $group       = 'sms';
    protected $name        = 'sms:logs';
    protected $description = 'Check SMS logs';

    public function run(array $params)
    {
        $db = \Config\Database::connect();
        
        // Check if sms_logs table exists
        if (!$db->tableExists('sms_logs')) {
            CLI::write('SMS logs table does not exist', 'red');
            return;
        }

        $query = $db->query('SELECT * FROM sms_logs ORDER BY sent_at DESC LIMIT 10');
        $logs = $query->getResultArray();

        if (empty($logs)) {
            CLI::write('No SMS logs found', 'yellow');
            return;
        }

        CLI::write('Recent SMS Logs:', 'green');
        CLI::write('================', 'green');

        foreach ($logs as $log) {
            CLI::write("ID: {$log['id']}", 'cyan');
            CLI::write("Phone: {$log['phone_number']}", 'white');
            CLI::write("Status: {$log['status']}", $log['status'] === 'sent' ? 'green' : 'red');
            CLI::write("Message: {$log['message']}", 'white');
            CLI::write("Sent: {$log['sent_at']}", 'yellow');
            if ($log['response']) {
                CLI::write("Response: {$log['response']}", 'blue');
            }
            CLI::write("---", 'white');
        }
    }
}