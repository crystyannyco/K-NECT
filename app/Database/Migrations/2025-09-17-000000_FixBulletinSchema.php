<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FixBulletinSchema extends Migration
{
	public function up()
	{
		$db = \Config\Database::connect();

		// Fix bulletin_posts.id to be PRIMARY KEY AUTO_INCREMENT and repair existing rows with id=0
		if ($db->tableExists('bulletin_posts')) {
			// Assign unique IDs to rows with id=0 before adding PK/AI
			// Use a user variable to increment from current MAX(id)
			$db->query('SET @max := (SELECT IFNULL(MAX(id), 0) FROM bulletin_posts)');
			$db->query('UPDATE bulletin_posts SET id = (@max := @max + 1) WHERE id = 0');

			// Add PRIMARY KEY if missing
			$primary = $db->query("SHOW KEYS FROM bulletin_posts WHERE Key_name = 'PRIMARY'");
			if ($primary && $primary->getNumRows() === 0) {
				$db->query('ALTER TABLE bulletin_posts ADD PRIMARY KEY (id)');
			}

			// Ensure AUTO_INCREMENT on id
			$db->query('ALTER TABLE bulletin_posts MODIFY id INT(11) NOT NULL AUTO_INCREMENT');
		}

		// Fix bulletin_tags.id
		if ($db->tableExists('bulletin_tags')) {
			$db->query('SET @max := (SELECT IFNULL(MAX(id), 0) FROM bulletin_tags)');
			$db->query('UPDATE bulletin_tags SET id = (@max := @max + 1) WHERE id = 0');

			$primary = $db->query("SHOW KEYS FROM bulletin_tags WHERE Key_name = 'PRIMARY'");
			if ($primary && $primary->getNumRows() === 0) {
				$db->query('ALTER TABLE bulletin_tags ADD PRIMARY KEY (id)');
			}
			$db->query('ALTER TABLE bulletin_tags MODIFY id INT(11) NOT NULL AUTO_INCREMENT');
		}

		// Fix bulletin_categories.id
		if ($db->tableExists('bulletin_categories')) {
			$db->query('SET @max := (SELECT IFNULL(MAX(id), 0) FROM bulletin_categories)');
			$db->query('UPDATE bulletin_categories SET id = (@max := @max + 1) WHERE id = 0');

			$primary = $db->query("SHOW KEYS FROM bulletin_categories WHERE Key_name = 'PRIMARY'");
			if ($primary && $primary->getNumRows() === 0) {
				$db->query('ALTER TABLE bulletin_categories ADD PRIMARY KEY (id)');
			}
			$db->query('ALTER TABLE bulletin_categories MODIFY id INT(11) NOT NULL AUTO_INCREMENT');
		}
	}

	public function down()
	{
		$db = \Config\Database::connect();
		if ($db->tableExists('bulletin_posts')) {
			$db->query('ALTER TABLE bulletin_posts MODIFY id INT(11) NOT NULL');
		}
		if ($db->tableExists('bulletin_tags')) {
			$db->query('ALTER TABLE bulletin_tags MODIFY id INT(11) NOT NULL');
		}
		if ($db->tableExists('bulletin_categories')) {
			$db->query('ALTER TABLE bulletin_categories MODIFY id INT(11) NOT NULL');
		}
	}
}

	