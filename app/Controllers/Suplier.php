<?php

namespace App\Controllers;

use App\Models\SuplierModel;
use Config\Database;

class Suplier extends BaseController
{
    public function index()
    {
        $db       = Database::connect();
        $hasTable = $db->tableExists('tb_suppliers');
        $fields   = $hasTable ? $db->getFieldNames('tb_suppliers') : [];
        $search   = trim((string) $this->request->getGet('search'));
        $editId   = (int) $this->request->getGet('edit');

        $rows = [];
        $editRow = null;

        if ($hasTable) {
            $builder = $db->table('tb_suppliers');

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
                $editRow = (new SuplierModel())->find($editId);
            }
        }

        return view('suplier/index', [
            'title'      => 'Suplier',
            'username'   => (string) session()->get('username'),
            'level'      => (string) session()->get('level'),
            'activeMenu' => 'suplier',
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
            return redirect()->to('/suplier')->with('error', 'Akses ditolak. Hanya admin yang dapat mengubah data suplier.');
        }

        $db = Database::connect();
        if (! $db->tableExists('tb_suppliers')) {
            return redirect()->to('/suplier')->with('error', 'Tabel suplier belum tersedia.');
        }

        $fields = $db->getFieldNames('tb_suppliers');
        $rules = [];

        if (in_array('name', $fields, true)) {
            $rules['name'] = 'required|min_length[2]|max_length[120]';
        }

        if (! $this->validate($rules)) {
            return redirect()->to('/suplier')->withInput()->with('error', 'Data suplier belum valid.');
        }

        (new SuplierModel())->insert($this->payloadFromRequest($fields, true));

        return redirect()->to('/suplier')->with('success', 'Suplier berhasil ditambahkan.');
    }

    public function update(int $id)
    {
        if (! $this->isAdmin()) {
            return redirect()->to('/suplier')->with('error', 'Akses ditolak. Hanya admin yang dapat mengubah data suplier.');
        }

        $model = new SuplierModel();
        $row   = $model->find($id);

        if (! $row) {
            return redirect()->to('/suplier')->with('error', 'Suplier tidak ditemukan.');
        }

        $db = Database::connect();
        $fields = $db->tableExists('tb_suppliers') ? $db->getFieldNames('tb_suppliers') : [];
        $rules = [];

        if (in_array('name', $fields, true)) {
            $rules['name'] = 'required|min_length[2]|max_length[120]';
        }

        if (! $this->validate($rules)) {
            return redirect()->to('/suplier?edit=' . $id)->withInput()->with('error', 'Data suplier belum valid.');
        }

        $model->update($id, $this->payloadFromRequest($fields, false));

        return redirect()->to('/suplier')->with('success', 'Suplier berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        if (! $this->isAdmin()) {
            return redirect()->to('/suplier')->with('error', 'Akses ditolak. Hanya admin yang dapat mengubah data suplier.');
        }

        $model = new SuplierModel();

        if (! $model->find($id)) {
            return redirect()->to('/suplier')->with('error', 'Suplier tidak ditemukan.');
        }

        $model->delete($id);

        return redirect()->to('/suplier')->with('success', 'Suplier berhasil dihapus.');
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
