<?php

// Direct MySQL connection
$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'toko_lej';

$conn = new mysqli($hostname, $username, $password, $database);

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

echo "Truncating old data...\n";
$conn->query('SET FOREIGN_KEY_CHECKS=0');
$conn->query('TRUNCATE TABLE tb_transaction_details');
$conn->query('TRUNCATE TABLE tb_transactions');
$conn->query('TRUNCATE TABLE tb_products');
$conn->query('TRUNCATE TABLE tb_customers');
$conn->query('TRUNCATE TABLE tb_units');
$conn->query('TRUNCATE TABLE tb_categories');
$conn->query('SET FOREIGN_KEY_CHECKS=1');

echo "✓ Tables truncated\n\n";

// Re-seed
echo "Running seeder...\n";
$conn->close();

?>
