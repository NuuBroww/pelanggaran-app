<?php
namespace App\Models;

use CodeIgniter\Model;

class SuratSPModel extends Model
{
    protected $table = 'surat_peringatan';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'nis', 
        'nama_santri', 
        'jenis_sp',
        'semester', 
        'tahun_ajaran', 
        'nomor_registrasi', 
        'tanggal_surat',
        'content_pembuka',
        'isi_teguran',
        'tanda_tangan',
        'created_at'
    ];
    
    protected $useTimestamps = false;
    
    /**
     * Auto-generate SP berdasarkan poin dari santri (original method)
     */
    public function autoGenerateSP($tahun_ajaran = null, $semester = null)
    {
        // Ambil santri dengan poin tertentu
        $santriModel = new \App\Models\SantriModel();
        
        // Tentukan tahun ajaran & semester
        if (!$tahun_ajaran) {
            $tahun_ajaran = $this->getCurrentTahunAjaran();
        }
        if (!$semester) {
            $semester = $this->getCurrentSemester();
        }
        
        // SP1: Poin = 100
        $sp1Santri = $santriModel->where('total_poin =', 100)
                                ->where('total_poin <', 200)
                                ->findAll();
        
        // SP2: Poin >= 200  
        $sp2Santri = $santriModel->where('total_poin >=', 200)
                                ->where('total_poin <', 300)
                                ->findAll();
        
        // SP3: Poin >= 300
        $sp3Santri = $santriModel->where('total_poin >=', 300)->findAll();
        
        $generated = [];
        
        // Generate SP1
        foreach ($sp1Santri as $santri) {
            // Cek apakah sudah ada SP1 untuk santri ini dengan tahun ajaran & semester yang sama
            $existing = $this->where('nis', $santri['nis'])
                            ->where('jenis_sp', 'SP1')
                            ->where('tahun_ajaran', $tahun_ajaran)
                            ->where('semester', $semester)
                            ->first();
            
            if (!$existing) {
                $this->insert([
                    'nis' => $santri['nis'],
                    'nama_santri' => $santri['nama_santri'],
                    'jenis_sp' => 'SP1',
                    'semester' => $semester,
                    'tahun_ajaran' => $tahun_ajaran,
                    'nomor_registrasi' => 'AUTO/SP1/' . date('m/Y'),
                    'tanggal_surat' => date('Y-m-d'),
                    'isi_teguran' => 'Poin mencapai ' . $santri['total_poin'],
                    'tanda_tangan' => null,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
                $generated[] = $santri['nama_santri'] . ' - SP1';
            }
        }
        
        // Generate SP2
        foreach ($sp2Santri as $santri) {
            // Cek apakah sudah ada SP2 untuk santri ini dengan tahun ajaran & semester yang sama
            $existing = $this->where('nis', $santri['nis'])
                            ->where('jenis_sp', 'SP2')
                            ->where('tahun_ajaran', $tahun_ajaran)
                            ->where('semester', $semester)
                            ->first();
            
            if (!$existing) {
                $this->insert([
                    'nis' => $santri['nis'],
                    'nama_santri' => $santri['nama_santri'],
                    'jenis_sp' => 'SP2',
                    'semester' => $semester,
                    'tahun_ajaran' => $tahun_ajaran,
                    'nomor_registrasi' => 'AUTO/SP2/' . date('m/Y'),
                    'tanggal_surat' => date('Y-m-d'),
                    'isi_teguran' => 'Poin mencapai ' . $santri['total_poin'],
                    'tanda_tangan' => null,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
                $generated[] = $santri['nama_santri'] . ' - SP2';
            }
        }
        
        // Generate SP3
        foreach ($sp3Santri as $santri) {
            // Cek apakah sudah ada SP3 untuk santri ini dengan tahun ajaran & semester yang sama
            $existing = $this->where('nis', $santri['nis'])
                            ->where('jenis_sp', 'SP3')
                            ->where('tahun_ajaran', $tahun_ajaran)
                            ->where('semester', $semester)
                            ->first();
            
            if (!$existing) {
                $this->insert([
                    'nis' => $santri['nis'],
                    'nama_santri' => $santri['nama_santri'],
                    'jenis_sp' => 'SP3',
                    'semester' => $semester,
                    'tahun_ajaran' => $tahun_ajaran,
                    'nomor_registrasi' => 'AUTO/SP3/' . date('m/Y'),
                    'tanggal_surat' => date('Y-m-d'),
                    'isi_teguran' => 'Poin mencapai ' . $santri['total_poin'],
                    'tanda_tangan' => null,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
                $generated[] = $santri['nama_santri'] . ' - SP3';
            }
        }
        
        return $generated;
    }
    
    /**
     * Auto-generate SP berdasarkan poin_semesteran dengan sistem tahun ajaran
     */
    public function autoGenerateSPFromPoinSemesteran($tahun_ajaran = null, $semester = null)
    {
        // Load models
        $santriModel = new \App\Models\SantriModel();
        $poinSemesteranModel = new \App\Models\PoinSemesteranModel();
        
        // Tentukan semester & tahun ajaran
        if (!$tahun_ajaran) {
            $tahun_ajaran = $this->getCurrentTahunAjaran();
        }
        if (!$semester) {
            $semester = $this->getCurrentSemester();
        }
        
        // Ambil semua data poin_semesteran untuk semester & tahun ajaran tertentu
        $poinSemesteran = $poinSemesteranModel->where([
            'semester' => $semester,
            'tahun_ajaran' => $tahun_ajaran
        ])->findAll();
        
        foreach ($poinSemesteran as $poinData) {
            $nis = $poinData['nis'];
            $totalPoin = $poinData['total_poin'];
            
            // Ambil data santri
            $santri = $santriModel->where('nis', $nis)->first();
            if (!$santri) continue;
            
            // Tentukan SP level berdasarkan poin
            $jenisSP = null;
            if ($totalPoin >= 300) {
                $jenisSP = 'SP3';
            } elseif ($totalPoin >= 200) {
                $jenisSP = 'SP2';
            } elseif ($totalPoin >= 100) {
                $jenisSP = 'SP1';
            }
            
            if ($jenisSP) {
                // Cek apakah sudah ada SP untuk santri ini dengan jenis, semester, dan tahun ajaran yang sama
                $existing = $this->where([
                    'nis' => $nis,
                    'jenis_sp' => $jenisSP,
                    'semester' => $semester,
                    'tahun_ajaran' => $tahun_ajaran
                ])->first();
                
                if (!$existing) {
                    // Hapus SP lama untuk semester & tahun ajaran yang sama jika ada
                    $this->where([
                        'nis' => $nis,
                        'semester' => $semester,
                        'tahun_ajaran' => $tahun_ajaran
                    ])->delete();
                    
                    // Insert SP baru
                    $this->insert([
                        'nis' => $nis,
                        'nama_santri' => $santri['nama_santri'],
                        'jenis_sp' => $jenisSP,
                        'semester' => $semester,
                        'tahun_ajaran' => $tahun_ajaran,
                        'nomor_registrasi' => 'AUTO/' . $jenisSP . '/' . date('m/Y'),
                        'tanggal_surat' => date('Y-m-d'),
                        'isi_teguran' => 'Total poin semester ' . $semester . ' tahun ' . $tahun_ajaran . ' mencapai ' . $totalPoin,
                        'tanda_tangan' => null,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                } else {
                    // Update SP yang sudah ada jika poin berubah
                    $this->update($existing['id'], [
                        'isi_teguran' => 'Total poin semester ' . $semester . ' tahun ' . $tahun_ajaran . ' mencapai ' . $totalPoin,
                        'tanggal_surat' => date('Y-m-d')
                    ]);
                }
            } else {
                // Jika poin di bawah 100, hapus SP untuk semester & tahun ajaran ini jika ada
                $this->where([
                    'nis' => $nis,
                    'semester' => $semester,
                    'tahun_ajaran' => $tahun_ajaran
                ])->delete();
            }
        }
        
        return true;
    }
    
    /**
     * Update SP untuk santri tertentu berdasarkan poin_semesteran
     */
    public function updateSPForSantriFromPoinSemesteran($nis, $tahun_ajaran = null, $semester = null)
    {
        $santriModel = new \App\Models\SantriModel();
        $poinSemesteranModel = new \App\Models\PoinSemesteranModel();
        
        // Tentukan semester & tahun ajaran
        if (!$tahun_ajaran) {
            $tahun_ajaran = $this->getCurrentTahunAjaran();
        }
        if (!$semester) {
            $semester = $this->getCurrentSemester();
        }
        
        // Ambil data poin_semesteran untuk santri ini
        $poinData = $poinSemesteranModel->where([
            'nis' => $nis,
            'semester' => $semester,
            'tahun_ajaran' => $tahun_ajaran
        ])->first();
        
        if (!$poinData) return false;
        
        $totalPoin = $poinData['total_poin'];
        $santri = $santriModel->where('nis', $nis)->first();
        if (!$santri) return false;
        
        // Tentukan SP level
        $jenisSP = null;
        if ($totalPoin >= 300) {
            $jenisSP = 'SP3';
        } elseif ($totalPoin >= 200) {
            $jenisSP = 'SP2';
        } elseif ($totalPoin >= 100) {
            $jenisSP = 'SP1';
        }
        
        // Hapus SP lama untuk semester & tahun ajaran ini
        $this->where([
            'nis' => $nis,
            'semester' => $semester,
            'tahun_ajaran' => $tahun_ajaran
        ])->delete();
        
        // Insert SP baru jika memenuhi syarat
        if ($jenisSP) {
            $this->insert([
                'nis' => $nis,
                'nama_santri' => $santri['nama_santri'],
                'jenis_sp' => $jenisSP,
                'semester' => $semester,
                'tahun_ajaran' => $tahun_ajaran,
                'nomor_registrasi' => 'AUTO/' . $jenisSP . '/' . date('m/Y'),
                'tanggal_surat' => date('Y-m-d'),
                'isi_teguran' => 'Total poin semester ' . $semester . ' tahun ' . $tahun_ajaran . ' mencapai ' . $totalPoin,
                'tanda_tangan' => null,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            return $jenisSP;
        }
        
        return false;
    }
    
    /**
     * Update SP jika poin berubah - dengan penambahan tahun ajaran
     */
    public function updateSPByPoin($nis, $currentPoin, $semester = null, $tahun_ajaran = null)
    {
        // Tentukan semester & tahun ajaran
        if (!$tahun_ajaran) {
            $tahun_ajaran = $this->getCurrentTahunAjaran();
        }
        if (!$semester) {
            $semester = $this->getCurrentSemester();
        }
        
        $santri = (new \App\Models\SantriModel())->where('nis', $nis)->first();
        
        if (!$santri) return false;
        
        // Hapus SP lama untuk semester & tahun ajaran ini jika poin turun di bawah threshold
        if ($currentPoin < 100) {
            $this->where([
                'nis' => $nis,
                'semester' => $semester,
                'tahun_ajaran' => $tahun_ajaran
            ])->delete();
        } 
        // Update ke SP yang sesuai
        elseif ($currentPoin >= 300) {
            $this->where([
                'nis' => $nis,
                'semester' => $semester,
                'tahun_ajaran' => $tahun_ajaran
            ])->delete(); // Hapus dulu
            
            $this->insert([
                'nis' => $santri['nis'],
                'nama_santri' => $santri['nama_santri'],
                'jenis_sp' => 'SP3',
                'semester' => $semester,
                'tahun_ajaran' => $tahun_ajaran,
                'nomor_registrasi' => 'AUTO/SP3/' . date('m/Y'),
                'tanggal_surat' => date('Y-m-d'),
                'isi_teguran' => 'Poin semester ' . $semester . ' tahun ' . $tahun_ajaran . ' mencapai ' . $currentPoin,
                'tanda_tangan' => null,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }
        elseif ($currentPoin >= 200) {
            $this->where([
                'nis' => $nis,
                'semester' => $semester,
                'tahun_ajaran' => $tahun_ajaran
            ])->delete(); // Hapus dulu
            
            $this->insert([
                'nis' => $santri['nis'],
                'nama_santri' => $santri['nama_santri'],
                'jenis_sp' => 'SP2',
                'semester' => $semester,
                'tahun_ajaran' => $tahun_ajaran,
                'nomor_registrasi' => 'AUTO/SP2/' . date('m/Y'),
                'tanggal_surat' => date('Y-m-d'),
                'isi_teguran' => 'Poin semester ' . $semester . ' tahun ' . $tahun_ajaran . ' mencapai ' . $currentPoin,
                'tanda_tangan' => null,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }
        elseif ($currentPoin >= 100) {
            $this->where([
                'nis' => $nis,
                'semester' => $semester,
                'tahun_ajaran' => $tahun_ajaran
            ])->delete(); // Hapus dulu
            
            $this->insert([
                'nis' => $santri['nis'],
                'nama_santri' => $santri['nama_santri'],
                'jenis_sp' => 'SP1',
                'semester' => $semester,
                'tahun_ajaran' => $tahun_ajaran,
                'nomor_registrasi' => 'AUTO/SP1/' . date('m/Y'),
                'tanggal_surat' => date('Y-m-d'),
                'isi_teguran' => 'Poin semester ' . $semester . ' tahun ' . $tahun_ajaran . ' mencapai ' . $currentPoin,
                'tanda_tangan' => null,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }
    }
    
    /**
     * Method untuk mendapatkan jumlah SP per semester & tahun ajaran
     */
    public function getSPCountBySemester($semester, $tahun_ajaran = null)
    {
        if (!$tahun_ajaran) {
            $tahun_ajaran = $this->getCurrentTahunAjaran();
        }
        
        return [
            'SP1' => $this->where([
                'jenis_sp' => 'SP1', 
                'semester' => $semester,
                'tahun_ajaran' => $tahun_ajaran
            ])->countAllResults(),
            'SP2' => $this->where([
                'jenis_sp' => 'SP2', 
                'semester' => $semester,
                'tahun_ajaran' => $tahun_ajaran
            ])->countAllResults(),
            'SP3' => $this->where([
                'jenis_sp' => 'SP3', 
                'semester' => $semester,
                'tahun_ajaran' => $tahun_ajaran
            ])->countAllResults()
        ];
    }
    
    /**
     * Method untuk mendapatkan semua SP berdasarkan semester & tahun ajaran
     */
    public function getSPBySemester($semester, $tahun_ajaran = null)
    {
        if (!$tahun_ajaran) {
            $tahun_ajaran = $this->getCurrentTahunAjaran();
        }
        
        return $this->where([
            'semester' => $semester,
            'tahun_ajaran' => $tahun_ajaran
        ])->findAll();
    }

    /**
     * Get available tahun ajaran dari data SP
     */
    public function getAvailableTahunAjaran()
    {
        return $this->distinct()
                    ->select('tahun_ajaran')
                    ->orderBy('tahun_ajaran', 'DESC')
                    ->findAll();
    }

    /**
     * Get current tahun ajaran
     */
    public function getCurrentTahunAjaran()
    {
        $currentYear = date('Y');
        $currentMonth = date('n');
        
        if ($currentMonth >= 1 && $currentMonth <= 6) {
            return ($currentYear - 1) . '/' . $currentYear;
        } else {
            return $currentYear . '/' . ($currentYear + 1);
        }
    }

    /**
     * Get current semester
     */
    public function getCurrentSemester()
    {
        $currentMonth = date('n');
        return ($currentMonth >= 1 && $currentMonth <= 6) ? 'genap' : 'ganjil';
    }

    /**
     * Get SP by NIS, semester, dan tahun ajaran
     */
    public function getSPByNis($nis, $semester, $tahun_ajaran)
    {
        return $this->where([
            'nis' => $nis,
            'semester' => $semester,
            'tahun_ajaran' => $tahun_ajaran
        ])->first();
    }

    /**
     * Get all SP by tahun ajaran
     */
    public function getSPByTahunAjaran($tahun_ajaran)
    {
        return $this->where('tahun_ajaran', $tahun_ajaran)
                   ->orderBy('semester', 'ASC')
                   ->orderBy('jenis_sp', 'ASC')
                   ->findAll();
    }

    /**
     * Delete SP by ID
     */
    public function deleteSP($id)
    {
        return $this->delete($id);
    }

    /**
     * Get SP statistics for dashboard
     */
    public function getSPStatistics($tahun_ajaran = null)
    {
        if (!$tahun_ajaran) {
            $tahun_ajaran = $this->getCurrentTahunAjaran();
        }

        $stats = [
            'total' => $this->where('tahun_ajaran', $tahun_ajaran)->countAllResults(),
            'ganjil' => [
                'SP1' => $this->where(['tahun_ajaran' => $tahun_ajaran, 'semester' => 'ganjil', 'jenis_sp' => 'SP1'])->countAllResults(),
                'SP2' => $this->where(['tahun_ajaran' => $tahun_ajaran, 'semester' => 'ganjil', 'jenis_sp' => 'SP2'])->countAllResults(),
                'SP3' => $this->where(['tahun_ajaran' => $tahun_ajaran, 'semester' => 'ganjil', 'jenis_sp' => 'SP3'])->countAllResults(),
            ],
            'genap' => [
                'SP1' => $this->where(['tahun_ajaran' => $tahun_ajaran, 'semester' => 'genap', 'jenis_sp' => 'SP1'])->countAllResults(),
                'SP2' => $this->where(['tahun_ajaran' => $tahun_ajaran, 'semester' => 'genap', 'jenis_sp' => 'SP2'])->countAllResults(),
                'SP3' => $this->where(['tahun_ajaran' => $tahun_ajaran, 'semester' => 'genap', 'jenis_sp' => 'SP3'])->countAllResults(),
            ]
        ];

        return $stats;
    }
}