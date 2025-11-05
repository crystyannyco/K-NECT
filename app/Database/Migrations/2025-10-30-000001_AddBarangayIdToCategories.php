<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddBarangayIdToCategories extends Migration
{
    public function up()
    {
        // Add barangay_id column to categories table
        $this->forge->addColumn('categories', [
            'barangay_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'name'
            ]
        ]);
        
        // Add index for better query performance
        $this->forge->addKey('barangay_id', false, false, 'categories');
    }

    public function down()
    {
        // Remove the barangay_id column
        $this->forge->dropColumn('categories', 'barangay_id');
    }
}
