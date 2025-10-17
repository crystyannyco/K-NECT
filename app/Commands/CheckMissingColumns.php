<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class CheckMissingColumns extends BaseCommand
{
    protected $group       = 'Database';
    protected $name        = 'db:check-missing-columns';
    protected $description = 'Check for missing barangay and visibility scope columns';

    public function run(array $params)
    {
        $db = \Config\Database::connect();

        CLI::write('Checking for barangay_id and visibility_scope columns...', 'yellow');
        CLI::newLine();

        $query = $db->query("SHOW COLUMNS FROM documents WHERE Field IN ('barangay_id', 'visibility_scope')");
        $existingColumns = $query->getResultArray();

        $expected = ['barangay_id', 'visibility_scope'];
        $found = array_column($existingColumns, 'Field');
        $missing = array_diff($expected, $found);

        if (empty($missing)) {
            CLI::write('✓ All required columns exist!', 'green');
            foreach ($existingColumns as $col) {
                CLI::write("  - {$col['Field']} ({$col['Type']})", 'green');
            }
        } else {
            CLI::write('⚠ Missing columns:', 'red');
            foreach ($missing as $col) {
                CLI::write("  - {$col}", 'red');
            }
        }
    }
}
