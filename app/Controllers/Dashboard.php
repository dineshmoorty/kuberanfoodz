<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Admin;
use App\Models\CompanySetting;

class Dashboard extends BaseController
{
    public function index()
    {
        if ($redirect = $this->requireAuthenticated('Please login to access the dashboard')) {
            return $redirect;
        }

        if (session()->get('admin_role') !== 'admin') {
            return redirect()->to($this->dashboardPathForRole((string) session()->get('admin_role')));
        }

        $companySettings = (new CompanySetting())->orderBy('id', 'ASC')->first();
        $admin = (new Admin())->find((int) session()->get('admin_id'));

        $data = [
            'company_name' => $companySettings['company_name'] ?? 'Kuberan Foods Admin',
            'admin_name' => $admin['name'] ?? session()->get('admin_name'),
        ];

        return view('/admin/dashboard', $data);
    }
}
