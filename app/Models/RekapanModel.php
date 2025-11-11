<?php namespace App\Models;

use CodeIgniter\Model;

class RekapanModel extends Model
{
    protected $table = 'rekapan';
    protected $primaryKey = 'id_rekap';
    protected $allowedFields = [
        'nis', 
        'total_poin', 
        'seluruh_pelanggaran', 
        'bulan_terbanyak', 
        'sp_level',
        'tahun_ajaran',
        'semester'
    ];

    protected $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }

    /**
 * Generate rekapan berdasarkan tahun ajaran dan semester
 */
public function generateRekapan($tahun_ajaran = null, $semester = null)
{
    if (!$tahun_ajaran) {
        $tahun_ajaran = $this->getCurrentTahunAjaran();
    }
    if (!$semester) {
        $semester = $this->getCurrentSemester();
    }

    log_message('debug', '=== START GENERATE REKAPAN ===');
    log_message('debug', 'Generating rekapan for: ' . $tahun_ajaran . ' - ' . $semester);

    try {
        // **CEK KOLOM YANG ADA DI TABEL**
        $db = \Config\Database::connect();
        $hasTahunAjaranInPoin = $db->fieldExists('tahun_ajaran', 'poin_bulanan');
        $hasSemesterInPoin = $db->fieldExists('semester', 'poin_bulanan');
        
        log_message('debug', 'Tahun ajaran di poin_bulanan: ' . ($hasTahunAjaranInPoin ? 'ADA' : 'TIDAK ADA'));
        log_message('debug', 'Semester di poin_bulanan: ' . ($hasSemesterInPoin ? 'ADA' : 'TIDAK ADA'));

        // **AMBIL SEMUA SANTRI**
        $santriQuery = $this->db->query("SELECT nis, nama_santri FROM santri");
        $allSantri = $santriQuery->getResultArray();

        log_message('debug', 'Total santri found: ' . count($allSantri));

        if (empty($allSantri)) {
            log_message('error', 'Tidak ada santri ditemukan!');
            return false;
        }

        $successCount = 0;
        $errorCount = 0;

        foreach ($allSantri as $santri) {
            $nis = $santri['nis'];
            
            log_message('debug', 'Processing santri: ' . $nis . ' - ' . $santri['nama_santri']);

            try {
                // **QUERY TOTAL POIN - SESUAIKAN DENGAN KOLOM YANG ADA**
                if ($hasTahunAjaranInPoin && $hasSemesterInPoin) {
                    // Jika kolom tahun_ajaran dan semester ada
                    $totalPoinQuery = $this->db->query("
                        SELECT nis, SUM(poin) AS total_poin
                        FROM poin_bulanan 
                        WHERE nis = ? AND tahun_ajaran = ? AND semester = ?
                        GROUP BY nis
                    ", [$nis, $tahun_ajaran, $semester]);
                } else {
                    // **JIKA KOLOM TIDAK ADA, AMBIL SEMUA POIN**
                    $totalPoinQuery = $this->db->query("
                        SELECT nis, SUM(poin) AS total_poin
                        FROM poin_bulanan 
                        WHERE nis = ?
                        GROUP BY nis
                    ", [$nis]);
                }
                
                $totalRow = $totalPoinQuery->getRow();
                $total_poin = $totalRow ? (int)$totalRow->total_poin : 0;

                log_message('debug', 'Santri ' . $nis . ' total poin: ' . $total_poin);

                // **AMBIL DATA PELANGGARAN**
                if ($hasTahunAjaranInPoin && $hasSemesterInPoin) {
                    $pelanggaranQuery = $this->db->query("
                        SELECT keterangan, poin, bulan, tanggal
                        FROM poin_bulanan
                        WHERE nis = ? AND tahun_ajaran = ? AND semester = ?
                        ORDER BY tanggal DESC
                    ", [$nis, $tahun_ajaran, $semester]);
                } else {
                    $pelanggaranQuery = $this->db->query("
                        SELECT keterangan, poin, bulan, tanggal
                        FROM poin_bulanan
                        WHERE nis = ?
                        ORDER BY tanggal DESC
                    ", [$nis]);
                }
                
                $pelanggaranData = $pelanggaranQuery->getResultArray();
                
                // Format seluruh pelanggaran
                $seluruh_pelanggaran = '-';
                if (!empty($pelanggaranData)) {
                    $pelanggaranList = [];
                    foreach ($pelanggaranData as $p) {
                        $pelanggaranList[] = "{$p['keterangan']} ({$p['poin']} poin) - {$p['bulan']}";
                    }
                    $seluruh_pelanggaran = implode('; ', $pelanggaranList);
                }

                // **CARI BULAN TERBANYAK**
                if ($hasTahunAjaranInPoin && $hasSemesterInPoin) {
                    $bulanQuery = $this->db->query("
                        SELECT bulan, SUM(poin) AS total_bulan
                        FROM poin_bulanan
                        WHERE nis = ? AND tahun_ajaran = ? AND semester = ?
                        GROUP BY bulan
                        ORDER BY total_bulan DESC
                        LIMIT 1
                    ", [$nis, $tahun_ajaran, $semester]);
                } else {
                    $bulanQuery = $this->db->query("
                        SELECT bulan, SUM(poin) AS total_bulan
                        FROM poin_bulanan
                        WHERE nis = ?
                        GROUP BY bulan
                        ORDER BY total_bulan DESC
                        LIMIT 1
                    ", [$nis]);
                }
                
                $bulan = $bulanQuery->getRow();
                $bulan_terbanyak = $bulan ? $bulan->bulan : '-';

                // **TENTUKAN SP LEVEL**
                $sp_level = $this->determineSPLevel($total_poin);

                // **CEK APAKAH SUDAH ADA DI REKAPAN - PERBAIKI QUERY INI**
                $existingQuery = $this->db->query("
                    SELECT id_rekap 
                    FROM rekapan 
                    WHERE nis = ? AND tahun_ajaran = ? AND semester = ?
                ", [$nis, $tahun_ajaran, $semester]);
                
                $existing = $existingQuery->getRow();

                if ($existing) {
                    // ✅ PERBAIKAN: Gunakan query builder untuk update
                    $updateData = [
                        'total_poin' => $total_poin,
                        'seluruh_pelanggaran' => $seluruh_pelanggaran,
                        'bulan_terbanyak' => $bulan_terbanyak,
                        'sp_level' => $sp_level,
                        'updated_at' => date('Y-m-d H:i:s')
                    ];
                    
                    $updateResult = $this->db->table('rekapan')
                        ->where('id_rekap', $existing->id_rekap)
                        ->update($updateData);
                        
                    if ($updateResult) {
                        log_message('debug', '✅ Updated rekapan for: ' . $nis);
                        $successCount++;
                    } else {
                        log_message('error', '❌ Failed to update rekapan for: ' . $nis);
                        $errorCount++;
                    }
                } else {
                    // ✅ PERBAIKAN: Insert baru dengan query builder
                    $insertData = [
                        'nis' => $nis,
                        'total_poin' => $total_poin,
                        'seluruh_pelanggaran' => $seluruh_pelanggaran,
                        'bulan_terbanyak' => $bulan_terbanyak,
                        'sp_level' => $sp_level,
                        'tahun_ajaran' => $tahun_ajaran,
                        'semester' => $semester,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ];
                    
                    $insertResult = $this->db->table('rekapan')->insert($insertData);
                    
                    if ($insertResult) {
                        log_message('debug', '✅ Inserted new rekapan for: ' . $nis);
                        $successCount++;
                    } else {
                        log_message('error', '❌ Failed to insert rekapan for: ' . $nis);
                        $errorCount++;
                    }
                }

            } catch (\Exception $e) {
                log_message('error', 'Error processing santri ' . $nis . ': ' . $e->getMessage());
                $errorCount++;
                continue; // Lanjut ke santri berikutnya
            }
        }

        log_message('debug', '=== FINISHED GENERATE REKAPAN ===');
        log_message('debug', 'Success: ' . $successCount . ', Errors: ' . $errorCount);
        
        return $successCount > 0;

    } catch (\Exception $e) {
        log_message('error', 'Error in generateRekapan: ' . $e->getMessage());
        return false;
    }
}

    /**
     * Get current tahun ajaran
     * Format: 2025/2026
     */
    // public function getCurrentTahunAjaran()
    // {
    //     $currentYear = date('Y');
    //     $currentMonth = date('n');
        
    //     // Jika bulan Jan-Juni: tahun ajaran (tahun-1)/tahun
    //     // Jika bulan Jul-Des: tahun ajaran tahun/(tahun+1)
    //     if ($currentMonth >= 1 && $currentMonth <= 6) {
    //         return ($currentYear - 1) . '/' . $currentYear;
    //     } else {
    //         return $currentYear . '/' . ($currentYear + 1);
    //     }
    // }

    /**
     * Get current semester
     */
    // public function getCurrentSemester()
    // {
    //     $currentMonth = date('n');
    //     return ($currentMonth >= 1 && $currentMonth <= 6) ? 'genap' : 'ganjil';
    // }

   
    /**
 * Get available tahun ajaran dari data yang ada
 */
// public function getAvailableTahunAjaran()
// {
//     try {
//         // **TAMBAHKAN 2025/2026 DAN 2026/2027 SECARA MANUAL**
//         $defaultTahunAjaran = ['2026/2027', '2025/2026'];
        
//         // Cek apakah kolom tahun_ajaran ada
//         if (!$this->db->fieldExists('tahun_ajaran', 'rekapan')) {
//             return $defaultTahunAjaran;
//         }
        
//         $query = $this->db->query("
//             SELECT DISTINCT tahun_ajaran 
//             FROM rekapan 
//             WHERE tahun_ajaran IS NOT NULL AND tahun_ajaran != ''
//             ORDER BY tahun_ajaran DESC
//         ");
        
//         $result = $query->getResultArray();
        
//         // Jika ada data dari database, gabungkan dengan default
//         if (!empty($result)) {
//             $dbTahunAjaran = array_column($result, 'tahun_ajaran');
//             $allTahunAjaran = array_merge($defaultTahunAjaran, $dbTahunAjaran);
//         } else {
//             $allTahunAjaran = $defaultTahunAjaran;
//         }
        
//         // Remove duplicates dan urutkan descending
//         $allTahunAjaran = array_unique($allTahunAjaran);
//         rsort($allTahunAjaran);
        
//         return $allTahunAjaran;
        
//     } catch (\Exception $e) {
//         log_message('error', 'Error in getAvailableTahunAjaran: ' . $e->getMessage());
//         return ['2026/2027', '2025/2026'];
//     }
// }

    
    /**
     * Tentukan SP Level berdasarkan total poin
     */
    private function determineSPLevel($total_poin)
    {
        if ($total_poin >= 300) {
            return 'SP3';
        } elseif ($total_poin >= 200) {
            return 'SP2';
        } elseif ($total_poin >= 100) {
            return 'SP1';
        } else {
            return '-';
        }
    }

   /**
 * Cek apakah tahun ajaran terkunci (belum boleh diakses)
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

    /**
     * Get current tahun ajaran berdasarkan tanggal
     */
    public function getCurrentTahunAjaran()
    {
        $currentYear = date('Y');
        $currentMonth = date('n');
        
        // Jika bulan Jan-Juni: tahun ajaran (tahun-1)/tahun
        // Jika bulan Jul-Des: tahun ajaran tahun/(tahun+1)
        if ($currentMonth >= 1 && $currentMonth <= 6) {
            return ($currentYear - 1) . '/' . $currentYear;
        } else {
            return $currentYear . '/' . ($currentYear + 1);
        }
    }

    /**
     * Get current semester berdasarkan bulan
     */
    public function getCurrentSemester()
    {
        $currentMonth = date('n');
        return ($currentMonth >= 1 && $currentMonth <= 6) ? 'genap' : 'ganjil';
    }

    /**
 * METHOD BARU: Get riwayat penghapusan poin
 */
public function getRiwayatPenghapusan($nis, $tahun_ajaran = null, $semester = null)
{
    try {
        $where = ['nis' => $nis];
        
        if ($tahun_ajaran) {
            $where['tahun_ajaran'] = $tahun_ajaran;
        }
        if ($semester) {
            $where['semester'] = $semester;
        }

        $result = $this->where($where)
            ->select('catatan_poin_penghapusan, total_poin_dihapus, dihapus_oleh, tanggal_dihapus, updated_at')
            ->orderBy('tanggal_dihapus', 'DESC')
            ->findAll();

        // Format data untuk response
        $riwayat = [];
        foreach ($result as $row) {
            if (!empty($row['catatan_poin_penghapusan'])) {
                $riwayat[] = [
                    'catatan' => $row['catatan_poin_penghapusan'],
                    'total_poin_dihapus' => $row['total_poin_dihapus'] ?? 0,
                    'dihapus_oleh' => $row['dihapus_oleh'] ?? 'System',
                    'tanggal_dihapus' => $row['tanggal_dihapus'] ?? $row['updated_at']
                ];
            }
        }

        return $riwayat;

    } catch (\Exception $e) {
        log_message('error', 'Error getting deletion history: ' . $e->getMessage());
        return [];
    }
}

/**
 * METHOD BARU: Update catatan penghapusan
 */
public function updateCatatanPenghapusan($nis, $poin_dihapus, $catatan, $tahun_ajaran, $semester)
{
    try {
        $data = [
            'total_poin_dihapus' => $this->db->escape($poin_dihapus),
            'catatan_poin_penghapusan' => $this->db->escape($catatan),
            'dihapus_oleh' => $this->db->escape(session()->get('nama') ?? 'System'),
            'tanggal_dihapus' => $this->db->escape(date('Y-m-d H:i:s'))
        ];

        return $this->where([
            'nis' => $nis,
            'tahun_ajaran' => $tahun_ajaran,
            'semester' => $semester
        ])->set($data)->update();

    } catch (\Exception $e) {
        log_message('error', 'Error updating deletion note: ' . $e->getMessage());
        return false;
    }
}
}