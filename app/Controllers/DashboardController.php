<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PelanggaranModel;
use App\Models\AdminLogModel;
use App\Models\AdminModel;
use App\Models\PoinBulananModel;
use App\Models\SantriModel;
use App\Models\SuratSPModel;
use App\Models\PoinSemesteranModel;
use App\Models\RekapanModel;

class DashboardController extends BaseController
{
    public function index()
    {
        $session = session();
        if (!$session->get('logged_in')) {
            return redirect()->to('/login');
        }

        // Load models
        $pelanggaranModel = new PelanggaranModel();
        $adminLogModel = new AdminLogModel();
        $adminModel = new AdminModel();
        $poinBulananModel = new PoinBulananModel();
        $santriModel = new SantriModel();
        $suratSPModel = new SuratSPModel();
        $poinSemesteranModel = new PoinSemesteranModel();
        $rekapanModel = new RekapanModel();

        // **AMBIL ROLE DARI SESSION**
        $id_role = $session->get('id_role') ?? 1;

        // **AMBIL PARAMETER TAHUN AJARAN DARI URL - BIARKAN APA ADANYA**
        $tahun_ajaran = $this->request->getGet('tahun_ajaran') ?? '2025/2026';

        try {
            // Update data poin_semesteran untuk tahun ajaran yang dipilih
            $poinSemesteranModel->updateSemesteran($tahun_ajaran);
        } catch (\Exception $e) {
            log_message('error', 'Error updating semesteran: ' . $e->getMessage());
        }

        // Tentukan semester aktif berdasarkan bulan saat ini
        $currentMonth = date('n');
        $semesterAktif = ($currentMonth >= 1 && $currentMonth <= 6) ? 'genap' : 'ganjil';
        
        // Bulan untuk masing-masing semester
        $bulanGanjil = ['Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $bulanGenap = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni'];
        
        $bulanList = $semesterAktif === 'ganjil' ? $bulanGanjil : $bulanGenap;

        // **AMBIL DATA PELANGGARAN BERDASARKAN TAHUN AJARAN**
        try {
            $pelanggaran = $this->getPelanggaranByTahunAjaran($tahun_ajaran);
        } catch (\Exception $e) {
            log_message('error', 'Error getting pelanggaran data: ' . $e->getMessage());
            $pelanggaran = [];
        }

        // **AMBIL DATA POIN SEMESTERAN BERDASARKAN TAHUN AJARAN**
        $poinSemesterGanjil = [];
        $poinSemesterGenap = [];
        
        try {
            $poinSemesterGanjil = $poinSemesteranModel->where([
                'semester' => 'ganjil',
                'tahun_ajaran' => $tahun_ajaran
            ])->findAll();
            
            $poinSemesterGenap = $poinSemesteranModel->where([
                'semester' => 'genap', 
                'tahun_ajaran' => $tahun_ajaran
            ])->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error getting poin semesteran: ' . $e->getMessage());
        }
        
        // Mapping poin per NIS
        $poinGanjil = [];
        $poinGenap = [];
        
        foreach ($poinSemesterGanjil as $row) {
            $poinGanjil[$row['nis']] = $row['total_poin'];
        }
        
        foreach ($poinSemesterGenap as $row) {
            $poinGenap[$row['nis']] = $row['total_poin'];
        }

        // Hitung total poin per semester
        $totalPoinGanjil = array_sum($poinGanjil);
        $totalPoinGenap = array_sum($poinGenap);

        // Ambil logs
        try {
            $logs = $adminLogModel->orderBy('created_at', 'desc')->limit(10)->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error getting logs: ' . $e->getMessage());
            $logs = [];
        }

        // Mapping admin names
        $adminNames = [];
        try {
            $admins = $adminModel->findAll();
            foreach ($admins as $admin) {
                $adminNames[$admin['admin_id']] = $admin['nama'];
            }
        } catch (\Exception $e) {
            log_message('error', 'Error getting admin names: ' . $e->getMessage());
        }

        // **HITUNG POIN BULANAN BERDASARKAN TAHUN AJARAN**
        $poinBulanan = [];
        try {
            foreach ($pelanggaran as $row) {
                $nis = $row['nis'];
                
                // Semester Ganjil
                foreach ($bulanGanjil as $bulan) {
                    $poin = $poinBulananModel
                        ->selectSum('poin')
                        ->where([
                            'nis' => $nis, 
                            'bulan' => $bulan,
                            'tahun_ajaran' => $tahun_ajaran
                        ])
                        ->get()
                        ->getRow();
                    $poinBulanan[$nis][$bulan] = $poin && $poin->poin ? (int)$poin->poin : 0;
                }
                
                // Semester Genap  
                foreach ($bulanGenap as $bulan) {
                    $poin = $poinBulananModel
                        ->selectSum('poin')
                        ->where([
                            'nis' => $nis, 
                            'bulan' => $bulan,
                            'tahun_ajaran' => $tahun_ajaran
                        ])
                        ->get()
                        ->getRow();
                    $poinBulanan[$nis][$bulan] = $poin && $poin->poin ? (int)$poin->poin : 0;
                }
            }
        } catch (\Exception $e) {
            log_message('error', 'Error calculating poin bulanan: ' . $e->getMessage());
        }

        // ===== DATA UNTUK NAVBAR INTERAKTIF =====
        // Total santri
        $totalSantri = 0;
        try {
            $totalSantri = $santriModel->countAll();
        } catch (\Exception $e) {
            log_message('error', 'Error counting santri: ' . $e->getMessage());
        }
        
        // Auto-generate SP untuk tahun ajaran yang dipilih
        try {
            $suratSPModel->autoGenerateSPFromPoinSemesteran($tahun_ajaran);
        } catch (\Exception $e) {
            log_message('error', 'Error auto-generating SP: ' . $e->getMessage());
        }
        
        // Hitung jumlah SP per semester untuk tahun ajaran yang dipilih
$sp1CountGanjil = 0;
$sp2CountGanjil = 0;
$sp3CountGanjil = 0;
$sp1CountGenap = 0;
$sp2CountGenap = 0;
$sp3CountGenap = 0;

try {
    $db = db_connect();
    $hasSemesterColumn = $db->fieldExists('semester', 'surat_sp');
    $hasTahunAjaranColumn = $db->fieldExists('tahun_ajaran', 'surat_sp');
    
    log_message('debug', 'SP Calculation - Semester Column: ' . ($hasSemesterColumn ? 'YES' : 'NO'));
    log_message('debug', 'SP Calculation - Tahun Ajaran Column: ' . ($hasTahunAjaranColumn ? 'YES' : 'NO'));
    log_message('debug', 'SP Calculation - Tahun Ajaran: ' . $tahun_ajaran);
    
    if ($hasSemesterColumn && $hasTahunAjaranColumn) {
        // Hitung dengan filter semester dan tahun ajaran
        $sp1CountGanjil = $suratSPModel->where([
            'jenis_sp' => 'SP1', 
            'semester' => 'ganjil',
            'tahun_ajaran' => $tahun_ajaran
        ])->countAllResults();
        
        $sp2CountGanjil = $suratSPModel->where([
            'jenis_sp' => 'SP2', 
            'semester' => 'ganjil',
            'tahun_ajaran' => $tahun_ajaran
        ])->countAllResults();
        
        $sp3CountGanjil = $suratSPModel->where([
            'jenis_sp' => 'SP3', 
            'semester' => 'ganjil',
            'tahun_ajaran' => $tahun_ajaran
        ])->countAllResults();
        
        $sp1CountGenap = $suratSPModel->where([
            'jenis_sp' => 'SP1', 
            'semester' => 'genap',
            'tahun_ajaran' => $tahun_ajaran
        ])->countAllResults();
        
        $sp2CountGenap = $suratSPModel->where([
            'jenis_sp' => 'SP2', 
            'semester' => 'genap',
            'tahun_ajaran' => $tahun_ajaran
        ])->countAllResults();
        
        $sp3CountGenap = $suratSPModel->where([
            'jenis_sp' => 'SP3', 
            'semester' => 'genap',
            'tahun_ajaran' => $tahun_ajaran
        ])->countAllResults();
        
        log_message('debug', 'SP Counts - Ganjil: SP1=' . $sp1CountGanjil . ', SP2=' . $sp2CountGanjil . ', SP3=' . $sp3CountGanjil);
        log_message('debug', 'SP Counts - Genap: SP1=' . $sp1CountGenap . ', SP2=' . $sp2CountGenap . ', SP3=' . $sp3CountGenap);
        
    } else {
        // FALLBACK: Hitung semua SP tanpa filter (untuk kompatibilitas)
        log_message('warning', 'Using fallback SP calculation - columns missing');
        
        $allSP = $suratSPModel->findAll();
        
        foreach ($allSP as $sp) {
            $jenis = $sp['jenis_sp'] ?? '';
            $semester = $sp['semester'] ?? 'ganjil'; // Default ke ganjil jika tidak ada
            
            if ($jenis === 'SP1') {
                if ($semester === 'genap') $sp1CountGenap++;
                else $sp1CountGanjil++;
            } elseif ($jenis === 'SP2') {
                if ($semester === 'genap') $sp2CountGenap++;
                else $sp2CountGanjil++;
            } elseif ($jenis === 'SP3') {
                if ($semester === 'genap') $sp3CountGenap++;
                else $sp3CountGanjil++;
            }
        }
        
        log_message('debug', 'Fallback SP Counts - Ganjil: SP1=' . $sp1CountGanjil . ', SP2=' . $sp2CountGanjil . ', SP3=' . $sp3CountGanjil);
        log_message('debug', 'Fallback SP Counts - Genap: SP1=' . $sp1CountGenap . ', SP2=' . $sp2CountGenap . ', SP3=' . $sp3CountGenap);
    }
} catch (\Exception $e) {
    log_message('error', 'Error counting SP: ' . $e->getMessage());
    
    // Emergency fallback - hitung sederhana
    $allSP = $suratSPModel->findAll();
    $sp1CountGanjil = count(array_filter($allSP, function($sp) {
        return ($sp['jenis_sp'] ?? '') === 'SP1';
    }));
    $sp1CountGenap = 0; // Default untuk genap
}

        // **AMBIL AVAILABLE TAHUN AJARAN UNTUK DROPDOWN**
        $availableTahunAjaran = $rekapanModel->getAvailableTahunAjaran();

        return view('admin_dashboard', [
            'nama'               => $session->get('nama'),
            'id_role'            => $id_role,
            'pelanggaran'        => $pelanggaran,
            'logs'               => $logs,
            'adminNames'         => $adminNames,
            'poinBulanan'        => $poinBulanan,
            'bulanList'          => $bulanList,
            'semesterAktif'      => $semesterAktif,
            'bulanGanjil'        => $bulanGanjil,
            'bulanGenap'         => $bulanGenap,
            'poinGanjil'         => $poinGanjil,
            'poinGenap'          => $poinGenap,
            'totalSantri'        => $totalSantri,
            'totalPoinGanjil'    => $totalPoinGanjil,
            'totalPoinGenap'     => $totalPoinGenap,
            'sp1CountGanjil'     => $sp1CountGanjil,
            'sp2CountGanjil'     => $sp2CountGanjil,
            'sp3CountGanjil'     => $sp3CountGanjil,
            'sp1CountGenap'      => $sp1CountGenap,
            'sp2CountGenap'      => $sp2CountGenap,
            'sp3CountGenap'      => $sp3CountGenap,
            'tahun_ajaran'       => $tahun_ajaran,
            'availableTahunAjaran' => $availableTahunAjaran
        ]);
    }

private function getPelanggaranByTahunAjaran($tahun_ajaran)
{
    $db = \Config\Database::connect();
    
    return $db->table('santri s')
        ->select("
            s.nis, 
            s.nama_santri, 
            COALESCE(MAX(p.tanggal), '-') AS tanggal, 
            COALESCE(SUM(p.poin), 0) AS total_poin,
            SUM(CASE WHEN p.bulan = 'Juli' THEN p.poin ELSE 0 END) AS Juli,
            SUM(CASE WHEN p.bulan = 'Agustus' THEN p.poin ELSE 0 END) AS Agustus,
            SUM(CASE WHEN p.bulan = 'September' THEN p.poin ELSE 0 END) AS September,
            SUM(CASE WHEN p.bulan = 'Oktober' THEN p.poin ELSE 0 END) AS Oktober,
            SUM(CASE WHEN p.bulan = 'November' THEN p.poin ELSE 0 END) AS November,
            SUM(CASE WHEN p.bulan = 'Desember' THEN p.poin ELSE 0 END) AS Desember
        ")
        ->join('poin_bulanan p', "p.nis = s.nis AND p.tahun_ajaran = '$tahun_ajaran'", 'left')
        ->groupBy('s.nis, s.nama_santri')
        ->orderBy('s.nama_santri', 'ASC')
        ->get()
        ->getResultArray();
}

    /**
     * API untuk check status tahun ajaran
     */
    public function checkTahunAjaran()
    {
        $tahun_ajaran = $this->request->getGet('tahun_ajaran');
        $rekapanModel = new RekapanModel();
        
        $available = $rekapanModel->isTahunAjaranAvailable($tahun_ajaran);
        
        return $this->response->setJSON([
            'tahun_ajaran' => $tahun_ajaran,
            'available' => $available,
            'message' => $available ? 'Tahun ajaran dapat diakses' : 'Tahun ajaran belum dapat diakses'
        ]);
    }

    /**
     * METHOD UNTUK GRAFIK PELANGGARAN (ROLE 2 & 3)
     */
    /**
 * METHOD UNTUK GRAFIK PELANGGARAN (ROLE 2 & 3)
 */
public function grafik_pelanggaran()
{
    $session = session();
    if (!$session->get('logged_in')) {
        return redirect()->to('/login');
    }

    // Cek role, hanya role 2 & 3 yang bisa akses
    $id_role = $session->get('id_role');
    // if ($id_role != 2 && $id_role != 3) {
    //     return redirect()->to('/dashboard')->with('error', 'Akses ditolak. Hanya Ustadz Spesial dan Yayasan yang dapat mengakses grafik.');
    // }

    $santriModel = new SantriModel();
    $rekapanModel = new RekapanModel();
    
    // PERBAIKAN: Hapus kondisi status='aktif' karena kolom status tidak ada
    try {
        // Coba ambil semua santri tanpa filter status
        $santri = $santriModel->findAll();
        
        // Debug: log jumlah santri yang ditemukan
        log_message('debug', 'Total santri found: ' . count($santri));
        
    } catch (\Exception $e) {
        log_message('error', 'Error getting santri data: ' . $e->getMessage());
        $santri = [];
    }

    // Ambil data seluruh_pelanggaran dari rekapan untuk grafik
    $tahun_ajaran = $this->request->getGet('tahun_ajaran') ?? '2025/2026';
    $rekapanData = [];
    
    try {
        $rekapanData = $rekapanModel->where('tahun_ajaran', $tahun_ajaran)->findAll();
    } catch (\Exception $e) {
        log_message('error', 'Error getting rekapan data: ' . $e->getMessage());
    }

    $data = [
        'title' => 'Grafik Pelanggaran Santri',
        'santri' => $santri,
        'rekapanData' => $rekapanData,
        'nama' => $session->get('nama'),
        'id_role' => $id_role,
        'tahun_ajaran' => $tahun_ajaran
    ];

    return view('grafik_pelanggaran', $data);
}

    /**
     * API untuk ambil data grafik per santri
     */
    public function get_data_grafik($nis)
    {
        $session = session();
        $id_role = $session->get('id_role');
        
        // Cek role, hanya role 2 & 3 yang bisa akses
        // if (!$session->get('logged_in') || ($id_role != 2 && $id_role != 3)) {
        //     return $this->response->setJSON(['error' => 'Akses ditolak']);
        // }

        $poinBulananModel = new PoinBulananModel();
        $rekapanModel = new RekapanModel();
        
        $bulanList = ['Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember', 'Januari'];
        $dataPelanggaran = [];
        
        foreach ($bulanList as $bulan) {
            $poin = $poinBulananModel
                ->selectSum('poin')
                ->where(['nis' => $nis, 'bulan' => $bulan])
                ->get()
                ->getRow();
                
            $dataPelanggaran[] = $poin && $poin->poin ? (int)$poin->poin : 0;
        }

        // Ambil data seluruh_pelanggaran dari rekapan
        $tahun_ajaran = $this->request->getGet('tahun_ajaran') ?? '2025/2026';
        $rekapanData = $rekapanModel->where(['nis' => $nis, 'tahun_ajaran' => $tahun_ajaran])->first();
        $seluruhPelanggaran = $rekapanData['seluruh_pelanggaran'] ?? '-';

        // Ambil nama santri
        $santriModel = new SantriModel();
        $santri = $santriModel->where('nis', $nis)->first();
        $nama_santri = $santri ? $santri['nama_santri'] : 'Santri Tidak Ditemukan';

        return $this->response->setJSON([
            'nama_santri' => $nama_santri,
            'pelanggaran' => $dataPelanggaran,
            'bulan' => $bulanList,
            'seluruh_pelanggaran' => $seluruhPelanggaran
        ]);
    }
}