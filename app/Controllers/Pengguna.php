<?php

namespace App\Controllers;

use App\Models\UserModel;
use Config\Database;

class Pengguna extends BaseController
{
    public function index()
    {
        $db = Database::connect();
        $hasTable = $db->tableExists('tb_users');
        $fields = $hasTable ? $db->getFieldNames('tb_users') : [];
        $search = trim((string) $this->request->getGet('search'));
        $editId = (int) $this->request->getGet('edit');

        $rows = [];
        $editRow = null;

        if ($hasTable) {
            $builder = $db->table('tb_users');

            if ($search !== '' && in_array('username', $fields, true)) {
                $builder->like('username', $search);
            }

            if (in_array('id', $fields, true)) {
                $builder->orderBy('id', 'DESC');
            }

            $rows = $builder->get()->getResultArray();

            if ($editId > 0) {
                $editRow = (new UserModel())->find($editId);
            }
        }

        return view('pengguna/index', [
            'title' => 'Manajemen Pengguna',
            'username' => (string) session()->get('username'),
            'level' => (string) session()->get('level'),
            'activeMenu' => 'pengguna',
            'rows' => $rows,
            'search' => $search,
            'hasTable' => $hasTable,
            'fields' => $fields,
            'editRow' => $editRow,
            'currentUserId' => (int) session()->get('user_id'),
        ]);
    }

    public function store()
    {
        $db = Database::connect();

        if (! $db->tableExists('tb_users')) {
            return redirect()->to('/pengguna')->with('error', 'Tabel pengguna belum tersedia.');
        }

        $rules = [
            'username' => 'required|min_length[3]|max_length[60]|is_unique[tb_users.username]',
            'password' => 'required|min_length[6]',
            'level' => 'required|in_list[admin,kasir]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->to('/pengguna')->withInput()->with('error', 'Data pengguna belum valid.');
        }

        $fields = $db->getFieldNames('tb_users');
        $payload = [
            'username' => trim((string) $this->request->getPost('username')),
            'password' => md5((string) $this->request->getPost('password')),
            'level' => trim((string) $this->request->getPost('level')),
        ];

        $now = date('Y-m-d H:i:s');
        if (in_array('created_at', $fields, true)) {
            $payload['created_at'] = $now;
        }
        if (in_array('updated_at', $fields, true)) {
            $payload['updated_at'] = $now;
        }

        (new UserModel())->insert($payload);

        return redirect()->to('/pengguna')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function update(int $id)
    {
        $userModel = new UserModel();
        $row = $userModel->find($id);

        if (! $row) {
            return redirect()->to('/pengguna')->with('error', 'Pengguna tidak ditemukan.');
        }

        $rules = [
            'username' => 'required|min_length[3]|max_length[60]',
            'level' => 'required|in_list[admin,kasir]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->to('/pengguna?edit=' . $id)->withInput()->with('error', 'Data pengguna belum valid.');
        }

        $newUsername = trim((string) $this->request->getPost('username'));
        $exists = $userModel->where('username', $newUsername)->where('id !=', $id)->first();
        if ($exists) {
            return redirect()->to('/pengguna?edit=' . $id)->withInput()->with('error', 'Username sudah dipakai pengguna lain.');
        }

        $db = Database::connect();
        $fields = $db->tableExists('tb_users') ? $db->getFieldNames('tb_users') : [];

        $payload = [
            'username' => $newUsername,
            'level' => trim((string) $this->request->getPost('level')),
        ];

        $password = trim((string) $this->request->getPost('password'));
        if ($password !== '') {
            if (strlen($password) < 6) {
                return redirect()->to('/pengguna?edit=' . $id)->withInput()->with('error', 'Password minimal 6 karakter.');
            }

            $payload['password'] = md5($password);
        }

        if (in_array('updated_at', $fields, true)) {
            $payload['updated_at'] = date('Y-m-d H:i:s');
        }

        $userModel->update($id, $payload);

        return redirect()->to('/pengguna')->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        $currentUserId = (int) session()->get('user_id');
        if ($id === $currentUserId) {
            return redirect()->to('/pengguna')->with('error', 'Anda tidak bisa menghapus akun yang sedang dipakai login.');
        }

        $userModel = new UserModel();

        if (! $userModel->find($id)) {
            return redirect()->to('/pengguna')->with('error', 'Pengguna tidak ditemukan.');
        }

        $userModel->delete($id);

        return redirect()->to('/pengguna')->with('success', 'Pengguna berhasil dihapus.');
    }
}
