<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAdminProfileFields extends Migration
{
    public function up()
    {
        $this->forge->addColumn('admins', [
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
                'null' => true,
                'after' => 'username',
            ],
            'dob' => [
                'type' => 'DATE',
                'null' => true,
                'after' => 'name',
            ],
            'mobile' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
                'after' => 'dob',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('admins', 'mobile');
        $this->forge->dropColumn('admins', 'dob');
        $this->forge->dropColumn('admins', 'name');
    }
}
