<?php

namespace App\Controllers;

use App\Models\SantriModel;
use App\Models\PoinBulananModel;
use App\Models\PelanggaranModel;
use App\Models\AdminLogModel;
use App\Models\RekapanModel;
use App\Models\PoinSemesteranModel;
use App\Models\SuratSPModel;
use App\Models\RiwayatPoinModel;
use App\Models\RiwayatEditModel;

class Pelanggaran extends BaseController
{
    protected $santriModel;
    protected $poinBulananModel;
    protected $pelanggaranModel;
    protected $adminLogModel;
    protected $rekapanModel;
    protected $poinSemesteranModel;
    protected $suratSPModel;
    protected $riwayatPoinModel;
    protected $riwayatEditModel;

    public function __construct()
{
    $this->santriModel = new SantriModel();
    $this->poinBulananModel = new PoinBulananModel();
    $this->pelanggaranModel = new PelanggaranModel();
    $this->adminLogModel = new AdminLogModel();
    $this->rekapanModel = new RekapanModel();
    $this->poinSemesteranModel = new PoinSemesteranModel(); // HAPUS assignment ulang
    $this->suratSPModel = new SuratSPModel();
    $this->riwayatPoinModel = new RiwayatPoinModel();
    $this->riwayatEditModel = new RiwayatEditModel();
}

    public function index()
    {
        return redirect()->to('/dashboard');
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

    // Ambil data dari form
    $nis = $this->request->getPost('nis');
    $bulan = $this->request->getPost('bulan');
    $jenis = $this->request->getPost('jenis');
    $detail = trim($this->request->getPost('detail'));
    $poin = (int) $this->request->getPost('poin');
    $kategori = $this->request->getPost('kategori');
    $tahun_ajaran = $this->request->getPost('tahun_ajaran') ?? '2025/2026';
    $semester = $this->request->getPost('semester') ?? 'ganjil';

    // VALIDASI LENGKAP
    if (empty($nis) || empty($bulan) || empty($jenis) || empty($detail) || $poin < 1 || empty($kategori)) {
        session()->setFlashdata('error', 'Semua field harus diisi!');
        return redirect()->to("pelanggaran/update_pelanggaran/$nis/$bulan?tahun_ajaran=$tahun_ajaran&semester=$semester");
    }

    // Validasi NIS exists
    $santri = $this->santriModel->where('nis', $nis)->first();
    if (!$santri) {
        session()->setFlashdata('error', 'Santri dengan NIS tersebut tidak ditemukan!');
        return redirect()->to("pelanggaran/update_pelanggaran/$nis/$bulan?tahun_ajaran=$tahun_ajaran&semester=$semester");
    }

    $labelJenis = match ($jenis) {
        'telat_sholat' => 'Telat Sholat',
        'tidak_sholat_berjamaah' => 'Tidak Sholat Berjamaah',
        'tidak_piket' => 'Tidak Piket',
        default => 'Pelanggaran Lain'
    };

    $keterangan_baru = "$labelJenis: " . ucfirst($detail);
    $tanggal = date('Y-m-d H:i:s');

    // ✅ DATA YANG BENAR
    $data = [
        'nis' => $nis,
        'bulan' => $bulan,
        'poin' => $poin,
        'keterangan' => $keterangan_baru,
        'detail_pelanggaran' => $detail,
        'kategori' => $kategori,
        'tanggal' => $tanggal,
        'tahun_ajaran' => $tahun_ajaran,
        'semester' => $semester
    ];

    try {
        log_message('debug', '=== START ADD POIN PROCESS ===');
        log_message('debug', 'Data to insert: ' . print_r($data, true));

        $db = \Config\Database::connect();
        
        // ✅ PERBAIKAN: Gunakan Query Builder langsung (bypass Model validation)
        $builder = $db->table('poin_bulanan');
        $result = $builder->insert($data);
        
        log_message('debug', 'Direct Insert result: ' . ($result ? 'TRUE' : 'FALSE'));
        
        if (!$result) {
            $error = $db->error();
            log_message('error', 'Database error: ' . print_r($error, true));
            throw new \Exception('Gagal menyimpan data: ' . ($error['message'] ?? 'Unknown error'));
        }

        $poinId = $db->insertID();
        log_message('debug', 'Successfully inserted with ID: ' . $poinId);

        // Verifikasi data
        $verifyData = $db->table('poin_bulanan')->where('id', $poinId)->get()->getRow();
        if (!$verifyData) {
            throw new \Exception('Data tidak berhasil diverifikasi');
        }

        log_message('debug', 'Verified data: ' . print_r($verifyData, true));

        // Update total poin santri
        log_message('debug', 'Updating total poin santri...');
        $total = $this->poinBulananModel->getTotalPoin($nis);
        log_message('debug', 'Total poin: ' . $total);
        
        $updateSantri = $db->table('santri')
                          ->where('nis', $nis)
                          ->set('total_poin', $total)
                          ->update();
        log_message('debug', 'Update santri result: ' . ($updateSantri ? 'SUCCESS' : 'FAILED'));

        // Log admin
        log_message('debug', 'Creating admin log...');
        $adminId = session()->get('admin_id');
        $nama_santri = $santri['nama_santri'];

        $db->table('admin_log')->insert([
            'admin_id' => $adminId,
            'action' => "Menambahkan pelanggaran ($keterangan_baru) untuk $nama_santri pada bulan $bulan",
            'target_id' => $poinId,
            'created_at' => $tanggal,
        ]);

        log_message('debug', '=== END ADD POIN PROCESS - SUCCESS ===');
        session()->setFlashdata('success', "Pelanggaran '$keterangan_baru' berhasil ditambahkan!");

    } catch (\Exception $e) {
        log_message('error', '=== ADD POIN PROCESS FAILED ===');
        log_message('error', 'Error: ' . $e->getMessage());
        log_message('error', 'Trace: ' . $e->getTraceAsString());
        session()->setFlashdata('error', 'Gagal menambahkan pelanggaran: ' . $e->getMessage());
    }

    return redirect()->to("pelanggaran/update_pelanggaran/$nis/$bulan?tahun_ajaran=$tahun_ajaran&semester=$semester");
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

    // Ambil parameter tahun ajaran dan semester
    $tahun_ajaran = $this->request->getGet('tahun_ajaran') ?? '2025/2026';
    $semester = $this->request->getGet('semester') ?? 'ganjil';

    // ✅ QUERY YANG BENAR - dengan bulan
    $poinBulanan = $this->poinBulananModel
                        ->where([
                            'nis' => $nis, 
                            'bulan' => $bulan,
                            'tahun_ajaran' => $tahun_ajaran,
                            'semester' => $semester
                        ])
                        ->findAll();

    $santri = $this->santriModel->where('nis', $nis)->first();
    $nama_santri = $santri ? $santri['nama_santri'] : 'Santri Tidak Diketahui';

    $data = [
        'nis' => $nis,
        'bulan' => $bulan,
        'nama' => $nama_santri,
        'tahun_ajaran' => $tahun_ajaran,
        'semester' => $semester,
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
    

   public function delete_poin()
{
    // Cek role - hanya role 1 & 2 yang bisa akses
    $session = session();
    $id_role = $session->get('id_role');
    
    if ($id_role == 3) {
        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Akses ditolak untuk yayasan']);
        }
        session()->setFlashdata('error', 'Akses ditolak. Yayasan tidak dapat menghapus data pelanggaran.');
        return redirect()->to('/dashboard');
    }

    $id = $this->request->getPost('id');
    $nis = $this->request->getPost('nis');
    $bulan = $this->request->getPost('bulan');
    $tahun_ajaran = $this->request->getPost('tahun_ajaran') ?? '2025/2026';
    $semester = $this->request->getPost('semester') ?? 'ganjil';
    $alasan_penghapusan = $this->request->getPost('catatan_penghapusan');
    $poin_dihapus = $this->request->getPost('poin_dihapus');
    $isAjax = $this->request->isAJAX();

    if (!$id || !$nis || !$bulan) {
        $msg = ['status' => 'error', 'message' => 'Data tidak valid untuk dihapus!'];
        if ($isAjax) return $this->response->setJSON($msg);
        session()->setFlashdata('error', $msg['message']);
        return redirect()->back();
    }

    try {
        $db = \Config\Database::connect();
        
        // 1️⃣ Ambil data poin sebelum dihapus
        $poinData = $db->table('poin_bulanan')->where('id', $id)->get()->getRowArray();
        if (!$poinData) {
            throw new \Exception('Data poin tidak ditemukan');
        }

        $poin_yang_dihapus = $poinData['poin'] ?? $poin_dihapus;
        $keterangan_poin = $poinData['keterangan'] ?? 'Data poin';
        $kategori_poin = $poinData['kategori'] ?? 'ringan';
        $bulan_poin = $poinData['bulan'] ?? $bulan;

        // 2️⃣ Simpan ke riwayat_poin SEBELUM menghapus
        $riwayatData = [
            'nis' => $nis,
            'id_poin_asli' => $id,
            'poin_dihapus' => $poin_yang_dihapus,
            'keterangan_poin' => $keterangan_poin,
            'alasan_penghapusan' => $alasan_penghapusan ?: 'Tidak ada alasan',
            'kategori_poin' => $kategori_poin,
            'bulan_poin' => $bulan_poin,
            'tahun_ajaran' => $tahun_ajaran,
            'semester' => $semester,
            'dihapus_oleh' => session()->get('nama') ?? 'System',
            'tanggal_dihapus' => date('Y-m-d H:i:s')
        ];

        $riwayatSaved = $db->table('riwayat_poin')->insert($riwayatData);
        if (!$riwayatSaved) {
            throw new \Exception('Gagal menyimpan riwayat penghapusan');
        }

        // 3️⃣ Hapus dari poin_bulanan dengan Query Builder langsung
        $deleted = $db->table('poin_bulanan')->where('id', $id)->delete();
        log_message('debug', 'Delete result: ' . ($deleted ? 'SUCCESS' : 'FAILED'));

        if ($deleted) {
            // 4️⃣ Update total poin santri
            $totalQuery = $db->table('poin_bulanan')
                            ->selectSum('poin')
                            ->where('nis', $nis)
                            ->get();
            $totalRow = $totalQuery->getRow();
            $total = $totalRow ? (int)$totalRow->poin : 0;
            
            $db->table('santri')
               ->where('nis', $nis)
               ->set('total_poin', $total)
               ->update();

            // 5️⃣ Catat log admin
            $santri = $db->table('santri')->where('nis', $nis)->get()->getRowArray();
            $namaSantri = $santri['nama_santri'] ?? 'Tidak diketahui';

            $db->table('admin_log')->insert([
                'admin_id'   => session()->get('admin_id'),
                'action'     => "Menghapus poin: $keterangan_poin ($poin_yang_dihapus poin) milik $namaSantri. Alasan: " . ($alasan_penghapusan ?: 'Tidak ada alasan'),
                'target_id'  => $id,
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            $msg = [
                'status' => 'success', 
                'message' => '✅ Poin berhasil dihapus dan riwayat disimpan.',
                'poin_dihapus' => $poin_yang_dihapus,
                'alasan' => $alasan_penghapusan
            ];
        } else {
            $error = $db->error();
            log_message('error', 'Delete error: ' . print_r($error, true));
            throw new \Exception('Gagal menghapus data dari database');
        }

    } catch (\Exception $e) {
        log_message('error', 'Error deleting poin: ' . $e->getMessage());
        $msg = ['status' => 'error', 'message' => 'Terjadi kesalahan: ' . $e->getMessage()];
    }

    if ($isAjax) return $this->response->setJSON($msg);
    
    if ($msg['status'] === 'success') {
        session()->setFlashdata('success', $msg['message']);
    } else {
        session()->setFlashdata('error', $msg['message']);
    }

    return redirect()->to("pelanggaran/update_pelanggaran/$nis/$bulan?tahun_ajaran=$tahun_ajaran&semester=$semester");
}
    

    /**
     * METHOD BARU: Simpan ke tabel riwayat_poin
     */
    private function simpanKeRiwayatPoin($nis, $id_poin_asli, $poin_dihapus, $keterangan_poin, $alasan_penghapusan, $kategori_poin, $bulan_poin, $tahun_ajaran, $semester)
    {
        try {
            $dataRiwayat = [
                'nis' => $nis,
                'id_poin_asli' => $id_poin_asli,
                'poin_dihapus' => $poin_dihapus,
                'keterangan_poin' => $keterangan_poin,
                'alasan_penghapusan' => $alasan_penghapusan ?: 'Tidak ada alasan',
                'kategori_poin' => $kategori_poin,
                'bulan_poin' => $bulan_poin,
                'tahun_ajaran' => $tahun_ajaran,
                'semester' => $semester,
                'dihapus_oleh' => session()->get('nama') ?? 'System',
                'tanggal_dihapus' => date('Y-m-d H:i:s')
            ];

            $result = $this->riwayatPoinModel->simpanRiwayat($dataRiwayat);
            
            if ($result) {
                log_message('debug', "Riwayat penghapusan disimpan untuk NIS: $nis, Poin: $poin_dihapus");
                return true;
            } else {
                log_message('error', "Gagal menyimpan riwayat penghapusan untuk NIS: $nis");
                return false;
            }

        } catch (\Exception $e) {
            log_message('error', 'Error saving to riwayat_poin: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * METHOD BARU: Lihat riwayat penghapusan poin
     */
    public function lihat_riwayat_penghapusan($nis = null)
    {
        // Cek role - hanya role 1 & 2 yang bisa akses
        $session = session();
        $id_role = $session->get('id_role');
        
        if ($id_role == 3) {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak. Yayasan tidak dapat melihat riwayat penghapusan.');
        }

        $tahun_ajaran = $this->request->getGet('tahun_ajaran') ?? '2025/2026';
        $semester = $this->request->getGet('semester') ?? 'ganjil';
        
        // Jika ada NIS tertentu, ambil riwayat untuk NIS itu
        $riwayat = [];
        $totalPoinDihapus = 0;
        $santriData = null;

        if ($nis) {
            $riwayat = $this->riwayatPoinModel->getRiwayatByNis($nis, $tahun_ajaran, $semester);
            $totalPoinDihapus = $this->riwayatPoinModel->getTotalPoinDihapus($nis, $tahun_ajaran, $semester);
            $santriData = $this->santriModel->where('nis', $nis)->first();
        }

        $data = [
            'title' => 'Riwayat Penghapusan Poin',
            'riwayat' => $riwayat,
            'nis' => $nis,
            'santri' => $santriData,
            'total_poin_dihapus' => $totalPoinDihapus,
            'tahun_ajaran' => $tahun_ajaran,
            'semester' => $semester,
            'id_role' => $id_role
        ];

        return view('riwayat_penghapusan', $data);
    }

    /**
     * METHOD BARU: API untuk get riwayat penghapusan
     */
    public function get_riwayat_penghapusan($nis)
    {
        $tahun_ajaran = $this->request->getGet('tahun_ajaran') ?? '2025/2026';
        $semester = $this->request->getGet('semester') ?? 'ganjil';

        try {
            $riwayat = $this->riwayatPoinModel->getRiwayatByNis($nis, $tahun_ajaran, $semester);
            $totalPoinDihapus = $this->riwayatPoinModel->getTotalPoinDihapus($nis, $tahun_ajaran, $semester);
            
            return $this->response->setJSON([
                'status' => 'success',
                'data' => $riwayat,
                'total_poin_dihapus' => $totalPoinDihapus
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error getting riwayat penghapusan: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Gagal mengambil riwayat penghapusan'
            ]);
        }
    }

    /**
     * EDIT POIN - Method untuk menampilkan form edit
     */
    public function edit_poin($id)
    {
        $session = session();
        if (!$session->get('logged_in')) {
            return redirect()->to('/login');
        }

        // Cek role
        $id_role = $session->get('id_role');
        if ($id_role == 3) {
            return redirect()->to('/dashboard')->with('error', 'Yayasan tidak dapat mengedit data pelanggaran.');
        }

        // Ambil data pelanggaran
        $pelanggaran = $this->poinBulananModel->getPelanggaranById($id);
        
        if (!$pelanggaran) {
            return redirect()->back()->with('error', 'Data pelanggaran tidak ditemukan.');
        }

        $data = [
            'title' => 'Edit Pelanggaran',
            'pelanggaran' => $pelanggaran,
            'id_role' => $id_role,
            'nama' => $session->get('nama'),
            'tahun_ajaran' => $this->request->getGet('tahun_ajaran') ?? '2025/2026',
            'semester' => $this->request->getGet('semester') ?? 'ganjil'
        ];

        return view('edit_pelanggaran', $data);
    }

    /**
     * UPDATE POIN - Method untuk proses update
     */
    public function update_poin()
    {
        $session = session();
        if (!$session->get('logged_in')) {
            return redirect()->to('/login');
        }

        // Cek role
        $id_role = $session->get('id_role');
        if ($id_role == 3) {
            return redirect()->to('/dashboard')->with('error', 'Yayasan tidak dapat mengedit data pelanggaran.');
        }

        $id = $this->request->getPost('id');
        $nis = $this->request->getPost('nis');
        $bulan = $this->request->getPost('bulan');
        $tahun_ajaran = $this->request->getPost('tahun_ajaran');
        $semester = $this->request->getPost('semester');
        $alasan_edit = $this->request->getPost('alasan_edit');

        // Validasi input
        if (!$id || !$nis) {
            return redirect()->back()->with('error', 'Data tidak valid!');
        }

        // Ambil data sebelum edit
        $dataSebelum = $this->poinBulananModel->getPelanggaranById($id);
        
        if (!$dataSebelum) {
            return redirect()->back()->with('error', 'Data pelanggaran tidak ditemukan.');
        }

        // Data yang akan diupdate
        $dataUpdate = [
            'poin' => $this->request->getPost('poin'),
            'keterangan' => $this->request->getPost('keterangan'),
            'kategori' => $this->request->getPost('kategori'),
            'detail_pelanggaran' => $this->request->getPost('detail_pelanggaran')
        ];

        // Validasi
        if (empty($dataUpdate['poin']) || $dataUpdate['poin'] < 1 || $dataUpdate['poin'] > 100) {
            return redirect()->back()->with('error', 'Poin harus antara 1-100.');
        }

        if (empty($dataUpdate['keterangan']) || empty($dataUpdate['kategori'])) {
            return redirect()->back()->with('error', 'Keterangan dan kategori harus diisi.');
        }

        try {
            // Simpan riwayat edit sebelum update
            $riwayatEditData = [
                'nis' => $nis,
                'id_poin_asli' => $id,
                'poin_sebelum' => $dataSebelum['poin'],
                'poin_sesudah' => $dataUpdate['poin'],
                'keterangan_sebelum' => $dataSebelum['keterangan'],
                'keterangan_sesudah' => $dataUpdate['keterangan'],
                'kategori_sebelum' => $dataSebelum['kategori'],
                'kategori_sesudah' => $dataUpdate['kategori'],
                'detail_sebelum' => $dataSebelum['detail_pelanggaran'] ?? '',
                'detail_sesudah' => $dataUpdate['detail_pelanggaran'],
                'bulan_poin' => $bulan,
                'tahun_ajaran' => $tahun_ajaran,
                'semester' => $semester,
                'diedit_oleh' => $session->get('nama') ?? 'System',
                'tanggal_edit' => date('Y-m-d H:i:s'),
                'alasan_edit' => $alasan_edit ?: 'Tidak ada alasan'
            ];

            $this->riwayatEditModel->simpanRiwayatEdit($riwayatEditData);

            // Update data pelanggaran
            $updated = $this->poinBulananModel->updatePelanggaran($id, $dataUpdate);

            if ($updated) {
                // Update rekapan dan semesteran
                $this->rekapanModel->generateRekapan($tahun_ajaran, $semester);
                $this->poinSemesteranModel->updateSemesteran($tahun_ajaran, $semester);
                $this->suratSPModel->autoGenerateSPFromPoinSemesteran($tahun_ajaran, $semester);

                session()->setFlashdata('success', '✅ Data pelanggaran berhasil diupdate!');
            } else {
                session()->setFlashdata('error', '❌ Gagal mengupdate data pelanggaran.');
            }

        } catch (\Exception $e) {
            log_message('error', 'Error updating poin: ' . $e->getMessage());
            session()->setFlashdata('error', '❌ Terjadi kesalahan sistem: ' . $e->getMessage());
        }

        // Redirect kembali ke halaman update pelanggaran
        return redirect()->to("pelanggaran/update_pelanggaran/$nis/$bulan?tahun_ajaran=$tahun_ajaran&semester=$semester");
    }

    /**
     * LIHAT RIWAYAT EDIT - Method untuk melihat riwayat edit
     */
    public function lihat_riwayat_edit($nis = null)
    {
        $session = session();
        if (!$session->get('logged_in')) {
            return redirect()->to('/login');
        }

        // Cek role
        $id_role = $session->get('id_role');
        if ($id_role == 3) {
            return redirect()->to('/dashboard')->with('error', 'Yayasan tidak dapat melihat riwayat edit.');
        }

        $tahun_ajaran = $this->request->getGet('tahun_ajaran') ?? '2025/2026';
        $semester = $this->request->getGet('semester') ?? 'ganjil';
        
        // Jika ada NIS tertentu, ambil riwayat untuk NIS itu
        $riwayat = [];
        $santriData = null;

        if ($nis) {
            $riwayat = $this->riwayatEditModel->getRiwayatEditByNis($nis, $tahun_ajaran, $semester);
            $santriData = $this->santriModel->where('nis', $nis)->first();
        }

        $data = [
            'title' => 'Riwayat Edit Pelanggaran',
            'riwayat' => $riwayat,
            'nis' => $nis,
            'santri' => $santriData,
            'tahun_ajaran' => $tahun_ajaran,
            'semester' => $semester,
            'id_role' => $id_role
        ];

        return view('riwayat_edit', $data);
    }

    public function test_fix()
{
    $db = \Config\Database::connect();
    
    echo "<h2>Testing Fix</h2>";
    
    // Test data
    $testData = [
        'nis' => '28101',
        'bulan' => 'Juli',
        'poin' => 5,
        'keterangan' => 'Test Fix: Telat Sholat',
        'detail_pelanggaran' => 'Sholat Subuh',
        'kategori' => 'ringan',
        'tanggal' => date('Y-m-d H:i:s'),
        'tahun_ajaran' => '2025/2026',
        'semester' => 'ganjil'
    ];
    
    echo "<h3>Test Data:</h3>";
    echo "<pre>";
    print_r($testData);
    echo "</pre>";
    
    // Test insert
    echo "<h3>Test Insert:</h3>";
    try {
        $result = $db->table('poin_bulanan')->insert($testData);
        echo "Result: " . ($result ? "SUCCESS - ID: " . $db->insertID() : "FAILED");
        echo "<br>Error: " . json_encode($db->error());
    } catch (\Exception $e) {
        echo "ERROR: " . $e->getMessage();
    }
    
    // Test delete
    echo "<h3>Test Delete:</h3>";
    $lastId = $db->insertID();
    if ($lastId) {
        $deleteResult = $db->table('poin_bulanan')->where('id', $lastId)->delete();
        echo "Delete Result: " . ($deleteResult ? "SUCCESS" : "FAILED");
    }
}
}