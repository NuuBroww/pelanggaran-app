<?php

namespace App\Models;

use CodeIgniter\Model;

class SantriModel extends Model
{
     protected $table = 'santri';
    protected $primaryKey = 'nis';
    protected $allowedFields = ['nis', 'nama_santri', 'tempat_lahir', 'tanggal_lahir', 'foto'];
    protected $useAutoIncrement = false;
    protected $returnType = 'array';
    
    // Jika nis adalah string, tambahkan ini
    protected $keyType = 'string';

    // Ambil santri berdasarkan NIS
    public function getByNis($nis)
    {
        return $this->where('nis', $nis)->first();
    }

    // Update total poin pelanggaran santri
    public function updatePoin($nis, $totalPoin, $stampTanggal = true)
    {
        $updateData = ['poin_pelanggaran' => $totalPoin];
        if ($stampTanggal) {
            $updateData['tanggal'] = date('Y-m-d H:i:s');
        }
        return $this->update($nis, $updateData);
    }

    // Di SantriModel, tambahkan method ini:
public function updateTotalPoin($nis, $totalPoin)
{
    return $this->where('nis', $nis)->set(['total_poin' => $totalPoin])->update();
}
}
