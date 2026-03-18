<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CompanySetting;

class Settings extends BaseController
{
    protected function requireAdmin()
    {
        if (!session()->get('admin')) {
            return redirect()->to('/admin/login')->with('error', 'Please login to access the settings');
        }

        return null;
    }

    protected function loadCompanyName(): string
    {
        $model = new CompanySetting();
        $companySettings = $model->orderBy('id', 'ASC')->first();

        return $companySettings['company_name'] ?? 'Kuberan Foods Admin';
    }

    public function list()
    {
        if ($redirect = $this->requireAdmin()) {
            return $redirect;
        }

        $model = new CompanySetting();
        $companySettings = $model->findAll();
        $data = [
            'companies' => $companySettings,
            'company_name' => $this->loadCompanyName(),
        ];

        return view('/settings/list', $data);
    }

    public function add()
    {
        if ($redirect = $this->requireAdmin()) {
            return $redirect;
        }

        $data = [
            'company_name' => $this->loadCompanyName(),
            'validation' => \Config\Services::validation(),
        ];

        return view('/settings/add', $data);
    }

    public function create()
    {
        if ($redirect = $this->requireAdmin()) {
            return $redirect;
        }

        $rules = [
            'company_name' => 'required|min_length[2]|max_length[255]',
            'company_phone' => 'required|numeric|exact_length[10]',
            'company_email' => 'required|valid_email',
            'company_address' => 'permit_empty|max_length[500]',
            'company_fssai' => 'required|numeric|exact_length[14]',
            'company_logo' => 'permit_empty|is_image[company_logo]|max_size[company_logo,2048]',
            'company_gst' => ['required', 'regex_match[/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z][1-9A-Z]Z[0-9A-Z]$/]'],
            'swiggy' => 'permit_empty|valid_url',
            'zomato' => 'permit_empty|valid_url',
            'whatsapp_group' => 'permit_empty|valid_url',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $post = $this->request->getPost();
        $model = new CompanySetting();

        // Unique checks
        if ($model->where('company_phone', $post['company_phone'])->first()) {
            return redirect()->back()->withInput()->with('validation', $this->validator->setError('company_phone', 'Phone is already in use.'));
        }
        if ($model->where('company_email', $post['company_email'])->first()) {
            return redirect()->back()->withInput()->with('validation', $this->validator->setError('company_email', 'Email is already in use.'));
        }
        if ($model->where('company_fssai', $post['company_fssai'])->first()) {
            return redirect()->back()->withInput()->with('validation', $this->validator->setError('company_fssai', 'FSSAI is already in use.'));
        }
        if ($model->where('company_gst', $post['company_gst'])->first()) {
            return redirect()->back()->withInput()->with('validation', $this->validator->setError('company_gst', 'GST is already in use.'));
        }

        $logoPath = null;
        $file = $this->request->getFile('company_logo');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $uploadPath = FCPATH . 'uploads/company_logos';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            $newName = $file->getRandomName();
            $file->move($uploadPath, $newName);
            $logoPath = '/uploads/company_logos/' . $newName;
        }

        $model->insert([
            'company_name' => $post['company_name'],
            'company_phone' => $post['company_phone'],
            'company_email' => $post['company_email'],
            'company_address' => $post['company_address'],
            'company_fssai' => $post['company_fssai'],
            'company_logo' => $logoPath,
            'company_gst' => $post['company_gst'],
            'swiggy' => $post['swiggy'],
            'zomato' => $post['zomato'],
            'whatsapp_group' => $post['whatsapp_group'],
        ]);

        return redirect()->to('/admin/settings')->with('success', 'Company settings created successfully');
    }

    public function edit($id)
    {
        if ($redirect = $this->requireAdmin()) {
            return $redirect;
        }

        $model = new CompanySetting();
        $company = $model->find($id);

        if (!$company) {
            return redirect()->back()->with('error', 'Company settings record not found');
        }

        $data = [
            'company' => $company,
            'company_name' => $this->loadCompanyName(),
        ];

        return view('/settings/edit', $data);
    }

    public function update($id)
    {
        if ($redirect = $this->requireAdmin()) {
            return $redirect;
        }

        $model = new CompanySetting();
        $company = $model->find($id);
        if (!$company) {
            return redirect()->back()->with('error', 'Company settings record not found');
        }

        $rules = [
            'company_name' => 'required|min_length[2]|max_length[255]',
            'company_phone' => 'required|numeric|exact_length[10]',
            'company_email' => 'required|valid_email',
            'company_address' => 'permit_empty|max_length[500]',
            'company_fssai' => 'required|numeric|exact_length[14]',
            'company_logo' => 'permit_empty|is_image[company_logo]|max_size[company_logo,2048]',
            'company_gst' => ['required', 'regex_match[/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z][1-9A-Z]Z[0-9A-Z]$/]'],
            'swiggy' => 'permit_empty|valid_url',
            'zomato' => 'permit_empty|valid_url',
            'whatsapp_group' => 'permit_empty|valid_url',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $post = $this->request->getPost();

        // Unique checks (exclude current record)
        if ($model->where('company_phone', $post['company_phone'])->where('id !=', $id)->first()) {
            return redirect()->back()->withInput()->with('validation', $this->validator->setError('company_phone', 'Phone is already in use.'));
        }
        if ($model->where('company_email', $post['company_email'])->where('id !=', $id)->first()) {
            return redirect()->back()->withInput()->with('validation', $this->validator->setError('company_email', 'Email is already in use.'));
        }
        if ($model->where('company_fssai', $post['company_fssai'])->where('id !=', $id)->first()) {
            return redirect()->back()->withInput()->with('validation', $this->validator->setError('company_fssai', 'FSSAI is already in use.'));
        }
        if ($model->where('company_gst', $post['company_gst'])->where('id !=', $id)->first()) {
            return redirect()->back()->withInput()->with('validation', $this->validator->setError('company_gst', 'GST is already in use.'));
        }

        $logoPath = $post['existing_company_logo'] ?? '';
        $file = $this->request->getFile('company_logo');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $uploadPath = FCPATH . 'uploads/company_logos';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            $newName = $file->getRandomName();
            $file->move($uploadPath, $newName);
            $logoPath = '/uploads/company_logos/' . $newName;
        }

        $data = [
            'company_name' => $post['company_name'] ?? '',
            'company_phone' => $post['company_phone'] ?? '',
            'company_email' => $post['company_email'] ?? '',
            'company_address' => $post['company_address'] ?? '',
            'company_fssai' => $post['company_fssai'] ?? '',
            'company_logo' => $logoPath,
            'company_gst' => $post['company_gst'] ?? '',
            'swiggy' => $post['swiggy'] ?? '',
            'zomato' => $post['zomato'] ?? '',
            'whatsapp_group' => $post['whatsapp_group'] ?? '',
        ];

        $model->update($id, $data);

        return redirect()->to('/admin/settings')->with('success', 'Company settings updated successfully');
    }

    public function delete($id)
    {
        if ($redirect = $this->requireAdmin()) {
            return $redirect;
        }

        $model = new CompanySetting();
        if (!$model->find($id)) {
            return redirect()->back()->with('error', 'Company settings record not found');
        }

        $model->delete($id);

        return redirect()->to('/admin/settings')->with('success', 'Company settings deleted');
    }
}
