<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSystemSettingsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'setting_key' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'unique' => true,
            ],
            'setting_value' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'setting_type' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'default' => 'string',
            ],
            'description' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'updated_by' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->createTable('system_settings');
        
        // Insert default location settings
        $data = [
            [
                'setting_key' => 'default_region_code',
                'setting_value' => '050000000',
                'setting_type' => 'string',
                'description' => 'Default region code (Region V - Bicol Region)',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'setting_key' => 'default_region_name',
                'setting_value' => 'Region V',
                'setting_type' => 'string',
                'description' => 'Default region name',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'setting_key' => 'default_province_code',
                'setting_value' => '051700000',
                'setting_type' => 'string',
                'description' => 'Default province code (Camarines Sur)',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'setting_key' => 'default_province_name',
                'setting_value' => 'Camarines Sur',
                'setting_type' => 'string',
                'description' => 'Default province name',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'setting_key' => 'default_municipality_code',
                'setting_value' => '051716000',
                'setting_type' => 'string',
                'description' => 'Default municipality code (Iriga City)',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'setting_key' => 'default_municipality_name',
                'setting_value' => 'Iriga City',
                'setting_type' => 'string',
                'description' => 'Default municipality name',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];
        
        $this->db->table('system_settings')->insertBatch($data);
    }

    public function down()
    {
        $this->forge->dropTable('system_settings');
    }
}
