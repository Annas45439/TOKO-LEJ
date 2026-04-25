<?php

namespace App\Controllers;

use App\Models\TransaksiModel;
use Config\Database;

class Transaksi extends BaseController
{
    public function index()
    {
        $model = new TransaksiModel();
        $products = $model->getProductsForPos();
        $lowStockProducts = array_values(array_filter($products, static function (array $product): bool {
            return (int) ($product['stock'] ?? 0) <= (int) ($product['min_stock'] ?? 0);
        }));

        return view('transaksi/pos', [
            'title'      => 'Transaksi POS',
            'username'   => (string) session()->get('username'),
            'level'      => (string) session()->get('level'),
            'activeMenu' => 'transaksi',
            'products'   => $products,
            'lowStockProducts' => $lowStockProducts,
            'customers'  => $model->getCustomers(),
        ]);
    }

    public function store()
    {
        $customerId    = (int) $this->request->getPost('customer_id');
        $paymentMethod = trim((string) $this->request->getPost('payment_method'));
        $paymentAmount = (float) $this->request->getPost('payment_amount');
        $itemsJson     = (string) $this->request->getPost('items_json');

        if ($customerId <= 0 || $itemsJson === '' || ! in_array($paymentMethod, ['Tunai', 'Kartu'], true)) {
            return redirect()->to('/transaksi')->with('error', 'Data transaksi tidak lengkap.');
        }

        $items = json_decode($itemsJson, true);
        if (! is_array($items) || $items === []) {
            return redirect()->to('/transaksi')->with('error', 'Keranjang belanja kosong.');
        }

        $db = Database::connect();

        try {
            $db->transStart();

            $total   = 0.0;
            $details = [];

            foreach ($items as $item) {
                $productId = (int) ($item['product_id'] ?? 0);
                $qty       = (int) ($item['qty'] ?? 0);

                if ($productId <= 0 || $qty <= 0) {
                    throw new \RuntimeException('Item transaksi tidak valid.');
                }

                $product = $db->table('tb_products')->where('id', $productId)->get()->getRowArray();
                if (! $product) {
                    throw new \RuntimeException('Produk tidak ditemukan.');
                }

                if ((int) $product['stock'] < $qty) {
                    throw new \RuntimeException('Stok produk "' . $product['name'] . '" tidak mencukupi.');
                }

                $price    = (float) $product['price'];
                $subtotal = $price * $qty;
                $total   += $subtotal;

                $details[] = [
                    'product_id' => $productId,
                    'qty'        => $qty,
                    'price'      => $price,
                    'subtotal'   => $subtotal,
                ];
            }

            if ($paymentMethod === 'Kartu') {
                $paymentAmount = $total;
            }

            if ($paymentAmount < $total) {
                throw new \RuntimeException('Nominal pembayaran kurang dari total transaksi.');
            }

            $invoiceNo    = $this->generateInvoiceNo($db);

            $db->table('tb_transactions')->insert([
                'invoice_no'      => $invoiceNo,
                'customer_id'     => $customerId,
                'user_id'         => (int) session()->get('user_id'),
                'total'           => $total,
                'payment_method'  => $paymentMethod,
                'payment_amount'  => $paymentAmount,
                'status'          => 'Selesai',
                'date'            => date('Y-m-d'),
                'created_at'      => date('Y-m-d H:i:s'),
            ]);

            $transactionId = (int) $db->insertID();

            foreach ($details as $detail) {
                $db->table('tb_transaction_details')->insert([
                    'transaction_id' => $transactionId,
                    'product_id'     => $detail['product_id'],
                    'qty'            => $detail['qty'],
                    'price'          => $detail['price'],
                ]);

                $db->table('tb_products')
                    ->set('stock', 'stock - ' . (int) $detail['qty'], false)
                    ->where('id', (int) $detail['product_id'])
                    ->update();
            }

            $db->transComplete();

            if (! $db->transStatus()) {
                throw new \RuntimeException('Gagal menyimpan transaksi.');
            }

            return redirect()->to('/transaksi/riwayat')->with('success', 'Transaksi berhasil disimpan. Invoice: ' . $invoiceNo);
        } catch (\Throwable $e) {
            $db->transRollback();

            return redirect()->to('/transaksi')->with('error', $e->getMessage());
        }
    }

    public function riwayat()
    {
        $date  = trim((string) $this->request->getGet('date'));
        $model = new TransaksiModel();

        return view('transaksi/riwayat', [
            'title'        => 'Riwayat Transaksi',
            'username'     => (string) session()->get('username'),
            'level'        => (string) session()->get('level'),
            'activeMenu'   => 'transaksi',
            'dateFilter'   => $date,
            'transactions' => $model->getHistory($date),
        ]);
    }

    private function generateInvoiceNo($db): string
    {
        $prefix = 'TRX-' . date('Ymd') . '-';

        $last = $db->table('tb_transactions')
            ->select('invoice_no')
            ->like('invoice_no', $prefix, 'after')
            ->orderBy('id', 'DESC')
            ->get()
            ->getRowArray();

        $nextNumber = 1;

        if ($last && isset($last['invoice_no'])) {
            $parts = explode('-', (string) $last['invoice_no']);
            $lastSeq = (int) end($parts);
            $nextNumber = $lastSeq + 1;
        }

        return $prefix . str_pad((string) $nextNumber, 4, '0', STR_PAD_LEFT);
    }
}