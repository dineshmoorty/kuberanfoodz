<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Admin;
use App\Models\CompanySetting;
use App\Models\Role;

class Auth extends BaseController
{
    protected function requireAdmin()
    {
        return $this->requireAdminAccess('Only admin can manage profiles');
    }

    protected function resolveRoleSlugFromAdmin(array $admin): string
    {
        if (!empty($admin['role_id'])) {
            $role = (new Role())->find((int) $admin['role_id']);

            if ($role) {
                return $role['slug'];
            }
        }

        return trim(strtolower((string) ($admin['role'] ?? ''))) ?: 'admin';
    }

    protected function syncAdminSession(array $admin): void
    {
        $roleSlug = $this->resolveRoleSlugFromAdmin($admin);

        session()->set([
            'admin' => $admin['username'],
            'admin_id' => $admin['id'],
            'admin_name' => (($admin['name'] ?? '') ?: $admin['username']),
            'admin_role' => $roleSlug,
            'admin_role_id' => $admin['role_id'] ?? null,
            'company_id' => $admin['company_id'] ?? null,
        ]);
    }

    protected function profileReferenceData(): array
    {
        return [
            'roles' => (new Role())->orderBy('name', 'ASC')->findAll(),
            'companies' => (new CompanySetting())->orderBy('company_name', 'ASC')->findAll(),
        ];
    }

    protected function buildProfilePayload(Role $roleModel, CompanySetting $companyModel): ?array
    {
        $roleId = (int) $this->request->getPost('role_id');
        $companyId = (int) $this->request->getPost('company_id');
        $role = $roleModel->find($roleId);
        $company = $companyModel->find($companyId);

        if (!$role || !$company) {
            return null;
        }

        return [
            'username' => trim((string) $this->request->getPost('username')),
            'name' => trim((string) $this->request->getPost('name')),
            'dob' => $this->request->getPost('dob') ?: null,
            'mobile' => trim((string) $this->request->getPost('mobile')) ?: null,
            'company_id' => (int) $company['id'],
            'role_id' => (int) $role['id'],
            'role' => $role['slug'],
        ];
    }

    protected function setProfileModalFlash(string $mode, ?int $profileId = null, bool $showPasswordSection = false): void
    {
        session()->setFlashdata('profile_modal_mode', $mode);
        session()->setFlashdata('profile_show_password', $showPasswordSection);

        if ($profileId !== null) {
            session()->setFlashdata('profile_modal_id', $profileId);
        }
    }

    protected function validateUniqueProfileFields(Admin $model, array $data, ?int $ignoreId = null): ?\CodeIgniter\HTTP\RedirectResponse
    {
        $usernameQuery = $model->where('username', $data['username']);
        if ($ignoreId !== null) {
            $usernameQuery->where('id !=', $ignoreId);
        }

        if ($usernameQuery->first()) {
            return redirect()->back()->withInput()->with('validation', $this->validator->setError('username', 'Username already exists.'));
        }

        if (!empty($data['mobile'])) {
            $mobileQuery = $model->where('mobile', $data['mobile']);
            if ($ignoreId !== null) {
                $mobileQuery->where('id !=', $ignoreId);
            }

            if ($mobileQuery->first()) {
                return redirect()->back()->withInput()->with('validation', $this->validator->setError('mobile', 'Mobile number already exists.'));
            }
        }

        return null;
    }

    public function adminLogin()
    {
        if (session()->get('admin')) {
            return redirect()->to($this->dashboardPathForRole((string) session()->get('admin_role')));
        }

        return view('Auth/adminlogin');
    }

    public function adminAuthenticate()
    {
        $rules = [
            'username' => 'required|max_length[255]',
            'password' => 'required|max_length[255]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $model = new Admin();
        $username = trim((string) $this->request->getPost('username'));
        $password = $this->request->getPost('password');

        $admin = $model->where('username', $username)->first();

        if ($admin && password_verify($password, $admin['password'])) {
            session()->regenerate();
            $this->syncAdminSession($admin);

            return redirect()->to($this->dashboardPathForRole($this->resolveRoleSlugFromAdmin($admin)));
        }

        return redirect()->back()->withInput()->with('error', 'Invalid username or password');
    }

    public function profile()
    {
        if ($redirect = $this->requireAdmin()) {
            return $redirect;
        }

        $model = new Admin();
        $profiles = $model->orderBy('id', 'ASC')->paginate(10);
        $modalProfileId = (int) (session()->getFlashdata('profile_modal_id') ?: 0);
        $modalProfileRoleSlug = '';
        $modalProfileRoleName = '';

        if ($modalProfileId > 0) {
            $modalProfile = $model->find($modalProfileId);
            if ($modalProfile) {
                $modalProfileRoleSlug = $this->resolveRoleSlugFromAdmin($modalProfile);

                if (!empty($modalProfile['role_id'])) {
                    $modalRole = (new Role())->find((int) $modalProfile['role_id']);
                    $modalProfileRoleName = $modalRole['name'] ?? '';
                }
            }
        }

        return view('/admin/profiles/list', array_merge([
            'profiles' => $profiles,
            'pager' => $model->pager,
            'currentAdminId' => (int) session()->get('admin_id'),
            'modalMode' => session()->getFlashdata('profile_modal_mode') ?: '',
            'modalProfileId' => $modalProfileId,
            'modalProfileRoleSlug' => $modalProfileRoleSlug,
            'modalProfileRoleName' => $modalProfileRoleName,
            'showPasswordSection' => (bool) session()->getFlashdata('profile_show_password'),
        ], $this->profileReferenceData()));
    }

    public function createProfile()
    {
        if ($redirect = $this->requireAdmin()) {
            return $redirect;
        }

        $adminModel = new Admin();
        $roleModel = new Role();
        $companyModel = new CompanySetting();

        $rules = [
            'username' => 'required|min_length[3]|max_length[255]',
            'name' => 'required|min_length[2]|max_length[150]',
            'dob' => 'permit_empty|valid_date[Y-m-d]',
            'mobile' => 'permit_empty|numeric|exact_length[10]',
            'role_id' => 'required|integer|greater_than[0]',
            'company_id' => 'required|integer|greater_than[0]',
            'new_password' => 'required|min_length[6]|max_length[255]',
            'confirm_password' => 'required|matches[new_password]',
        ];

        if (!$this->validate($rules)) {
            $this->setProfileModalFlash('create');

            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $data = $this->buildProfilePayload($roleModel, $companyModel);
        if (!$data) {
            $this->setProfileModalFlash('create');

            return redirect()->back()->withInput()->with('validation', $this->validator->setError('role_id', 'Please select a valid role and company.'));
        }

        if ($data['role'] === 'admin') {
            $this->setProfileModalFlash('create');

            return redirect()->back()->withInput()->with('validation', $this->validator->setError('role_id', 'Admin role cannot be assigned from profiles.'));
        }

        if ($redirect = $this->validateUniqueProfileFields($adminModel, $data)) {
            $this->setProfileModalFlash('create');

            return $redirect;
        }

        $data['password'] = password_hash((string) $this->request->getPost('new_password'), PASSWORD_DEFAULT);

        $adminModel->insert($data);

        return redirect()->to('/admin/profiles')->with('success', 'Profile created successfully');
    }

    public function updateProfile($id)
    {
        if ($redirect = $this->requireAdmin()) {
            return $redirect;
        }

        $adminModel = new Admin();
        $roleModel = new Role();
        $companyModel = new CompanySetting();
        $admin = $adminModel->find($id);

        if (!$admin) {
            return redirect()->to('/admin/profiles')->with('error', 'Profile not found');
        }

        $isOwnProfile = (int) $admin['id'] === (int) session()->get('admin_id');
        $passwordFieldsFilled = (bool) ($this->request->getPost('new_password') || $this->request->getPost('confirm_password'));

        $rules = [
            'username' => 'required|min_length[3]|max_length[255]',
            'name' => 'required|min_length[2]|max_length[150]',
            'dob' => 'permit_empty|valid_date[Y-m-d]',
            'mobile' => 'permit_empty|numeric|exact_length[10]',
            'role_id' => 'required|integer|greater_than[0]',
            'company_id' => 'required|integer|greater_than[0]',
        ];

        if ($passwordFieldsFilled) {
            $rules['new_password'] = 'required|min_length[6]|max_length[255]';
            $rules['confirm_password'] = 'required|matches[new_password]';
        }

        if ($isOwnProfile && $passwordFieldsFilled) {
            $rules['current_password'] = 'required';
        }

        if (!$this->validate($rules)) {
            $this->setProfileModalFlash('edit', (int) $id, $isOwnProfile && $passwordFieldsFilled);

            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $data = $this->buildProfilePayload($roleModel, $companyModel);
        if (!$data) {
            $this->setProfileModalFlash('edit', (int) $id, $isOwnProfile && $passwordFieldsFilled);

            return redirect()->back()->withInput()->with('validation', $this->validator->setError('role_id', 'Please select a valid role and company.'));
        }

        if ($data['role'] === 'admin' && $this->resolveRoleSlugFromAdmin($admin) !== 'admin') {
            $this->setProfileModalFlash('edit', (int) $id, $isOwnProfile && $passwordFieldsFilled);

            return redirect()->back()->withInput()->with('validation', $this->validator->setError('role_id', 'Admin role cannot be assigned from profiles.'));
        }

        if ($redirect = $this->validateUniqueProfileFields($adminModel, $data, (int) $id)) {
            $this->setProfileModalFlash('edit', (int) $id, $isOwnProfile && $passwordFieldsFilled);

            return $redirect;
        }

        if ($isOwnProfile && $passwordFieldsFilled && !password_verify((string) $this->request->getPost('current_password'), $admin['password'])) {
            $this->setProfileModalFlash('edit', (int) $id, true);

            return redirect()->back()->withInput()->with('validation', $this->validator->setError('current_password', 'Current password is incorrect.'));
        }

        if ($passwordFieldsFilled) {
            $data['password'] = password_hash((string) $this->request->getPost('new_password'), PASSWORD_DEFAULT);
        }

        $adminModel->update($admin['id'], $data);

        $updatedAdmin = $adminModel->find($admin['id']);

        if ($isOwnProfile && $passwordFieldsFilled) {
            session()->destroy();

            return redirect()->to('/admin/login')->with('success', 'Password changed successfully. Please login again.');
        }

        if ($isOwnProfile) {
            $this->syncAdminSession($updatedAdmin);
        }

        return redirect()->to('/admin/profiles')->with('success', 'Profile updated successfully');
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to('/admin/login');
    }

    public function deleteProfile($id)
    {
        if ($redirect = $this->requireAdmin()) {
            return $redirect;
        }

        $adminModel = new Admin();
        $profile = $adminModel->find($id);

        if (!$profile) {
            return redirect()->to('/admin/profiles')->with('error', 'Profile not found');
        }

        if ($this->resolveRoleSlugFromAdmin($profile) === 'admin') {
            return redirect()->to('/admin/profiles')->with('error', 'Admin profile cannot be deleted.');
        }

        $adminModel->delete($id);

        return redirect()->to('/admin/profiles')->with('success', 'Profile deleted successfully');
    }
}
