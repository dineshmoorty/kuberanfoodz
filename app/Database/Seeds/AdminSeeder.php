<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $companyId = null;
        $roleId = null;

        if ($this->db->tableExists('company_settings')) {
            $companyRow = $this->db->table('company_settings')->select('id')->orderBy('id', 'ASC')->get()->getFirstRow('array');
            $companyId = $companyRow['id'] ?? null;
        }

        if ($this->db->tableExists('roles')) {
            $roleRow = $this->db->table('roles')->select('id')->where('slug', 'admin')->get()->getFirstRow('array');
            $roleId = $roleRow['id'] ?? null;
        }

        $data = [
            'username' => 'admin',
            'name' => 'Administrator',
            'dob' => null,
            'mobile' => null,
            'company_id' => $companyId,
            'role_id' => $roleId,
            'role' => 'admin',
            'password' => password_hash('admin123', PASSWORD_DEFAULT),
        ];

        $existingAdmin = $this->db->table('admins')->where('username', 'admin')->get()->getFirstRow('array');

        if (!$existingAdmin) {
            $this->db->table('admins')->insert($data);
            return;
        }

        $this->db->table('admins')
            ->where('id', $existingAdmin['id'])
            ->update([
                'company_id' => $companyId,
                'role_id' => $roleId,
                'role' => 'admin',
            ]);
    }
}
