<?php

namespace App\Controllers;

use App\Models\ProdukModel;
use Config\Database;

class Produk extends BaseController
{
    public function index()
    {
        $produkModel = new ProdukModel();

        $search     = trim((string) $this->request->getGet('search'));
        $categoryId = (int) $this->request->getGet('category_id');

        $db = Database::connect();

        $categories = $db->table('tb_categories')
            ->select('id, name')
            ->orderBy('name', 'ASC')
            ->get()
            ->getResultArray();

        $produkList = $produkModel->getAllWithRelation($search, $categoryId > 0 ? $categoryId : null);

        return view('produk/index', [
            'title'       => 'Produk',
            'username'    => (string) session()->get('username'),
            'level'       => (string) session()->get('level'),
            'activeMenu'  => 'produk',
            'produkList'  => $produkList,
            'categories'  => $categories,
            'search'      => $search,
            'categoryId'  => $categoryId,
            'lowStockIds' => array_column($produkModel->getStokHampirHabis(), 'id'),
        ]);
    }

    public function create()
    {
        if (! $this->isAdmin()) {
            return $this->forbiddenRedirect();
        }

        return view('produk/form', $this->formData('Tambah Produk'));
    }

    public function store()
    {
        if (! $this->isAdmin()) {
            return $this->forbiddenRedirect();
        }

        $rules = $this->productValidationRules();

        if (! $this->validate($rules)) {
            return redirect()->to('/produk/create')->withInput()->with('error', 'Data produk belum valid.');
        }

        $produkModel = new ProdukModel();
        $produkModel->insert($this->payloadFromRequest());

        return redirect()->to('/produk')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(int $id)
    {
        if (! $this->isAdmin()) {
            return $this->forbiddenRedirect();
        }

        $produkModel = new ProdukModel();
        $produk      = $produkModel->find($id);

        if (! $produk) {
            return redirect()->to('/produk')->with('error', 'Produk tidak ditemukan.');
        }

        return view('produk/form', $this->formData('Edit Produk', $produk));
    }

    public function update(int $id)
    {
        if (! $this->isAdmin()) {
            return $this->forbiddenRedirect();
        }

        $produkModel = new ProdukModel();

        if (! $produkModel->find($id)) {
            return redirect()->to('/produk')->with('error', 'Produk tidak ditemukan.');
        }

        $rules = $this->productValidationRules();

        if (! $this->validate($rules)) {
            return redirect()->to('/produk/edit/' . $id)->withInput()->with('error', 'Data produk belum valid.');
        }

        $produkModel->update($id, $this->payloadFromRequest());

        return redirect()->to('/produk')->with('success', 'Produk berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        if (! $this->isAdmin()) {
            return $this->forbiddenRedirect();
        }

        $produkModel = new ProdukModel();

        if (! $produkModel->find($id)) {
            return redirect()->to('/produk')->with('error', 'Produk tidak ditemukan.');
        }

        if ($this->isProductReferenced($id)) {
            return redirect()->to('/produk')->with('error', 'Produk tidak bisa dihapus karena sudah dipakai pada transaksi atau stok masuk.');
        }

        $produkModel->delete($id);

        return redirect()->to('/produk')->with('success', 'Produk berhasil dihapus.');
    }

    private function formData(string $title, ?array $produk = null): array
    {
        $db = Database::connect();

        $categories = $db->table('tb_categories')
            ->select('id, name')
            ->orderBy('name', 'ASC')
            ->get()
            ->getResultArray();

        $units = $db->table('tb_units')
            ->select('id, name')
            ->orderBy('name', 'ASC')
            ->get()
            ->getResultArray();

        return [
            'title'      => $title,
            'username'   => (string) session()->get('username'),
            'level'      => (string) session()->get('level'),
            'activeMenu' => 'produk',
            'produk'     => $produk,
            'categories' => $categories,
            'units'      => $units,
        ];
    }

    private function payloadFromRequest(): array
    {
        return [
            'name'        => trim((string) $this->request->getPost('name')),
            'category_id' => (int) $this->request->getPost('category_id'),
            'unit_id'     => (int) $this->request->getPost('unit_id'),
            'price'       => (float) $this->request->getPost('price'),
            'buy_price'   => (float) $this->request->getPost('buy_price'),
            'stock'       => (int) $this->request->getPost('stock'),
            'min_stock'   => (int) $this->request->getPost('min_stock'),
            'description' => trim((string) $this->request->getPost('description')),
        ];
    }

    private function isAdmin(): bool
    {
        return session()->get('level') === 'admin';
    }

    private function productValidationRules(): array
    {
        return [
            'name'        => 'required|min_length[3]|max_length[120]',
            'category_id' => 'required|integer|is_not_unique[tb_categories.id]',
            'unit_id'     => 'required|integer|is_not_unique[tb_units.id]',
            'price'       => 'required|decimal|greater_than_equal_to[0]',
            'buy_price'   => 'required|decimal|greater_than_equal_to[0]',
            'stock'       => 'required|integer|greater_than_equal_to[0]',
            'min_stock'   => 'required|integer|greater_than_equal_to[0]',
        ];
    }

    private function isProductReferenced(int $productId): bool
    {
        $db = Database::connect();

        if ($db->tableExists('tb_stock_in')) {
            $isUsedInStockIn = (bool) $db->table('tb_stock_in')
                ->where('product_id', $productId)
                ->countAllResults();

            if ($isUsedInStockIn) {
                return true;
            }
        }

        if ($db->tableExists('tb_transaction_details')) {
            $isUsedInTransaction = (bool) $db->table('tb_transaction_details')
                ->where('product_id', $productId)
                ->countAllResults();

            if ($isUsedInTransaction) {
                return true;
            }
        }

        return false;
    }

    private function forbiddenRedirect()
    {
        return redirect()->to('/dashboard')->with('error', 'Akses ditolak. Hanya admin yang dapat mengubah data produk.');
    }
}