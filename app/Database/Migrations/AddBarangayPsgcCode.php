<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddBarangayPsgcCode extends Migration
{
    public function up()
    {
        // Add barangay_psgc_code column to address table
        $fields = [
            'barangay_psgc_code' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
                'after'      => 'barangay',
                'comment'    => 'PSGC Code for the barangay'
            ]
        ];
        
        $this->forge->addColumn('address', $fields);
        
        log_message('info', 'Migration: Added barangay_psgc_code column to address table');
    }

    public function down()
    {
        // Remove barangay_psgc_code column from address table
        $this->forge->dropColumn('address', 'barangay_psgc_code');
        
        log_message('info', 'Migration: Removed barangay_psgc_code column from address table');
    }
}
