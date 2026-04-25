<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DemoSeeder extends Seeder
{
    public function run()
    {
        $this->seedCategories();
        $this->seedUnits();
        $this->seedProducts();
        $this->seedTransactions();
    }

    private function seedCategories()
    {
        $db = \Config\Database::connect();

        if ($db->table('tb_categories')->countAllResults() === 0) {
            $data = [
                ['name' => 'Makanan'],
                ['name' => 'Minuman'],
                ['name' => 'Snack'],
            ];
            $db->table('tb_categories')->insertBatch($data);
        }
    }

    private function seedUnits()
    {
        $db = \Config\Database::connect();

        if ($db->table('tb_units')->countAllResults() === 0) {
            $data = [
                ['name' => 'pcs'],
                ['name' => 'botol'],
                ['name' => 'dus'],
            ];
            $db->table('tb_units')->insertBatch($data);
        }
    }

    private function seedProducts()
    {
        $db = \Config\Database::connect();

        if ($db->table('tb_products')->countAllResults() === 0) {
            $data = [
                [
                    'category_id' => 1,
                    'unit_id' => 1,
                    'name' => 'Kopi Sachet', // Product untuk test prediksi
                    'price' => 2000,
                    'buy_price' => 1500,
                    'stock' => 50,
                    'min_stock' => 10,
                    'description' => 'Kopi instan sachet',
                    'created_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'category_id' => 1,
                    'unit_id' => 1,
                    'name' => 'Roti Tawar',
                    'price' => 15000,
                    'buy_price' => 12000,
                    'stock' => 20,
                    'min_stock' => 5,
                    'created_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'category_id' => 2,
                    'unit_id' => 2,
                    'name' => 'Teh Botol',
                    'price' => 5000,
                    'buy_price' => 4000,
                    'stock' => 30,
                    'min_stock' => 5,
                    'created_at' => date('Y-m-d H:i:s'),
                ],
            ];
            $db->table('tb_products')->insertBatch($data);
        }
    }

    private function seedTransactions()
    {
        $db = \Config\Database::connect();
        $userId = 1; // demo admin
        $customerId = 1; // demo pelanggan

        // Clear existing demo transactions (optional)
        $db->table('tb_transaction_details')->truncate();
        $db->table('tb_transactions')->truncate();

        $baseDate = date('Y-m-d', strtotime('-30 days'));

        $kopiQtys = [2,3,1,5,2,4,3,6,2,3]; // 10 variasi qty untuk Kopi (product_id=1) → fallback urutan transaksi
        $invoices = [];
        $transactions = [];
        $details = [];

        for ($i = 0; $i < 10; $i++) {
            $date = date('Y-m-d', strtotime($baseDate . " +{$i} days"));
            $invoice = 'INV-' . date('Ymd', strtotime($date)) . '-' . str_pad($i+1, 3, '0', STR_PAD_LEFT);
            $invoices[] = $invoice;

            $total = 0;

            // Transaksi 1-10: Kopi + item lain
            $kopiSub = 2000 * $kopiQtys[$i];
            $total += $kopiSub;
            $details[] = [
                'transaction_id' => $i+1,
                'product_id' => 1,
                'qty' => $kopiQtys[$i],
                'price' => 2000,
                'subtotal' => $kopiSub,
            ];

            if ($i % 3 == 0) { // Tambah roti setiap 3rd transaksi
                $rotiSub = 15000;
                $total += $rotiSub;
                $details[] = [
                    'transaction_id' => $i+1,
                    'product_id' => 2,
                    'qty' => 1,
                    'price' => 15000,
                    'subtotal' => $rotiSub,
                ];
            }

            $payment = $total + 5000; // bayar lebih
            $change = $payment - $total;

            $transactions[] = [
                'invoice_no' => $invoice,
                'customer_id' => $customerId,
                'user_id' => $userId,
                'total' => $total,
                'payment_method' => 'cash',
                'payment_amount' => $payment,
                'change_amount' => $change,
                'status' => 'completed',
                'date' => $date,
                'created_at' => date('Y-m-d H:i:s'),
            ];
        }

        // Insert transactions
        $db->table('tb_transactions')->insertBatch($transactions);

        // Insert details
        $db->table('tb_transaction_details')->insertBatch($details);
    }
}
?>

