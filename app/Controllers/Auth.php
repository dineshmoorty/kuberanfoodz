<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\Admin;

class Auth extends BaseController
{
    public function adminLogin()
    {
        return view('Auth/adminlogin');
    }

    public function adminAuthenticate()
    {
        $model = new Admin();
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $admin = $model->where('username', $username)->first();

        if ($admin && password_verify($password, $admin['password'])) {
            session()->set('admin' , $admin['username']);
            return redirect()->to('/dashboard');

        } else {
            // Login failed
            return redirect()->back()->with('error', 'Invalid username or password');
        }
    }

    public function logout()
    {
        session()->destroy('admin');
        return redirect()->to('/admin/login');
    }
}

