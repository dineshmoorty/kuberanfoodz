<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Admin;
use App\Models\CompanySetting;

class SubAdminDashboard extends BaseController
{
    public function index()
    {
        if ($redirect = $this->requireAuthenticated('Please login to access the sub-admin dashboard')) {
            return $redirect;
        }

        if (session()->get('admin_role') !== 'sub-admin') {
            return redirect()->to($this->dashboardPathForRole((string) session()->get('admin_role')));
        }

        $profile = (new Admin())->find((int) session()->get('admin_id'));
        $company = null;

        if (!empty($profile['company_id'])) {
            $company = (new CompanySetting())->find((int) $profile['company_id']);
        }

        return view('/sub_admin/dashboard', [
            'profile' => $profile,
            'company' => $company,
        ]);
    }
}
