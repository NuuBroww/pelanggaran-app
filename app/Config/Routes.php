<?php
use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// ===== ROUTES UTAMA =====
$routes->get('/login', 'Admin::index');
$routes->get('/logout', 'Admin::logout');
$routes->post('/login', 'Admin::login');
$routes->post('/logout', 'Admin::logout');

// ===== DASHBOARD =====
$routes->get('/dashboard', 'DashboardController::index');

// ===== SETTINGS =====
$routes->get('/settings', 'Settings::index');
$routes->post('/update_settings', 'Admin::update_settings');

// ===== DATA SANTRI =====
$routes->get('/data_santri', 'SantriController::index');
$routes->get('/santri/add', 'SantriController::add');
$routes->post('/santri/add', 'SantriController::save');
$routes->get('/santri/edit/(:num)', 'SantriController::edit/$1');
$routes->post('/santri/update/(:num)', 'SantriController::update/$1');
$routes->get('/santri/delete/(:num)', 'SantriController::delete/$1');

// ===== PELANGGARAN =====
$routes->get('pelanggaran', 'Pelanggaran::index');
$routes->get('pelanggaran/update_pelanggaran/(:num)/(:any)', 'Pelanggaran::update_pelanggaran/$1/$2');
$routes->post('pelanggaran/add_poin', 'Pelanggaran::add_poin');
$routes->get('pelanggaran/getPoinBulan/(:any)/(:any)', 'Pelanggaran::getPoinBulan/$1/$2');
$routes->post('pelanggaran/delete_poin', 'Pelanggaran::delete_poin');
$routes->get('pelanggaran/getPoinSemuaBulan/(:any)', 'Pelanggaran::getPoinSemuaBulan/$1');

// ===== RIWAYAT POIN =====
// PERBAIKI ROUTES INI - gunakan (:num) untuk NIS
$routes->get('pelanggaran/lihat_riwayat_penghapusan/(:num)', 'Pelanggaran::lihat_riwayat_penghapusan/$1');
$routes->get('pelanggaran/lihat_riwayat_penghapusan', 'Pelanggaran::lihat_riwayat_penghapusan');
$routes->get('pelanggaran/api/riwayat/(:num)', 'Pelanggaran::get_riwayat_penghapusan/$1');

// ===== REKAPAN =====
$routes->get('/rekapan', 'RekapanController::index');
$routes->get('/rekapan/(:any)/(:any)', 'RekapanController::cetak/$1/$2');

// ===== SP =====
$routes->get('/sp/input/(:segment)', 'SPController::input/$1');
$routes->get('/sp/view/(:segment)', 'SPController::view/$1');
$routes->post('/sp/simpan', 'SPController::simpan');
$routes->get('/sp1/(:segment)', 'SPController::sp1/$1');
$routes->get('/sp2/(:segment)', 'SPController::sp2/$1');
$routes->get('/sp3/(:segment)', 'SPController::sp3/$1');
$routes->post('/surat/update', 'SPController::update');

// ===== BACKWARD COMPATIBILITY =====
$routes->get('rekapan/cetak_sp1/(:num)', 'RekapanController::cetak_sp1/$1');
$routes->get('rekapan/cetak_sp2/(:num)', 'RekapanController::cetak_sp2/$1');
$routes->get('rekapan/cetak_sp3/(:num)', 'RekapanController::cetak_sp3/$1');

// ===== ADMIN LOGS =====
$routes->get('admin-logs', 'AdminLogs::index');

// ===== API =====
$routes->get('api/tahun-ajaran', 'RekapanController::get_tahun_ajaran');
$routes->post('rekapan/create-tahun-ajaran', 'RekapanController::create_tahun_ajaran');

// ===== CHECK TAHUN AJARAN =====
$routes->get('/dashboard/check-tahun', 'DashboardController::checkTahunAjaran');
// Tambahkan route untuk grafik pelanggaran
$routes->get('dashboard/grafik_pelanggaran', 'DashboardController::grafik_pelanggaran');
$routes->get('dashboard/get_data_grafik/(:num)', 'DashboardController::get_data_grafik/$1');

$routes->post('/dashboard/upload_foto', 'SantriController::upload_foto');
$routes->get('/dashboard/get_foto/(:any)', 'SantriController::get_foto/$1');

// Tambahkan route untuk umum
$routes->get('/', 'Umum::index');
$routes->get('/umum', 'Umum::index');
$routes->get('/umum/getDetailPelanggaran/(:any)', 'Umum::getDetailPelanggaran/$1');

// ===== ADMIN LOGIN (ALIAS) =====
$routes->get('/admin_login', 'Admin::index');

// ===== USER VIEW (PUBLIC) =====
$routes->get('/user_view', 'Umum::index');
$routes->get('/public', 'Umum::index');