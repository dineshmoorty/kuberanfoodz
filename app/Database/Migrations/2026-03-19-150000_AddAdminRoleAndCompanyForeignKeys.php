<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAdminRoleAndCompanyForeignKeys extends Migration
{
    public function up()
    {
        $this->forge->addColumn('admins', [
            'company_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'mobile',
            ],
            'role_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'company_id',
            ],
        ]);

        $this->forge->addKey('company_id');
        $this->forge->addKey('role_id');
        $this->forge->addForeignKey('company_id', 'company_settings', 'id', 'CASCADE', 'SET NULL', 'admins_company_id_fk');
        $this->forge->addForeignKey('role_id', 'roles', 'id', 'CASCADE', 'SET NULL', 'admins_role_id_fk');
        $this->forge->processIndexes('admins');

        $companyRow = $this->db->table('company_settings')->select('id')->orderBy('id', 'ASC')->get()->getFirstRow('array');
        $companyId = $companyRow['id'] ?? null;
        $roleRows = $this->db->table('roles')->select('id, slug')->get()->getResultArray();
        $roleMap = [];

        foreach ($roleRows as $role) {
            $roleMap[$role['slug']] = $role['id'];
        }

        $admins = $this->db->table('admins')->select('id, role')->get()->getResultArray();

        foreach ($admins as $admin) {
            $roleSlug = trim(strtolower((string) ($admin['role'] ?? ''))) ?: 'admin';
            $this->db->table('admins')
                ->where('id', $admin['id'])
                ->update([
                    'company_id' => $companyId,
                    'role_id' => $roleMap[$roleSlug] ?? ($roleMap['admin'] ?? null),
                ]);
        }
    }

    public function down()
    {
        $this->forge->dropForeignKey('admins', 'admins_company_id_fk');
        $this->forge->dropForeignKey('admins', 'admins_role_id_fk');
        $this->forge->dropColumn('admins', 'company_id');
        $this->forge->dropColumn('admins', 'role_id');
    }
}
