<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Admin;
use App\Models\CompanySetting;

class ManagerDashboard extends BaseController
{
    public function index()
    {
        if ($redirect = $this->requireAuthenticated('Please login to access the manager dashboard')) {
            return $redirect;
        }

        if (session()->get('admin_role') !== 'manager') {
            return redirect()->to($this->dashboardPathForRole((string) session()->get('admin_role')));
        }

        $profile = (new Admin())->find((int) session()->get('admin_id'));
        $company = null;

        if (!empty($profile['company_id'])) {
            $company = (new CompanySetting())->find((int) $profile['company_id']);
        }

        return view('/manager/dashboard', [
            'profile' => $profile,
            'company' => $company,
        ]);
    }
}
