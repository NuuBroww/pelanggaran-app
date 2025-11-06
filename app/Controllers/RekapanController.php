<?php 
namespace App\Controllers;

use App\Models\RekapanModel;
use App\Models\PoinSemesteranModel;
use App\Models\PoinBulananModel;
use App\Models\SuratSPModel;

class RekapanController extends BaseController
{
    protected $rekapanModel;
    protected $semesterModel;
    protected $poinBulananModel;
    protected $suratSPModel;

    public function __construct()
    {
        $this->rekapanModel     = new RekapanModel();
        $this->semesterModel    = new PoinSemesteranModel();
        $this->poinBulananModel = new PoinBulananModel();
        $this->suratSPModel     = new SuratSPModel();
    }

    public function index()
    {
        $session = session();
        if (!$session->get('logged_in')) {
            return redirect()->to('/login');
        }

        try {
            // Cek dulu apakah kolom tahun_ajaran ada di database
            $db = \Config\Database::connect();
            $hasTahunAjaran = $db->fieldExists('tahun_ajaran', 'santri');
            
            // **AMBIL PARAMETER DARI URL - JANGAN DEFAULT OTOMATIS**
            $tahun_ajaran = $this->request->getGet('tahun_ajaran');
            $semester = $this->request->getGet('semester');
            
            // **CEK APAKAH TAHUN AJARAN BOLEH DIAKSES**
            if ($tahun_ajaran && !$this->rekapanModel->isTahunAjaranAvailable($tahun_ajaran)) {
                session()->setFlashdata('error', 
                    'Tahun ajaran ' . $tahun_ajaran . ' belum dapat diakses. ' .
                    'Akses akan dibuka mulai <strong>Juli 2026</strong>.'
                );
                // Redirect ke tahun yang available
                $tahun_ajaran = '2025/2026';
            }
            
            // **JIKA PARAMETER KOSONG, BARU PAKAI DEFAULT**
            if (!$tahun_ajaran) {
                $tahun_ajaran = $this->rekapanModel->getCurrentTahunAjaran();
            }
            if (!$semester) {
                $semester = $this->rekapanModel->getCurrentSemester();
            }

            // Generate rekapan untuk tahun ajaran dan semester yang dipilih
            $this->rekapanModel->generateRekapan($tahun_ajaran, $semester);

            // Update poin semesteran
            $this->semesterModel->updateSemesteran($tahun_ajaran, $semester);

            // Auto-generate SP
            $this->suratSPModel->autoGenerateSPFromPoinSemesteran($tahun_ajaran, $semester);

            // Ambil data rekapan dengan join yang benar
            $rekapan = $this->getRekapanData($tahun_ajaran, $semester);

            // Ambil available tahun ajaran untuk dropdown
            $availableTahunAjaran = $this->rekapanModel->getAvailableTahunAjaran();
            
            // **FIX: HAPUS LOGIC YANG PAKAI $currentYear**
            if (!empty($availableTahunAjaran)) {
                $availableTahunAjaran = array_map(function($item) {
                    if (is_array($item)) {
                        return $item['tahun_ajaran'] ?? $item[0] ?? $item;
                    }
                    return $item;
                }, $availableTahunAjaran);
                
                // Remove duplicates
                $availableTahunAjaran = array_unique($availableTahunAjaran);
            } else {
                // Fallback jika kosong
                $availableTahunAjaran = ['2025/2026'];
            }

            // Sort tahun ajaran descending (terbaru di atas)
            rsort($availableTahunAjaran);

        } catch (\Exception $e) {
            // Fallback jika ada error
            log_message('error', 'Error in RekapanController: ' . $e->getMessage());
            
            $tahun_ajaran = $this->request->getGet('tahun_ajaran') ?? '2025/2026';
            $semester = $this->request->getGet('semester') ?? 'ganjil';
            $rekapan = [];
            $availableTahunAjaran = ['2026/2027', '2025/2026'];
        }

        $data = [
            'title' => 'Rekapan Pelanggaran',
            'rekapan' => $rekapan,
            'tahun_ajaran' => $tahun_ajaran,
            'semester' => $semester,
            'availableTahunAjaran' => $availableTahunAjaran,
            'namaAdmin' => $session->get('nama') ?? 'Admin',
            'id_role' => $session->get('id_role') ?? 1
        ];

        return view('rekapan_view', $data);
    }

    /**
     * Ambil data rekapan dengan join ke tabel lainnya
     */
    private function getRekapanData($tahun_ajaran, $semester)
    {
        $db = \Config\Database::connect();
        
        try {
            // Cek apakah kolom tahun_ajaran ada di tabel rekapan
            $hasTahunAjaranInRekapan = $db->fieldExists('tahun_ajaran', 'rekapan');
            $hasTahunAjaranInSP = $db->fieldExists('tahun_ajaran', 'surat_peringatan');
            
            $query = $db->table('santri s')
                ->select([
                    's.nis',
                    's.nama_santri',
                    'COALESCE(r.total_poin, 0) AS total_poin',
                    'COALESCE(r.seluruh_pelanggaran, "-") AS seluruh_pelanggaran',
                    'COALESCE(r.bulan_terbanyak, "-") AS bulan_terbanyak',
                    'COALESCE(r.sp_level, "-") AS jenis_sp',
                    'COALESCE(sp.id, 0) AS sp_id'
                ]);
            
            if ($hasTahunAjaranInRekapan) {
                $query->join('rekapan r', "s.nis = r.nis AND r.tahun_ajaran = '$tahun_ajaran' AND r.semester = '$semester'", 'left');
            } else {
                $query->join('rekapan r', 's.nis = r.nis', 'left');
            }
            
            if ($hasTahunAjaranInSP) {
                $query->join('surat_peringatan sp', "s.nis = sp.nis AND sp.tahun_ajaran = '$tahun_ajaran' AND sp.semester = '$semester'", 'left');
            } else {
                $query->join('surat_peringatan sp', 's.nis = sp.nis', 'left');
            }
            
            $result = $query
                ->groupBy('s.nis')
                ->orderBy('s.nis', 'ASC')
                ->get()
                ->getResultArray();

            return $result;
                
        } catch (\Exception $e) {
            log_message('error', 'Error in getRekapanData: ' . $e->getMessage());
            
            // Fallback: ambil data santri saja
            try {
                return $db->table('santri s')
                    ->select('s.nis, s.nama_santri, 0 as total_poin, "-" as seluruh_pelanggaran, "-" as bulan_terbanyak, "-" as jenis_sp, 0 as sp_id')
                    ->orderBy('s.nis', 'ASC')
                    ->get()
                    ->getResultArray();
            } catch (\Exception $e2) {
                return [];
            }
        }
    }

    /**
     * Method untuk redirect dari routes lama (backward compatibility)
     */
    public function rekapan()
    {
        return $this->index();
    }

    public function cetak_sp($nis, $jenis_sp = null)
    {
        try {
            // Cek apakah kolom tahun_ajaran ada
            $db = \Config\Database::connect();
            $hasTahunAjaran = $db->fieldExists('tahun_ajaran', 'santri');
            
            $tahun_ajaran = $this->request->getGet('tahun_ajaran') ?? '2025/2026';
            $semester = $this->request->getGet('semester') ?? 'ganjil';

            // Ambil data santri
            $santri = $db->table('santri')->where('nis', $nis)->get()->getRowArray();
            
            if (!$santri) {
                return redirect()->back()->with('error', 'Santri tidak ditemukan.');
            }

            // Ambil data poin dari rekapan
            $rekapanWhere = ['nis' => $nis];
            if ($hasTahunAjaran) {
                $rekapanWhere['tahun_ajaran'] = $tahun_ajaran;
                $rekapanWhere['semester'] = $semester;
            }
            
            $rekapanData = $this->rekapanModel->where($rekapanWhere)->first();
            $totalPoin = $rekapanData ? $rekapanData['total_poin'] : 0;

            // Jika jenis_sp tidak ditentukan, tentukan berdasarkan poin
            if (!$jenis_sp) {
                if ($totalPoin >= 300) {
                    $jenis_sp = 'SP3';
                } elseif ($totalPoin >= 200) {
                    $jenis_sp = 'SP2';
                } elseif ($totalPoin >= 100) {
                    $jenis_sp = 'SP1';
                } else {
                    return redirect()->back()->with('error', 'Santri belum mencapai poin untuk mendapatkan SP.');
                }
            }

            // Validasi poin
            if ($totalPoin < 100) {
                return redirect()->back()->with('error', 'Santri belum mencapai poin untuk mendapatkan SP.');
            }

            $data = [
                'nis' => $santri['nis'],
                'nama_santri' => $santri['nama_santri'],
                'total_poin' => $totalPoin,
                'jenis_sp' => $jenis_sp,
                'semester' => $semester,
                'tahun_ajaran' => $tahun_ajaran
            ];
            
            // Redirect ke SP Controller untuk input manual
            $redirectUrl = '/sp/input/' . $nis;
            if ($hasTahunAjaran) {
                $redirectUrl .= '?tahun_ajaran=' . $tahun_ajaran . '&semester=' . $semester;
            }
            
            return redirect()->to($redirectUrl)->with('sp_data', $data);

        } catch (\Exception $e) {
            log_message('error', 'Error in cetak_sp: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem.');
        }
    }

    public function cetak_sp1($nis)
    {
        return $this->cetak_sp($nis, 'SP1');
    }

    public function cetak_sp2($nis)
    {
        return $this->cetak_sp($nis, 'SP2');
    }

    public function cetak_sp3($nis)
    {
        return $this->cetak_sp($nis, 'SP3');
    }

    public function delete_poin()
    {
        try {
            $id    = $this->request->getPost('id');
            $nis   = $this->request->getPost('nis');
            $bulan = $this->request->getPost('bulan');
            $tahun_ajaran = $this->request->getPost('tahun_ajaran');
            $semester = $this->request->getPost('semester');

            if (!$id || !$nis) {
                session()->setFlashdata('error', 'Data tidak valid untuk dihapus!');
                return redirect()->back();
            }

            // Hapus poin dari tabel poin_bulanan
            $deleted = $this->poinBulananModel->delete($id);

            if ($deleted) {
                // Cek apakah kolom tahun_ajaran ada
                $db = \Config\Database::connect();
                $hasTahunAjaran = $db->fieldExists('tahun_ajaran', 'santri');
                
                // Regenerasi rekapan & poin semesteran
                if ($hasTahunAjaran && $tahun_ajaran && $semester) {
                    $this->rekapanModel->generateRekapan($tahun_ajaran, $semester);
                    $this->semesterModel->updateSemesteran($tahun_ajaran, $semester);
                    $this->suratSPModel->autoGenerateSPFromPoinSemesteran($tahun_ajaran, $semester);
                } else {
                    $this->rekapanModel->generateRekapan();
                    $this->semesterModel->updateSemesteran();
                    $this->suratSPModel->autoGenerateSPFromPoinSemesteran();
                }

                session()->setFlashdata('success', '✅ Data poin berhasil dihapus & rekapan diperbarui.');
            } else {
                session()->setFlashdata('error', '❌ Gagal menghapus data poin.');
            }

            // Kembali ke halaman dengan parameter yang sama
            $redirectUrl = "pelanggaran/update_pelanggaran/$nis/$bulan";
            if ($tahun_ajaran && $semester) {
                $redirectUrl .= "?tahun_ajaran=$tahun_ajaran&semester=$semester";
            }

            return redirect()->to($redirectUrl);

        } catch (\Exception $e) {
            log_message('error', 'Error in delete_poin: ' . $e->getMessage());
            session()->setFlashdata('error', '❌ Terjadi kesalahan sistem.');
            return redirect()->back();
        }
    }

    /**
     * API untuk get available tahun ajaran (jika butuh)
     */
    public function get_tahun_ajaran()
    {
        try {
            $tahunAjaran = $this->rekapanModel->getAvailableTahunAjaran();
            return $this->response->setJSON($tahunAjaran);
        } catch (\Exception $e) {
            log_message('error', 'Error in get_tahun_ajaran: ' . $e->getMessage());
            return $this->response->setJSON([]);
        }
    }

    /**
     * Buat tahun ajaran baru
     */
    public function create_tahun_ajaran()
    {
        try {
            // Ambil tahun ajaran dari GET atau POST
            $tahun_baru = $this->request->getGet('tahun_ajaran') ?? $this->request->getPost('tahun_ajaran');
            
            if (!$tahun_baru) {
                session()->setFlashdata('error', 'Tahun ajaran tidak valid.');
                return redirect()->to('/rekapan');
            }

            // Generate data awal untuk tahun ajaran baru
            $this->rekapanModel->generateRekapan($tahun_baru, 'ganjil');
            $this->semesterModel->updateSemesteran($tahun_baru, 'ganjil');
            $this->suratSPModel->autoGenerateSPFromPoinSemesteran($tahun_baru, 'ganjil');
            
            session()->setFlashdata('success', 'Tahun ajaran ' . $tahun_baru . ' berhasil digenerate! Data poin dimulai dari 0.');
            
            // Redirect ke dashboard dengan tahun ajaran baru
            return redirect()->to('/dashboard?tahun_ajaran=' . $tahun_baru);

        } catch (\Exception $e) {
            log_message('error', 'Error in create_tahun_ajaran: ' . $e->getMessage());
            session()->setFlashdata('error', 'Gagal membuat tahun ajaran baru: ' . $e->getMessage());
            return redirect()->to('/rekapan');
        }
    }
    
    // Tambahkan method ini di RekapanModel
    /**
     * Cek apakah tahun ajaran boleh diakses berdasarkan tanggal sekarang
     */
    public function isTahunAjaranAvailable($tahun_ajaran)
    {
        // Tahun 2025/2026 selalu bisa diakses
        if ($tahun_ajaran === '2025/2026') {
            return true;
        }
        
        // Untuk tahun 2026/2027, cek apakah sudah Juli 2026
        if ($tahun_ajaran === '2026/2027') {
            $currentYear = date('Y');
            $currentMonth = date('n');
            
            // Bisa diakses jika tahun >= 2026 DAN bulan >= 7 (Juli)
            return ($currentYear >= 2026 && $currentMonth >= 7);
        }
        
        // Tahun lainnya default tidak bisa diakses
        return false;
    }

    /**
     * Get available tahun ajaran yang boleh diakses
     */
    public function getAvailableTahunAjaran()
    {
        $allTahunAjaran = $this->getAllTahunAjaran();
        $availableTahunAjaran = [];
        
        foreach ($allTahunAjaran as $tahun) {
            if ($this->isTahunAjaranAvailable($tahun)) {
                $availableTahunAjaran[] = $tahun;
            }
        }
        
        return $availableTahunAjaran;
    }

    /**
     * Get semua tahun ajaran tanpa filter
     */
    private function getAllTahunAjaran()
    {
        $db = \Config\Database::connect();
        
        // Cek dulu apakah tabel poin_bulanan ada kolom tahun_ajaran
        if ($db->fieldExists('tahun_ajaran', 'poin_bulanan')) {
            $result = $db->table('poin_bulanan')
                ->select('tahun_ajaran')
                ->distinct()
                ->orderBy('tahun_ajaran', 'DESC')
                ->get()
                ->getResultArray();
                
            $tahunAjaran = array_column($result, 'tahun_ajaran');
        } else {
            // Fallback ke data default
            $tahunAjaran = ['2025/2026', '2026/2027'];
        }
        
        // Pastikan selalu ada tahun 2025/2026
        if (!in_array('2025/2026', $tahunAjaran)) {
            $tahunAjaran[] = '2025/2026';
        }
        
        // Pastikan selalu ada tahun 2026/2027 untuk dropdown
        if (!in_array('2026/2027', $tahunAjaran)) {
            $tahunAjaran[] = '2026/2027';
        }
        
        // Sort descending
        rsort($tahunAjaran);
        
        return $tahunAjaran;
    }

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
}