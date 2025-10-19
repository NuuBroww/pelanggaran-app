<?php

namespace App\Models;

use CodeIgniter\Model;

class PelanggaranModel extends Model
{
    protected $table = 'poin_bulanan'; // Tabel yang menyimpan data pelanggaran
    protected $primaryKey = 'id';      // Primary key tabel poin_bulanan

    protected $allowedFields = [
        'nis',
        'bulan',
        'poin',
        'keterangan',
        'tanggal'
    ];

    // Aktifkan auto timestamp kalau tabel kamu punya kolom created_at & updated_at
    protected $useTimestamps = false;

    /**
     * Ambil semua santri beserta total poin pelanggarannya.
     */
    public function getSantriWithTotalPoin()
{
    return $this->db->table('santri s')
        ->select("
            s.nis, 
            s.nama_santri, 
            MAX(p.tanggal) AS tanggal, 
            COALESCE(SUM(p.poin), 0) AS total_poin,
            SUM(CASE WHEN p.bulan = 'Juli' THEN p.poin ELSE 0 END) AS Juli,
            SUM(CASE WHEN p.bulan = 'Agustus' THEN p.poin ELSE 0 END) AS Agustus,
            SUM(CASE WHEN p.bulan = 'September' THEN p.poin ELSE 0 END) AS September,
            SUM(CASE WHEN p.bulan = 'Oktober' THEN p.poin ELSE 0 END) AS Oktober,
            SUM(CASE WHEN p.bulan = 'November' THEN p.poin ELSE 0 END) AS November,
            SUM(CASE WHEN p.bulan = 'Desember' THEN p.poin ELSE 0 END) AS Desember
        ")
        ->join('poin_bulanan p', 'p.nis = s.nis', 'left')
        ->groupBy('s.nis, s.nama_santri')
        ->orderBy('s.nama_santri', 'ASC')
        ->get()
        ->getResultArray();
}


    /**
     * Ambil semua pelanggaran berdasarkan NIS dan bulan.
     */
    public function getPoinByBulan($nis, $bulan)
    {
        return $this->where(['nis' => $nis, 'bulan' => $bulan])->first();
    }

    /**
     * Update data pelanggaran santri berdasarkan NIS dan bulan.
     */
    public function updatePoinByBulan($nis, $bulan, $data)
    {
        return $this->where(['nis' => $nis, 'bulan' => $bulan])->set($data)->update();
    }

    /**
     * Hitung total poin seluruh bulan untuk satu santri.
     */
    public function getTotalPoin($nis)
    {
        return $this->selectSum('poin')->where('nis', $nis)->get()->getRowArray()['poin'] ?? 0;
    }
}
