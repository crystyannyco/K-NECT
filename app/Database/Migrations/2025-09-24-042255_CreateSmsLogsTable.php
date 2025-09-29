<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSmsLogsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'phone_number' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],
            'message' => [
                'type' => 'TEXT',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['sent', 'failed', 'delivered'],
                'default'    => 'sent',
            ],
            'response' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'event_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'sent_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'sent_at' => [
                'type' => 'DATETIME',
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
        $this->forge->addKey('phone_number');
        $this->forge->addKey('status');
        $this->forge->addKey('event_id');
        $this->forge->addKey('sent_by');
        $this->forge->addKey('sent_at');
        
        $this->forge->createTable('sms_logs');
    }

    public function down()
    {
        $this->forge->dropTable('sms_logs');
    }
}
