<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Categories extends Migration
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
      'category_name' => [
        'type' => 'VARCHAR',
        'constraint' => '100',
        'null' => false,
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
    $this->forge->addUniqueKey('category_name');
    $this->forge->createTable('categories');
  }

  public function down()
  {
    $this->forge->dropTable('categories', true);
  }
}
