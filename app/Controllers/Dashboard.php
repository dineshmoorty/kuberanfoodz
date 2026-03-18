<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CompanySetting;
use CodeIgniter\HTTP\ResponseInterface;

class Dashboard extends BaseController
{
    public function index()
    {
        if (!session()->get('admin')) {
            return redirect()->to('/admin/login')->with('error', 'Please login to access the dashboard');
        }

        $companySettings = (new CompanySetting())->orderBy('id', 'ASC')->first();

        $data = [
            'company_name' => $companySettings['company_name'] ?? 'Kuberan Foods Admin',
        ];

        return view('/admin/dashboard', $data);
    }
}
