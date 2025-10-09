<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTargetParticipantsToEvent extends Migration
{
    public function up()
    {
        // Step 1: Add the target_participants column (nullable initially)
        $this->forge->addColumn('event', [
            'target_participants' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
                'default'    => null,
                'comment'    => 'Target number of participants for the event (REQUIRED for analytics)',
                'after'      => 'location',
            ],
        ]);

        // Step 2: Set default values for all existing events
        $this->db->query("UPDATE `event` SET `target_participants` = 50 WHERE `target_participants` IS NULL");

        // Step 3: Make the column NOT NULL now that all rows have values
        $this->db->query("ALTER TABLE `event` MODIFY COLUMN `target_participants` INT(11) NOT NULL COMMENT 'Target number of participants for the event (REQUIRED for analytics)'");
    }

    public function down()
    {
        // Remove the target_participants column
        $this->forge->dropColumn('event', 'target_participants');
    }
}
