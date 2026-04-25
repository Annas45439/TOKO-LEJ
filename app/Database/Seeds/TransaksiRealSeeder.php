<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TransaksiRealSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        $now = date('Y-m-d H:i:s');

        // Pastikan ada product
        $this->seedCategories($db, $now);
        $this->seedUnits($db, $now);
        $this->seedProducts($db, $now);
        
        // Insert data customer
        $this->seedCustomers($db, $now);
        
        // Insert data transaksi real dengan history penjualan
        $this->seedRealTransactions($db, $now);
    }

    private function seedCategories($db, string $now): void
    {
        if ($db->table('tb_categories')->countAllResults() === 0) {
            $categories = [
                ['name' => 'Minuman', 'created_at' => $now, 'updated_at' => $now],
                ['name' => 'Makanan', 'created_at' => $now, 'updated_at' => $now],
                ['name' => 'Snack', 'created_at' => $now, 'updated_at' => $now],
                ['name' => 'Kebutuhan Harian', 'created_at' => $now, 'updated_at' => $now],
            ];
            $db->table('tb_categories')->insertBatch($categories);
        }
    }

    private function seedUnits($db, string $now): void
    {
        if ($db->table('tb_units')->countAllResults() === 0) {
            $units = [
                ['name' => 'Pcs', 'created_at' => $now, 'updated_at' => $now],
                ['name' => 'Box', 'created_at' => $now, 'updated_at' => $now],
                ['name' => 'Pack', 'created_at' => $now, 'updated_at' => $now],
                ['name' => 'Botol', 'created_at' => $now, 'updated_at' => $now],
            ];
            $db->table('tb_units')->insertBatch($units);
        }
    }

    private function seedProducts($db, string $now): void
    {
        if ($db->table('tb_products')->countAllResults() === 0) {
            $products = [
                [
                    'category_id' => 1,
                    'unit_id' => 4,
                    'name' => 'Air Mineral 600ml',
                    'price' => 5000,
                    'buy_price' => 3500,
                    'stock' => 150,
                    'min_stock' => 20,
                    'description' => 'Air mineral kemasan botol 600ml',
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'category_id' => 1,
                    'unit_id' => 4,
                    'name' => 'Teh Botol 500ml',
                    'price' => 6000,
                    'buy_price' => 4500,
                    'stock' => 15,
                    'min_stock' => 20,
                    'description' => 'Teh kemasan botol 500ml',
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'category_id' => 1,
                    'unit_id' => 4,
                    'name' => 'Kopi Botol 250ml',
                    'price' => 8000,
                    'buy_price' => 6000,
                    'stock' => 8,
                    'min_stock' => 15,
                    'description' => 'Kopi siap minum kemasan botol 250ml',
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'category_id' => 2,
                    'unit_id' => 1,
                    'name' => 'Mi Instan Goreng',
                    'price' => 4000,
                    'buy_price' => 3000,
                    'stock' => 200,
                    'min_stock' => 30,
                    'description' => 'Mie instan goreng biasa',
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'category_id' => 2,
                    'unit_id' => 1,
                    'name' => 'Telur Ayam',
                    'price' => 25000,
                    'buy_price' => 20000,
                    'stock' => 5,
                    'min_stock' => 10,
                    'description' => 'Telur ayam segar per kg',
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'category_id' => 2,
                    'unit_id' => 1,
                    'name' => 'Roti Tawar',
                    'price' => 15000,
                    'buy_price' => 11000,
                    'stock' => 12,
                    'min_stock' => 10,
                    'description' => 'Roti tawar 1 loaf',
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'category_id' => 3,
                    'unit_id' => 1,
                    'name' => 'Kacang Panggang 100g',
                    'price' => 12000,
                    'buy_price' => 9000,
                    'stock' => 25,
                    'min_stock' => 15,
                    'description' => 'Kacang panggang salted 100g',
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'category_id' => 3,
                    'unit_id' => 1,
                    'name' => 'Wafer Keju 150g',
                    'price' => 10000,
                    'buy_price' => 7500,
                    'stock' => 110,
                    'min_stock' => 20,
                    'description' => 'Wafer keju premium 150g',
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            ];
            $db->table('tb_products')->insertBatch($products);
        }
    }

    private function seedCustomers($db, string $now): void
    {
        if ($db->table('tb_customers')->countAllResults() === 0) {
            $customers = [
                ['name' => 'Pelanggan Umum', 'created_at' => $now],
                ['name' => 'Budi Santoso', 'created_at' => $now],
                ['name' => 'Siti Nurhaliza', 'created_at' => $now],
                ['name' => 'Ahmad Wijaya', 'created_at' => $now],
                ['name' => 'Rina Kusuma', 'created_at' => $now],
            ];
            $db->table('tb_customers')->insertBatch($customers);
        }
    }

    private function seedRealTransactions($db, string $now): void
    {
        // Clear transaksi sebelumnya untuk testing
        if ($db->table('tb_transaction_details')->countAllResults() > 0) {
            return; // Skip jika sudah ada data transaksi
        }

        $userId = 1; // Admin user
        $transactions = [];
        $details = [];
        $transactionId = 1;

        // Generate 30 hari transaksi dengan data realistic
        for ($dayOffset = 30; $dayOffset >= 0; $dayOffset--) {
            $date = $dayOffset === 0 ? date('Y-m-d') : date('Y-m-d', strtotime("-{$dayOffset} days"));
            
            // Generate 1-5 transaksi per hari (lebih banyak untuk hari ini)
            $transPerDay = $dayOffset === 0 ? rand(5, 8) : rand(2, 5);
            
            for ($t = 0; $t < $transPerDay; $t++) {
                $invoice = 'INV-' . date('Ymd', strtotime($date)) . '-' . str_pad($transactionId, 4, '0', STR_PAD_LEFT);
                $customerId = rand(1, 5);
                
                // Random produk dalam satu transaksi (1-3 items)
                $itemCount = rand(1, 3);
                $total = 0;
                
                $selectedProducts = array_rand(range(1, 8), $itemCount);
                if (!is_array($selectedProducts)) {
                    $selectedProducts = [$selectedProducts];
                }
                
                foreach ($selectedProducts as $idx => $product_id) {
                    $product_id = $product_id + 1; // Adjust untuk product_id starting dari 1
                    $qty = rand(1, 5);
                    
                    // Get harga dari products
                    $product = $db->table('tb_products')->where('id', $product_id)->first();
                    if (!$product) continue;
                    
                    $price = $product->price;
                    $subtotal = $price * $qty;
                    $total += $subtotal;
                    
                    $details[] = [
                        'transaction_id' => $transactionId,
                        'product_id' => $product_id,
                        'qty' => $qty,
                        'price' => $price,
                        'subtotal' => $subtotal,
                    ];
                    
                    // Update stock
                    $db->table('tb_products')->where('id', $product_id)->update([
                        'stock' => $db->raw('stock - ' . $qty)
                    ]);
                }
                
                // Generate pembayaran (bisa cash atau transfer)
                $paymentMethod = rand(0, 1) === 0 ? 'cash' : 'transfer';
                $payment = $total;
                if ($paymentMethod === 'cash') {
                    // Round up ke kelipatan 1000/5000/10000
                    $roundTo = [1000, 5000, 10000][rand(0, 2)];
                    $payment = ceil($total / $roundTo) * $roundTo;
                }
                $change = $payment - $total;
                
                $time = sprintf('%02d:%02d:%02d', rand(8, 20), rand(0, 59), rand(0, 59));
                
                $transactions[] = [
                    'invoice_no' => $invoice,
                    'customer_id' => $customerId,
                    'user_id' => $userId,
                    'total' => $total,
                    'payment_method' => $paymentMethod,
                    'payment_amount' => $payment,
                    'change_amount' => $change,
                    'status' => 'completed',
                    'date' => $date . ' ' . $time,
                    'created_at' => $date . ' ' . $time,
                ];
                
                $transactionId++;
            }
        }
        
        // Insert semua transaksi
        if (!empty($transactions)) {
            $db->table('tb_transactions')->insertBatch($transactions);
        }
        
        // Insert semua detail
        if (!empty($details)) {
            $db->table('tb_transaction_details')->insertBatch($details);
        }

        log_message('info', "Seeded {$transactionId} transactions with details");
    }
}
