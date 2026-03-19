<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            [
                'name' => 'Admin',
                'slug' => 'admin',
                'description' => 'Full access to all admin modules',
            ],
            [
                'name' => 'Sub Admin',
                'slug' => 'sub-admin',
                'description' => 'Sub-admin dashboard access',
            ],
            [
                'name' => 'Manager',
                'slug' => 'manager',
                'description' => 'Manager dashboard access',
            ],
        ];

        foreach ($roles as $role) {
            if (!$this->db->table('roles')->where('slug', $role['slug'])->get()->getFirstRow()) {
                $this->db->table('roles')->insert($role);
            }
        }
    }
}
