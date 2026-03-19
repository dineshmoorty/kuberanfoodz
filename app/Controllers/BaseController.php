<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 *
 * Extend this class in any new controllers:
 * ```
 *     class Home extends BaseController
 * ```
 *
 * For security, be sure to declare any new methods as protected or private.
 */

use App\Models\CompanySetting;

abstract class BaseController extends Controller
{
    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */

    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Load here all helpers you want to be available in your controllers that extend BaseController.
        // Caution: Do not put the this below the parent::initController() call below.
        // $this->helpers = ['form', 'url'];

        // Caution: Do not edit this line.
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.
        // $this->session = service('session');

        // Load company settings for global access in views (header, etc.)
        helper('url');
        $settings = (new CompanySetting())->orderBy('id', 'ASC')->first();

        $companyName = $settings['company_name'] ?? 'Kuberan Foods Admin';
        $companyLogo = $settings['company_logo'] ?? base_url('/images/logo.png');

        service('renderer')->setData([
            'company_name' => $companyName,
            'company_logo' => $companyLogo,
        ]);
    }

    protected function dashboardPathForRole(?string $role): string
    {
        return match ($role) {
            'sub-admin' => '/sub-admin/dashboard',
            'manager' => '/manager/dashboard',
            default => '/admin/dashboard',
        };
    }

    protected function requireAuthenticated(string $message = 'Please login to continue')
    {
        if (!session()->get('admin')) {
            return redirect()->to('/admin/login')->with('error', $message);
        }

        return null;
    }

    protected function requireAdminAccess(string $message = 'You do not have permission to access this page')
    {
        if ($redirect = $this->requireAuthenticated()) {
            return $redirect;
        }

        if (session()->get('admin_role') !== 'admin') {
            return redirect()->to($this->dashboardPathForRole((string) session()->get('admin_role')))->with('error', $message);
        }

        return null;
    }
}
