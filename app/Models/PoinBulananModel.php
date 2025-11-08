<?php namespace App\Models;

use CodeIgniter\Model;

class PoinBulananModel extends Model
{
    protected $table = 'poin_bulanan';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'nis',
        'poin',
        'keterangan',
        'kategori',
        'detail_pelanggaran',
        'bulan',
        'tanggal'
    ];

    protected $useTimestamps = false;

    public function getTotalPoin($nis)
    {
        return $this->where('nis', $nis)->selectSum('poin')->get()->getRow()->poin ?? 0;
    }

    public function getPoinByBulan($nis, $bulan)
{
    return $this->db->table('poin_bulanan')
        ->select('id, nis, poin, keterangan, bulan, detail_pelanggaran, kategori')
        ->where('nis', $nis)
        ->where('bulan', $bulan)
        ->get()
        ->getRowArray();
}
public function afterInsert($data)
    {
        // Update poin_semesteran
        $poinSemesteranModel = new \App\Models\PoinSemesteranModel();
        $poinSemesteranModel->updateSemesteran();
        
        return true;
    }
    
    /**
     * METHOD BARU: Setelah delete, update poin_semesteran dan SP
     */
    public function afterDelete($data)
    {
        // Update poin_semesteran
        $poinSemesteranModel = new \App\Models\PoinSemesteranModel();
        $poinSemesteranModel->updateSemesteran();
        
        return true;
    }

    public function getPelanggaranById($id)
    {
        return $this->where('id', $id)->first();
    }

    /**
     * Update data pelanggaran
     */
    public function updatePelanggaran($id, $data)
    {
        return $this->update($id, $data);
    }
}
    

