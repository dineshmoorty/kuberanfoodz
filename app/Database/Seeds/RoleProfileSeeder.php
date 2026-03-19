<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RoleProfileSeeder extends Seeder
{
    public function run()
    {
        $companyRow = $this->db->table('company_settings')->select('id')->orderBy('id', 'ASC')->get()->getFirstRow('array');
        $companyId = $companyRow['id'] ?? null;

        if ($companyId === null) {
            $this->db->table('company_settings')->insert([
                'company_name' => 'Dinesh Foods',
            ]);
            $companyId = $this->db->insertID();
        }
        $roles = $this->db->table('roles')->select('id, slug')->get()->getResultArray();
        $roleMap = [];

        foreach ($roles as $role) {
            $roleMap[$role['slug']] = $role['id'];
        }

        $profiles = [
            [
                'username' => 'dinesh.subadmin',
                'name' => 'Dinesh',
                'mobile' => '9876543210',
                'dob' => '1996-06-15',
                'company_id' => $companyId,
                'role_id' => $roleMap['sub-admin'] ?? null,
                'role' => 'sub-admin',
                'password' => password_hash('subadmin123', PASSWORD_DEFAULT),
            ],
            [
                'username' => 'manager.demo',
                'name' => 'Manager Demo',
                'mobile' => '9876543211',
                'dob' => '1994-03-22',
                'company_id' => $companyId,
                'role_id' => $roleMap['manager'] ?? null,
                'role' => 'manager',
                'password' => password_hash('manager123', PASSWORD_DEFAULT),
            ],
        ];

        foreach ($profiles as $profile) {
            if (!$this->db->table('admins')->where('username', $profile['username'])->get()->getFirstRow()) {
                $this->db->table('admins')->insert($profile);
            }
        }
    }
}
