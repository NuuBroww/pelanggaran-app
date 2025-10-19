<?php

namespace App\Controllers;

use App\Models\SantriModel;
use App\Models\PoinBulananModel;
use App\Models\PelanggaranModel;
use App\Models\AdminLogModel;
use App\Models\RekapanModel;

class Pelanggaran extends BaseController
{
    protected $santriModel;
    protected $poinBulananModel;
    protected $pelanggaranModel;
    protected $adminLogModel;
    protected $rekapanModel;

    public function __construct()
    {
        $this->santriModel = new SantriModel();
        $this->poinBulananModel = new PoinBulananModel();
        $this->pelanggaranModel = new PelanggaranModel();
        $this->adminLogModel = new AdminLogModel();
        $this->rekapanModel = new RekapanModel();
    }

    public function index()
    {
        return redirect()->to('/dashboard');
    }

    public function getPoinSemuaBulan($nis)
    {
        $bulanList = ['Juli','Agustus','September','Oktober','November','Desember'];
        $hasil = [];

        foreach ($bulanList as $bulan) {
            $row = $this->poinBulananModel
                ->selectSum('poin')
                ->where(['nis' => $nis, 'bulan' => $bulan])
                ->get()
                ->getRow();

            $hasil[$bulan] = $row && $row->poin ? (int)$row->poin : 0;
        }

        return $this->response->setJSON($hasil);
    }

    public function getPoinBulan($nis, $bulan)
    {
        // Cek role - hanya role 1 & 2 yang bisa akses
        $session = session();
        $id_role = $session->get('id_role');
        
        if ($id_role == 3) {
            return $this->response->setJSON(['error' => 'Akses ditolak untuk yayasan']);
        }

        // Ambil semua pelanggaran bulan ini
        $poinBulanan = $this->poinBulananModel
            ->select('id, nis, bulan, poin, keterangan, kategori, tanggal, detail_pelanggaran')
            ->where(['nis' => $nis, 'bulan' => $bulan])
            ->findAll();

        // Hitung total poin dan gabung semua keterangan
        $totalPoin = 0;
        $semuaKeterangan = [];

        foreach ($poinBulanan as $row) {
            $totalPoin += (int)$row['poin'];
            $semuaKeterangan[] = $row['keterangan'];
        }

        // Gabungkan jadi string (pisah pakai "; ")
        $keteranganGabung = !empty($semuaKeterangan) ? implode('; ', $semuaKeterangan) : '-';

        // Ambil nama santri
        $santri = $this->santriModel->where('nis', $nis)->first();
        $nama_santri = $santri ? $santri['nama_santri'] : 'Santri Tidak Ditemukan';

        // Return JSON untuk modal dashboard
        return $this->response->setJSON([
            'nama_santri' => $nama_santri,
            'poin' => $totalPoin,
            'keterangan' => $keteranganGabung
        ]);
    }

    public function update_pelanggaran($nis, $bulan)
    {
        // Cek role - hanya role 1 & 2 yang bisa akses
        $session = session();
        $id_role = $session->get('id_role');
        
        if ($id_role == 3) {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak. Yayasan tidak dapat mengubah data pelanggaran.');
        }

        $poinBulanan = $this->poinBulananModel
                            ->where(['nis' => $nis, 'bulan' => $bulan])
                            ->findAll();

        $santri = $this->santriModel->where('nis', $nis)->first();
        $nama_santri = $santri ? $santri['nama_santri'] : 'Santri Tidak Diketahui';

        $data = [
            'nis' => $nis,
            'bulan' => $bulan,
            'nama' => $nama_santri,
            'success' => session()->getFlashdata('success'),
            'error' => session()->getFlashdata('error'),
            'poinBulanan' => $poinBulanan,
            'id_role' => $id_role
        ];
        
        $adminLogModel = new AdminLogModel();
        $adminLogModel->logActivity(
            session()->get('admin_id'),
            'update', 
            $nis
        );

        return view('update_pelanggaran', $data);
    }

    public function add_poin()
{
    // Cek role - hanya role 1 & 2 yang bisa akses
    $session = session();
    $id_role = $session->get('id_role');
    
    if ($id_role == 3) {
        session()->setFlashdata('error', 'Akses ditolak. Yayasan tidak dapat menambah data pelanggaran.');
        return redirect()->to('/dashboard');
    }

    // Debug dulu data yang masuk
    $nis      = $this->request->getPost('nis');
    $bulan    = $this->request->getPost('bulan');
    $jenis    = $this->request->getPost('jenis');
    $detail   = trim($this->request->getPost('detail'));
    $poin     = (int) $this->request->getPost('poin');
    $kategori = $this->request->getPost('kategori');

    // VALIDASI LENGKAP
    if (empty($nis) || empty($bulan) || empty($jenis) || empty($detail) || $poin < 1 || empty($kategori)) {
        session()->setFlashdata('error', 'Semua field harus diisi!');
        return redirect()->to("pelanggaran/update_pelanggaran/$nis/$bulan");
    }

    // Label jenis pelanggaran
    $labelJenis = match ($jenis) {
        'telat_sholat' => 'Telat Sholat',
        'tidak_sholat_berjamaah' => 'Tidak Sholat Berjamaah',
        'tidak_piket' => 'Tidak Piket',
        default => 'Pelanggaran Lain'
    };

    $keterangan_baru = "$labelJenis " . ucfirst($detail);
    $tanggal = date('Y-m-d H:i:s');

    // Tentukan semester berdasarkan bulan
    $bulanGanjil = ['Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    $semester = in_array($bulan, $bulanGanjil) ? 'ganjil' : 'genap';
    
    // Tahun ajaran (sesuaikan dengan kebutuhan)
    $tahun_ajaran = '2025/2026'; // atau ambil dari session/config

    // DATA YANG AKAN DISIMPAN - PASTIKAN SEMUA FIELD ADA
    $data = [
        'nis' => $nis,
        'bulan' => $bulan,
        'semester' => $semester, // TAMBAHIN INI
        'tahun_ajaran' => $tahun_ajaran, // TAMBAHIN INI
        'poin' => $poin,
        'keterangan' => $keterangan_baru,
        'detail_pelanggaran' => $detail,
        'kategori' => $kategori,
        'tanggal' => $tanggal,
        'created_at' => $tanggal, // biasanya ada created_at
        'updated_at' => $tanggal  // biasanya ada updated_at
    ];

    // CEK DULU SEBELUM INSERT
    if (empty($data['nis']) || empty($data['bulan'])) {
        session()->setFlashdata('error', 'Data NIS atau Bulan tidak valid!');
        return redirect()->to("pelanggaran/update_pelanggaran/$nis/$bulan");
    }

    try {
        $poinId = $this->poinBulananModel->insert($data);

        if ($poinId) {
            // Update total poin santri (jika method ini ada)
            if (method_exists($this->poinBulananModel, 'getTotalPoin')) {
                $total = $this->poinBulananModel->getTotalPoin($nis);
                if (method_exists($this->santriModel, 'updatePoin')) {
                    $this->santriModel->updatePoin($nis, $total);
                }
            }

            // Log admin
            $adminId = session()->get('admin_id');
            $santri = $this->santriModel->where('nis', $nis)->first();
            $nama_santri = $santri['nama_santri'] ?? 'Santri Tidak Diketahui';

            $this->adminLogModel->insert([
                'admin_id' => $adminId,
                'action' => "Menambahkan pelanggaran ($keterangan_baru) untuk $nama_santri pada bulan $bulan",
                'target_id' => $poinId,
                'created_at' => $tanggal,
            ]);

            session()->setFlashdata('success', "Pelanggaran '$keterangan_baru' berhasil ditambahkan!");
        } else {
            session()->setFlashdata('error', 'Gagal menyimpan data!');
        }
    } catch (\Exception $e) {
        session()->setFlashdata('error', 'Error: ' . $e->getMessage());
    }

    return redirect()->to("pelanggaran/update_pelanggaran/$nis/$bulan");
}

    public function delete_poin()
    {
        $id    = $this->request->getPost('id');
        $nis   = $this->request->getPost('nis');
        $bulan = $this->request->getPost('bulan');
        $isAjax = $this->request->isAJAX(); // cek kalau lewat fetch()

        if (!$id || !$nis) {
            $msg = ['status' => 'error', 'message' => 'Data tidak valid untuk dihapus!'];

            if ($isAjax) {
                return $this->response->setJSON($msg);
            }

            session()->setFlashdata('error', $msg['message']);
            return redirect()->back();
        }

        // 1️⃣ Hapus dari poin_bulanan
        $deleted = $this->poinBulananModel->delete($id);

        if ($deleted) {
            // 2️⃣ Hapus rekapan milik NIS yang sama
            $this->rekapanModel->where('nis', $nis)->delete();

            // 3️⃣ Update total poin santri
            $total = $this->poinBulananModel->getTotalPoin($nis);
            $this->santriModel->updatePoin($nis, $total);

            // 4️⃣ Catat log admin
            $adminId = session()->get('admin_id');
            $santri     = $this->santriModel->where('nis', $nis)->first();
            $namaSantri = $santri['nama_santri'] ?? 'Tidak diketahui';

            $this->adminLogModel->insert([
                'admin_id'   => $adminId,
                'action'     => "Menghapus poin bulanan & rekapan milik $namaSantri ($nis)",
                'target_id'  => $id,
                'created_at' => date('Y-m-d H:i:s', strtotime('+7 hours')),
            ]);

            $msg = ['status' => 'success', 'message' => 'Data poin & rekapan berhasil dihapus.'];
        } else {
            $msg = ['status' => 'error', 'message' => 'Gagal menghapus data poin.'];
        }

        // Kalau request-nya lewat AJAX → kirim JSON
        if ($isAjax) {
            return $this->response->setJSON($msg);
        }

        // Kalau request biasa (form submit) → redirect
        if ($msg['status'] === 'success') {
            session()->setFlashdata('success', $msg['message']);
        } else {
            session()->setFlashdata('error', $msg['message']);
        }

        return redirect()->to("pelanggaran/update_pelanggaran/$nis/$bulan");
    }
}