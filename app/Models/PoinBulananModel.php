<?php namespace App\Models;

use CodeIgniter\Model;

class PoinBulananModel extends Model
{
    protected $table = 'poin_bulanan';
    protected $primaryKey = 'id';
    
    // ✅ PERBAIKAN: Hapus allowedFields sementara untuk bypass validation
    // protected $allowedFields = [];
    
    // ✅ ATAU: Gunakan allowedFields yang benar-benar sesuai
    protected $allowedFields = [
        'id',                    // int(11) - AUTO_INCREMENT
        'nis',                   // varchar(20)
        'bulan',                 // varchar(20)
        'poin',                  // int(11)  
        'keterangan',            // text
        'detail_pelanggaran',    // varchar(255)
        'kategori',              // varchar(100)
        'tanggal',               // datetime
        'tahun_ajaran',          // varchar(9)
        'semester'               // varchar(10)
    ];

    protected $useTimestamps = false;
    protected $returnType = 'array';
    
    // ✅ PERBAIKAN: Non-aktifkan validation sementara
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = true; // ✅ INI YANG PENTING!

    public function getTotalPoin($nis)
    {
        try {
            $result = $this->where('nis', $nis)->selectSum('poin')->get();
            if ($result) {
                $row = $result->getRow();
                return $row->poin ?? 0;
            }
            return 0;
        } catch (\Exception $e) {
            log_message('error', 'Error in getTotalPoin: ' . $e->getMessage());
            return 0;
        }
    }

    public function getPelanggaranById($id)
    {
        return $this->where('id', $id)->first();
    }

    public function updatePelanggaran($id, $data)
    {
        return $this->update($id, $data);
    }
    
    // ✅ METHOD BARU: Insert tanpa validation
    public function insertData($data)
    {
        return $this->db->table($this->table)->insert($data);
    }
}