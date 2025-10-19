<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AdminModel;
use App\Models\AdminLogModel;

class Admin extends BaseController
{
    // METHOD UNTUK GET /login DAN / (sesuai routes)
    public function index()
    {
        // Jika sudah login, redirect ke dashboard
        if (session()->get('logged_in')) {
            return redirect()->to('/dashboard');
        }
        
        return view('admin_login');
    }
    
    // METHOD UNTUK POST /login (sesuai routes)
    public function login()
    {
        $session = session();
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        
        $adminModel = new AdminModel();
        
        // Cari admin berdasarkan username - sesuaikan dengan kolom yang ada
        $admin = $adminModel->where('username', $username)->first();
        
        if (!$admin) {
            $session->setFlashdata('msg', '❌ Username tidak ditemukan.');
            return redirect()->to('/login');
        }
        
        // VERIFIKASI PASSWORD DENGAN DUA CARA:
        // 1. Cek jika password sudah di-hash
        $passwordOk = password_verify($password, $admin['password']);
        
        // 2. Jika tidak berhasil, cek password plain text
        if (!$passwordOk) {
            $passwordOk = ($password === $admin['password']);
            
            // Jika password plain text benar, hash passwordnya untuk keamanan
            if ($passwordOk) {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $adminModel->update($admin['admin_id'], ['password' => $hashedPassword]);
            }
        }
        
        if (!$passwordOk) {
            $session->setFlashdata('msg', '❌ Password salah.');
            return redirect()->to('/login');
        }

        // Set session data - TAMBAH id_role
        $sessionData = [
            'admin_id' => $admin['admin_id'], 
            'username' => $admin['username'],
            'nama' => $admin['nama'] ?? $admin['username'],
            'role' => $admin['role'] ?? 'admin',
            'id_role' => $admin['id_role'] ?? 1,
            'logged_in' => true
        ];
        
        session()->set($sessionData);
        
        // Log aktivitas login
        $logModel = new AdminLogModel();
        $logModel->logActivity($sessionData['admin_id'], 'login', 'User login berhasil');
        
        return redirect()->to('/dashboard');
    }
    
    public function logout()
    {
        // Log aktivitas logout
        if (session()->get('logged_in')) {
            $logModel = new AdminLogModel();
            $logModel->logActivity(session()->get('admin_id'), 'logout', 'User logout');
        }
        
        session()->destroy();
        return redirect()->to('/login');
    }
    
    public function update_settings()
    {
        // Method untuk update settings
        $session = session();
        $newUsername = $this->request->getPost('username');
        $newPassword = $this->request->getPost('password');
        
        $adminModel = new AdminModel();
        $adminId = session()->get('admin_id');
        
        $data = [];
        if (!empty($newUsername)) {
            $data['username'] = $newUsername;
        }
        if (!empty($newPassword)) {
            $data['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
        }
        
        if (!empty($data)) {
            $adminModel->update($adminId, $data);
            
            // Log aktivitas update settings
            $logModel = new AdminLogModel();
            $logModel->logActivity($adminId, 'update', 'Update pengaturan sistem');
            
            $session->setFlashdata('msg', '✅ Pengaturan berhasil diupdate.');
        }
        
        return redirect()->to('/dashboard');
    }
}