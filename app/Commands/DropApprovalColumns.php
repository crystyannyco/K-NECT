<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class DropApprovalColumns extends BaseCommand
{
    protected $group       = 'Database';
    protected $name        = 'db:drop-approval-columns';
    protected $description = 'Drop approval-related columns from documents table';

    public function run(array $params)
    {
        $db = \Config\Database::connect();

        CLI::write('==============================================', 'yellow');
        CLI::write('Drop Approval Columns Migration', 'yellow');
        CLI::write('==============================================', 'yellow');
        CLI::newLine();

        // Check if columns exist
        $query = $db->query("SHOW COLUMNS FROM documents WHERE Field IN ('approval_status', 'approver', 'approval_at', 'approval_comment')");
        $existingColumns = $query->getResultArray();

        if (empty($existingColumns)) {
            CLI::write('No approval columns found. Migration already applied or not needed.', 'green');
            return;
        }

        CLI::write('Found ' . count($existingColumns) . ' approval column(s) to drop:', 'yellow');
        foreach ($existingColumns as $column) {
            CLI::write("  - {$column['Field']}", 'yellow');
        }
        CLI::newLine();

        // Confirm before proceeding
        $confirm = CLI::prompt('Do you want to proceed with dropping these columns? (yes/no)', ['yes', 'no']);
        
        if ($confirm !== 'yes') {
            CLI::write('Migration cancelled.', 'red');
            return;
        }

        CLI::newLine();
        CLI::write('Starting migration...', 'blue');

        try {
            // Create backup notification
            CLI::write('TIP: Consider backing up your database first!', 'yellow');
            CLI::newLine();

            // Drop columns one by one for better error handling
            $dropped = [];
            $failed = [];

            foreach ($existingColumns as $column) {
                $columnName = $column['Field'];
                try {
                    $db->query("ALTER TABLE documents DROP COLUMN `{$columnName}`");
                    $dropped[] = $columnName;
                    CLI::write("✓ Dropped column: {$columnName}", 'green');
                } catch (\Exception $e) {
                    $failed[] = $columnName;
                    CLI::write("✗ Failed to drop column: {$columnName}", 'red');
                    CLI::write("  Error: " . $e->getMessage(), 'red');
                }
            }

            CLI::newLine();
            CLI::write('==============================================', 'yellow');
            CLI::write('Migration Summary', 'yellow');
            CLI::write('==============================================', 'yellow');
            CLI::write('Dropped: ' . count($dropped) . ' column(s)', 'green');
            if (!empty($failed)) {
                CLI::write('Failed: ' . count($failed) . ' column(s)', 'red');
            }
            CLI::newLine();

            // Verify final state
            CLI::write('Verifying final table structure...', 'blue');
            $remainingApprovalCols = $db->query("SHOW COLUMNS FROM documents WHERE Field LIKE '%approval%' OR Field = 'approver'")->getResultArray();
            
            if (empty($remainingApprovalCols)) {
                CLI::write('✓ All approval columns successfully removed!', 'green');
            } else {
                CLI::write('⚠ Some approval columns still remain:', 'yellow');
                foreach ($remainingApprovalCols as $col) {
                    CLI::write("  - {$col['Field']}", 'yellow');
                }
            }

        } catch (\Exception $e) {
            CLI::error('Migration failed: ' . $e->getMessage());
            return;
        }

        CLI::newLine();
        CLI::write('Migration completed!', 'green');
    }
}
