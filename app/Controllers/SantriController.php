<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SantriModel;

class SantriController extends BaseController
{
    public function index()
    {
        $session = session();
        if (!$session->get('logged_in')) {
            return redirect()->to('/login');
        }

        // Load model
        $santriModel = new SantriModel();
        
        // Ambil data santri
        $santri = $santriModel->findAll();

        $data = [
            'title' => 'Data Santri',
            'santri' => $santri,
            'nama' => $session->get('nama')
        ];

        return view('data_santri_view', $data);
    }

    public function add()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        return view('tambah_santri_view', [
            'nama' => session()->get('nama')
        ]);
    }

    public function save()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $santriModel = new SantriModel();
        
        $data = [
            'nis' => $this->request->getPost('nis'),
            'nama_santri' => $this->request->getPost('nama_santri'),
            'tempat_lahir' => $this->request->getPost('tempat_lahir'),
            'tanggal_lahir' => $this->request->getPost('tanggal_lahir')
        ];

        $santriModel->insert($data);
        
        return redirect()->to('/data_santri')->with('success', 'Santri berhasil ditambahkan!');
    }

    public function edit($nis)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $santriModel = new SantriModel();
        $santri = $santriModel->where('nis', $nis)->first();

        if (!$santri) {
            return redirect()->to('/data_santri')->with('error', 'Santri tidak ditemukan!');
        }

        return view('edit_santri_view', [
            'title' => 'Edit Santri',
            'santri' => $santri,
            'nama' => session()->get('nama')
        ]);
    }

    public function update($nis)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $santriModel = new SantriModel();
        
        $data = [
            'nama_santri' => $this->request->getPost('nama_santri'),
            'tempat_lahir' => $this->request->getPost('tempat_lahir'),
            'tanggal_lahir' => $this->request->getPost('tanggal_lahir')
        ];

        $santriModel->where('nis', $nis)->set($data)->update();
        
        return redirect()->to('/data_santri')->with('success', 'Data santri berhasil diupdate!');
    }

    public function delete($nis)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $santriModel = new SantriModel();
        $santri = $santriModel->where('nis', $nis)->first();

        if ($santri) {
            $santriModel->where('nis', $nis)->delete();
            return redirect()->to('/data_santri')->with('success', 'Santri berhasil dihapus!');
        }

        return redirect()->to('/data_santri')->with('error', 'Santri tidak ditemukan!');
    }
    // Di SantriController atau DashboardController
public function upload_foto()
{
    $session = session();
    if (!$session->get('logged_in') || $session->get('id_role') != 2) {
        return $this->response->setJSON(['error' => 'Akses ditolak']);
    }

    $santriModel = new SantriModel();
    $nis = $this->request->getPost('nis');
    $file = $this->request->getFile('foto');

    // Debug informasi
    log_message('debug', '🔄 Upload foto dimulai - NIS: ' . $nis);
    log_message('debug', '📁 File info: ' . ($file ? $file->getName() : 'No file'));

    if (!$nis) {
        log_message('error', '❌ NIS tidak valid');
        return $this->response->setJSON(['error' => 'NIS tidak valid']);
    }

    // Cek apakah santri ada
    $santri = $santriModel->where('nis', $nis)->first();
    if (!$santri) {
        log_message('error', '❌ Santri tidak ditemukan - NIS: ' . $nis);
        return $this->response->setJSON(['error' => 'Santri dengan NIS ' . $nis . ' tidak ditemukan']);
    }

    log_message('debug', '✅ Santri ditemukan: ' . $santri['nama_santri']);

    if (!$file || !$file->isValid()) {
        $error = $file ? $file->getErrorString() : 'No file uploaded';
        log_message('error', '❌ File tidak valid: ' . $error);
        return $this->response->setJSON(['error' => 'File tidak valid: ' . $error]);
    }

    // Validasi file
    $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
    if (!in_array($file->getMimeType(), $allowedTypes)) {
        return $this->response->setJSON(['error' => 'Hanya file JPG, JPEG, PNG yang diizinkan']);
    }

    if ($file->getSize() > 2097152) {
        return $this->response->setJSON(['error' => 'File terlalu besar. Maksimal 2MB']);
    }

    try {
        // Pastikan folder upload ada
        $uploadPath = ROOTPATH . 'public/uploads/foto_santri/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
            log_message('debug', '📁 Folder created: ' . $uploadPath);
        }

        // Generate nama file
        $newName = $nis . '_' . time() . '.' . $file->getExtension();
        $fullPath = $uploadPath . $newName;

        log_message('debug', '📝 New filename: ' . $newName);
        log_message('debug', '📁 Full path: ' . $fullPath);

        // Pindahkan file
        if ($file->move($uploadPath, $newName)) {
            log_message('debug', '✅ File berhasil dipindahkan');
            
            // Hapus foto lama jika ada
            if (!empty($santri['foto']) && file_exists($uploadPath . $santri['foto'])) {
                unlink($uploadPath . $santri['foto']);
                log_message('debug', '🗑️ Foto lama dihapus: ' . $santri['foto']);
            }

            // UPDATE menggunakan where() yang eksplisit
            $updateData = ['foto' => $newName];
            
            log_message('debug', '🔄 Update data - NIS: ' . $nis . ', Data: ' . json_encode($updateData));
            
            $result = $santriModel->where('nis', $nis)->set($updateData)->update();
            
            log_message('debug', '📊 Update result: ' . ($result ? 'SUCCESS' : 'FAILED'));
            
            if ($result) {
                // Verifikasi update
                $updatedSantri = $santriModel->where('nis', $nis)->first();
                log_message('debug', '✅ Foto setelah update: ' . $updatedSantri['foto']);
                
                return $this->response->setJSON([
                    'success' => true, 
                    'message' => 'Foto berhasil diupload dan disimpan',
                    'filename' => $newName
                ]);
            } else {
                $error = $santriModel->errors();
                log_message('error', '❌ Gagal update database: ' . json_encode($error));
                return $this->response->setJSON(['error' => 'Gagal menyimpan ke database: ' . json_encode($error)]);
            }
        } else {
            log_message('error', '❌ Gagal memindahkan file');
            return $this->response->setJSON(['error' => 'Gagal memindahkan file']);
        }
        
    } catch (\Exception $e) {
        log_message('error', '❌ Upload foto exception: ' . $e->getMessage());
        log_message('error', '❌ Stack trace: ' . $e->getTraceAsString());
        return $this->response->setJSON(['error' => 'Terjadi kesalahan sistem: ' . $e->getMessage()]);
    }
}
// Method untuk menampilkan foto dari database
public function get_foto($nis)
{
    $santriModel = new SantriModel();
    $santri = $santriModel->where('nis', $nis)->first();
    
    if ($santri && !empty($santri['foto'])) {
        // Tentukan content type berdasarkan data
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->buffer($santri['foto']);
        
        $this->response->setContentType($mimeType);
        return $this->response->setBody($santri['foto']);
    } else {
        // Return default avatar
        $defaultAvatar = file_get_contents(ROOTPATH . 'public/assets/img/default-avatar.png');
        $this->response->setContentType('image/png');
        return $this->response->setBody($defaultAvatar);
    }
}
}