<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Category;

class Categories extends BaseController
{
  protected function requireAdmin()
  {
    if (!session()->get('admin')) {
      return redirect()->to('/admin/login')->with('error', 'Please login to access categories');
    }

    return null;
  }

  public function list()
  {
    if ($redirect = $this->requireAdmin()) {
      return $redirect;
    }

    $model = new Category();
    $categories = $model->orderBy('id', 'ASC')->paginate(10);

    return view('/admin/categories/list', [
      'categories' => $categories,
      'pager' => $model->pager,
    ]);
  }

  public function create()
  {
    if ($redirect = $this->requireAdmin()) {
      return $redirect;
    }

    $model = new Category();

    $data = [
      'category_name' => trim($this->request->getPost('category_name')),
    ];

    if (!empty($data['category_name']) && $model->where('category_name', $data['category_name'])->first()) {
      return redirect()->back()->withInput()->with('error', 'Category name already exists.');
    }

    if (!$this->validate($model->getValidationRules())) {
      return redirect()->back()->withInput()->with('validation', $this->validator);
    }

    $model->insert($data);

    return redirect()->to('/admin/categories')->with('success', 'Category created successfully');
  }

  public function edit($id)
  {
    if ($redirect = $this->requireAdmin()) {
      return $redirect;
    }

    $model = new Category();
    $category = $model->find($id);

    if (!$category) {
      return redirect()->to('/admin/categories')->with('error', 'Category not found');
    }

    return view('/admin/categories/edit', ['category' => $category]);
  }

  public function update($id)
  {
    if ($redirect = $this->requireAdmin()) {
      return $redirect;
    }

    $model = new Category();
    $category = $model->find($id);

    if (!$category) {
      return redirect()->to('/admin/categories')->with('error', 'Category not found');
    }

    $data = [
      'category_name' => trim($this->request->getPost('category_name')),
    ];

    if (!empty($data['category_name']) && $model->where('category_name', $data['category_name'])->where('id !=', $id)->first()) {
      return redirect()->back()->withInput()->with('error', 'Category name already exists.');
    }

    if (!$this->validate($model->getValidationRules())) {
      return redirect()->back()->withInput()->with('validation', $this->validator);
    }

    $model->update($id, $data);

    return redirect()->to('/admin/categories')->with('success', 'Category updated successfully');
  }

  public function delete($id)
  {
    if ($redirect = $this->requireAdmin()) {
      return $redirect;
    }

    $model = new Category();
    if (!$model->find($id)) {
      return redirect()->to('/admin/categories')->with('error', 'Category not found');
    }

    $model->delete($id);

    return redirect()->to('/admin/categories')->with('success', 'Category deleted successfully');
  }
}
