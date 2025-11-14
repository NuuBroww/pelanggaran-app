<?php
date_default_timezone_set('Asia/Jakarta');
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>E - Mahkamah - Dashboard</title>
<style>
/* ===== VARIABLES & RESET ===== */
:root {
    --primary: #2563eb;
    --primary-dark: #1d4ed8;
    --secondary: #475569;
    --accent: #dc2626;
    --success: #16a34a;
    --warning: #d97706;
    --danger: #b91c1c;
    --light: #f8fafc;
    --dark: #1e293b;
    --gray: #64748b;
    --border: #e2e8f0;
    --shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
    --gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --gradient-primary: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    --gradient-success: linear-gradient(135deg, #10b981 0%, #059669 100%);
    --gradient-warning: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    --gradient-danger: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
    background: #1e1e2e;
    color: #ddd;
    line-height: 1.6;
    -webkit-font-smoothing: antialiased;
}

/* ===== HEADER STYLES ===== */
.header {
    background: #2a2a3d;
    padding: 15px 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.5);
    position: sticky;
    top: 0;
    z-index: 100;
}

.header-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 16px;
    max-width: 1400px;
    margin: 0 auto;
}

.header-content h1 {
    font-size: 1.2rem;
    margin: 0;
    color: #ddd;
}

.header-actions {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}

/* ===== NAVBAR STATS ===== */
.navbar-top {
    background: linear-gradient(135deg, #2a2a3d 0%, #3a3a4f 100%);
    padding: 15px 20px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    position: sticky;
    top: 0;
    z-index: 99;
    transition: all 0.4s ease;
}

.navbar-container {
    max-width: 1400px;
    margin: 0 auto;
}

.navbar-stats {
    display: flex;
    gap: 15px;
    align-items: center;
    flex-wrap: wrap;
    justify-content: center;
}

.stat-item {
    background: rgba(255,255,255,0.15);
    padding: 10px 15px;
    border-radius: 10px;
    text-align: center;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.2);
    transition: all 0.3s ease;
    min-width: 100px;
    cursor: pointer;
    flex: 1;
    min-width: 100px;
    max-width: 150px;
}

.stat-item:hover {
    background: rgba(255,255,255,0.25);
    transform: translateY(-2px);
}

.stat-number {
    display: block;
    font-size: 1.3rem;
    font-weight: bold;
    color: white;
}

.stat-label {
    font-size: 0.85rem;
    color: rgba(255,255,255,0.9);
    margin-top: 4px;
}

.stat-item small {
    display: block;
    font-size: 0.7rem;
    opacity: 0.8;
    margin-top: 2px;
    color: rgba(255,255,255,0.9);
}

/* SP Badges */
.sp-badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: bold;
    margin-top: 3px;
    color: white;
}

.sp1 { background: #f39c12; }
.sp2 { background: #e74c3c; }
.sp3 { background: #c0392b; }

/* ===== BUTTON STYLES ===== */
.btn {
    background: #3498db;
    color: #fff;
    border: none;
    padding: 8px 16px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 0.9rem;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    min-width: 60px;
    justify-content: center;
    transition: all 0.2s;
    text-decoration: none;
}

.btn:hover {
    background: #2980b9;
}

.btn-danger {
    background: #e74c3c;
}

.btn-danger:hover {
    background: #c0392b;
}

.btn-info {
    background: #16a085;
}

.btn-info:hover {
    background: #138d75;
}

.btn-purple {
    background: #9b59b6;
}

.btn-purple:hover {
    background: #8e44ad;
}

.btn-month {
    margin: 2px;
    font-size: 0.8rem;
    padding: 6px 10px;
}

/* ===== MAIN CONTENT ===== */
.main-content {
    max-width: 1400px;
    margin: 0 auto;
    padding: 20px;
}

.content-card {
    background: #2a2a3d;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.6);
    margin-bottom: 20px;
}

.content-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    flex-wrap: wrap;
    gap: 16px;
}

.content-header h2 {
    margin: 0;
    font-size: 1.1rem;
    color: #ddd;
}

/* ===== SEARCH STYLES ===== */
.search-container {
    margin-bottom: 20px;
    position: relative;
    max-width: 250px;
    width: 100%;
}

.search-box {
    width: 100%;
    padding: 12px 40px 12px 10px;
    border: 2px solid #444;
    border-radius: 10px;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    background: #2a2a3d;
    color: #ddd;
}

.search-box:focus {
    outline: none;
    border-color: #3498db;
    box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
}

.search-icon {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    color: #7f8c8d;
    pointer-events: none;
}

/* ===== TABLE STYLES ===== */
.table-wrapper {
    overflow-x: auto;
    border-radius: 10px;
}

.desktop-table {
    width: 100%;
    border-collapse: collapse;
    background: #2a2a3d;
    box-shadow: 0 2px 8px rgba(0,0,0,0.6);
}

.desktop-table th {
    background: #3a3a4f;
    padding: 10px 12px;
    text-align: center;
    font-weight: 600;
    color: #ccc;
    border-bottom: 1px solid #444;
}

.desktop-table td {
    padding: 10px 12px;
    text-align: center;
    border-bottom: 1px solid #444;
    vertical-align: middle;
}

.desktop-table tr:hover {
    background: #44445a;
}

/* ===== MOBILE CARD STYLES ===== */
.mobile-cards {
    display: none;
    flex-direction: column;
    gap: 15px;
}

.mobile-card {
    background: #2a2a3d;
    border-radius: 12px;
    padding: 15px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    border: 1px solid #444;
}

.mobile-card-header {
    border-bottom: 2px solid #3498db;
    padding-bottom: 12px;
    margin-bottom: 15px;
}

.mobile-card-title {
    font-size: 1.1rem;
    font-weight: bold;
    color: #ddd;
    margin: 0;
    word-break: break-word;
}

.mobile-card-nis {
    color: #7f8c8d;
    font-size: 0.9rem;
    margin-top: 5px;
}

.mobile-card-section {
    margin-bottom: 15px;
    padding: 12px;
    background: #3a3a4f;
    border-radius: 8px;
    border-left: 4px solid #3498db;
}

.mobile-card-section h4 {
    margin: 0 0 8px 0;
    font-size: 0.95rem;
    color: #ddd;
}

.mobile-card-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    border-bottom: 1px solid #444;
}

.mobile-card-row:last-child {
    border-bottom: none;
}

.mobile-card-label {
    font-weight: 600;
    color: #bbb;
    font-size: 0.9rem;
    flex-shrink: 0;
}

.mobile-card-value {
    color: #ddd;
    font-weight: 500;
    text-align: right;
    flex: 1;
    margin-left: 10px;
}

.mobile-buttons {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-top: 10px;
}

.mobile-btn-month {
    background: #3498db;
    color: white;
    border: none;
    padding: 10px;
    border-radius: 6px;
    font-size: 0.85rem;
    cursor: pointer;
    transition: all 0.3s ease;
    text-align: center;
    width: 100%;
}

.mobile-btn-month:hover {
    background: #2980b9;
}

.mobile-btn-month.btn-disabled {
    background: #95a5a6 !important;
    cursor: not-allowed;
    opacity: 0.6;
}

/* Status untuk mobile */
.mobile-status {
    padding: 8px 12px;
    border-radius: 6px;
    font-size: 0.85rem;
    font-weight: 500;
    text-align: center;
    margin-top: 5px;
    min-width: 80px;
}

.status-aman { background: #1b5e20; color: #81c784; }
.status-sp1 { background: #3a3a2a; color: #ffd54f; }
.status-sp2 { background: #4a235a; color: #f1948a; }
.status-sp3 { background: #3a2a32; color: #f48fb1; }

/* Poin box untuk mobile */
.poin-box-mobile {
    background: #3a3a4f;
    padding: 10px;
    border-radius: 6px;
    font-size: 0.85rem;
    line-height: 1.4;
    margin-top: 8px;
    border-left: 4px solid #3498db;
    word-break: break-word;
}

.poin-box-mobile.semester-ganjil { border-left-color: #3498db; }
.poin-box-mobile.semester-genap { border-left-color: #e74c3c; }

/* ===== FLOATING TOGGLE BUTTON ===== */
.navbar-toggle {
    position: fixed;
    bottom: 400px;
    right: 25px;
    background: linear-gradient(135deg, #2a2a3d 0%, #3a3a4f 100%);
    color: white;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.4);
    display: flex;
    justify-content: center;
    align-items: center;
    cursor: pointer;
    z-index: 9999;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    border: none;
    font-size: 1.2rem;
}

/* State hidden (saat scroll down) */
.navbar-toggle.hidden {
    transform: translateY(100px);
    opacity: 0;
    pointer-events: none;
}

/* State visible (saat scroll up/top) */
.navbar-toggle.visible {
    transform: translateY(0);
    opacity: 1;
    pointer-events: all;
}

.navbar-toggle:hover {
    transform: scale(1.1) translateY(0);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.5);
}

.navbar-top.hide {
    transform: translateY(-100%);
    opacity: 0;
    pointer-events: none;
    transition: all 0.4s ease;
}

.navbar-top {
    transition: all 0.4s ease;
}

/* ===== RESPONSIVE DESIGN ===== */
@media (max-width: 768px) {
    .desktop-table {
        display: none;
    }
    
    .mobile-cards {
        display: flex;
    }
    
    .header-container {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
    
    .header-actions {
        width: 100%;
        justify-content: space-between;
        flex-wrap: wrap;
    }
    
    .navbar-stats {
        flex-wrap: wrap;
        justify-content: center;
        gap: 10px;
    }
    
    .stat-item {
        min-width: 80px;
        padding: 8px 12px;
        font-size: 0.9rem;
        flex: 1;
        min-width: 100px;
    }
    
    .main-content {
        padding: 15px;
    }
    
    .mobile-card {
        padding: 15px;
    }
    
    .mobile-card-section {
        padding: 10px;
    }
    
    .mobile-card-row {
        flex-direction: column;
        align-items: flex-start;
        gap: 5px;
        padding: 10px 0;
    }
    
    .mobile-card-value {
        text-align: left;
        margin-left: 0;
        width: 100%;
    }
    
    .search-container {
        max-width: 100%;
        width: 100%;
    }
    
    /* Header actions mobile */
    .header-actions {
        flex-direction: column;
        width: 100%;
        gap: 8px;
    }
    
    .header-actions .btn {
        width: 100%;
        justify-content: center;
    }
    
    /* Button month container mobile */
    .btn-month-container {
        display: flex;
        flex-wrap: wrap;
        gap: 4px;
        justify-content: center;
    }
    
    .btn-month {
        flex: 1;
        min-width: 60px;
        font-size: 0.75rem;
        padding: 5px 8px;
    }
    
    /* Floating button mobile */
    .navbar-toggle {
        bottom: 20px;
        right: 20px;
        width: 55px;
        height: 55px;
    }
}

@media (max-width: 480px) {
    .header {
        padding: 12px;
    }
    
    .header h1 {
        font-size: 1.1rem;
    }
    
    .navbar-stats {
        gap: 8px;
    }
    
    .stat-item {
        min-width: 70px;
        padding: 6px 10px;
        font-size: 0.8rem;
        flex: 1;
        min-width: 80px;
    }
    
    .stat-number {
        font-size: 1.1rem;
    }
    
    .mobile-card {
        padding: 12px;
    }
    
    .mobile-card-title {
        font-size: 1rem;
    }
    
    .mobile-card-section h4 {
        font-size: 0.9rem;
    }
    
    .mobile-btn-month {
        padding: 8px;
        font-size: 0.8rem;
    }
    
    /* Tahun ajaran select mobile */
    select#tahunAjaranSelect {
        width: 100%;
        min-width: auto;
    }
}

/* ===== MODAL STYLES ===== */
.modal {
    display: flex;
    justify-content: center;
    align-items: center;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    z-index: 1000;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.35s ease;
}

.modal.active {
    opacity: 1;
    pointer-events: auto;
}

.modal .modal-content {
    background: #2a2a3d;
    padding: 20px;
    border-radius: 20px;
    width: 95%;
    max-width: 400px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.25);
    transform: translateY(-50px) scale(0.9);
    opacity: 0;
    transition: all 0.35s ease;
    margin: 0;
}

.modal.active .modal-content {
    transform: translateY(0) scale(1);
    opacity: 1;
}

.modal h2 {
    margin-top: 0;
    text-align: center;
    font-size: 1.5rem;
    margin-bottom: 25px;
    color: #ddd;
}

.modal label {
    display: block;
    margin-bottom: 5px;
    font-weight: 600;
    color: #ddd;
}

.modal input, .modal select {
    width: 100%;
    padding: 12px 5px;
    margin-bottom: 15px;
    border-radius: 7px;
    border: 1px solid #555;
    font-size: 0.9rem;
    transition: all 0.3s;
    background: #3a3a4f;
    color: #ddd;
}

.modal input:focus, .modal select:focus {
    outline: none;
    border-color: #3498db;
    box-shadow: 0 0 6px rgba(52,152,219,0.4);
}

.modal-buttons {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 10px;
}

.modal .btn {
    padding: 10px 18px;
    font-size: 1rem;
    border-radius: 8px;
}

.modal .btn-danger {
    padding: 10px 18px;
    font-size: 1rem;
    border-radius: 8px;
}

/* Button month container */
.btn-month-container {
    display: flex;
    gap: 6px;
    white-space: nowrap;
    overflow-x: auto;
    padding-bottom: 6px;
    flex-wrap: nowrap;
}

/* Semester Warning */
.semester-warning {
    background: #3a3a2a;
    border: 1px solid #555;
    border-radius: 5px;
    padding: 8px;
    margin-top: 8px;
    font-size: 0.8rem;
    color: #ffd54f;
    text-align: center;
}

.btn-disabled {
    background-color: #95a5a6 !important;
    cursor: not-allowed !important;
    opacity: 0.6;
}

.btn-disabled:hover {
    background-color: #95a5a6 !important;
    transform: none !important;
}

/* Style untuk dropdown tahun ajaran */
select#tahunAjaranSelect {
    padding: 8px 12px;
    border: 1px solid #555;
    border-radius: 5px;
    background: #3a3a4f;
    color: #ddd;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.3s ease;
    min-width: 150px;
}

/* Search Results */
.search-results {
    margin-top: 8px;
    font-size: 0.85rem;
    color: #7f8c8d;
}

.no-results {
    text-align: center;
    padding: 20px;
    color: #7f8c8d;
    background: #3a3a4f;
    border-radius: 8px;
    margin: 10px 0;
}

footer {
    text-align: center;
    padding: 15px;
    font-size: 0.85rem;
    margin-top: 30px;
    color: #bbb;
}

/* Highlight untuk search */
.highlight {
    background-color: #ffeb3b;
    color: #333;
    padding: 2px 4px;
    border-radius: 3px;
    font-weight: bold;
}
/* ===== CONTENT TRANSITION ===== */
.main-content {
    max-width: 1400px;
    margin: 0 auto;
    padding: 20px;
    transition: all 0.4s ease;
}

/* Ketika navbar hidden, content naik */
.navbar-top.hide + .main-content {
    margin-top: -80px; /* Sesuaikan dengan tinggi navbar */
}

/* Untuk mobile adjustment */
@media (max-width: 768px) {
    .navbar-top.hide + .main-content {
        margin-top: -70px;
    }
}
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<!-- ===== HEADER USER ===== -->
<header class="header">
    <div class="header-container">
        <div class="header-content">
            <?php if ($id_role == 2 || $id_role == 1): ?>
            <h1>Assalamu'alaikum Ustadz <?= esc($nama) ?> 👋</h1>
            <?php endif; ?>
            <?php if ($id_role == 3): ?>
            <h1>Assalamu'alaikum Pak/Ibu <?= esc($nama) ?> 👋</h1>
            <?php endif; ?>
        </div>

        <div class="header-actions">
            <select name="tahun_ajaran" id="tahunAjaranSelect" onchange="changeTahunAjaran(this.value)">
                <?php if (!empty($availableTahunAjaran) && is_array($availableTahunAjaran)): ?>
                    <?php foreach($availableTahunAjaran as $tahun): ?>
                        <?php 
                        $tahunValue = is_array($tahun) ? ($tahun['tahun_ajaran'] ?? $tahun[0] ?? $tahun) : $tahun;
                        $tahunText = $tahunValue;
                        ?>
                        <option value="<?= $tahunValue ?>" <?= $tahun_ajaran == $tahunValue ? 'selected' : '' ?>>
                            <?= $tahunText ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
            
            <?php if ($id_role == 2): ?>
                <a href="<?= base_url('/rekapan') ?>?tahun_ajaran=<?= $tahun_ajaran ?>" class="btn btn-info">📄 Cetak Rekapan</a>
            <?php endif; ?>
            <?php if ($id_role == 1 || $id_role == 3): ?>
                <a href="<?= base_url('/rekapan') ?>?tahun_ajaran=<?= $tahun_ajaran ?>" class="btn btn-info">📄 Lihat Rekapan</a>
            <?php endif; ?>

            <?php if ($id_role == 1): ?>
                <a href="#" class="btn" id="btnSettings">⚙️ Pengaturan</a>
            <?php endif; ?>
            
            <form action="<?= base_url('/logout') ?>" method="post" style="margin:0;">
                <button type="submit" class="btn btn-danger">🚪 Logout</button>
            </form>
        </div>
    </div>
</header>

<!-- ===== NAVBAR INTERAKTIF ===== -->
<div class="navbar-toggle visible" id="navbarToggle">
    <i class="fas fa-angle-up"></i>
</div>

<nav class="navbar-top">
    <div class="navbar-container">
        <div class="navbar-stats">
            <div class="stat-item">
                <span class="stat-number"><?= $totalSantri ?></span>
                <span class="stat-label">Total Santri</span>
            </div>
            
            <div class="stat-item">
                <span class="stat-number"><?= $totalPoinGanjil + $totalPoinGenap ?></span>
                <span class="stat-label">Total Poin</span>
                <small>Ganjil: <?= $totalPoinGanjil ?> | Genap: <?= $totalPoinGenap ?></small>
            </div>
            
            <!-- SP Counters -->
            <div class="stat-item">
                <span class="stat-number"><?= $sp1CountGanjil + $sp1CountGenap ?></span>
                <span class="sp-badge sp1">SP1</span>
                <small>Ganjil: <?= $sp1CountGanjil ?> | Genap: <?= $sp1CountGenap ?></small>
            </div>
            
            <div class="stat-item">
                <span class="stat-number"><?= $sp2CountGanjil + $sp2CountGenap ?></span>
                <span class="sp-badge sp2">SP2</span>
                <small>Ganjil: <?= $sp2CountGanjil ?> | Genap: <?= $sp2CountGenap ?></small>
            </div>
            
            <div class="stat-item">
                <span class="stat-number"><?= $sp3CountGanjil + $sp3CountGenap ?></span>
                <span class="sp-badge sp3">SP3</span>
                <small>Ganjil: <?= $sp3CountGanjil ?> | Genap: <?= $sp3CountGenap ?></small>
            </div>
            
            <?php if ($id_role == 2): ?>
            <div class="stat-item" style="cursor: pointer; background: rgba(155, 89, 182, 0.2);">
                <a href="<?= base_url('/data_santri') ?>" style="text-decoration: none; color: white; display: block;">
                    <span class="stat-number">
                        <i class="fas fa-users"></i>
                    </span>
                    <span class="stat-label">Atur Data Santri</span>
                </a>
            </div>
            <?php endif; ?>
            
            <div class="stat-item" style="cursor: pointer; background: rgba(155, 89, 182, 0.2);">
                <a href="<?= base_url('/dashboard/grafik_pelanggaran') ?>" style="text-decoration: none; color: white; display: block;">
                    <span class="stat-number">📊 Lihat Grafik</span>
                </a>
            </div>
            
            <!-- Admin Logs -->
            <?php if ($id_role == 1 || $id_role == 2): ?>
            <div class="stat-item" style="cursor: pointer; background: rgba(46, 204, 113, 0.2);">
                <a href="<?= base_url('/admin-logs') ?>" style="text-decoration: none; color: white; display: block;">
                    <span class="stat-number">
                        <i class="fas fa-history"></i>
                    </span>
                    <span class="stat-label">Aktivitas Admin</span>
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</nav>

<main class="main-content">
    <?php if(session()->getFlashdata('error')): ?>
        <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
            <strong><i class="fas fa-lock"></i> Akses Ditolak:</strong> 
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <div class="content-header">
        <h2>📋 Data Pelanggaran Santri</h2>
    </div>

    <div class="search-container">
        <input type="text" id="searchInput" class="search-box" placeholder="Cari nama santri atau NIS..." autocomplete="off">
        <i class="fas fa-search search-icon"></i>
    </div>

    <!-- No Results Message -->
    <div id="noResults" class="no-results" style="display: none;">
        <i class="fas fa-search" style="font-size: 2rem; margin-bottom: 10px; opacity: 0.5;"></i>
        <h3 style="margin-bottom: 10px; color: #7f8c8d;">Santri tidak ditemukan</h3>
        <p>Coba gunakan kata kunci yang berbeda atau periksa ejaan</p>
    </div>
    
    <!-- Desktop Table -->
    <div class="table-wrapper">
        <table class="desktop-table">
            <thead>
                <tr>
                    <th>NIS</th>
                    <th>Nama Santri</th>
                    <th>Total Poin Semester Ganjil</th>
                    <th>Total Poin Semester Genap</th>
                    <th>Poin Bulanan (<?= $semesterAktif === 'ganjil' ? 'Ganjil' : 'Genap' ?>)</th>
                    <th>Status Semester Ganjil</th>
                    <th>Status Semester Genap</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($pelanggaran)): ?>
                    <?php foreach($pelanggaran as $row): ?>
                    <tr>
                        <td><?= esc($row['nis']) ?></td>
                        <td><?= esc($row['nama_santri']) ?></td>
                        <td><?= $poinGanjil[$row['nis']] ?? 0 ?></td>
                        <td><?= $poinGenap[$row['nis']] ?? 0 ?></td>
                        <td>
                            <!-- Poin bulanan desktop view -->
                            <!-- Semester Ganjil -->
                            <div style="margin-bottom: 10px;">
                                <strong>Semester Ganjil:</strong>
                                <?php if ($id_role == 3): ?>
                                    <div class="poin-box-mobile semester-ganjil" style="margin-top: 8px;">
                                        <?php
                                            $listPoinGanjil = [];
                                            foreach ($bulanGanjil as $bln) {
                                                $poin = $poinBulanan[$row['nis']][$bln] ?? 0;
                                                $listPoinGanjil[] = "<strong>$bln:</strong> $poin";
                                            }
                                            echo implode(' | ', $listPoinGanjil);
                                        ?>
                                    </div>
                                <?php else: ?>
                                    <div class="btn-month-container">
                                        <?php foreach ($bulanGanjil as $bln): ?>
                                            <button class="btn btn-month" data-nis="<?= esc($row['nis']) ?>" data-bulan="<?= $bln ?>" data-semester="ganjil">
                                                <?= $bln ?>
                                            </button>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Semester Genap -->
                            <div>
                                <strong>Semester Genap:</strong>
                                <?php if ($id_role == 3): ?>
                                    <div class="poin-box-mobile semester-genap" style="margin-top: 8px;">
                                        <?php
                                            $listPoinGenap = [];
                                            foreach ($bulanGenap as $bln) {
                                                $poin = $poinBulanan[$row['nis']][$bln] ?? 0;
                                                $listPoinGenap[] = "<strong>$bln:</strong> $poin";
                                            }
                                            echo implode(' | ', $listPoinGenap);
                                        ?>
                                    </div>
                                <?php else: ?>
                                    <div class="btn-month-container">
                                        <?php foreach ($bulanGenap as $bln): ?>
                                            <button class="btn btn-month <?= $semesterAktif === 'genap' ? '' : 'btn-disabled' ?>" 
                                                    data-nis="<?= esc($row['nis']) ?>" data-bulan="<?= $bln ?>" data-semester="genap">
                                                <?= $bln ?>
                                            </button>
                                        <?php endforeach; ?>
                                    </div>
                                    
                                    <?php if ($semesterAktif !== 'genap'): ?>
                                        <div class="semester-warning">
                                            <i class="fas fa-lock"></i> Semester Genap akan dimulai bulan Januari
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td>
                            <?= getStatusSP($poinGanjil[$row['nis']] ?? 0, $id_role, $row['nis'], $tahun_ajaran, 'ganjil') ?>
                        </td>
                        <td>
                            <?= getStatusSP($poinGenap[$row['nis']] ?? 0, $id_role, $row['nis'], $tahun_ajaran, 'genap') ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="7" style="text-align:center; color:#777;">Belum ada data pelanggaran.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Mobile Cards -->
    <div class="mobile-cards">
        <?php if(!empty($pelanggaran)): ?>
            <?php foreach($pelanggaran as $row): ?>
                <div class="mobile-card">
                    <div class="mobile-card-header">
                        <h3 class="mobile-card-title"><?= esc($row['nama_santri']) ?></h3>
                        <div class="mobile-card-nis">NIS: <?= esc($row['nis']) ?></div>
                    </div>
                    
                    <!-- Total Poin -->
                    <div class="mobile-card-section">
                        <h4>📊 Total Poin</h4>
                        <div class="mobile-card-row">
                            <span class="mobile-card-label">Semester Ganjil:</span>
                            <span class="mobile-card-value"><?= $poinGanjil[$row['nis']] ?? 0 ?></span>
                        </div>
                        <div class="mobile-card-row">
                            <span class="mobile-card-label">Semester Genap:</span>
                            <span class="mobile-card-value"><?= $poinGenap[$row['nis']] ?? 0 ?></span>
                        </div>
                    </div>

                    <!-- Poin Bulanan -->
                    <div class="mobile-card-section">
                        <h4>📅 Poin Bulanan</h4>
                        
                        <!-- Semester Ganjil -->
                        <div style="margin-bottom: 15px;">
                            <strong style="color: #3498db;">Semester Ganjil:</strong>
                            <?php if ($id_role == 3): ?>
                                <div class="poin-box-mobile semester-ganjil">
                                    <?php
                                        $listPoinGanjil = [];
                                        foreach ($bulanGanjil as $bln) {
                                            $poin = $poinBulanan[$row['nis']][$bln] ?? 0;
                                            $listPoinGanjil[] = "<strong>$bln:</strong> $poin";
                                        }
                                        echo implode(' | ', $listPoinGanjil);
                                    ?>
                                </div>
                            <?php else: ?>
                                <div class="mobile-buttons">
                                    <?php foreach ($bulanGanjil as $bln): ?>
                                        <button class="mobile-btn-month" 
                                                data-nis="<?= esc($row['nis']) ?>" 
                                                data-bulan="<?= $bln ?>" 
                                                data-semester="ganjil">
                                            <?= $bln ?> - Poin: <?= $poinBulanan[$row['nis']][$bln] ?? 0 ?>
                                        </button>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Semester Genap -->
                        <div>
                            <strong style="color: #e74c3c;">Semester Genap:</strong>
                            <?php if ($id_role == 3): ?>
                                <div class="poin-box-mobile semester-genap">
                                    <?php
                                        $listPoinGenap = [];
                                        foreach ($bulanGenap as $bln) {
                                            $poin = $poinBulanan[$row['nis']][$bln] ?? 0;
                                            $listPoinGenap[] = "<strong>$bln:</strong> $poin";
                                        }
                                        echo implode(' | ', $listPoinGenap);
                                    ?>
                                </div>
                            <?php else: ?>
                                <div class="mobile-buttons">
                                    <?php foreach ($bulanGenap as $bln): ?>
                                        <button class="mobile-btn-month <?= $semesterAktif === 'genap' ? '' : 'btn-disabled' ?>" 
                                                data-nis="<?= esc($row['nis']) ?>" 
                                                data-bulan="<?= $bln ?>" 
                                                data-semester="genap">
                                            <?= $bln ?> - Poin: <?= $poinBulanan[$row['nis']][$bln] ?? 0 ?>
                                        </button>
                                    <?php endforeach; ?>
                                </div>
                                <?php if ($semesterAktif !== 'genap'): ?>
                                    <div style="background: #fff3cd; color: #856404; padding: 8px; border-radius: 5px; margin-top: 8px; font-size: 0.8rem; text-align: center;">
                                        <i class="fas fa-lock"></i> Semester Genap akan dimulai bulan Januari
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="mobile-card-section">
                        <h4>📈 Status Pelanggaran</h4>
                        <div class="mobile-card-row">
                            <span class="mobile-card-label">Status Ganjil:</span>
                            <div class="mobile-status <?= getStatusClassMobile($poinGanjil[$row['nis']] ?? 0) ?>">
                                <?= getStatusTextMobile($poinGanjil[$row['nis']] ?? 0) ?>
                            </div>
                        </div>
                        <div class="mobile-card-row">
                            <span class="mobile-card-label">Status Genap:</span>
                            <div class="mobile-status <?= getStatusClassMobile($poinGenap[$row['nis']] ?? 0) ?>">
                                <?= getStatusTextMobile($poinGenap[$row['nis']] ?? 0) ?>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons untuk Role 2 -->
                    <?php if ($id_role == 2): ?>
                        <div class="mobile-card-section">
                            <h4>🛠️ Aksi</h4>
                            <div class="mobile-buttons">
                                <?php if(($poinGanjil[$row['nis']] ?? 0) >= 100): ?>
                                    <a href="<?= base_url('/sp1/' . esc($row['nis'])) ?>?tahun_ajaran=<?= $tahun_ajaran ?>&semester=ganjil" class="mobile-btn-month" style="background: #f39c12;">
                                        <i class="fas fa-print"></i> Cetak SP1 Ganjil
                                    </a>
                                <?php endif; ?>
                                <?php if(($poinGanjil[$row['nis']] ?? 0) >= 200): ?>
                                    <a href="<?= base_url('/sp2/' . esc($row['nis'])) ?>?tahun_ajaran=<?= $tahun_ajaran ?>&semester=ganjil" class="mobile-btn-month" style="background: #e74c3c;">
                                        <i class="fas fa-print"></i> Cetak SP2 Ganjil
                                    </a>
                                <?php endif; ?>
                                <?php if(($poinGanjil[$row['nis']] ?? 0) >= 300): ?>
                                    <a href="<?= base_url('/sp3/' . esc($row['nis'])) ?>?tahun_ajaran=<?= $tahun_ajaran ?>&semester=ganjil" class="mobile-btn-month" style="background: #c0392b;">
                                        <i class="fas fa-print"></i> Cetak SP3 Ganjil
                                    </a>
                                <?php endif; ?>
                                <?php if(($poinGenap[$row['nis']] ?? 0) >= 100): ?>
                                    <a href="<?= base_url('/sp1/' . esc($row['nis'])) ?>?tahun_ajaran=<?= $tahun_ajaran ?>&semester=genap" class="mobile-btn-month" style="background: #f39c12;">
                                        <i class="fas fa-print"></i> Cetak SP1 Genap
                                    </a>
                                <?php endif; ?>
                                <?php if(($poinGenap[$row['nis']] ?? 0) >= 200): ?>
                                    <a href="<?= base_url('/sp2/' . esc($row['nis'])) ?>?tahun_ajaran=<?= $tahun_ajaran ?>&semester=genap" class="mobile-btn-month" style="background: #e74c3c;">
                                        <i class="fas fa-print"></i> Cetak SP2 Genap
                                    </a>
                                <?php endif; ?>
                                <?php if(($poinGenap[$row['nis']] ?? 0) >= 300): ?>
                                    <a href="<?= base_url('/sp3/' . esc($row['nis'])) ?>?tahun_ajaran=<?= $tahun_ajaran ?>&semester=genap" class="mobile-btn-month" style="background: #c0392b;">
                                        <i class="fas fa-print"></i> Cetak SP3 Genap
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="mobile-card" style="text-align: center; color: #777;">
                <i class="fas fa-inbox" style="font-size: 3rem; margin-bottom: 15px; opacity: 0.5;"></i>
                <h3>Belum ada data pelanggaran</h3>
                <p>Data pelanggaran santri akan muncul di sini</p>
            </div>
        <?php endif; ?>
    </div>
</main>

<footer>
    &copy; <?= date('Y') ?> E - Mahkamah | Dirancang Oleh Santri PTD
    <br>
    <small>Server Time: <?= date('Y-m-d H:i:s') ?> | Browser Time: <span id="footerBrowserTime"></span></small>
</footer>

<!-- Modal Settings -->
<div id="settingsModal" class="modal">
    <div class="modal-content">
        <h2>Pengaturan</h2>
        <form action="/update_settings" method="post">
            <label>Username Baru:</label>
            <input type="text" name="username" placeholder="Masukkan username baru" value="<?= esc($username ?? '') ?>">
            <label>Password Baru:</label>
            <input type="password" name="password" placeholder="Masukkan password baru">
            <div class="modal-buttons">
                <button type="submit" class="btn">Save</button>
                <button type="button" class="btn-danger" id="closeSettings">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Bulanan -->
<div id="bulanModal" class="modal">
    <div class="modal-content">
        <h2 id="modalTitle">Poin Bulan</h2>
        <p id="modalTotal">Total Poin: 0</p>
        <p id="modalKeterangan">Keterangan: -</p>
        <button type="button" class="btn" id="btnAddPoin">➕ Add</button>
        <button type="button" class="btn btn-danger" id="closeBulanModal">Tutup</button>
    </div>
</div>

<script>
// ===== GLOBAL VARIABLES =====
let currentNis = '', currentBulan = '', currentSemester = '';
let navbarHidden = false;
let lastScrollTop = 0;
const scrollThreshold = 100;

// ===== HOSTING-READY NAVBAR STATE MANAGEMENT =====
function saveNavbarState(isHidden) {
    try {
        if (typeof(Storage) === "undefined") {
            console.warn('Web Storage not supported');
            return false;
        }
        
        localStorage.setItem('navbarHidden', isHidden);
        console.log('✅ Navbar state saved:', isHidden, 'on', window.location.hostname);
        return true;
        
    } catch (error) {
        console.warn('❌ localStorage failed, using memory fallback:', error);
        window.navbarMemoryState = isHidden;
        return false;
    }
}

function loadNavbarState() {
    try {
        if (typeof(Storage) === "undefined") {
            return window.navbarMemoryState || false;
        }
        
        const savedState = localStorage.getItem('navbarHidden');
        if (savedState !== null) {
            console.log('✅ Navbar state loaded:', savedState === 'true');
            return savedState === 'true';
        }
        
    } catch (error) {
        console.warn('❌ localStorage load failed:', error);
    }
    
    if (typeof window.navbarMemoryState !== 'undefined') {
        return window.navbarMemoryState;
    }
    
    return false;
}

function applyNavbarState() {
    const navbar = document.querySelector('.navbar-top');
    const navbarToggle = document.getElementById('navbarToggle');
    const navbarIcon = navbarToggle ? navbarToggle.querySelector('i') : null;
    const mainContent = document.querySelector('.main-content');
    
    if (navbar && navbarToggle && navbarIcon && mainContent) {
        if (navbarHidden) {
            navbar.classList.add('hide');
            navbarIcon.classList.replace('fa-angle-up', 'fa-angle-down');
            // Content naik
            mainContent.style.marginTop = '-80px';
        } else {
            navbar.classList.remove('hide');
            navbarIcon.classList.replace('fa-angle-down', 'fa-angle-up');
            // Content turun ke posisi normal
            mainContent.style.marginTop = '0';
        }
    }
}

function toggleNavbar() {
    const navbar = document.querySelector('.navbar-top');
    const navbarToggle = document.getElementById('navbarToggle');
    const navbarIcon = navbarToggle ? navbarToggle.querySelector('i') : null;
    const mainContent = document.querySelector('.main-content');
    
    if (navbar && navbarToggle && navbarIcon && mainContent) {
        navbarHidden = !navbarHidden;
        
        if (navbarHidden) {
            navbar.classList.add('hide');
            navbarIcon.classList.replace('fa-angle-up', 'fa-angle-down');
            // Smooth transition untuk content
            mainContent.style.transition = 'margin-top 0.4s ease';
            mainContent.style.marginTop = '-80px';
        } else {
            navbar.classList.remove('hide');
            navbarIcon.classList.replace('fa-angle-down', 'fa-angle-up');
            // Smooth transition untuk content
            mainContent.style.transition = 'margin-top 0.4s ease';
            mainContent.style.marginTop = '0';
        }
        
        saveNavbarState(navbarHidden);
        console.log('Navbar state saved:', navbarHidden);
    }
}

// ===== FLOATING BUTTON SCROLL BEHAVIOR =====
function handleScroll() {
    const navbarToggle = document.getElementById('navbarToggle');
    if (!navbarToggle) return;
    
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    const scrollDirection = scrollTop > lastScrollTop ? 'down' : 'up';
    
    // Hide when scrolling down and not at top
    if (scrollDirection === 'down' && scrollTop > scrollThreshold) {
        navbarToggle.classList.remove('visible');
        navbarToggle.classList.add('hidden');
    } 
    // Show when scrolling up
    else if (scrollDirection === 'up') {
        navbarToggle.classList.remove('hidden');
        navbarToggle.classList.add('visible');
    }
    // Always show at very top
    else if (scrollTop <= scrollThreshold) {
        navbarToggle.classList.remove('hidden');
        navbarToggle.classList.add('visible');
    }
    
    lastScrollTop = scrollTop;
}

function initScrollBehavior() {
    const navbarToggle = document.getElementById('navbarToggle');
    if (navbarToggle) {
        navbarToggle.classList.add('visible');
    }
    
    // Throttled scroll event
    let ticking = false;
    window.addEventListener('scroll', function() {
        if (!ticking) {
            requestAnimationFrame(function() {
                handleScroll();
                ticking = false;
            });
            ticking = true;
        }
    });
    
    // Show button when user stops scrolling
    let scrollEndTimer;
    window.addEventListener('scroll', function() {
        clearTimeout(scrollEndTimer);
        scrollEndTimer = setTimeout(function() {
            const navbarToggle = document.getElementById('navbarToggle');
            if (navbarToggle) {
                navbarToggle.classList.remove('hidden');
                navbarToggle.classList.add('visible');
            }
        }, 1500);
    });
}

// ===== INITIALIZE EVERYTHING WHEN DOM LOADED =====
document.addEventListener('DOMContentLoaded', function() {
    console.log('=== DASHBOARD INITIALIZATION ===');
    console.log('🌐 Host:', window.location.hostname);
    console.log('🔧 Storage supported:', typeof(Storage) !== "undefined");
    
    // ===== LOAD NAVBAR STATE =====
    navbarHidden = loadNavbarState();
    console.log('🎯 Initial navbar state:', navbarHidden);
    applyNavbarState();
    
    // ===== INIT FLOATING BUTTON SCROLL =====
    initScrollBehavior();
    
    // ===== THEME SYSTEM =====
    const themeToggle = document.getElementById('themeToggle');
    const themeIcon = document.getElementById('themeIcon');
    const themeLabel = document.getElementById('themeLabel');
    
    function toggleTheme() {
        const isDark = document.body.classList.toggle('dark-mode');
        localStorage.setItem('themeMode', isDark ? 'dark' : 'light');
        updateThemeIcon(isDark);
    }
    
    function updateThemeIcon(isDark) {
        if (themeIcon && themeLabel) {
            if (isDark) {
                themeIcon.className = 'fas fa-sun';
                themeLabel.textContent = 'Pindah Ke Mode Terang';
            } else {
                themeIcon.className = 'fas fa-moon';
                themeLabel.textContent = 'Pindah Ke Mode Gelap';
            }
        }
    }
    
    const savedTheme = localStorage.getItem('themeMode') || 'light';
    if (savedTheme === 'dark') {
        document.body.classList.add('dark-mode');
    }
    updateThemeIcon(savedTheme === 'dark');
    
    if (themeToggle) {
        themeToggle.addEventListener('click', toggleTheme);
    }

    // ===== SETTINGS MODAL =====
    const btnSettings = document.getElementById('btnSettings');
    const modalSettings = document.getElementById('settingsModal');
    const closeSettings = document.getElementById('closeSettings');

    if (btnSettings && modalSettings && closeSettings) {
        btnSettings.addEventListener('click', function(e) {
            e.preventDefault();
            modalSettings.classList.add('active');
        });

        closeSettings.addEventListener('click', function() {
            modalSettings.classList.remove('active');
        });

        modalSettings.addEventListener('click', function(e) {
            if (e.target === modalSettings) {
                modalSettings.classList.remove('active');
            }
        });
    }

    // ===== BULAN MODAL SYSTEM =====
    const bulanModal = document.getElementById('bulanModal');
    const closeBulanBtn = document.getElementById('closeBulanModal');
    const modalTitle = document.getElementById('modalTitle');
    const modalTotal = document.getElementById('modalTotal');
    const modalKeterangan = document.getElementById('modalKeterangan');
    const btnAddPoin = document.getElementById('btnAddPoin');

    // Event delegation untuk tombol bulan
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-month') || e.target.classList.contains('mobile-btn-month')) {
            e.preventDefault();
            
            const btn = e.target;
            if (btn.classList.contains('btn-disabled')) {
                alert('Semester Genap belum dimulai. Silakan tunggu hingga bulan Januari.');
                return;
            }
            
            const nis = btn.dataset.nis;
            const bulan = btn.dataset.bulan;
            const semester = btn.dataset.semester;
            
            if (semester === 'genap') {
                const currentMonth = new Date().getMonth() + 1;
                if (currentMonth < 1 || currentMonth > 6) {
                    alert('Semester Genap belum dimulai. Silakan tunggu hingga bulan Januari.');
                    return;
                }
            }

            let namaSantri = 'Unknown';
            if (btn.classList.contains('btn-month')) {
                const row = btn.closest('tr');
                const namaSantriCell = row.querySelector('td:nth-child(2)');
                namaSantri = namaSantriCell ? namaSantriCell.textContent : 'Unknown';
            } else {
                const card = btn.closest('.mobile-card');
                namaSantri = card ? card.querySelector('.mobile-card-title').textContent : 'Unknown';
            }
            
            currentNis = nis;
            currentBulan = bulan;
            currentSemester = semester;

            if (modalTitle) modalTitle.textContent = `Poin ${bulan} - ${namaSantri} (Semester ${semester === 'ganjil' ? 'Ganjil' : 'Genap'})`;
            if (modalTotal) modalTotal.textContent = `Total Poin: ...`;
            if (modalKeterangan) modalKeterangan.textContent = `Keterangan: ...`;
            
            if (bulanModal) {
                bulanModal.classList.add('active');
            }

            fetch(`/pelanggaran/getPoinBulan/${nis}/${bulan}`)
                .then(res => {
                    if (!res.ok) throw new Error('Network response was not ok');
                    return res.json();
                })
                .then(data => {
                    if (modalTotal) modalTotal.textContent = `Total Poin: ${data.poin || 0}`;
                    if (modalKeterangan) modalKeterangan.textContent = `Keterangan: ${data.keterangan || '-'}`;
                })
                .catch(err => {
                    console.error('Error fetch poin:', err);
                    if (modalTotal) modalTotal.textContent = 'Total Poin: 0';
                    if (modalKeterangan) modalKeterangan.textContent = 'Keterangan: -';
                });
        }
    });

    if (btnAddPoin) {
        btnAddPoin.addEventListener('click', function() {
            if (currentNis && currentBulan) {
                window.location.href = `/pelanggaran/update_pelanggaran/${currentNis}/${currentBulan}`;
            } else {
                alert('Pilih bulan terlebih dahulu!');
            }
        });
    }

    if (closeBulanBtn) {
        closeBulanBtn.addEventListener('click', function() {
            if (bulanModal) bulanModal.classList.remove('active');
        });
    }

    if (bulanModal) {
        bulanModal.addEventListener('click', function(e) {
            if (e.target === bulanModal) {
                bulanModal.classList.remove('active');
            }
        });
    }

    // ===== NAVBAR TOGGLE SYSTEM =====
    const navbarToggle = document.getElementById('navbarToggle');
    
    if (navbarToggle) {
        navbarToggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            toggleNavbar();
        });
    }

    // ===== TAHUN AJARAN DROPDOWN =====
    const tahunAjaranSelect = document.getElementById('tahunAjaranSelect');
    if (tahunAjaranSelect) {
        tahunAjaranSelect.addEventListener('change', function() {
            const tahun = this.value;
            window.location.href = '<?= base_url('/dashboard') ?>?tahun_ajaran=' + tahun;
        });
    }

    // ===== SEARCH FUNCTIONALITY =====
    const searchInput = document.getElementById('searchInput');
    const noResults = document.getElementById('noResults');
    const desktopTable = document.querySelector('.desktop-table tbody');
    const mobileCards = document.querySelector('.mobile-cards');
    
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase().trim();
            let resultsCount = 0;
            
            if (desktopTable) {
                const rows = desktopTable.querySelectorAll('tr');
                rows.forEach(row => {
                    const nis = row.cells[0]?.textContent.toLowerCase() || '';
                    const nama = row.cells[1]?.textContent.toLowerCase() || '';
                    
                    if (nis.includes(searchTerm) || nama.includes(searchTerm)) {
                        row.style.display = '';
                        resultsCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });
            }
            
            if (mobileCards) {
                const cards = mobileCards.querySelectorAll('.mobile-card');
                cards.forEach(card => {
                    const title = card.querySelector('.mobile-card-title')?.textContent.toLowerCase() || '';
                    const nis = card.querySelector('.mobile-card-nis')?.textContent.toLowerCase() || '';
                    
                    if (title.includes(searchTerm) || nis.includes(searchTerm)) {
                        card.style.display = '';
                        resultsCount++;
                    } else {
                        card.style.display = 'none';
                    }
                });
            }
            
            if (noResults) {
                if (searchTerm && resultsCount === 0) {
                    noResults.style.display = 'block';
                } else {
                    noResults.style.display = 'none';
                }
            }
        });
        
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                searchInput.value = '';
                searchInput.dispatchEvent(new Event('input'));
                searchInput.blur();
            }
        });
    }

    console.log('=== INITIALIZATION COMPLETE ===');
});

// ===== CHANGE TAHUN AJARAN FUNCTION =====
function changeTahunAjaran(tahun) {
    const currentYear = new Date().getFullYear();
    const currentMonth = new Date().getMonth() + 1;
    
    if (tahun === '2026/2027' && (currentYear < 2026 || (currentYear === 2026 && currentMonth < 7))) {
        showAccessDeniedModal(tahun);
        setTimeout(() => {
            const select = document.getElementById('tahunAjaranSelect');
            if (select) select.value = '2025/2026';
        }, 100);
        return false;
    }
    
    window.location.href = '<?= base_url('/dashboard') ?>?tahun_ajaran=' + tahun;
}

// ===== ACCESS DENIED MODAL =====
function showAccessDeniedModal(tahun) {
    const modal = document.createElement('div');
    modal.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1000;
    `;
    
    modal.innerHTML = `
        <div style="
            background: white;
            padding: 25px;
            border-radius: 15px;
            text-align: center;
            max-width: 450px;
            margin: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        ">
            <div style="font-size: 48px; color: #f39c12; margin-bottom: 15px;">⏳</div>
            <h3 style="color: #f39c12; margin-bottom: 15px;">Akses Belum Tersedia</h3>
            <p style="margin-bottom: 20px; line-height: 1.5; color: #555;">
                Tahun ajaran <strong>${tahun}</strong> belum dapat diakses. 
                <br><br>
                <strong>Akses akan dibuka mulai Juli 2026</strong>
                <br>
                <small style="color: #777;">
                    Saat ini: ${new Date().toLocaleDateString('id-ID', { month: 'long', year: 'numeric' })}
                </small>
            </p>
            <div style="margin-top: 20px;">
                <button onclick="closeAccessModal()" style="
                    padding: 12px 25px;
                    background: #3498db;
                    color: white;
                    border: none;
                    border-radius: 5px;
                    cursor: pointer;
                    font-size: 14px;
                    font-weight: bold;
                ">
                    <i class="fas fa-arrow-left"></i> Kembali ke 2025/2026
                </button>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    
    window.closeAccessModal = function() {
        document.body.removeChild(modal);
    };
}

// ===== BROWSER TIME =====
function updateBrowserTime() {
    const now = new Date();
    const browserTimeEl = document.getElementById('footerBrowserTime');
    if (browserTimeEl) {
        browserTimeEl.textContent = now.toLocaleString('id-ID');
    }
}

setInterval(updateBrowserTime, 1000);
updateBrowserTime();

console.log('JavaScript loaded successfully');
</script>
</body>
</html>

<?php
// Helper function untuk status SP di desktop
function getStatusSP($poin, $role, $nis, $tahun_ajaran, $semester) {
    if($poin >= 300) {
        if ($role == 2) {
            return '<a href="' . base_url('/sp3/' . $nis) . '?tahun_ajaran=' . $tahun_ajaran . '&semester=' . $semester . '" class="btn" style="background: #c0392b; margin-bottom: 5px;">
                <i class="fas fa-print"></i> Cetak SP3
            </a>';
        } else {
            return '<div style="background: #fce4ec; color: #c2185b; padding: 8px; border-radius: 5px; text-align: center; font-weight: bold;">
                <i class="fas fa-radiation"></i> SP3 Aktif
            </div>';
        }
    } elseif($poin >= 200) {
        if ($role == 2) {
            return '<a href="' . base_url('/sp2/' . $nis) . '?tahun_ajaran=' . $tahun_ajaran . '&semester=' . $semester . '" class="btn" style="background: #e74c3c; margin-bottom: 5px;">
                <i class="fas fa-print"></i> Cetak SP2
            </a>';
        } else {
            return '<div style="background: #ffebee; color: #e74c3c; padding: 8px; border-radius: 5px; text-align: center; font-weight: bold;">
                <i class="fas fa-exclamation-circle"></i> SP2 Aktif
            </div>';
        }
    } elseif($poin >= 100) {
        if ($role == 2) {
            return '<a href="' . base_url('/sp1/' . $nis) . '?tahun_ajaran=' . $tahun_ajaran . '&semester=' . $semester . '" class="btn" style="background: #f39c12; margin-bottom: 5px;">
                <i class="fas fa-print"></i> Cetak SP1
            </a>';
        } else {
            return '<div style="background: #fff8e1; color: #f39c12; padding: 8px; border-radius: 5px; text-align: center; font-weight: bold;">
                <i class="fas fa-exclamation-triangle"></i> SP1 Aktif
            </div>';
        }
    } else {
        return '<span style="color: #27ae60; font-weight: bold;">Aman</span>';
    }
}

// Helper function untuk status di mobile view
function getStatusClassMobile($poin) {
    if ($poin >= 300) return 'status-sp3';
    if ($poin >= 200) return 'status-sp2';
    if ($poin >= 100) return 'status-sp1';
    return 'status-aman';
}

function getStatusTextMobile($poin) {
    if ($poin >= 300) return 'SP3 Aktif';
    if ($poin >= 200) return 'SP2 Aktif';
    if ($poin >= 100) return 'SP1 Aktif';
    return 'Aman';
}
?>