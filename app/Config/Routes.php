<?php
use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// ===== ROUTES UMUM/PUBLIC ===== (Harus di atas)
$routes->get('/', 'Umum::index');
$routes->get('umum', 'Umum::index');
$routes->get('user_view', 'Umum::index');
$routes->get('public', 'Umum::index');

// ===== API UNTUK UMUM ===== (Harus sebelum route lainnya)
$routes->get('getDetailPelanggaran/(:any)', 'Umum::getDetailPelanggaran/$1');
$routes->get('umum/getDetailPelanggaran/(:any)', 'Umum::getDetailPelanggaran/$1');

// ===== ROUTES LAINNYA =====
$routes->get('login', 'Admin::index');
$routes->get('admin_login', 'Admin::index');
$routes->post('login', 'Admin::login');
$routes->get('logout', 'Admin::logout');
$routes->post('logout', 'Admin::logout');

$routes->get('dashboard', 'DashboardController::index');
$routes->get('settings', 'Settings::index');
$routes->post('update_settings', 'Admin::update_settings');

$routes->get('data_santri', 'SantriController::index');
$routes->get('santri/add', 'SantriController::add');
$routes->post('santri/add', 'SantriController::save');
$routes->get('santri/edit/(:num)', 'SantriController::edit/$1');
$routes->post('santri/update/(:num)', 'SantriController::update/$1');
$routes->get('santri/delete/(:num)', 'SantriController::delete/$1');

$routes->get('pelanggaran', 'Pelanggaran::index');
$routes->get('pelanggaran/update_pelanggaran/(:num)/(:any)', 'Pelanggaran::update_pelanggaran/$1/$2');
$routes->post('pelanggaran/add_poin', 'Pelanggaran::add_poin');
$routes->get('pelanggaran/getPoinBulan/(:any)/(:any)', 'Pelanggaran::getPoinBulan/$1/$2');
$routes->post('pelanggaran/delete_poin', 'Pelanggaran::delete_poin');
$routes->get('pelanggaran/getPoinSemuaBulan/(:any)', 'Pelanggaran::getPoinSemuaBulan/$1');

$routes->get('pelanggaran/lihat_riwayat_penghapusan/(:num)', 'Pelanggaran::lihat_riwayat_penghapusan/$1');
$routes->get('pelanggaran/lihat_riwayat_penghapusan', 'Pelanggaran::lihat_riwayat_penghapusan');
$routes->get('pelanggaran/api/riwayat/(:num)', 'Pelanggaran::get_riwayat_penghapusan/$1');

$routes->get('rekapan', 'RekapanController::index');
$routes->get('rekapan/(:any)/(:any)', 'RekapanController::cetak/$1/$2');

$routes->get('sp/input/(:segment)', 'SPController::input/$1');
$routes->get('sp/view/(:segment)', 'SPController::view/$1');
$routes->post('sp/simpan', 'SPController::simpan');
$routes->get('sp1/(:segment)', 'SPController::sp1/$1');
$routes->get('sp2/(:segment)', 'SPController::sp2/$1');
$routes->get('sp3/(:segment)', 'SPController::sp3/$1');
$routes->post('surat/update', 'SPController::update');

$routes->get('rekapan/cetak_sp1/(:num)', 'RekapanController::cetak_sp1/$1');
$routes->get('rekapan/cetak_sp2/(:num)', 'RekapanController::cetak_sp2/$1');
$routes->get('rekapan/cetak_sp3/(:num)', 'RekapanController::cetak_sp3/$1');

$routes->get('admin-logs', 'AdminLogs::index');
$routes->get('api/tahun-ajaran', 'RekapanController::get_tahun_ajaran');
$routes->post('rekapan/create-tahun-ajaran', 'RekapanController::create_tahun_ajaran');

$routes->get('dashboard/check-tahun', 'DashboardController::checkTahunAjaran');
$routes->get('dashboard/grafik_pelanggaran', 'DashboardController::grafik_pelanggaran');
$routes->get('dashboard/get_data_grafik/(:num)', 'DashboardController::get_data_grafik/$1');

$routes->post('dashboard/upload_foto', 'SantriController::upload_foto');
$routes->get('dashboard/get_foto/(:any)', 'SantriController::get_foto/$1');

$routes->get('pelanggaran/edit_poin/(:num)', 'Pelanggaran::edit_poin/$1');
$routes->post('pelanggaran/update_poin', 'Pelanggaran::update_poin');
$routes->get('pelanggaran/lihat_riwayat_edit/(:num)', 'Pelanggaran::lihat_riwayat_edit/$1');
$routes->get('pelanggaran/lihat_riwayat_edit', 'Pelanggaran::lihat_riwayat_edit');