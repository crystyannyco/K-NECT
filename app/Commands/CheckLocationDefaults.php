<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class CheckLocationDefaults extends BaseCommand
{
    protected $group       = 'Demo';
    protected $name        = 'check:location';
    protected $description = 'Check current location defaults in database';

    public function run(array $params)
    {
        $db = \Config\Database::connect();
        
        CLI::write('=== Current Location Defaults in Database ===', 'yellow');
        CLI::newLine();
        
        $query = $db->query("SELECT setting_key, setting_value FROM system_settings WHERE setting_key LIKE 'default_%' ORDER BY setting_key");
        $results = $query->getResultArray();
        
        foreach ($results as $row) {
            CLI::write(str_pad($row['setting_key'], 35) . ': ' . CLI::color($row['setting_value'], 'green'));
        }
        
        CLI::newLine();
        CLI::write('=== End of Data ===', 'yellow');
    }
}
