<?php namespace App\Models;

use CodeIgniter\Model;

class PoinSemesteranModel extends Model
{
    protected $table = 'poin_semesteran';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nis', 'total_poin', 'semester', 'tahun_ajaran'];
    protected $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }

    /**
     * Update poin semesteran dengan support tahun ajaran
     */
    public function updateSemesteran($tahun_ajaran = null, $semester = null)
{
    if (!$tahun_ajaran) {
        $tahun_ajaran = $this->getCurrentTahunAjaran();
    }
    if (!$semester) {
        $semester = $this->getCurrentSemester();
    }
        
        // Ambil total poin per santri dari poin_bulanan untuk semester & tahun ajaran yang sesuai
        $bulanList = $semester === 'ganjil' 
            ? ['Juli','Agustus','September','Oktober','November','Desember']
            : ['Januari','Februari','Maret','April','Mei','Juni'];

        $placeholders = implode(',', array_fill(0, count($bulanList), '?'));
        
        $query = $this->db->query("
            SELECT nis, SUM(poin) AS total_poin
            FROM poin_bulanan
            WHERE bulan IN ($placeholders) 
            AND tahun_ajaran = ?
            AND semester = ?
            GROUP BY nis
        ", array_merge($bulanList, [$tahun_ajaran, $semester]));

        $data = $query->getResultArray();

        foreach ($data as $row) {
            $existing = $this->where([
                'nis' => $row['nis'], 
                'semester' => $semester,
                'tahun_ajaran' => $tahun_ajaran
            ])->first();

            if ($existing) {
                $this->update($existing['id'], ['total_poin' => $row['total_poin']]);
            } else {
                $this->insert([
                    'nis' => $row['nis'],
                    'total_poin' => $row['total_poin'],
                    'semester' => $semester,
                    'tahun_ajaran' => $tahun_ajaran
                ]);
            }
        }
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
     * Get available tahun ajaran
     */
    public function getAvailableTahunAjaran()
    {
        return $this->distinct()
                    ->select('tahun_ajaran')
                    ->orderBy('tahun_ajaran', 'DESC')
                    ->findAll();
    }

    /**
     * Method untuk mendapatkan poin berdasarkan semester & tahun ajaran
     */
    public function getPoinBySemester($semester, $tahun_ajaran = null)
    {
        if (!$tahun_ajaran) {
            $tahun_ajaran = $this->getCurrentTahunAjaran();
        }
        
        return $this->where('semester', $semester)
                   ->where('tahun_ajaran', $tahun_ajaran)
                   ->findAll();
    }

    /**
     * Get total poin by NIS, semester, dan tahun ajaran
     */
    public function getPoinByNis($nis, $semester, $tahun_ajaran)
    {
        $data = $this->where([
            'nis' => $nis,
            'semester' => $semester,
            'tahun_ajaran' => $tahun_ajaran
        ])->first();
        
        return $data ? $data['total_poin'] : 0;
    }
}