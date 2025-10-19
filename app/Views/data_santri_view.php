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
    /* Copy semua CSS dari dashboard */
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
        padding:8px 12px; 
        border-radius:6px; 
        cursor:pointer; 
        font-size:0.85rem; 
        display:inline-flex; 
        align-items:center; 
        gap:5px; 
        text-decoration: none;
        transition: all 0.2s; 
        margin: 2px;
    }
    .btn:hover { background-color:#2980b9; }
    .btn-danger { background-color:#e74c3c; }
    .btn-danger:hover { background-color:#c0392b; }
    .btn-success { background-color:#27ae60; }
    .btn-success:hover { background-color:#219a52; }
    .btn-warning { background-color:#f39c12; }
    .btn-warning:hover { background-color:#e67e22; }
    
    main { padding:20px; }
    .table-wrapper { overflow-x:auto; border-radius:10px; margin-top:20px; }
    table { width:100%; border-collapse:collapse; background:#fff; box-shadow:0 2px 6px rgba(0,0,0,0.1); }
    body.dark-mode table { background:#2a2a3d; color:#ddd; }
    th, td { padding:12px 15px; text-align:left; border-bottom:1px solid #e0e0e0; }
    body.dark-mode th, body.dark-mode td { border-bottom:1px solid #444; }
    th { background:#f7f7f7; font-weight:600; }
    body.dark-mode th { background:#3a3a4f; }
    tr:hover { background:#f1f9ff; }
    body.dark-mode tr:hover { background:#44445a; }
    
    /* Modal styles */
    .modal {
        display: flex;
        justify-content: center;
        align-items: center;
        position: fixed;
        top:0; left:0;
        width:100%; height:100%;
        background: rgba(0,0,0,0.5);
        z-index:1000;
        opacity:0;
        pointer-events: none;
        transition: opacity 0.35s ease;
    }
    .modal.active { opacity:1; pointer-events:auto; }
    .modal-content {
        background:#fff;
        padding:25px;
        border-radius:15px;
        width:95%; max-width:450px;
        box-shadow:0 20px 60px rgba(0,0,0,0.25);
    }
    body.dark-mode .modal-content { background:#2a2a3d; }
    
    .form-group {
        margin-bottom: 15px;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: 600;
    }
    
    .form-group input, .form-group select {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 14px;
        box-sizing: border-box;
    }
    
    body.dark-mode .form-group input, 
    body.dark-mode .form-group select {
        background: #3a3a4f;
        color: #ddd;
        border: 1px solid #555;
    }
    
    .modal-buttons {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 20px;
    }
    
    @media (max-width: 768px) {
        .table-wrapper table, .table-wrapper thead, .table-wrapper tbody, 
        .table-wrapper th, .table-wrapper td, .table-wrapper tr { display: block; }
        .table-wrapper thead { display: none; }
        .table-wrapper tr { margin-bottom:15px; padding:10px; border-radius:8px; background:#7f8c8d; color:#fff; }
        body.dark-mode .table-wrapper tr { background:#2a2a3d; color:#ddd; }
        .table-wrapper td { padding:8px 12px; text-align:left; position:relative; }
        .table-wrapper td::before { 
            content: attr(data-label); 
            font-weight:bold; 
            display:block; 
            margin-bottom:5px; 
            font-size: 0.9rem;
        }
        
        .btn {
            padding: 6px 10px;
            font-size: 0.8rem;
        }
    }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header>
        <h1>📋 Data Santri</h1>
        <div class="top-actions">
            <a href="<?= base_url('/dashboard') ?>" class="btn">
                <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
            </a>
            <button class="btn btn-success" id="btnAddSantri">
                <i class="fas fa-plus"></i> Tambah Santri
            </button>
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

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>NIS</th>
                        <th>Nama Santri</th>
                        <th>Tanggal Lahir</th>
                        <th>Tempat Lahir</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($santri)): ?>
                        <?php foreach($santri as $s): ?>
                        <tr>
                            <td data-label="NIS"><?= esc($s['nis']) ?></td>
                            <td data-label="Nama"><?= esc($s['nama_santri']) ?></td>
                            <td data-label="Tanggal Lahir">
                                <?= $s['tanggal_lahir'] ? date('d-m-Y', strtotime($s['tanggal_lahir'])) : '-' ?>
                            </td>
                            <td data-label="Tempat Lahir">
                                <?= esc($s['tempat_lahir'] ?? '-') ?>
                            </td>
                            <td data-label="Aksi">
                                <div style="display: flex; gap: 5px; flex-wrap: wrap;">
                                    <a href="<?= base_url('/santri/edit/' . $s['nis']) ?>" class="btn btn-warning">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <button class="btn btn-danger" onclick="deleteSantri('<?= $s['nis'] ?>', '<?= esc($s['nama_santri']) ?>')">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align:center; padding:20px; color:#777;">
                                <i class="fas fa-users" style="font-size: 2rem; margin-bottom: 10px; display: block; opacity: 0.5;"></i>
                                Belum ada data santri.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>

    <!-- Modal Tambah Santri -->
    <div id="addSantriModal" class="modal">
        <div class="modal-content">
            <h2 style="margin-bottom: 20px; text-align: center;">
                <i class="fas fa-user-plus"></i> Tambah Santri Baru
            </h2>
            <form action="<?= base_url('/santri/add') ?>" method="post">
                <div class="form-group">
                    <label for="nis">NIS:</label>
                    <input type="text" id="nis" name="nis" required placeholder="Masukkan NIS">
                </div>
                
                <div class="form-group">
                    <label for="nama_santri">Nama Santri:</label>
                    <input type="text" id="nama_santri" name="nama_santri" required placeholder="Masukkan nama santri">
                </div>
                
                <div class="form-group">
                    <label for="tempat_lahir">Kota Lahir:</label>
                    <input type="text" id="tempat_lahir" name="tempat_lahir" placeholder="Masukkan kota lahir">
                </div>
                
                <div class="form-group">
                    <label for="tanggal_lahir">Tanggal Lahir:</label>
                    <input type="date" id="tanggal_lahir" name="tanggal_lahir">
                </div>
                
                <div class="modal-buttons">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                    <button type="button" class="btn btn-danger" id="closeAddModal">
                        <i class="fas fa-times"></i> Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
    // Modal functionality
    const btnAddSantri = document.getElementById('btnAddSantri');
    const addSantriModal = document.getElementById('addSantriModal');
    const closeAddModal = document.getElementById('closeAddModal');

    btnAddSantri.addEventListener('click', () => {
        addSantriModal.classList.add('active');
    });

    closeAddModal.addEventListener('click', () => {
        addSantriModal.classList.remove('active');
    });

    function deleteSantri(nis, nama) {
        if(confirm(`Apakah Anda yakin ingin menghapus santri "${nama}" dengan NIS ${nis}?`)) {
            window.location.href = '<?= base_url('/santri/delete') ?>/' + nis;
        }
    }

    // Close modal when clicking outside
    window.addEventListener('click', (e) => {
        if (e.target === addSantriModal) {
            addSantriModal.classList.remove('active');
        }
    });

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