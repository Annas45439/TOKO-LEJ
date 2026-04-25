<?php

namespace App\Controllers;

use App\Models\PelangganModel;
use Config\Database;

class Pelanggan extends BaseController
{
    public function index()
    {
        $db       = Database::connect();
        $hasTable = $db->tableExists('tb_customers');
        $fields   = $hasTable ? $db->getFieldNames('tb_customers') : [];
        $search   = trim((string) $this->request->getGet('search'));
        $editId   = (int) $this->request->getGet('edit');

        $rows = [];
        $editRow = null;

        if ($hasTable) {
            $builder = $db->table('tb_customers');

            if ($search !== '' && in_array('name', $fields, true)) {
                $builder->like('name', $search);
            }

            if (in_array('id', $fields, true)) {
                $builder->orderBy('id', 'DESC');
            } elseif (in_array('name', $fields, true)) {
                $builder->orderBy('name', 'ASC');
            }

            $rows = $builder->get()->getResultArray();

            if ($editId > 0) {
                $editRow = (new PelangganModel())->find($editId);
            }
        }

        return view('pelanggan/index', [
            'title'      => 'Pelanggan',
            'username'   => (string) session()->get('username'),
            'level'      => (string) session()->get('level'),
            'activeMenu' => 'pelanggan',
            'rows'       => $rows,
            'search'     => $search,
            'hasTable'   => $hasTable,
            'fields'     => $fields,
            'editRow'    => $editRow,
            'isAdmin'    => $this->isAdmin(),
        ]);
    }

    public function store()
    {
        if (! $this->isAdmin()) {
            return redirect()->to('/pelanggan')->with('error', 'Akses ditolak. Hanya admin yang dapat mengubah data pelanggan.');
        }

        $db = Database::connect();
        if (! $db->tableExists('tb_customers')) {
            return redirect()->to('/pelanggan')->with('error', 'Tabel pelanggan belum tersedia.');
        }

        $fields = $db->getFieldNames('tb_customers');
        $rules = [];

        if (in_array('name', $fields, true)) {
            $rules['name'] = 'required|min_length[2]|max_length[120]';
        }

        if (! $this->validate($rules)) {
            return redirect()->to('/pelanggan')->withInput()->with('error', 'Data pelanggan belum valid.');
        }

        (new PelangganModel())->insert($this->payloadFromRequest($fields, true));

        return redirect()->to('/pelanggan')->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    public function update(int $id)
    {
        if (! $this->isAdmin()) {
            return redirect()->to('/pelanggan')->with('error', 'Akses ditolak. Hanya admin yang dapat mengubah data pelanggan.');
        }

        $model = new PelangganModel();
        $row   = $model->find($id);

        if (! $row) {
            return redirect()->to('/pelanggan')->with('error', 'Pelanggan tidak ditemukan.');
        }

        $db = Database::connect();
        $fields = $db->tableExists('tb_customers') ? $db->getFieldNames('tb_customers') : [];
        $rules = [];

        if (in_array('name', $fields, true)) {
            $rules['name'] = 'required|min_length[2]|max_length[120]';
        }

        if (! $this->validate($rules)) {
            return redirect()->to('/pelanggan?edit=' . $id)->withInput()->with('error', 'Data pelanggan belum valid.');
        }

        $model->update($id, $this->payloadFromRequest($fields, false));

        return redirect()->to('/pelanggan')->with('success', 'Pelanggan berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        if (! $this->isAdmin()) {
            return redirect()->to('/pelanggan')->with('error', 'Akses ditolak. Hanya admin yang dapat mengubah data pelanggan.');
        }

        $model = new PelangganModel();

        if (! $model->find($id)) {
            return redirect()->to('/pelanggan')->with('error', 'Pelanggan tidak ditemukan.');
        }

        $model->delete($id);

        return redirect()->to('/pelanggan')->with('success', 'Pelanggan berhasil dihapus.');
    }

    private function payloadFromRequest(array $fields, bool $isCreate): array
    {
        $payload = [];

        $map = [
            'name'    => trim((string) $this->request->getPost('name')),
            'phone'   => trim((string) $this->request->getPost('phone')),
            'email'   => trim((string) $this->request->getPost('email')),
            'address' => trim((string) $this->request->getPost('address')),
            'notes'   => trim((string) $this->request->getPost('notes')),
        ];

        foreach ($map as $column => $value) {
            if (in_array($column, $fields, true)) {
                $payload[$column] = $value;
            }
        }

        $now = date('Y-m-d H:i:s');
        if ($isCreate && in_array('created_at', $fields, true)) {
            $payload['created_at'] = $now;
        }

        if (in_array('updated_at', $fields, true)) {
            $payload['updated_at'] = $now;
        }

        return $payload;
    }

    private function isAdmin(): bool
    {
        return session()->get('level') === 'admin';
    }
}
