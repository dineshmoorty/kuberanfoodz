<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Admin;
use App\Models\Role;

class Roles extends BaseController
{
    protected function setRoleModalFlash(string $mode, ?int $roleId = null): void
    {
        session()->setFlashdata('role_modal_mode', $mode);

        if ($roleId !== null) {
            session()->setFlashdata('role_modal_id', $roleId);
        }
    }

    protected function buildRolePayload(): array
    {
        $name = trim((string) $this->request->getPost('name'));

        return [
            'name' => $name,
            'slug' => url_title($name, '-', true),
            'description' => trim((string) $this->request->getPost('description')) ?: null,
        ];
    }

    public function list()
    {
        if ($redirect = $this->requireAdminAccess('Only admin can manage roles')) {
            return $redirect;
        }

        $model = new Role();
        $roles = $model->orderBy('id', 'ASC')->paginate(10);

        return view('/admin/roles/list', [
            'roles' => $roles,
            'pager' => $model->pager,
            'modalMode' => session()->getFlashdata('role_modal_mode') ?: '',
            'modalRoleId' => (int) (session()->getFlashdata('role_modal_id') ?: 0),
        ]);
    }

    public function create()
    {
        if ($redirect = $this->requireAdminAccess('Only admin can manage roles')) {
            return $redirect;
        }

        $model = new Role();
        $data = $this->buildRolePayload();

        if (!$this->validate($model->getValidationRules())) {
            $this->setRoleModalFlash('create');

            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        if ($model->where('slug', $data['slug'])->first()) {
            $this->setRoleModalFlash('create');

            return redirect()->back()->withInput()->with('validation', $this->validator->setError('name', 'Role already exists.'));
        }

        $model->insert($data);

        return redirect()->to('/admin/roles')->with('success', 'Role created successfully');
    }

    public function update($id)
    {
        if ($redirect = $this->requireAdminAccess('Only admin can manage roles')) {
            return $redirect;
        }

        $model = new Role();
        $role = $model->find($id);

        if (!$role) {
            return redirect()->to('/admin/roles')->with('error', 'Role not found');
        }

        $data = $this->buildRolePayload();

        if (!$this->validate($model->getValidationRules())) {
            $this->setRoleModalFlash('edit', (int) $id);

            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        if ($model->where('slug', $data['slug'])->where('id !=', $id)->first()) {
            $this->setRoleModalFlash('edit', (int) $id);

            return redirect()->back()->withInput()->with('validation', $this->validator->setError('name', 'Role already exists.'));
        }

        $model->update($id, $data);

        return redirect()->to('/admin/roles')->with('success', 'Role updated successfully');
    }

    public function delete($id)
    {
        if ($redirect = $this->requireAdminAccess('Only admin can manage roles')) {
            return $redirect;
        }

        $model = new Role();
        $role = $model->find($id);

        if (!$role) {
            return redirect()->to('/admin/roles')->with('error', 'Role not found');
        }

        if ((new Admin())->where('role_id', $id)->first()) {
            return redirect()->to('/admin/roles')->with('error', 'Role is already assigned to one or more profiles.');
        }

        $model->delete($id);

        return redirect()->to('/admin/roles')->with('success', 'Role deleted successfully');
    }
}
