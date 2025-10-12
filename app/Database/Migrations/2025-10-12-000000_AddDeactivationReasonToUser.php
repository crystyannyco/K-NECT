<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDeactivationReasonToUser extends Migration
{
    public function up()
    {
        $this->forge->addColumn('user', [
            'deactivation_reason' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'is_active'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('user', 'deactivation_reason');
    }
}
