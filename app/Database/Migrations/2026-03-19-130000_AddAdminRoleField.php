<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAdminRoleField extends Migration
{
    public function up()
    {
        $this->forge->addColumn('admins', [
            'role' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'default' => 'admin',
                'after' => 'mobile',
            ],
        ]);

        $this->db->query("UPDATE admins SET role = 'admin' WHERE role IS NULL OR role = ''");
    }

    public function down()
    {
        $this->forge->dropColumn('admins', 'role');
    }
}
