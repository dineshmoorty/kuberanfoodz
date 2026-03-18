<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CompanySettingsSeeder extends Seeder
{
  public function run()
  {
    $data = [
      'company_name' => 'Kuberan Foods',
      'company_phone' => '9488270932',
      'company_email' => 'storeskuberan@gmail.com',
      'company_address' => '106A, Panthadi 9th Street, Thavittusandhai, Madurai - 625001',
      'company_fssai' => '224225770137',
      'company_logo' => '',
      'company_gst' => ' 27ABCDE1234F2Z5',
      'swiggy' => 'https://www.swiggy.com/',
      'zomato' => 'https://www.zomato.com/madurai/shri-kuberan-tiffen-home-periyar',
      'whatsapp_group' => '',
    ];

    $this->db->table('company_settings')->insert($data);
  }
}
