<?php
date_default_timezone_set('Asia/Jakarta');
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    <style>
    body { font-family: "Segoe UI", sans-serif; margin:0; background-color:#f4f5f7; color:#333; transition: background-color 0.3s, color 0.3s; }
    body.dark-mode { background-color:#1e1e2e; color:#ddd; }
    
    header { 
        background-color:#fff; 
        padding:15px 20px; 
        display:flex; 
        flex-wrap:wrap; 
        justify-content:space-between; 
        align-items:center; 
        box-shadow:0 2px 8px rgba(0,0,0,0.1); 
        transition: background-color 0.3s; 
    }
    body.dark-mode header { 
        background-color:#2a2a3d; 
        box-shadow:0 2px 8px rgba(0,0,0,0.5); 
    }
    
    .btn { 
        background-color:#3498db; 
        color:#fff; 
        border:none; 
        padding:10px 15px; 
        border-radius:6px; 
        cursor:pointer; 
        font-size:0.9rem; 
        display:inline-flex; 
        align-items:center; 
        gap:5px; 
        text-decoration: none;
        transition: all 0.2s; 
    }
    .btn:hover { background-color:#2980b9; }
    .btn-success { background-color:#27ae60; }
    .btn-success:hover { background-color:#219a52; }
    .btn-warning { background-color:#f39c12; }
    .btn-warning:hover { background-color:#e67e22; }
    
    main { 
        padding:20px; 
        max-width: 600px; 
        margin: 0 auto; 
    }
    
    .form-container {
        background: #fff;
        padding: 25px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    body.dark-mode .form-container {
        background: #2a2a3d;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #333;
    }
    
    body.dark-mode .form-group label {
        color: #ddd;
    }
    
    .form-group input {
        width: 100%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 14px;
        box-sizing: border-box;
        transition: border-color 0.3s;
    }
    
    .form-group input:focus {
        outline: none;
        border-color: #3498db;
        box-shadow: 0 0 5px rgba(52, 152, 219, 0.3);
    }
    
    body.dark-mode .form-group input {
        background: #3a3a4f;
        color: #ddd;
        border: 1px solid #555;
    }
    
    .form-actions {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
        margin-top: 25px;
    }
    
    .nis-display {
        background: #f8f9fa;
        padding: 12px;
        border-radius: 5px;
        border: 1px solid #e9ecef;
        font-weight: 600;
        color: #495057;
    }
    
    body.dark-mode .nis-display {
        background: #3a3a4f;
        border: 1px solid #555;
        color: #ddd;
    }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header>
        <h1>✏️ Edit Data Santri</h1>
        <div class="top-actions">
            <a href="<?= base_url('/data_santri') ?>" class="btn">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </header>

    <main>
        <?php if (session()->getFlashdata('success')): ?>
            <div style="background:#d4edda; color:#155724; padding:12px; border-radius:5px; margin-bottom:15px; border:1px solid #c3e6cb;">
                <i class="fas fa-check-circle"></i> <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div style="background:#f8d7da; color:#721c24; padding:12px; border-radius:5px; margin-bottom:15px; border:1px solid #f5c6cb;">
                <i class="fas fa-exclamation-circle"></i> <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <div class="form-container">
            <form action="<?= base_url('/santri/update/' . $santri['nis']) ?>" method="post">
                <div class="form-group">
                    <label>NIS:</label>
                    <div class="nis-display">
                        <?= esc($santri['nis']) ?>
                    </div>
                    <small style="color: #666;">NIS tidak dapat diubah</small>
                </div>
                
                <div class="form-group">
                    <label for="nama_santri">Nama Santri:</label>
                    <input type="text" id="nama_santri" name="nama_santri" required 
                           value="<?= esc($santri['nama_santri']) ?>" 
                           placeholder="Masukkan nama santri">
                </div>
                
                <div class="form-group">
                    <label for="tempat_lahir">Kota Lahir:</label>
                    <input type="text" id="tempat_lahir" name="tempat_lahir" 
                           value="<?= esc($santri['tempat_lahir'] ?? '') ?>" 
                           placeholder="Masukkan kota lahir">
                </div>
                
                <div class="form-group">
                    <label for="tanggal_lahir">Tanggal Lahir:</label>
                    <input type="date" id="tanggal_lahir" name="tanggal_lahir" 
                           value="<?= $santri['tanggal_lahir'] ? date('Y-m-d', strtotime($santri['tanggal_lahir'])) : '' ?>">
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Update Data
                    </button>
                    <a href="<?= base_url('/data_santri') ?>" class="btn btn-warning">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </main>

    <script>
    // Theme toggle functionality
    document.addEventListener('DOMContentLoaded', () => {
        const savedTheme = localStorage.getItem('themeMode') || 'light';
        if (savedTheme === 'dark') {
            document.body.classList.add('dark-mode');
        }
    });
    </script>
</body>
</html>