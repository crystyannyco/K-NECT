<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class CheckApprovalColumns extends BaseCommand
{
    protected $group       = 'Database';
    protected $name        = 'db:check-approval';
    protected $description = 'Check for approval-related columns in documents table';

    public function run(array $params)
    {
        $db = \Config\Database::connect();

        CLI::write('Checking for approval-related columns in documents table...', 'yellow');
        CLI::newLine();

        $query = $db->query("SHOW COLUMNS FROM documents WHERE Field LIKE '%approval%'");
        $approvalColumns = $query->getResultArray();

        if (empty($approvalColumns)) {
            CLI::write('No approval columns found in documents table.', 'green');
        } else {
            CLI::write('Found approval columns:', 'red');
            foreach ($approvalColumns as $column) {
                CLI::write("- {$column['Field']} ({$column['Type']})", 'red');
            }
        }

        CLI::newLine();
        CLI::write('All columns in documents table:', 'yellow');
        $allColumns = $db->getFieldData('documents');
        foreach ($allColumns as $field) {
            CLI::write("- {$field->name} ({$field->type})");
        }
    }
}
