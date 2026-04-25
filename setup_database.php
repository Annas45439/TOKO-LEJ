<?php

$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'toko_lej';

$conn = new mysqli($hostname, $username, $password, $database);

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

$conn->query('SET FOREIGN_KEY_CHECKS=0');

// Drop all tables
$conn->query('DROP TABLE IF EXISTS tb_transaction_details');
$conn->query('DROP TABLE IF EXISTS tb_transactions');
$conn->query('DROP TABLE IF EXISTS tb_products');
$conn->query('DROP TABLE IF EXISTS tb_customers');
$conn->query('DROP TABLE IF EXISTS tb_users');
$conn->query('DROP TABLE IF EXISTS tb_units');
$conn->query('DROP TABLE IF EXISTS tb_categories');

echo "✓ Tables dropped\n";

// Create categories
$conn->query("CREATE TABLE tb_categories (
    id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    created_at DATETIME NULL,
    updated_at DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

// Create units
$conn->query("CREATE TABLE tb_units (
    id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    created_at DATETIME NULL,
    updated_at DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

// Create users
$conn->query("CREATE TABLE tb_users (
    id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    level VARCHAR(20) DEFAULT 'user',
    created_at DATETIME NULL,
    updated_at DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

// Create customers
$conn->query("CREATE TABLE tb_customers (
    id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    created_at DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

// Create products
$conn->query("CREATE TABLE tb_products (
    id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    category_id INT(11) UNSIGNED NOT NULL,
    unit_id INT(11) UNSIGNED NOT NULL,
    name VARCHAR(120) NOT NULL UNIQUE,
    price DECIMAL(15,2) DEFAULT 0,
    buy_price DECIMAL(15,2) DEFAULT 0,
    stock INT(11) DEFAULT 0,
    min_stock INT(11) DEFAULT 0,
    description TEXT NULL,
    created_at DATETIME NULL,
    updated_at DATETIME NULL,
    FOREIGN KEY (category_id) REFERENCES tb_categories(id),
    FOREIGN KEY (unit_id) REFERENCES tb_units(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

// Create transactions
$conn->query("CREATE TABLE tb_transactions (
    id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    invoice_no VARCHAR(50) NOT NULL UNIQUE,
    customer_id INT(11) UNSIGNED NOT NULL,
    user_id INT(11) UNSIGNED NOT NULL,
    total DECIMAL(15,2) NOT NULL,
    payment_method VARCHAR(20) NOT NULL,
    payment_amount DECIMAL(15,2) NOT NULL,
    change_amount DECIMAL(15,2) DEFAULT 0,
    status VARCHAR(20) DEFAULT 'completed',
    date DATETIME NOT NULL,
    created_at DATETIME NULL,
    FOREIGN KEY (customer_id) REFERENCES tb_customers(id),
    FOREIGN KEY (user_id) REFERENCES tb_users(id),
    KEY idx_date (date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

// Create transaction details
$conn->query("CREATE TABLE tb_transaction_details (
    id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    transaction_id INT(11) UNSIGNED NOT NULL,
    product_id INT(11) UNSIGNED NOT NULL,
    qty INT(11) NOT NULL,
    price DECIMAL(15,2) NOT NULL,
    subtotal DECIMAL(15,2) NOT NULL,
    FOREIGN KEY (transaction_id) REFERENCES tb_transactions(id),
    FOREIGN KEY (product_id) REFERENCES tb_products(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

echo "✓ Tables created\n";

$now = date('Y-m-d H:i:s');

// Insert categories
$conn->query("INSERT INTO tb_categories (name, created_at, updated_at) VALUES 
    ('Minuman', '$now', '$now'),
    ('Makanan', '$now', '$now'),
    ('Snack', '$now', '$now'),
    ('Kebutuhan Harian', '$now', '$now')
");

// Insert units
$conn->query("INSERT INTO tb_units (name, created_at, updated_at) VALUES 
    ('Pcs', '$now', '$now'),
    ('Box', '$now', '$now'),
    ('Pack', '$now', '$now'),
    ('Botol', '$now', '$now')
");

// Insert user
$conn->query("INSERT INTO tb_users (username, password, level, created_at, updated_at) VALUES 
    ('admin', '" . password_hash('admin123', PASSWORD_DEFAULT) . "', 'admin', '$now', '$now')
");

// Insert customers
$conn->query("INSERT INTO tb_customers (name, created_at) VALUES 
    ('Pelanggan Umum', '$now'),
    ('Budi Santoso', '$now'),
    ('Siti Nurhaliza', '$now'),
    ('Ahmad Wijaya', '$now'),
    ('Rina Kusuma', '$now')
");

// Insert products dengan beberapa stok rendah
$products = [
    ['category_id' => 1, 'unit_id' => 4, 'name' => 'Air Mineral 600ml', 'price' => 5000, 'buy_price' => 3500, 'stock' => 150, 'min_stock' => 20],
    ['category_id' => 1, 'unit_id' => 4, 'name' => 'Teh Botol 500ml', 'price' => 6000, 'buy_price' => 4500, 'stock' => 15, 'min_stock' => 20],
    ['category_id' => 1, 'unit_id' => 4, 'name' => 'Kopi Botol 250ml', 'price' => 8000, 'buy_price' => 6000, 'stock' => 8, 'min_stock' => 15],
    ['category_id' => 2, 'unit_id' => 1, 'name' => 'Mi Instan Goreng', 'price' => 4000, 'buy_price' => 3000, 'stock' => 200, 'min_stock' => 30],
    ['category_id' => 2, 'unit_id' => 1, 'name' => 'Telur Ayam', 'price' => 25000, 'buy_price' => 20000, 'stock' => 5, 'min_stock' => 10],
    ['category_id' => 2, 'unit_id' => 1, 'name' => 'Roti Tawar', 'price' => 15000, 'buy_price' => 11000, 'stock' => 12, 'min_stock' => 10],
    ['category_id' => 3, 'unit_id' => 1, 'name' => 'Kacang Panggang 100g', 'price' => 12000, 'buy_price' => 9000, 'stock' => 25, 'min_stock' => 15],
    ['category_id' => 3, 'unit_id' => 1, 'name' => 'Wafer Keju 150g', 'price' => 10000, 'buy_price' => 7500, 'stock' => 110, 'min_stock' => 20],
];

foreach ($products as $p) {
    $conn->query("INSERT INTO tb_products (category_id, unit_id, name, price, buy_price, stock, min_stock, created_at, updated_at) VALUES 
        ({$p['category_id']}, {$p['unit_id']}, '{$p['name']}', {$p['price']}, {$p['buy_price']}, {$p['stock']}, {$p['min_stock']}, '$now', '$now')
    ");
}

echo "✓ Master data inserted\n";

// Insert transactions for last 30 days + today
$transactions = [];
$details = [];
$transactionId = 1;

for ($dayOffset = 30; $dayOffset >= 0; $dayOffset--) {
    $date = $dayOffset === 0 ? date('Y-m-d') : date('Y-m-d', strtotime("-{$dayOffset} days"));
    $transPerDay = $dayOffset === 0 ? rand(5, 8) : rand(2, 5);
    
    for ($t = 0; $t < $transPerDay; $t++) {
        $invoice = 'INV-' . date('Ymd', strtotime($date)) . '-' . str_pad($transactionId, 4, '0', STR_PAD_LEFT);
        $customerId = rand(1, 5);
        $itemCount = rand(1, 3);
        $total = 0;
        
        $selectedProducts = array_rand(range(1, 8), $itemCount);
        if (!is_array($selectedProducts)) {
            $selectedProducts = [$selectedProducts];
        }
        
        foreach ($selectedProducts as $product_id) {
            $product_id = $product_id + 1;
            $qty = rand(1, 5);
            
            $result = $conn->query("SELECT price FROM tb_products WHERE id = $product_id");
            $product = $result->fetch_assoc();
            if (!$product) continue;
            
            $price = $product['price'];
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
            $conn->query("UPDATE tb_products SET stock = stock - $qty WHERE id = $product_id");
        }
        
        $paymentMethod = rand(0, 1) === 0 ? 'cash' : 'transfer';
        $payment = $total;
        if ($paymentMethod === 'cash') {
            $roundTo = [1000, 5000, 10000][rand(0, 2)];
            $payment = ceil($total / $roundTo) * $roundTo;
        }
        $change = $payment - $total;
        
        $time = sprintf('%02d:%02d:%02d', rand(8, 20), rand(0, 59), rand(0, 59));
        $datetime = $date . ' ' . $time;
        
        $conn->query("INSERT INTO tb_transactions (invoice_no, customer_id, user_id, total, payment_method, payment_amount, change_amount, status, date, created_at) 
            VALUES ('$invoice', $customerId, 1, $total, '$paymentMethod', $payment, $change, 'completed', '$datetime', '$datetime')
        ");
        
        $transactionId++;
    }
}

echo "✓ Transactions inserted: " . $transactionId . " total\n";

// Insert transaction details
foreach ($details as $detail) {
    $conn->query("INSERT INTO tb_transaction_details (transaction_id, product_id, qty, price, subtotal) VALUES 
        ({$detail['transaction_id']}, {$detail['product_id']}, {$detail['qty']}, {$detail['price']}, {$detail['subtotal']})
    ");
}

echo "✓ Transaction details inserted\n";

$conn->query('SET FOREIGN_KEY_CHECKS=1');

// Verify data
$prodCount = $conn->query("SELECT COUNT(*) as count FROM tb_products")->fetch_assoc()['count'];
$custCount = $conn->query("SELECT COUNT(*) as count FROM tb_customers")->fetch_assoc()['count'];
$transCount = $conn->query("SELECT COUNT(*) as count FROM tb_transactions")->fetch_assoc()['count'];
$detailCount = $conn->query("SELECT COUNT(*) as count FROM tb_transaction_details")->fetch_assoc()['count'];

echo "\n✅ DATABASE SEEDED SUCCESSFULLY!\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "Produk: $prodCount items\n";
echo "Pelanggan: $custCount items\n";
echo "Transaksi: $transCount items\n";
echo "Detail Transaksi: $detailCount items\n";

$conn->close();

?>
