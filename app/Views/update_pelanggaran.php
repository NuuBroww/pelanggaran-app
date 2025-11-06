<?php
date_default_timezone_set('Asia/Jakarta');
?>
<?php
// Check jika role 3 mencoba akses
if ($id_role == 3) {
    echo '<div style="background: #f8d7da; color: #721c24; padding: 20px; border-radius: 5px; text-align: center; margin: 20px;">
            <h3><i class="fas fa-ban"></i> Akses Ditolak</h3>
            <p>Yayasan tidak dapat mengubah data pelanggaran. Silakan hubungi Ustadz Spesial untuk perubahan data.</p>
            <a href="' . base_url('/dashboard') . '" class="btn btn-back">Kembali ke Dashboard</a>
          </div>';
    return;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Update Pelanggaran - <?= esc($bulan ?? '') ?> (NIS: <?= esc($nis ?? '') ?>)</title>
<!-- Bootstrap CSS untuk modal -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
/* ===== Reset & Font ===== */
:root {
    --primary: #3498db;
    --danger: #e74c3c;
    --success: #27ae60;
    --warning: #f39c12;
    --dark-bg: #1e1e2e;
    --dark-card: #2a2a3d;
    --dark-border: #444;
    --light-bg: #f4f5f7;
    --light-card: #ffffff;
    --text-light: #333;
    --text-dark: #ddd;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body { 
    font-family: "Segoe UI", -apple-system, BlinkMacSystemFont, sans-serif; 
    background-color: var(--light-bg); 
    color: var(--text-light);
    line-height: 1.6;
    transition: all 0.3s ease;
}

body.dark-mode { 
    background-color: var(--dark-bg); 
    color: var(--text-dark); 
}

/* ===== HEADER MOBILE FRIENDLY ===== */
.header {
    background: var(--light-card);
    padding: 15px 20px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.1);
    position: sticky;
    top: 0;
    z-index: 100;
    transition: all 0.3s ease;
}

body.dark-mode .header {
    background: var(--dark-card);
    box-shadow: 0 2px 15px rgba(0,0,0,0.3);
}

.header-content {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.header-main {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    flex-wrap: wrap;
    gap: 15px;
}

.header-title h1 {
    font-size: 1.3rem;
    margin: 0;
    color: var(--primary);
    font-weight: 700;
}

.header-subtitle {
    font-size: 0.85rem;
    color: #7f8c8d;
    margin-top: 4px;
}

.header-actions {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

/* ===== BUTTONS ===== */
.btn {
    background-color: var(--primary);
    color: white;
    border: none;
    padding: 10px 16px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 0.9rem;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    text-decoration: none;
    transition: all 0.3s ease;
    font-weight: 500;
    min-height: 44px; /* Better touch target */
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
}

.btn-danger { 
    background-color: var(--danger); 
}
.btn-danger:hover { 
    background-color: #c0392b; 
    box-shadow: 0 4px 12px rgba(231, 76, 60, 0.3);
}

.btn-back { 
    background-color: #95a5a6; 
}
.btn-back:hover { 
    background-color: #7f8c8d; 
}

.btn-success {
    background-color: var(--success);
}

/* ===== MAIN CONTENT ===== */
.main-content {
    padding: 20px;
    max-width: 1200px;
    margin: 0 auto;
}

/* ===== FORM STYLES ===== */
.form-section {
    background: var(--light-card);
    padding: 25px;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    margin-bottom: 25px;
    transition: all 0.3s ease;
}

body.dark-mode .form-section {
    background: var(--dark-card);
    box-shadow: 0 4px 20px rgba(0,0,0,0.3);
}

.form-section h2 {
    font-size: 1.2rem;
    margin-bottom: 20px;
    color: var(--primary);
    display: flex;
    align-items: center;
    gap: 8px;
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 15px;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group label {
    font-weight: 600;
    margin-bottom: 8px;
    font-size: 0.95rem;
    color: inherit;
}

.form-control {
    padding: 14px 12px;
    border: 2px solid #e0e0e0;
    border-radius: 10px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: white;
    min-height: 50px; /* Better touch target */
}

body.dark-mode .form-control {
    background: #3a3a4f;
    border-color: var(--dark-border);
    color: var(--text-dark);
}

.form-control:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
    transform: translateY(-1px);
}

.form-actions {
    display: flex;
    gap: 12px;
    justify-content: flex-end;
    margin-top: 20px;
    flex-wrap: wrap;
}

/* ===== TABLE STYLES - MOBILE FRIENDLY ===== */
.data-section {
    background: var(--light-card);
    padding: 25px;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

body.dark-mode .data-section {
    background: var(--dark-card);
    box-shadow: 0 4px 20px rgba(0,0,0,0.3);
}

.data-section h2 {
    font-size: 1.2rem;
    margin-bottom: 20px;
    color: var(--primary);
    display: flex;
    align-items: center;
    gap: 8px;
}

/* Desktop Table */
.desktop-table {
    width: 100%;
    border-collapse: collapse;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.desktop-table thead {
    background: var(--primary);
    color: white;
}

.desktop-table th, 
.desktop-table td {
    padding: 14px 12px;
    text-align: left;
    border-bottom: 1px solid #e0e0e0;
}

.desktop-table th:last-child, 
.desktop-table td:last-child {
    text-align: center;
    min-width: 100px;
}

body.dark-mode .desktop-table {
    background: var(--dark-card);
}

body.dark-mode .desktop-table thead {
    background: #2980b9;
}

body.dark-mode .desktop-table th, 
body.dark-mode .desktop-table td {
    border-bottom: 1px solid var(--dark-border);
}

/* Mobile Cards */
.mobile-cards {
    display: none;
    flex-direction: column;
    gap: 15px;
}

.mobile-card {
    background: var(--light-card);
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 3px 15px rgba(0,0,0,0.1);
    border-left: 4px solid var(--primary);
    transition: all 0.3s ease;
}

body.dark-mode .mobile-card {
    background: var(--dark-card);
    box-shadow: 0 3px 15px rgba(0,0,0,0.3);
}

.mobile-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(0,0,0,0.15);
}

.mobile-card-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    border-bottom: 1px solid #f0f0f0;
}

body.dark-mode .mobile-card-row {
    border-bottom: 1px solid var(--dark-border);
}

.mobile-card-row:last-child {
    border-bottom: none;
}

.mobile-card-label {
    font-weight: 600;
    color: #7f8c8d;
    font-size: 0.9rem;
}

.mobile-card-value {
    color: inherit;
    font-weight: 500;
    text-align: right;
}

.mobile-card-actions {
    display: flex;
    justify-content: flex-end;
    margin-top: 15px;
    padding-top: 15px;
    border-top: 1px solid #f0f0f0;
}

body.dark-mode .mobile-card-actions {
    border-top: 1px solid var(--dark-border);
}

/* ===== MODAL STYLES ===== */
.modal {
    display: none;
    position: fixed;
    z-index: 2000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    backdrop-filter: blur(5px);
    justify-content: center;
    align-items: center;
    padding: 20px;
}

.modal.active { 
    display: flex; 
}

.modal-content {
    background: var(--light-card);
    padding: 25px;
    border-radius: 15px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    width: 100%;
    max-width: 500px;
    animation: modalSlideIn 0.3s ease;
}

body.dark-mode .modal-content {
    background: var(--dark-card);
}

@keyframes modalSlideIn {
    from { 
        opacity: 0; 
        transform: translateY(-30px) scale(0.9); 
    }
    to { 
        opacity: 1; 
        transform: translateY(0) scale(1); 
    }
}

.modal-header {
    margin-bottom: 20px;
    text-align: center;
}

.modal-header h2 {
    color: var(--primary);
    margin-bottom: 10px;
}

.modal-body {
    margin-bottom: 25px;
}

.modal-actions {
    display: flex;
    gap: 10px;
    justify-content: center;
    flex-wrap: wrap;
}

/* ===== UTILITY CLASSES ===== */
.hidden { 
    display: none; 
}

.text-center { 
    text-align: center; 
}

.no-data {
    text-align: center;
    padding: 40px 20px;
    color: #7f8c8d;
    background: var(--light-card);
    border-radius: 10px;
    margin: 20px 0;
}

body.dark-mode .no-data {
    background: var(--dark-card);
    color: #bbb;
}

/* Alert Messages */
.alert {
    padding: 12px 16px;
    border-radius: 8px;
    margin-bottom: 20px;
    border: 1px solid transparent;
}

.alert-success {
    background-color: #d4edda;
    border-color: #c3e6cb;
    color: #155724;
}

.alert-error {
    background-color: #f8d7da;
    border-color: #f5c6cb;
    color: #721c24;
}

body.dark-mode .alert-success {
    background-color: #155724;
    border-color: #0f4019;
    color: #d4edda;
}

body.dark-mode .alert-error {
    background-color: #721c24;
    border-color: #5a151b;
    color: #f8d7da;
}

/* ===== RESPONSIVE BREAKPOINTS ===== */
@media (max-width: 768px) {
    .header-main {
        flex-direction: column;
        align-items: stretch;
    }
    
    .header-actions {
        justify-content: center;
    }
    
    .main-content {
        padding: 15px;
    }
    
    .form-section,
    .data-section {
        padding: 20px;
        margin-bottom: 20px;
    }
    
    .form-actions {
        justify-content: stretch;
    }
    
    .form-actions .btn {
        flex: 1;
        justify-content: center;
    }
    
    /* Hide desktop table on mobile */
    .desktop-table {
        display: none;
    }
    
    /* Show mobile cards on mobile */
    .mobile-cards {
        display: flex;
    }
    
    .modal-content {
        margin: 10px;
        padding: 20px;
    }
    
    .modal-actions {
        flex-direction: column;
    }
    
    .modal-actions .btn {
        width: 100%;
        justify-content: center;
    }
}

@media (max-width: 480px) {
    .header {
        padding: 12px 15px;
    }
    
    .header-title h1 {
        font-size: 1.1rem;
    }
    
    .btn {
        padding: 12px 14px;
        font-size: 0.85rem;
    }
    
    .form-section,
    .data-section {
        padding: 15px;
    }
    
    .mobile-card {
        padding: 15px;
    }
}

@media (min-width: 769px) {
    .form-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .form-group:last-child:nth-child(odd) {
        grid-column: 1 / -1;
    }
}

/* ===== FOOTER ===== */
.footer {
    text-align: center;
    padding: 20px;
    font-size: 0.85rem;
    margin-top: 40px;
    color: #7f8c8d;
    border-top: 1px solid #e0e0e0;
}

body.dark-mode .footer {
    color: #bbb;
    border-top: 1px solid var(--dark-border);
}
</style>
</head>
<body>

<!-- Header -->
<header class="header">
    <div class="header-content">
        <div class="header-main">
            <div class="header-title">
                <h1>📝 Update Pelanggaran</h1>
                <div class="header-subtitle">
                    <?= esc($nama ?? 'Nama Tidak Diketahui') ?> - <?= esc($bulan ?? '') ?>
                    <br>
                    <small>
                        NIS: <?= esc($nis ?? '') ?> | 
                        Tahun Ajaran: <?= esc($tahun_ajaran ?? '2025/2026') ?> | 
                        Semester: <?= esc($semester ?? 'ganjil') ?>
                        <br>
                        <?php if ($id_role == 1): ?>
                            👨‍🏫 Akses: Ustadz PTD Ar - Rahman
                        <?php elseif ($id_role == 2): ?>
                            👨‍🎓 Akses: Ustadz PTD Ar - Rahman (Spesial)
                        <?php endif; ?>
                    </small>
                </div>
            </div>
            <div class="header-actions">
    <a href="<?= base_url("/pelanggaran/lihat_riwayat_penghapusan/{$nis}?tahun_ajaran={$tahun_ajaran}&semester={$semester}") ?>" class="btn" style="background: #9b59b6;">
        <i class="fas fa-history"></i> Lihat Riwayat
    </a>
    <a href="<?= base_url('/dashboard') ?>" class="btn btn-back">
        <i class="fas fa-arrow-left"></i> Kembali Ke Dashboard
    </a>
    <form action="<?= base_url('/logout') ?>" method="post" style="display: inline;">
        <button type="submit" class="btn btn-danger">
            <i class="fas fa-sign-out-alt"></i> Logout
        </button>
    </form>
</div>
        </div>
    </div>
</header>

<!-- Main Content -->
<main class="main-content">
    <!-- Notifikasi -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i> <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <!-- Form Tambah Pelanggaran -->
    <section class="form-section">
        <h2>
            <i class="fas fa-plus-circle"></i>
            Tambah Pelanggaran Baru
        </h2>
        
        <form action="<?= base_url('/pelanggaran/add_poin') ?>" method="post" id="pelanggaranForm">
            <input type="hidden" name="nis" value="<?= esc($nis ?? '') ?>">
            <input type="hidden" name="bulan" value="<?= esc($bulan ?? '') ?>">
            <input type="hidden" name="tahun_ajaran" value="<?= esc($tahun_ajaran ?? '2025/2026') ?>">
            <input type="hidden" name="semester" value="<?= esc($semester ?? 'ganjil') ?>">

            <div class="form-grid">
                <div class="form-group">
                    <label for="jenis">
                        <i class="fas fa-list"></i> Jenis Pelanggaran
                    </label>
                    <select name="jenis" id="jenis" class="form-control" required onchange="handleJenisChange()">
                        <option value="">-- Pilih Jenis --</option>
                        <option value="telat_sholat">⏰ Telat Sholat</option>
                        <option value="tidak_sholat_berjamaah">🕌 Tidak Sholat Berjamaah</option>
                        <option value="tidak_piket">🧹 Tidak Piket</option>
                        <option value="other">📋 Lainnya</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="detail">
                        <i class="fas fa-align-left"></i> Detail Pelanggaran
                    </label>
                    <input type="text" name="detail" id="detail" class="form-control" 
                           placeholder="Masukkan detail pelanggaran" required>
                </div>

                <div id="poinContainer" class="form-group hidden">
                    <label for="poin">
                        <i class="fas fa-star"></i> Poin
                    </label>
                    <input type="number" name="poin" id="poin" class="form-control" 
                           min="1" max="100" placeholder="Masukkan poin" required>
                </div>

                <div id="kategoriContainer" class="form-group hidden">
                    <label for="kategori">
                        <i class="fas fa-tag"></i> Kategori
                    </label>
                    <select name="kategori" id="kategori" class="form-control" required>
                        <option value="">-- Pilih Kategori --</option>
                        <option value="ringan">🟢 Ringan</option>
                        <option value="ringan">Ringan -> Sedang</option>
                        <option value="sedang">🟡 Sedang</option>
                        <option value="sedang">Sedang -> Berat</option>
                        <option value="berat">🔴 Berat</option>
                    </select>
                </div>
            </div>

            <div class="form-actions">
                <button type="button" class="btn btn-back" onclick="window.history.back()">
                    <i class="fas fa-times"></i> Batal
                </button>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Simpan Pelanggaran
                </button>
            </div>
        </form>
    </section>

    <!-- Daftar Poin -->
    <section class="data-section">
        <h2>
            <i class="fas fa-list-alt"></i>
            Daftar Poin Bulan Ini
        </h2>

        <?php if (!empty($poinBulanan)): ?>
            <!-- Desktop Table -->
            <div class="table-responsive">
                <table class="desktop-table">
                    <thead>
                        <tr>
                            <th>Bulan</th>
                            <th>Poin</th>
                            <th>Keterangan</th>
                            <th>Kategori</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($poinBulanan as $p): ?>
                        <tr data-id="<?= $p['id'] ?>" data-poin="<?= $p['poin'] ?>">
                            <td><?= esc($p['bulan']) ?></td>
                            <td>
                                <span class="badge" style="background: #e74c3c; color: white; padding: 4px 8px; border-radius: 12px;">
                                    <?= esc($p['poin']) ?>
                                </span>
                            </td>
                            <td><?= esc($p['keterangan']) ?></td>
                            <td>
                                <?php 
                                $badgeColor = match($p['kategori']) {
                                    'ringan' => '#27ae60',
                                    'sedang' => '#f39c12', 
                                    'berat' => '#e74c3c',
                                    default => '#95a5a6'
                                };
                                ?>
                                <span style="background: <?= $badgeColor ?>; color: white; padding: 4px 8px; border-radius: 12px; font-size: 0.8rem;">
                                    <?= esc(ucfirst($p['kategori'])) ?>
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-danger btn-hapus" 
                                        data-id="<?= $p['id'] ?>" 
                                        data-poin="<?= $p['poin'] ?>"
                                        data-keterangan="<?= esc($p['keterangan']) ?>">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Mobile Cards -->
            <div class="mobile-cards">
                <?php foreach ($poinBulanan as $p): ?>
                <div class="mobile-card" data-id="<?= $p['id'] ?>" data-poin="<?= $p['poin'] ?>">
                    <div class="mobile-card-row">
                        <span class="mobile-card-label">Bulan</span>
                        <span class="mobile-card-value"><?= esc($p['bulan']) ?></span>
                    </div>
                    <div class="mobile-card-row">
                        <span class="mobile-card-label">Poin</span>
                        <span class="mobile-card-value" style="color: #e74c3c; font-weight: bold;">
                            <?= esc($p['poin']) ?>
                        </span>
                    </div>
                    <div class="mobile-card-row">
                        <span class="mobile-card-label">Keterangan</span>
                        <span class="mobile-card-value"><?= esc($p['keterangan']) ?></span>
                    </div>
                    <div class="mobile-card-row">
                        <span class="mobile-card-label">Kategori</span>
                        <span class="mobile-card-value">
                            <?php 
                            $badgeColor = match($p['kategori']) {
                                'ringan' => '#27ae60',
                                'sedang' => '#f39c12', 
                                'berat' => '#e74c3c',
                                default => '#95a5a6'
                            };
                            ?>
                            <span style="background: <?= $badgeColor ?>; color: white; padding: 4px 8px; border-radius: 12px; font-size: 0.8rem;">
                                <?= esc(ucfirst($p['kategori'])) ?>
                            </span>
                        </span>
                    </div>
                    <div class="mobile-card-actions">
                        <button class="btn btn-danger btn-hapus" 
                                data-id="<?= $p['id'] ?>" 
                                data-poin="<?= $p['poin'] ?>"
                                data-keterangan="<?= esc($p['keterangan']) ?>">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="no-data">
                <i class="fas fa-inbox" style="font-size: 3rem; margin-bottom: 15px; opacity: 0.5;"></i>
                <h3>Belum ada data poin</h3>
                <p>Tambahkan pelanggaran baru menggunakan form di atas</p>
            </div>
        <?php endif; ?>
    </section>
</main>

<!-- Footer -->
<footer class="footer">
    &copy; <?= date('Y') ?> E - Mahkamah | Dirancang Oleh Santri PTD 
    <br>
    <small>Server Time: <?= date('Y-m-d H:i:s') ?></small>
</footer>

<!-- Modal Konfirmasi Hapus -->
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2><i class="fas fa-exclamation-triangle text-danger"></i> Konfirmasi Penghapusan</h2>
        </div>
        <form action="<?= base_url('pelanggaran/delete_poin') ?>" method="post">
            <div class="modal-body">
                <input type="hidden" name="id" id="delete_id">
                <input type="hidden" name="nis" value="<?= esc($nis ?? '') ?>">
                <input type="hidden" name="bulan" value="<?= esc($bulan ?? '') ?>">
                <input type="hidden" name="tahun_ajaran" value="<?= esc($tahun_ajaran ?? '2025/2026') ?>">
                <input type="hidden" name="semester" value="<?= esc($semester ?? 'ganjil') ?>">
                <input type="hidden" name="poin_dihapus" id="delete_poin_dihapus">

                <p style="margin-bottom: 20px; text-align: center; font-weight: 600;">
                    Apakah Anda yakin ingin menghapus poin ini?
                </p>

                <div class="form-group">
                    <label for="catatan_penghapusan" class="form-label">
                        <i class="fas fa-sticky-note"></i> Catatan Penghapusan (Opsional)
                    </label>
                    <textarea class="form-control" name="catatan_penghapusan" id="catatan_penghapusan" rows="3"
                        placeholder="Contoh: Poin dihapus karena kesalahan input, sudah diperbaiki, dll..."></textarea>
                    <small style="color: #7f8c8d; margin-top: 5px; display: block;">
                        Catatan ini akan disimpan di database untuk riwayat penghapusan.
                    </small>
                </div>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-back" id="cancelDelete">
                    <i class="fas fa-times"></i> Batal
                </button>
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Hapus Poin
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Notifikasi -->
<div id="notifModal" class="modal">
    <div class="modal-content">
        <div class="modal-body text-center">
            <p id="notifMessage" style="font-weight: 600; font-size: 1.1rem; margin: 0;"></p>
        </div>
    </div>
</div>

<!-- Bootstrap JS untuk modal functionality -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Variabel global
let deleteId = null;
let deletePoin = null;
let deleteKeterangan = null;

// Ambil data dari server
const currentNIS = '<?= $nis ?>';
const currentBulan = '<?= $bulan ?>';
const currentTahunAjaran = '<?= $tahun_ajaran ?? '2025/2026' ?>';
const currentSemester = '<?= $semester ?? 'ganjil' ?>';

// === Buka Modal Hapus ===
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('btn-hapus') || e.target.closest('.btn-hapus')) {
        e.preventDefault();
        const btn = e.target.classList.contains('btn-hapus') ? e.target : e.target.closest('.btn-hapus');
        
        deleteId = btn.dataset.id;
        deletePoin = btn.dataset.poin;
        deleteKeterangan = btn.dataset.keterangan;
        
        // Set nilai untuk form hapus
        document.getElementById('delete_id').value = deleteId;
        document.getElementById('delete_poin_dihapus').value = deletePoin;
        
        // Reset textarea catatan
        document.getElementById('catatan_penghapusan').value = '';
        
        // Buka modal hapus
        const deleteModal = document.getElementById('deleteModal');
        if (deleteModal) {
            deleteModal.classList.add('active');
        }
    }
});

// === Tutup Modal Hapus ===
document.getElementById('cancelDelete')?.addEventListener('click', function() {
    const deleteModal = document.getElementById('deleteModal');
    if (deleteModal) {
        deleteModal.classList.remove('active');
    }
    resetDeleteData();
});

// === Reset data hapus ===
function resetDeleteData() {
    deleteId = null;
    deletePoin = null;
    deleteKeterangan = null;
}

// === Tutup Modal ketika klik di luar ===
window.addEventListener('click', function(e) {
    const deleteModal = document.getElementById('deleteModal');
    const notifModal = document.getElementById('notifModal');
    
    if (deleteModal && e.target === deleteModal) {
        deleteModal.classList.remove('active');
        resetDeleteData();
    }
    if (notifModal && e.target === notifModal) {
        notifModal.classList.remove('active');
    }
});

// ==== Mode Gelap ====
function applyMode(mode) {
    document.body.classList.toggle('dark-mode', mode === 'dark');
    localStorage.setItem('themeMode', mode);
}

// Initialize dark mode
document.addEventListener('DOMContentLoaded', function() {
    // Apply saved theme
    applyMode(localStorage.getItem('themeMode') || 'light');
    
    // Event delegation untuk semua fungsi
    initializeEventListeners();
});

function initializeEventListeners() {
    // Event listener untuk form jenis pelanggaran
    const jenisSelect = document.getElementById('jenis');
    if (jenisSelect) {
        jenisSelect.addEventListener('change', handleJenisChange);
    }
    
    // Initialize form state
    handleJenisChange();
}

function handleJenisChange() {
    const jenis = document.getElementById('jenis')?.value;
    const detailInput = document.getElementById('detail');
    const poinContainer = document.getElementById('poinContainer');
    const kategoriContainer = document.getElementById('kategoriContainer');
    const poinInput = document.getElementById('poin');
    const kategoriSelect = document.getElementById('kategori');

    if (!detailInput || !poinContainer || !kategoriContainer) return;

    // Reset tampilan awal
    poinContainer.classList.add('hidden');
    kategoriContainer.classList.add('hidden');
    if (poinInput) poinInput.value = '';
    if (kategoriSelect) kategoriSelect.value = '';
    if (detailInput) detailInput.value = '';
    if (detailInput) detailInput.placeholder = 'Masukkan detail pelanggaran';

    if (!jenis) return;

    switch (jenis) {
        case 'telat_sholat':
            poinContainer.classList.remove('hidden');
            kategoriContainer.classList.remove('hidden');
            if (poinInput) poinInput.value = 1;
            if (kategoriSelect) kategoriSelect.value = 'ringan';
            if (detailInput) detailInput.placeholder = 'Sholat apa? (contoh: Subuh, Maghrib, dll)';
            break;

        case 'tidak_sholat_berjamaah':
            poinContainer.classList.remove('hidden');
            kategoriContainer.classList.remove('hidden');
            if (poinInput) poinInput.value = 11;
            if (kategoriSelect) kategoriSelect.value = 'sedang';
            if (detailInput) detailInput.placeholder = 'Sholat apa? (contoh: Isya, Dzuhur, dll)';
            break;

        case 'tidak_piket':
            poinContainer.classList.remove('hidden');
            kategoriContainer.classList.remove('hidden');
            if (poinInput) poinInput.value = 1;
            if (kategoriSelect) kategoriSelect.value = 'ringan';
            if (detailInput) detailInput.placeholder = 'Kelas, dapur, atau kamar?';
            break;

        case 'other':
            poinContainer.classList.remove('hidden');
            kategoriContainer.classList.remove('hidden');
            if (poinInput) poinInput.value = '';
            if (kategoriSelect) kategoriSelect.value = '';
            if (detailInput) detailInput.placeholder = 'Masukkan jenis dan detail pelanggaran lainnya';
            break;

        default:
            if (poinInput) poinInput.value = '';
            if (kategoriSelect) kategoriSelect.value = '';
            if (detailInput) detailInput.placeholder = 'Masukkan detail pelanggaran';
    }
}

// ==== Modal Notifikasi ====
function showNotif(message, success = true) {
    const notifModal = document.getElementById('notifModal');
    const notifMessage = document.getElementById('notifMessage');
    
    if (!notifModal || !notifMessage) return;
    
    notifMessage.textContent = message;
    
    // Reset classes
    notifModal.className = 'modal';
    notifMessage.className = '';
    
    // Add appropriate class
    if (success) {
        notifMessage.style.color = '#27ae60';
    } else {
        notifMessage.style.color = '#e74c3c';
    }
    
    notifModal.classList.add('active');

    setTimeout(() => {
        notifModal.classList.remove('active');
    }, 3000);
}

// ==== Handle Form Submission ====
document.getElementById('pelanggaranForm')?.addEventListener('submit', function(e) {
    // Validasi tambahan bisa ditambah di sini
    const jenis = document.getElementById('jenis')?.value;
    const detail = document.getElementById('detail')?.value;
    const poin = document.getElementById('poin')?.value;
    const kategori = document.getElementById('kategori')?.value;
    
    if (!jenis || !detail) {
        e.preventDefault();
        showNotif('⚠️ Harap isi semua field yang required', false);
        return;
    }
    
    if (poin && (poin < 1 || poin > 100)) {
        e.preventDefault();
        showNotif('⚠️ Poin harus antara 1-100', false);
        return;
    }
});

// ==== Auto-hide alerts setelah 5 detik ====
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            alert.style.transition = 'opacity 0.5s ease';
            setTimeout(() => {
                alert.remove();
            }, 500);
        }, 5000);
    });
});

// ==== Debug info ====
console.log('Script loaded successfully');
console.log('Delete buttons found:', document.querySelectorAll('.btn-hapus').length);
console.log('Delete modal found:', !!document.getElementById('deleteModal'));
console.log('Current data:', {
    nis: currentNIS,
    bulan: currentBulan,
    tahun_ajaran: currentTahunAjaran,
    semester: currentSemester
});
</script>

</body>
</html>