<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CompanySettings extends Migration
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
      'company_name' => [
        'type' => 'VARCHAR',
        'constraint' => 255,
      ],
      'company_phone' => [
        'type' => 'VARCHAR',
        'constraint' => 50,
        'null' => true,
      ],
      'company_email' => [
        'type' => 'VARCHAR',
        'constraint' => 255,
        'null' => true,
      ],
      'company_address' => [
        'type' => 'VARCHAR',
        'constraint' => 500,
        'null' => true,
      ],
      'company_fssai' => [
        'type' => 'VARCHAR',
        'constraint' => 100,
        'null' => true,
      ],
      'company_logo' => [
        'type' => 'VARCHAR',
        'constraint' => 255,
        'null' => true,
      ],
      'company_gst' => [
        'type' => 'VARCHAR',
        'constraint' => 100,
        'null' => true,
      ],
      'swiggy' => [
        'type' => 'VARCHAR',
        'constraint' => 255,
        'null' => true,
      ],
      'zomato' => [
        'type' => 'VARCHAR',
        'constraint' => 255,
        'null' => true,
      ],
      'whatsapp_group' => [
        'type' => 'VARCHAR',
        'constraint' => 255,
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
    $this->forge->createTable('company_settings');
  }

  public function down()
  {
    $this->forge->dropTable('company_settings');
  }
}
