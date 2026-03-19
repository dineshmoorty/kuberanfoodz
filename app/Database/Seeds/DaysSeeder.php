<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DaysSeeder extends Seeder
{
  public function run()
  {
    $days = [
      ['day_name' => 'Monday'],
      ['day_name' => 'Tuesday'],
      ['day_name' => 'Wednesday'],
      ['day_name' => 'Thursday'],
      ['day_name' => 'Friday'],
      ['day_name' => 'Saturday'],
      ['day_name' => 'Sunday'],
    ];

    $this->db->table('days')->insertBatch($days);
  }
}
