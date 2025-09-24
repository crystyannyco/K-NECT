<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class DebugUsers extends BaseCommand
{
    protected $group = 'Debug';
    protected $name = 'debug:users';
    protected $description = 'Debug user data in barangays';

    public function run(array $params)
    {
        $db = \Config\Database::connect();

        // Get all users and their addresses
        CLI::write('=== ALL USERS WITH ADDRESSES ===', 'yellow');
        $users = $db->query('
            SELECT user.id, user.first_name, user.last_name, user.user_type, user.position, 
                   user.ped_position, user.phone_number, user.is_active, address.barangay
            FROM user 
            LEFT JOIN address ON user.id = address.user_id
            ORDER BY address.barangay, user.user_type, user.position
        ')->getResultArray();

        $barangayGroups = [];
        foreach ($users as $user) {
            $barangayId = $user['barangay'] ?? 'No Address';
            if (!isset($barangayGroups[$barangayId])) {
                $barangayGroups[$barangayId] = [];
            }
            $barangayGroups[$barangayId][] = $user;
        }

        foreach ($barangayGroups as $barangayId => $barangayUsers) {
            $barangayName = 'Unknown';
            if (is_numeric($barangayId)) {
                $barangay = $db->query('SELECT name FROM barangay WHERE barangay_id = ?', [$barangayId])->getRowArray();
                $barangayName = $barangay['name'] ?? 'Unknown';
            }
            
            CLI::write("\n=== BARANGAY $barangayId ($barangayName) - " . count($barangayUsers) . " users ===", 'cyan');
            
            foreach ($barangayUsers as $user) {
                $typeLabel = '';
                switch ($user['user_type']) {
                    case 1: $typeLabel = 'KK Member'; break;
                    case 2: $typeLabel = 'SK Official'; break;
                    case 3: $typeLabel = 'Pederasyon'; break;
                    default: $typeLabel = 'Unknown'; break;
                }
                
                CLI::write(sprintf(
                    'ID: %d, Name: %s %s, Type: %d (%s), Position: %s, Ped_Pos: %s, Phone: %s, Active: %s',
                    $user['id'],
                    $user['first_name'],
                    $user['last_name'],
                    $user['user_type'],
                    $typeLabel,
                    $user['position'] ?? 'None',
                    $user['ped_position'] ?? 'None',
                    $user['phone_number'] ?? 'None',
                    $user['is_active'] ? 'YES' : 'NO'
                ));
            }
        }

        // Special focus on users with phone numbers and active status
        CLI::write("\n=== USERS WITH PHONE NUMBERS (ACTIVE ONLY) ===", 'green');
        $activeUsersWithPhones = $db->query('
            SELECT user.id, user.first_name, user.last_name, user.user_type, user.position, 
                   user.ped_position, user.phone_number, address.barangay, barangay.name as barangay_name
            FROM user 
            LEFT JOIN address ON user.id = address.user_id
            LEFT JOIN barangay ON address.barangay = barangay.barangay_id
            WHERE user.phone_number IS NOT NULL 
            AND user.phone_number != "" 
            AND user.is_active = 1
            ORDER BY address.barangay, user.user_type, user.position
        ')->getResultArray();

        foreach ($activeUsersWithPhones as $user) {
            $typeLabel = '';
            switch ($user['user_type']) {
                case 1: $typeLabel = 'KK Member'; break;
                case 2: $typeLabel = 'SK Official'; break;
                case 3: $typeLabel = 'Pederasyon'; break;
                default: $typeLabel = 'Unknown'; break;
            }
            
            CLI::write(sprintf(
                'Barangay %s (%s): ID: %d, Name: %s %s, Type: %d (%s), Position: %s, Phone: %s',
                $user['barangay'] ?? 'None',
                $user['barangay_name'] ?? 'Unknown',
                $user['id'],
                $user['first_name'],
                $user['last_name'],
                $user['user_type'],
                $typeLabel,
                $user['position'] ?? 'None',
                $user['phone_number']
            ));
        }
    }
}