<?php

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Load CodeIgniter
$_SERVER['CI_ENVIRONMENT'] = 'development';
require_once __DIR__ . '/public/index.php';

// Get database connection
$db = \Config\Database::connect();

echo "=== DATABASE VERIFICATION ===\n";
echo "Produk: " . $db->table('tb_products')->countAllResults() . " items\n";
echo "Pelanggan: " . $db->table('tb_customers')->countAllResults() . " items\n";
echo "Transaksi: " . $db->table('tb_transactions')->countAllResults() . " items\n";
echo "Detail Transaksi: " . $db->table('tb_transaction_details')->countAllResults() . " items\n";

echo "\n=== SAMPLE TRANSAKSI TERBARU ===\n";
$recent = $db->table('tb_transactions')
    ->select('invoice_no, total, payment_method, date')
    ->orderBy('date', 'DESC')
    ->limit(3)
    ->get()
    ->getResultArray();

foreach ($recent as $trans) {
    echo "Invoice: " . $trans['invoice_no'] . 
         " | Total: Rp" . number_format($trans['total'], 0, ',', '.') . 
         " | Method: " . $trans['payment_method'] . 
         " | Date: " . $trans['date'] . "\n";
}

echo "\n=== SUMMARY PENJUALAN ===\n";
$monthly = $db->table('tb_transactions')
    ->select("DATE_FORMAT(date, '%Y-%m') AS period, COUNT(*) AS count, SUM(total) AS total", false)
    ->where('date IS NOT NULL', null, false)
    ->groupBy("DATE_FORMAT(date, '%Y-%m')", false)
    ->orderBy('period', 'DESC')
    ->limit(6)
    ->get()
    ->getResultArray();

foreach ($monthly as $row) {
    echo "Period: " . $row['period'] . 
         " | Transaksi: " . $row['count'] . 
         " | Total: Rp" . number_format($row['total'], 0, ',', '.') . "\n";
}
