<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSecurityLogsTable extends Migration
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
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'comment'    => 'Foreign key to users table, NULL if not authenticated',
            ],
            'event_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'comment'    => 'Type of security event (login_success, csrf_violation, etc.)',
            ],
            'severity' => [
                'type'       => 'ENUM',
                'constraint' => ['info', 'warning', 'error', 'critical'],
                'default'    => 'info',
                'comment'    => 'Severity level of the event',
            ],
            'ip_address' => [
                'type'       => 'VARCHAR',
                'constraint' => 45,
                'comment'    => 'IPv4 or IPv6 address',
            ],
            'user_agent' => [
                'type'       => 'TEXT',
                'null'       => true,
                'comment'    => 'Browser/client user agent string',
            ],
            'request_uri' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'comment'    => 'Requested URI path',
            ],
            'request_method' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
                'null'       => true,
                'comment'    => 'HTTP method (GET, POST, etc.)',
            ],
            'details' => [
                'type'       => 'TEXT',
                'null'       => true,
                'comment'    => 'Additional JSON-encoded details about the event',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('user_id');
        $this->forge->addKey('event_type');
        $this->forge->addKey('severity');
        $this->forge->addKey('ip_address');
        $this->forge->addKey('created_at');
        
        // Foreign key to users table
        $this->forge->addForeignKey('user_id', 'users', 'id', 'SET NULL', 'CASCADE');
        
        $this->forge->createTable('security_logs', true);
    }

    public function down()
    {
        $this->forge->dropTable('security_logs', true);
    }
}
