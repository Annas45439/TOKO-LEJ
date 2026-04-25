<?php
require __DIR__ . '/app/Config/Paths.php';
$paths = new Config\Paths();
require $paths->systemDirectory . '/Boot.php';

$app = Config\Services::codeigniter();
$app->initialize();
$db = Config\Database::connect();

$sql = "
SELECT p.id, p.name,
       COUNT(DISTINCT CONCAT(sm.year, '-', LPAD(sm.month, 2, '0'))) AS periods,
       COALESCE(SUM(sm.qty), 0) AS total_qty
FROM tb_products p
LEFT JOIN tb_sales_monthly sm ON sm.product_id = p.id
GROUP BY p.id, p.name
ORDER BY periods ASC, p.name ASC
";

$rows = $db->query($sql)->getResultArray();
foreach ($rows as $r) {
    echo $r['id'] . '|' . $r['name'] . '|' . $r['periods'] . '|' . $r['total_qty'] . PHP_EOL;
}
