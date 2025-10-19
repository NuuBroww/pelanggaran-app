<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SantriModel;
use App\Models\SuratSPModel;

class SPController extends BaseController
{
    public function input($nis)
    {
        $santriModel = new SantriModel();
        $santri = $santriModel->find($nis);
        
        if (!$santri) {
            return redirect()->to('/rekapan')->with('error', 'Santri tidak ditemukan');
        }
        
        $data = [
            'title' => 'Input Surat Peringatan',
            'santri' => $santri,
            'jenis_sp' => 'SP1'
        ];
        
        return view('input_sp', $data);
    }

    public function simpan()
{
    $spModel = new \App\Models\SuratSPModel();
    
    $jenisSP = $this->request->getPost('jenis_sp');
    $namaSantri = $this->request->getPost('nama_santri');
    $isiTeguran = $this->request->getPost('isi_teguran');
    $contentInput = $this->request->getPost('content_pembuka');
    
    // Tentukan default content_pembuka berdasarkan jenis SP
    if ($jenisSP === 'SP1') {
        $defaultContent = 'Berdasarkan catatan segenap dewan asatidz bahwa Ananda ' . $namaSantri . 
            ' memiliki poin pelanggaran melebihi 100 serta pertimbangan sikap dan perilaku.';
    } elseif ($jenisSP === 'SP2') {
        $defaultContent = 'Berdasarkan catatan segenap dewan asatidz bahwa Ananda ' . $namaSantri . 
            ' memiliki poin pelanggaran melebihi 200 serta pertimbangan sikap dan perilaku.';
    } else { // SP3
        $defaultContent = 'Berdasarkan hasil evaluasi disiplin dan perilaku, Ananda ' . $namaSantri . 
            ' telah menunjukkan pelanggaran berat sesuai ketentuan pondok.';
    }

    // Logika baru:
    // - Jika content_pembuka kosong → isi sama dengan isi_teguran
    // - Jika ada input → pakai input user
    // - Kalau null → fallback ke defaultContent
    $contentPembuka = !empty(trim($contentInput)) 
        ? $contentInput 
        : (!empty(trim($isiTeguran)) ? $isiTeguran : $defaultContent);

    $data = [
        'nis' => $this->request->getPost('nis'),
        'nama_santri' => $namaSantri,
        'jenis_sp' => $jenisSP,
        'semester' => $this->request->getPost('semester'),
        'tahun_ajaran' => $this->request->getPost('tahun_ajaran'),
        'nomor_registrasi' => $this->request->getPost('nomor_registrasi'),
        'tanggal_surat' => $this->request->getPost('tanggal_surat'),
        'content_pembuka' => $contentPembuka,
        'isi_teguran' => $isiTeguran,
        'tanda_tangan' => base_url('assets/img/barcode.gif')
    ];
    
    if ($spModel->insert($data)) {
        $lastId = $spModel->getInsertID();
        $sp = $spModel->find($lastId);
        
        $viewName = 'surat_' . strtolower($jenisSP);
        return view($viewName, ['sp' => $sp]);
    } else {
        return redirect()->back()->with('error', 'Gagal menyimpan Surat Peringatan');
    }
}

    public function update()
{
    $spModel = new SuratSPModel();
    
    $id = $this->request->getPost('id');
    $data = [
        'nama_santri' => $this->request->getPost('nama_santri'),
        'tanggal_surat' => $this->request->getPost('tanggal_surat'),
        'content_pembuka' => $this->request->getPost('content_pembuka'), // PASTIKAN ADA INI
        'isi_teguran' => $this->request->getPost('isi_teguran')
    ];
        
        if ($spModel->update($id, $data)) {
            // Ambil data terbaru
            $sp = $spModel->find($id);
            
            // Tentukan view berdasarkan jenis SP
            $jenisSP = $sp['jenis_sp'] ?? 'SP1';
            $viewName = 'surat_' . strtolower($jenisSP);
            
            $viewData = [
                'sp' => $sp,
                'success' => 'Surat berhasil diupdate'
            ];
            
            return view($viewName, $viewData);
        } else {
            return redirect()->back()->with('error', 'Gagal mengupdate surat');
        }
    }

    // Method untuk direct SP
    public function sp1($nis) { return $this->showSPForm($nis, 'SP1'); }
    public function sp2($nis) { return $this->showSPForm($nis, 'SP2'); }
    public function sp3($nis) { return $this->showSPForm($nis, 'SP3'); }

    private function showSPForm($nis, $jenisSP)
    {
        $santriModel = new SantriModel();
        $santri = $santriModel->find($nis);

        if (!$santri) {
            return redirect()->to('/rekapan')->with('error', 'Santri tidak ditemukan');
        }

        $data = [
            'title' => 'Input ' . $jenisSP,
            'santri' => $santri,
            'jenis_sp' => $jenisSP
        ];

        return view('input_sp', $data);
    }
}