<?php

namespace App\Models;

use CodeIgniter\Model;

class RiwayatPoinModel extends Model
{
    protected $table = 'riwayat_poin';
    protected $primaryKey = 'id_riwayat';
    protected $allowedFields = [
        'nis',
        'id_poin_asli', 
        'poin_dihapus',
        'keterangan_poin',
        'alasan_penghapusan',
        'kategori_poin',
        'bulan_poin',
        'tahun_ajaran',
        'semester',
        'dihapus_oleh',
        'tanggal_dihapus'
    ];
    
    protected $useTimestamps = false;
    
    /**
     * Simpan riwayat penghapusan poin
     */
    public function simpanRiwayat($data)
    {
        try {
            return $this->insert($data);
        } catch (\Exception $e) {
            log_message('error', 'Error saving riwayat poin: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Ambil riwayat penghapusan berdasarkan NIS
     */
    public function getRiwayatByNis($nis, $tahun_ajaran = null, $semester = null)
    {
        $where = ['nis' => $nis];
        
        if ($tahun_ajaran) {
            $where['tahun_ajaran'] = $tahun_ajaran;
        }
        if ($semester) {
            $where['semester'] = $semester;
        }
        
        return $this->where($where)
                   ->orderBy('tanggal_dihapus', 'DESC')
                   ->findAll();
    }
    
    /**
     * Ambil total poin yang dihapus per santri
     */
    public function getTotalPoinDihapus($nis, $tahun_ajaran = null, $semester = null)
    {
        $where = ['nis' => $nis];
        
        if ($tahun_ajaran) {
            $where['tahun_ajaran'] = $tahun_ajaran;
        }
        if ($semester) {
            $where['semester'] = $semester;
        }
        
        $result = $this->where($where)
                      ->selectSum('poin_dihapus')
                      ->get()
                      ->getRow();
                      
        return $result ? (int)$result->poin_dihapus : 0;
    }
    
    /**
     * Ambil riwayat dengan pagination
     */
    public function getRiwayatPagination($perPage = 10, $page = 1, $filters = [])
    {
        $builder = $this;
        
        if (!empty($filters['nis'])) {
            $builder->where('nis', $filters['nis']);
        }
        if (!empty($filters['tahun_ajaran'])) {
            $builder->where('tahun_ajaran', $filters['tahun_ajaran']);
        }
        if (!empty($filters['semester'])) {
            $builder->where('semester', $filters['semester']);
        }
        if (!empty($filters['dihapus_oleh'])) {
            $builder->like('dihapus_oleh', $filters['dihapus_oleh']);
        }
        
        return $builder->orderBy('tanggal_dihapus', 'DESC')
                      ->paginate($perPage, 'default', $page);
    }
}