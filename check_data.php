<?php
require 'bootstrap.php';
$db = \CodeIgniter\Database\Config::connect();

$result = $db->query('SELECT COUNT(*) as total FROM tb_transaksi')->getRow();
echo "Total Transaksi: " . $result->total . PHP_EOL;

$result = $db->query('SELECT COUNT(DISTINCT DATE_FORMAT(created_at, "%Y-%m")) as months FROM tb_transaksi')->getRow();
echo "Bulan Data: " . $result->months . PHP_EOL;

$result = $db->query('SELECT COUNT(*) as total FROM tb_sales_monthly')->getRow();
echo "Total Sales Monthly: " . $result->total . PHP_EOL;
