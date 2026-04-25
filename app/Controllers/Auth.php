<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function index()
    {
        if (session()->get('logged_in') === true) {
            $level = session()->get('level');

            if ($level === 'admin') {
                return redirect()->to('/dashboard/admin');
            }

            if ($level === 'kasir') {
                return redirect()->to('/dashboard/kasir');
            }

            return redirect()->to('/dashboard');
        }

        return view('auth/login');
    }

    public function login()
    {
        $rules = [
            'username' => 'required',
            'password' => 'required',
        ];

        if (! $this->validate($rules)) {
            return redirect()->to('/')->withInput()->with('error', 'Username dan password wajib diisi.');
        }

        $username = (string) $this->request->getPost('username');
        $password = (string) $this->request->getPost('password');

        $userModel = new UserModel();
        $user      = $userModel->getUserByUsername($username);

        if (! $user || $user['password'] !== md5($password)) {
            return redirect()->to('/')->withInput()->with('error', 'Login gagal. Username atau password salah.');
        }

        session()->set([
            'user_id'   => $user['id'],
            'username'  => $user['username'],
            'level'     => $user['level'],
            'logged_in' => true,
        ]);

        if ($user['level'] === 'admin') {
            return redirect()->to('/dashboard/admin');
        }

        if ($user['level'] === 'kasir') {
            return redirect()->to('/dashboard/kasir');
        }

        return redirect()->to('/dashboard');
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to('/')->with('success', 'Berhasil logout.');
    }
}