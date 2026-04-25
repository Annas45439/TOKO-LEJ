<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Auth::index');
$routes->get('/login', 'Auth::index');
$routes->post('/login', 'Auth::login');
$routes->get('/logout', 'Auth::logout', ['filter' => 'auth']);
$routes->get('/dashboard', 'Dashboard::index', ['filter' => 'auth']);
$routes->get('/dashboard/admin', 'Dashboard::admin', ['filter' => ['auth', 'role:admin']]);
$routes->get('/dashboard/kasir', 'Dashboard::kasir', ['filter' => ['auth', 'role:kasir']]);

$routes->get('/produk', 'Produk::index', ['filter' => 'auth']);
$routes->get('/produk/create', 'Produk::create', ['filter' => ['auth', 'role:admin']]);
$routes->post('/produk/store', 'Produk::store', ['filter' => ['auth', 'role:admin']]);
$routes->get('/produk/edit/(\d+)', 'Produk::edit/$1', ['filter' => ['auth', 'role:admin']]);
$routes->post('/produk/update/(\d+)', 'Produk::update/$1', ['filter' => ['auth', 'role:admin']]);
$routes->post('/produk/delete/(\d+)', 'Produk::delete/$1', ['filter' => ['auth', 'role:admin']]);
$routes->get('/stok-masuk', 'StokMasuk::index', ['filter' => 'auth']);
$routes->post('/stok-masuk/store', 'StokMasuk::store', ['filter' => ['auth', 'role:admin']]);
$routes->get('/transaksi', 'Transaksi::index', ['filter' => 'auth']);
$routes->post('/transaksi/store', 'Transaksi::store', ['filter' => 'auth']);
$routes->get('/transaksi/riwayat', 'Transaksi::riwayat', ['filter' => 'auth']);
$routes->get('/prediksi', 'Prediksi::index', ['filter' => ['auth', 'role:admin']]);
$routes->get('/prediksi/dashboard', 'Prediksi::dashboard', ['filter' => ['auth', 'role:admin']]);
$routes->get('/prediksi/guide', 'Prediksi::guide', ['filter' => ['auth', 'role:admin']]);
$routes->get('/pelanggan', 'Pelanggan::index', ['filter' => 'auth']);
$routes->post('/pelanggan/store', 'Pelanggan::store', ['filter' => ['auth', 'role:admin']]);
$routes->post('/pelanggan/update/(\d+)', 'Pelanggan::update/$1', ['filter' => ['auth', 'role:admin']]);
$routes->post('/pelanggan/delete/(\d+)', 'Pelanggan::delete/$1', ['filter' => ['auth', 'role:admin']]);
$routes->get('/suplier', 'Suplier::index', ['filter' => 'auth']);
$routes->post('/suplier/store', 'Suplier::store', ['filter' => ['auth', 'role:admin']]);
$routes->post('/suplier/update/(\d+)', 'Suplier::update/$1', ['filter' => ['auth', 'role:admin']]);
$routes->post('/suplier/delete/(\d+)', 'Suplier::delete/$1', ['filter' => ['auth', 'role:admin']]);
$routes->get('/pengguna', 'Pengguna::index', ['filter' => ['auth', 'role:admin']]);
$routes->post('/pengguna/store', 'Pengguna::store', ['filter' => ['auth', 'role:admin']]);
$routes->post('/pengguna/update/(\d+)', 'Pengguna::update/$1', ['filter' => ['auth', 'role:admin']]);
$routes->post('/pengguna/delete/(\d+)', 'Pengguna::delete/$1', ['filter' => ['auth', 'role:admin']]);
