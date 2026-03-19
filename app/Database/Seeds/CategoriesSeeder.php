<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CategoriesSeeder extends Seeder
{
  public function run()
  {
    $data = [
      ['category_name' => 'Breakfast'],
      ['category_name' => 'Lunch'],
      ['category_name' => 'Dinner'],
      ['category_name' => 'Snacks'],
      ['category_name' => 'Beverages'],
    ];

    $this->db->table('categories')->insertBatch($data);
  }
}
