<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SantriModel;
use App\Models\PoinBulananModel;
use App\Models\PoinSemesteranModel;
use App\Models\PelanggaranModel;
use App\Models\SuratSPModel;

class Umum extends BaseController
{
    public function index()
    {
        // Load models
        $santriModel = new SantriModel();
        $poinBulananModel = new PoinBulananModel();
        $poinSemesteranModel = new PoinSemesteranModel();
        $pelanggaranModel = new PelanggaranModel();
        $suratSPModel = new SuratSPModel();

        // Tahun ajaran default
        $tahun_ajaran = $this->request->getGet('tahun_ajaran') ?? '2025/2026';

        // Ambil semua data santri
        $santri = $santriModel->findAll();

        // Hitung stats untuk navbar
        $totalSantri = count($santri);
        
        // HITUNG TOTAL PELANGGARAN & POIN DARI DATABASE
        $totalPelanggaranGanjil = $pelanggaranModel->where(['semester' => 'ganjil', 'tahun_ajaran' => $tahun_ajaran])->countAllResults();
        $totalPelanggaranGenap = $pelanggaranModel->where(['semester' => 'genap', 'tahun_ajaran' => $tahun_ajaran])->countAllResults();
        
        $totalPoinGanjil = 0;
        $totalPoinGenap = 0;
        $sp1CountGanjil = 0;
        $sp2CountGanjil = 0;
        $sp3CountGanjil = 0;
        $sp1CountGenap = 0;
        $sp2CountGenap = 0;
        $sp3CountGenap = 0;

        // Data untuk cards
        $pelanggaranData = [];
        $poinGanjil = [];
        $poinGenap = [];
        $poinBulanan = [];

        // Bulan definitions
        $bulanGanjil = ['Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $bulanGenap = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni'];

        foreach ($santri as $santri_item) {
            $nis = $santri_item['nis'];
            
            // Ambil poin semesteran
            $poinGanjilData = $poinSemesteranModel->where([
                'nis' => $nis,
                'semester' => 'ganjil',
                'tahun_ajaran' => $tahun_ajaran
            ])->first();
            
            $poinGenapData = $poinSemesteranModel->where([
                'nis' => $nis,
                'semester' => 'genap',
                'tahun_ajaran' => $tahun_ajaran
            ])->first();
            
            $poinGanjil[$nis] = $poinGanjilData['total_poin'] ?? 0;
            $poinGenap[$nis] = $poinGenapData['total_poin'] ?? 0;
            
            $totalPoinGanjil += $poinGanjil[$nis];
            $totalPoinGenap += $poinGenap[$nis];

            // Ambil data pelanggaran untuk modal detail
            $pelanggaranData[$nis] = $pelanggaranModel->where('nis', $nis)->findAll();

            // AMBIL POIN BULANAN
            $poinBulanan[$nis] = [
                'ganjil' => [],
                'genap' => []
            ];

            // Isi data bulanan untuk semester ganjil
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
                $poinBulanan[$nis]['ganjil'][$bulan] = $poin && $poin->poin ? (int)$poin->poin : 0;
            }

            // Isi data bulanan untuk semester genap
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
                $poinBulanan[$nis]['genap'][$bulan] = $poin && $poin->poin ? (int)$poin->poin : 0;
            }

            // Hitung SP counts berdasarkan poin
            if ($poinGanjil[$nis] >= 300) $sp3CountGanjil++;
            elseif ($poinGanjil[$nis] >= 200) $sp2CountGanjil++;
            elseif ($poinGanjil[$nis] >= 100) $sp1CountGanjil++;
            
            if ($poinGenap[$nis] >= 300) $sp3CountGenap++;
            elseif ($poinGenap[$nis] >= 200) $sp2CountGenap++;
            elseif ($poinGenap[$nis] >= 100) $sp1CountGenap++;
        }

        $data = [
            'title' => 'E-Mahkamah - Monitoring Pelanggaran Santri',
            'santri' => $santri,
            'pelanggaranData' => $pelanggaranData,
            'poinGanjil' => $poinGanjil,
            'poinGenap' => $poinGenap,
            'poinBulanan' => $poinBulanan,
            'totalSantri' => $totalSantri,
            'totalPoinGanjil' => $totalPoinGanjil,
            'totalPoinGenap' => $totalPoinGenap,
            'totalPelanggaranGanjil' => $totalPelanggaranGanjil,
            'totalPelanggaranGenap' => $totalPelanggaranGenap,
            'sp1CountGanjil' => $sp1CountGanjil,
            'sp2CountGanjil' => $sp2CountGanjil,
            'sp3CountGanjil' => $sp3CountGanjil,
            'sp1CountGenap' => $sp1CountGenap,
            'sp2CountGenap' => $sp2CountGenap,
            'sp3CountGenap' => $sp3CountGenap,
            'tahun_ajaran' => $tahun_ajaran,
            'bulanGanjil' => $bulanGanjil,
            'bulanGenap' => $bulanGenap
        ];

        return view('user_view', $data);
    }

    /**
     * API untuk ambil detail pelanggaran santri dengan verifikasi
     */
    public function getDetailPelanggaran($nis)
    {
        try {
            // Verifikasi bahwa NIS valid
            $santriModel = new SantriModel();
            $santri = $santriModel->where('nis', $nis)->first();
            
            if (!$santri) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Data santri tidak ditemukan'
                ])->setStatusCode(404);
            }
            
            $pelanggaranModel = new PelanggaranModel();
            $pelanggaran = $pelanggaranModel->where('nis', $nis)->orderBy('tanggal', 'DESC')->findAll();
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $pelanggaran,
                'santri' => [
                    'nama' => $santri['nama_santri'],
                    'nis' => $santri['nis']
                ]
            ])->setStatusCode(200);
            
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan server: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }
}