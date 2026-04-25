<?php

namespace App\Controllers;

use App\Models\StokMasukModel;
use Config\Database;

class StokMasuk extends BaseController
{
    public function index()
    {
        $db = Database::connect();

        $products = $db->table('tb_products')
            ->select('id, name, stock, min_stock')
            ->orderBy('name', 'ASC')
            ->get()
            ->getResultArray();

        $lowStockProducts = array_values(array_filter($products, static function (array $product): bool {
            return (int) ($product['stock'] ?? 0) <= (int) ($product['min_stock'] ?? 0);
        }));

        usort($lowStockProducts, static function (array $a, array $b): int {
            return ((int) ($a['stock'] ?? 0)) <=> ((int) ($b['stock'] ?? 0));
        });

        $suppliers = $db->table('tb_suppliers')
            ->select('id, name')
            ->orderBy('name', 'ASC')
            ->get()
            ->getResultArray();

        $dateFilter = trim((string) $this->request->getGet('date'));
        $history    = (new StokMasukModel())->getHistory($dateFilter);

        return view('stok/index', [
            'title'      => 'Stok Masuk',
            'username'   => (string) session()->get('username'),
            'level'      => (string) session()->get('level'),
            'activeMenu' => 'stok-masuk',
            'products'   => $products,
            'lowStockProducts' => array_slice($lowStockProducts, 0, 8),
            'suppliers'  => $suppliers,
            'history'    => $history,
            'dateFilter' => $dateFilter,
            'isAdmin'    => session()->get('level') === 'admin',
        ]);
    }

    public function store()
    {
        if (session()->get('level') !== 'admin') {
            return redirect()->to('/stok-masuk')->with('error', 'Akses ditolak. Hanya admin yang dapat menambah stok masuk.');
        }

        $rules = [
            'product_id'  => 'required|integer',
            'supplier_id' => 'required|integer',
            'qty'         => 'required|integer|greater_than[0]',
            'buy_price'   => 'required|decimal|greater_than[0]',
            'date'        => 'required|valid_date',
        ];

        if (! $this->validate($rules)) {
            return redirect()->to('/stok-masuk')->withInput()->with('error', 'Data stok masuk tidak valid.');
        }

        $productId  = (int) $this->request->getPost('product_id');
        $supplierId = (int) $this->request->getPost('supplier_id');
        $qty        = (int) $this->request->getPost('qty');
        $buyPrice   = (float) $this->request->getPost('buy_price');
        $date       = (string) $this->request->getPost('date');
        $notes      = trim((string) $this->request->getPost('notes'));

        $db = Database::connect();

        try {
            $db->transStart();

            $product = $db->table('tb_products')->where('id', $productId)->get()->getRowArray();
            if (! $product) {
                throw new \RuntimeException('Produk tidak ditemukan.');
            }

            $totalPrice = $qty * $buyPrice;

            $fields = $db->getFieldNames('tb_stock_in');
            $insertData = [
                'product_id'  => $productId,
                'supplier_id' => $supplierId,
                'user_id'     => (int) session()->get('user_id'),
                'qty'         => $qty,
                'buy_price'   => $buyPrice,
                'date'        => $date,
                'notes'       => $notes,
                'created_at'  => date('Y-m-d H:i:s'),
            ];

            if (in_array('total_price', $fields, true) && ! $this->isGeneratedColumn('tb_stock_in', 'total_price')) {
                $insertData['total_price'] = $totalPrice;
            }

            if (! in_array('created_at', $fields, true)) {
                unset($insertData['created_at']);
            }

            if (! in_array('notes', $fields, true)) {
                unset($insertData['notes']);
            }

            $db->table('tb_stock_in')->insert($insertData);

            $db->table('tb_products')
                ->set('stock', 'stock + ' . $qty, false)
                ->where('id', $productId)
                ->update();

            $db->transComplete();

            if (! $db->transStatus()) {
                $error = $db->error();
                $message = isset($error['message']) && $error['message'] !== ''
                    ? $error['message']
                    : 'Gagal menyimpan data stok masuk.';

                throw new \RuntimeException($message);
            }

            return redirect()->to('/stok-masuk')->with('success', 'Data stok masuk berhasil disimpan dan stok produk diperbarui.');
        } catch (\Throwable $e) {
            $db->transRollback();

            return redirect()->to('/stok-masuk')->withInput()->with('error', $e->getMessage());
        }
    }

    private function isGeneratedColumn(string $table, string $column): bool
    {
        $db = Database::connect();
        $row = $db->query("SHOW COLUMNS FROM `$table` LIKE ?", [$column])->getRowArray();

        if (! $row || ! isset($row['Extra'])) {
            return false;
        }

        return stripos((string) $row['Extra'], 'generated') !== false;
    }
}