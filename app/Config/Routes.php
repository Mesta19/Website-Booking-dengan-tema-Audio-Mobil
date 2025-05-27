<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// --------------------------------------------------------------------
// Rute Default (Sesuaikan dengan halaman utama Anda)
$routes->get('/', 'HomeController::index', ['as' => 'home']);

// -- Autentikasi dan Registrasi Pelanggan --
$routes->get('register-pelanggan', 'AuthController::tampilkanRegistrasiPelanggan', ['as' => 'register_pelanggan_show']);
$routes->post('register-pelanggan', 'AuthController::prosesRegistrasiPelanggan', ['as' => 'register_pelanggan_process']);

$routes->get('login-pelanggan', 'AuthController::tampilkanLoginPelanggan', ['as' => 'login_pelanggan_show']);
$routes->post('login-pelanggan', 'AuthController::prosesLoginPelanggan', ['as' => 'login_pelanggan_process']);

$routes->get('logout-pelanggan', 'AuthController::logoutPelanggan', ['as' => 'logout_pelanggan']);

// -- Autentikasi Admin --
$routes->get('admin/login', 'AuthController::tampilkanLoginAdmin', ['as' => 'login_admin_show']);
$routes->post('admin/login', 'AuthController::prosesLoginAdmin', ['as' => 'login_admin_process']);

$routes->get('admin/logout', 'AuthController::logoutAdmin', ['as' => 'logout_admin']);

// === ADMIN CRUD LAYANAN ===
$routes->group('admin/layanan', ['namespace' => 'App\Controllers' /*, 'filter' => 'adminAuth' // Aktifkan filter ini nanti */ ], function ($routes) {

    // Read: Menampilkan daftar layanan
    $routes->get('/', 'LayananController::adminIndex', ['as' => 'admin_layanan_index']);
    // Create: Menampilkan form tambah layanan
    $routes->get('tambah', 'LayananController::adminTambah', ['as' => 'admin_layanan_tambah']);
    // Create: Memproses penyimpanan layanan baru
    $routes->post('simpan', 'LayananController::adminSimpan', ['as' => 'admin_layanan_simpan']);
    // Update: Menampilkan form edit layanan
    $routes->get('edit/(:segment)', 'LayananController::adminEdit/$1', ['as' => 'admin_layanan_edit']);
    // Update: Memproses pembaruan layanan
    $routes->post('update/(:segment)', 'LayananController::adminUpdate/$1', ['as' => 'admin_layanan_update']);
    // Delete: Memproses penghapusan layanan (soft delete)
    $routes->post('hapus/(:segment)', 'LayananController::adminHapus/$1', ['as' => 'admin_layanan_hapus_proses']);
});
// === AKHIR ADMIN CRUD LAYANAN ===

$routes->get('/layanan', 'LayananController::indexPublik', ['as' => 'layanan_publik_daftar']);

$routes->get('/booking/form', 'BookingController::tampilkanFormBooking', ['as' => 'booking_form_show']);
// Memproses data dari form booking
$routes->post('/booking/proses', 'BookingController::prosesBooking', ['as' => 'booking_form_process']);
$routes->get('/booking/sukses/(:segment)', 'BookingController::bookingSukses/$1', ['as' => 'booking_success_page']);

// Rute untuk menampilkan riwayat booking pelanggan
$routes->get('/booking/saya', 'BookingController::daftarBookingPelanggan', ['as' => 'pelanggan_booking_history']);
$routes->post('/booking/hapus/(:segment)', 'BookingController::hapusBookingPelanggan/$1', ['as' => 'pelanggan_booking_hapus' /*, 'filter' => 'customerAuth' // Aktifkan filter ini nanti */]);
$routes->post('admin/booking/hapus/(:segment)', 'Admin::hapusBooking/$1', ['as' => 'admin_booking_hapus_proses', 'filter' => 'adminAuth']);
$routes->get('/admin/dashboard', 'Admin::dashboard', ['filter' => 'adminAuth', 'name' => 'admin_dashboard']);

?>