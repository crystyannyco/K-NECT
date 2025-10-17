<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class AddBarangayColumns extends BaseCommand
{
    protected $group       = 'Database';
    protected $name        = 'db:add-barangay-columns';
    protected $description = 'Add barangay_id and visibility_scope columns to documents table';

    public function run(array $params)
    {
        $db = \Config\Database::connect();

        CLI::write('==============================================', 'yellow');
        CLI::write('Add Barangay Columns Migration', 'yellow');
        CLI::write('==============================================', 'yellow');
        CLI::newLine();

        // Check if columns already exist
        $query = $db->query("SHOW COLUMNS FROM documents WHERE Field IN ('barangay_id', 'visibility_scope')");
        $existingColumns = $query->getResultArray();

        if (count($existingColumns) >= 2) {
            CLI::write('Columns already exist. No migration needed.', 'green');
            return;
        }

        $expected = ['barangay_id', 'visibility_scope'];
        $found = array_column($existingColumns, 'Field');
        $toAdd = array_diff($expected, $found);

        CLI::write('Columns to add: ' . implode(', ', $toAdd), 'yellow');
        CLI::newLine();

        $confirm = CLI::prompt('Do you want to proceed? (yes/no)', ['yes', 'no']);
        
        if ($confirm !== 'yes') {
            CLI::write('Migration cancelled.', 'red');
            return;
        }

        CLI::newLine();
        CLI::write('Starting migration...', 'blue');

        try {
            // Add barangay_id column if missing
            if (in_array('barangay_id', $toAdd)) {
                $db->query("ALTER TABLE `documents` ADD COLUMN `barangay_id` INT NULL DEFAULT NULL COMMENT 'Barangay restriction (NULL = city-wide)' AFTER `visibility`");
                CLI::write('✓ Added column: barangay_id', 'green');
            }

            // Add visibility_scope column if missing
            if (in_array('visibility_scope', $toAdd)) {
                $db->query("ALTER TABLE `documents` ADD COLUMN `visibility_scope` ENUM('all', 'specific_barangay') DEFAULT 'all' COMMENT 'Visibility scope' AFTER `barangay_id`");
                CLI::write('✓ Added column: visibility_scope', 'green');
            }

            CLI::newLine();
            CLI::write('✓ Migration completed successfully!', 'green');

            // Verify
            CLI::write('Verifying columns...', 'blue');
            $verify = $db->query("SHOW COLUMNS FROM documents WHERE Field IN ('barangay_id', 'visibility_scope')")->getResultArray();
            foreach ($verify as $col) {
                CLI::write("  ✓ {$col['Field']} ({$col['Type']})", 'green');
            }

        } catch (\Exception $e) {
            CLI::error('Migration failed: ' . $e->getMessage());
            return;
        }
    }
}
