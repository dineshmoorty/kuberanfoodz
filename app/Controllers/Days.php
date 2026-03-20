<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Day;
use App\Models\CompanySetting;

class Days extends BaseController
{
  protected function requireAdmin()
  {
    return $this->requireAdminAccess('Only admin can manage days');
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

    $model = new Day();
    $days = $model->orderBy('id', 'ASC')->paginate(10);

    return view('/admin/days/list', [
      'days' => $days,
      'pager' => $model->pager,
      'company_name' => $this->loadCompanyName(),
    ]);
  }

  public function create()
  {
    if ($redirect = $this->requireAdmin()) {
      return $redirect;
    }

    $rules = [
      'day_name' => 'required|max_length[50]',
    ];

    if (!$this->validate($rules)) {
      return redirect()->back()->withInput()->with('validation', $this->validator);
    }

    $dayName = trim($this->request->getPost('day_name'));

    $model = new Day();
    if ($model->where('day_name', $dayName)->first()) {
      return redirect()->back()->withInput()->with('validation', $this->validator->setError('day_name', 'Day already exists.'));
    }

    $model->insert(['day_name' => $dayName]);

    return redirect()->to('/admin/days')->with('success', 'Day added successfully');
  }

  public function update($id)
  {
    if ($redirect = $this->requireAdmin()) {
      return $redirect;
    }

    $model = new Day();
    $day = $model->find($id);
    if (!$day) {
      return redirect()->to('/admin/days')->with('error', 'Day record not found');
    }

    $rules = [
      'day_name' => 'required|max_length[50]',
    ];

    if (!$this->validate($rules)) {
      return redirect()->back()->withInput()->with('validation', $this->validator);
    }

    $dayName = trim($this->request->getPost('day_name'));

    if ($model->where('day_name', $dayName)->where('id !=', $id)->first()) {
      return redirect()->back()->withInput()->with('validation', $this->validator->setError('day_name', 'Day already exists.'));
    }

    $model->update($id, ['day_name' => $dayName]);

    return redirect()->to('/admin/days')->with('success', 'Day updated successfully');
  }

  public function delete($id)
  {
    if ($redirect = $this->requireAdmin()) {
      return $redirect;
    }

    $model = new Day();
    $day = $model->find($id);
    if (!$day) {
      return redirect()->to('/admin/days')->with('error', 'Day record not found');
    }

    $model->delete($id);

    return redirect()->to('/admin/days')->with('success', 'Day deleted successfully');
  }
}
