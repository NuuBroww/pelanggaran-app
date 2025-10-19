<?php

namespace App\Controllers;
use CodeIgniter\Controller;
use Config\Database;

class TestDB extends Controller
{
    public function index()
    {
        $db = Database::connect();

        if ($db->connID) {
            echo "✅ Koneksi CodeIgniter ke database 'pelanggaran' BERHASIL!";
        } else {
            echo "❌ Gagal konek database (connID kosong)";
        }
    }
}
