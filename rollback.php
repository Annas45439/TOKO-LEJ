<?php
/**
 * Rollback Migrations via Browser
 * Jalankan di browser: http://localhost/toko-lej/rollback.php
 * Hapus file ini setelah selesai untuk keamanan.
 */

define('ENVIRONMENT', 'development');

define('SYSTEMPATH', realpath(__DIR__ . '/system') . DIRECTORY_SEPARATOR);
define('APPPATH', realpath(__DIR__ . '/app') . DIRECTORY_SEPARATOR);
define('ROOTPATH', realpath(__DIR__) . DIRECTORY_SEPARATOR);
define('FCPATH', realpath(__DIR__ . '/public') . DIRECTORY_SEPARATOR);
define('WRITEPATH', realpath(__DIR__ . '/writable') . DIRECTORY_SEPARATOR);

require_once SYSTEMPATH . 'Test/bootstrap.php';

// Bootstrap minimal CI4
require_once SYSTEMPATH . 'Config/BaseConfig.php';
require_once APPPATH . 'Config/Constants.php';

// Autoload
$paths = require APPPATH . 'Config/Paths.php';
require_once SYSTEMPATH . 'Autoloader/Autoloader.php';
$loader = new \CodeIgniter\Autoloader\Autoloader();
$loader->initialize(new \Config\Autoload(), new \Config\Modules());
$loader->register();

// Database
$db = \Config\Database::connect('default');

// Migration Runner
$config = new \Config\Migrations();
$runner = new \CodeIgniter\Database\MigrationRunner($config, $db);

try {
    $runner->regress();
    echo "<h2>Rollback Berhasil</h2>";
    echo "<p>Batch terakhir telah di-rollback. Periksa database Anda.</p>";
} catch (\Exception $e) {
    echo "<h2>Rollback Gagal</h2>";
    echo "<pre>" . $e->getMessage() . "</pre>";
}

echo "<hr><p><strong>Catatan:</strong> Hapus file <code>rollback.php</code> ini setelah selesai.</p>";

