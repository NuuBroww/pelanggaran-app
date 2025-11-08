<?php

namespace App\Models;

use CodeIgniter\Model;

class RiwayatEditModel extends Model
{
    protected $table = 'riwayat_edit_poin';
    protected $primaryKey = 'id_riwayat_edit';
    protected $allowedFields = [
        'nis',
        'id_poin_asli',
        'poin_sebelum',
        'poin_sesudah',
        'keterangan_sebelum',
        'keterangan_sesudah',
        'kategori_sebelum',
        'kategori_sesudah',
        'detail_sebelum',
        'detail_sesudah',
        'bulan_poin',
        'tahun_ajaran',
        'semester',
        'diedit_oleh',
        'tanggal_edit',
        'alasan_edit'
    ];
    
    protected $useTimestamps = false;
    
    /**
     * Simpan riwayat edit poin
     */
    public function simpanRiwayatEdit($data)
    {
        try {
            return $this->insert($data);
        } catch (\Exception $e) {
            log_message('error', 'Error saving riwayat edit: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Ambil riwayat edit berdasarkan NIS
     */
    public function getRiwayatEditByNis($nis, $tahun_ajaran = null, $semester = null)
    {
        $where = ['nis' => $nis];
        
        if ($tahun_ajaran) {
            $where['tahun_ajaran'] = $tahun_ajaran;
        }
        if ($semester) {
            $where['semester'] = $semester;
        }
        
        return $this->where($where)
                   ->orderBy('tanggal_edit', 'DESC')
                   ->findAll();
    }
    
    /**
     * Ambil riwayat edit dengan pagination
     */
    public function getRiwayatEditPagination($perPage = 10, $page = 1, $filters = [])
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
        if (!empty($filters['diedit_oleh'])) {
            $builder->like('diedit_oleh', $filters['diedit_oleh']);
        }
        
        return $builder->orderBy('tanggal_edit', 'DESC')
                      ->paginate($perPage, 'default', $page);
    }
}