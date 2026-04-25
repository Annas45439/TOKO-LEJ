<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (session()->get('logged_in') !== true) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $allowedRoles = is_array($arguments) ? $arguments : [];
        $userRole     = (string) session()->get('level');

        if ($allowedRoles === [] || in_array($userRole, $allowedRoles, true)) {
            return null;
        }

        return redirect()->to('/dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return null;
    }
}