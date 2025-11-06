<?php
date_default_timezone_set('Asia/Jakarta');
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Mahkamah - Monitoring Pelanggaran Santri</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
:root {
    --primary: #2563eb;
    --primary-dark: #1d4ed8;
    --secondary: #475569;
    --accent: #dc2626;
    --accent-light: #ef4444;
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
    font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
    min-height: 100vh;
    color: #334155;
    line-height: 1.6;
    -webkit-font-smoothing: antialiased;
}

.container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 20px;
}

/* Header Styles */
.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
    padding: 24px;
    background: rgba(255, 255, 255, 0.95);
    border-radius: 20px;
    box-shadow: var(--shadow);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.8);
    position: relative;
    overflow: hidden;
}

.header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--gradient-primary);
}

.header-content h1 {
    margin: 0;
    font-size: 2rem;
    font-weight: 800;
    background: linear-gradient(135deg, var(--primary), #7c3aed);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    letter-spacing: -0.5px;
}

.header-content .subtitle {
    color: var(--gray);
    font-size: 1rem;
    margin-top: 6px;
    font-weight: 500;
}

.btn {
    padding: 14px 24px;
    background: var(--gradient-primary);
    color: white;
    text-decoration: none;
    border-radius: 12px;
    border: none;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    box-shadow: 0 4px 14px 0 rgba(59, 130, 246, 0.4);
    font-size: 0.95rem;
    position: relative;
    overflow: hidden;
}

.btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s ease;
}

.btn:hover::before {
    left: 100%;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(59, 130, 246, 0.5);
}

.btn-admin {
    background: var(--gradient-success);
    box-shadow: 0 4px 14px 0 rgba(16, 185, 129, 0.4);
}

.btn-admin:hover {
    box-shadow: 0 8px 25px rgba(16, 185, 129, 0.5);
}

/* NAVBAR STATS - ELEGAN */
.navbar-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    margin-bottom: 24px;
    padding: 0;
}

.stat-item {
    background: rgba(255, 255, 255, 0.9);
    padding: 20px;
    border-radius: 16px;
    text-align: center;
    box-shadow: var(--shadow);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.8);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.stat-item::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 4px;
    background: var(--gradient-primary);
}

.stat-item:hover {
    transform: translateY(-4px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.stat-number {
    display: block;
    font-size: 2rem;
    font-weight: 800;
    color: var(--dark);
    margin-bottom: 6px;
    background: linear-gradient(135deg, var(--primary), #7c3aed);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.stat-label {
    font-size: 0.85rem;
    color: var(--gray);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* SEMESTER INFO BAR - ELEGAN */
.semester-info-bar {
    background: var(--gradient-primary);
    color: white;
    padding: 20px 24px;
    border-radius: 16px;
    margin-bottom: 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 16px;
    box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3);
    position: relative;
    overflow: hidden;
}

.semester-info-bar::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
}

.semester-title {
    font-weight: 700;
    font-size: 1.1rem;
    display: flex;
    align-items: center;
    gap: 10px;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.semester-title i {
    font-size: 1.2rem;
}

.pelanggaran-summary {
    background: rgba(255, 255, 255, 0.15);
    padding: 10px 20px;
    border-radius: 20px;
    font-size: 0.95rem;
    font-weight: 600;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    gap: 12px;
    transition: all 0.3s ease;
}

.pelanggaran-summary:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: translateY(-1px);
}

.total-poin-badge {
    background: linear-gradient(135deg, #fbbf24, #f59e0b);
    color: #78350f;
    padding: 6px 14px;
    border-radius: 20px;
    font-weight: 700;
    font-size: 0.9rem;
    box-shadow: 0 4px 12px rgba(251, 191, 36, 0.3);
    border: 1px solid rgba(255, 255, 255, 0.3);
}

/* Search Styles */
.search-container {
    margin: 0 auto 24px auto;
    position: relative;
    max-width: 500px;
}

.search-box {
    width: 100%;
    padding: 16px 50px 16px 20px;
    border: 2px solid rgba(59, 130, 246, 0.2);
    border-radius: 50px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(10px);
    color: var(--dark);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

.search-box:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1), 0 8px 20px rgba(0, 0, 0, 0.1);
    background: white;
}

.search-icon {
    position: absolute;
    right: 20px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--gray);
    pointer-events: none;
    font-size: 1.1rem;
}

.search-results {
    margin-top: 8px;
    font-size: 0.9rem;
    color: var(--gray);
    font-weight: 500;
    text-align: center;
}

/* Cards Grid */
.cards-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
    gap: 24px;
    margin-bottom: 30px;
}

.santri-card {
    background: rgba(255, 255, 255, 0.9);
    border-radius: 20px;
    padding: 24px;
    box-shadow: var(--shadow);
    border: 1px solid rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(10px);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.santri-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--gradient-primary);
}

.santri-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
}

.card-header {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 20px;
    padding-bottom: 16px;
    border-bottom: 2px solid var(--border);
}

.photo-container {
    position: relative;
}

.santri-photo {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 16px;
    border: 3px solid white;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    transition: all 0.3s ease;
}

.photo-placeholder {
    width: 80px;
    height: 80px;
    background: var(--gradient-primary);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    font-size: 1.8rem;
    border: 3px solid white;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
}

.card-info h3 {
    margin: 0;
    color: var(--dark);
    font-size: 1.3rem;
    font-weight: 700;
    letter-spacing: -0.5px;
}

.card-nis {
    color: var(--gray);
    font-size: 0.9rem;
    margin-top: 4px;
    font-weight: 500;
}

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    margin-bottom: 20px;
}

.stat-box {
    background: var(--light);
    padding: 16px;
    border-radius: 12px;
    text-align: center;
    border-left: 4px solid var(--primary);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
}

.stat-box:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
}

.stat-value {
    display: block;
    font-size: 1.5rem;
    font-weight: 800;
    color: var(--dark);
    margin-bottom: 4px;
}

.stat-label {
    font-size: 0.8rem;
    color: var(--gray);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Poin Bulanan Styles */
.poin-bulanan-section {
    background: var(--light);
    padding: 16px;
    border-radius: 12px;
    margin-bottom: 20px;
    border-left: 4px solid var(--warning);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

.poin-bulanan-title {
    font-size: 0.95rem;
    font-weight: 700;
    margin-bottom: 12px;
    color: var(--dark);
    display: flex;
    align-items: center;
    gap: 8px;
}

.bulanan-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
    gap: 8px;
    margin-top: 12px;
}

.bulanan-item {
    background: white;
    padding: 10px;
    border-radius: 8px;
    text-align: center;
    border: 1px solid var(--border);
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.bulanan-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.bulanan-bulan {
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--dark);
    margin-bottom: 4px;
}

.bulanan-poin {
    font-size: 1.1rem;
    font-weight: 800;
    color: var(--accent);
}

.semester-label {
    display: inline-block;
    padding: 4px 8px;
    background: var(--primary);
    color: white;
    border-radius: 6px;
    font-size: 0.7rem;
    font-weight: 700;
    margin-bottom: 8px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Status Badges */
.status-badges {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    margin-bottom: 20px;
}

.status-badge {
    padding: 12px;
    border-radius: 10px;
    text-align: center;
    font-size: 0.8rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.status-badge:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
}

.status-aman { 
    background: linear-gradient(135deg, #d1fae5, #a7f3d0);
    color: #065f46;
    border: 2px solid #10b981;
}
.status-sp1 { 
    background: linear-gradient(135deg, #fef3c7, #fde68a);
    color: #92400e;
    border: 2px solid #f59e0b;
}
.status-sp2 { 
    background: linear-gradient(135deg, #fecaca, #fca5a5);
    color: #991b1b;
    border: 2px solid #ef4444;
}
.status-sp3 { 
    background: linear-gradient(135deg, #fbcfe8, #f9a8d4);
    color: #831843;
    border: 2px solid #ec4899;
}

/* Action Button */
.btn-detail {
    width: 100%;
    background: var(--gradient-primary);
    color: white;
    border: none;
    padding: 16px;
    border-radius: 12px;
    cursor: pointer;
    font-size: 0.9rem;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    font-weight: 600;
    box-shadow: 0 4px 14px rgba(59, 130, 246, 0.4);
    position: relative;
    overflow: hidden;
}

.btn-detail:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(59, 130, 246, 0.5);
}

/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.8);
    backdrop-filter: blur(8px);
    z-index: 1000;
    justify-content: center;
    align-items: center;
    padding: 20px;
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.modal-content {
    background: white;
    padding: 30px;
    border-radius: 20px;
    width: 95%;
    max-width: 600px;
    max-height: 80vh;
    overflow-y: auto;
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
    animation: slideUp 0.3s ease;
}

@keyframes slideUp {
    from { transform: translateY(30px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
    padding-bottom: 16px;
    border-bottom: 2px solid var(--border);
}

.modal-title {
    margin: 0;
    color: var(--dark);
    font-size: 1.4rem;
    font-weight: 700;
}

.close-btn {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: var(--gray);
    transition: all 0.3s ease;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.close-btn:hover {
    color: var(--accent);
    background: rgba(239, 68, 68, 0.1);
}

.pelanggaran-list {
    max-height: 400px;
    overflow-y: auto;
}

.pelanggaran-item {
    padding: 16px;
    border-bottom: 1px solid var(--border);
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: background 0.3s ease;
    border-radius: 8px;
}

.pelanggaran-item:hover {
    background: var(--light);
}

.pelanggaran-item:last-child {
    border-bottom: none;
}

.pelanggaran-info {
    flex: 1;
}

.pelanggaran-desc {
    font-weight: 600;
    color: var(--dark);
    margin-bottom: 6px;
    font-size: 0.95rem;
}

.pelanggaran-meta {
    color: var(--gray);
    font-size: 0.85rem;
}

.pelanggaran-poin {
    background: var(--gradient-danger);
    color: white;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 700;
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
}

/* No Data State */
.no-data {
    text-align: center;
    padding: 60px 20px;
    color: var(--gray);
    background: rgba(255, 255, 255, 0.9);
    border-radius: 20px;
    backdrop-filter: blur(10px);
    box-shadow: var(--shadow);
}

.no-data i {
    font-size: 3.5rem;
    margin-bottom: 20px;
    opacity: 0.5;
}

.no-data h3 {
    margin-bottom: 12px;
    color: var(--dark);
    font-size: 1.5rem;
    font-weight: 700;
}

/* Highlight for search */
.highlight {
    background: linear-gradient(135deg, #fef3c7, #fde68a);
    color: #92400e;
    padding: 2px 4px;
    border-radius: 4px;
    font-weight: 600;
}

/* No Results Message */
.no-results {
    text-align: center;
    padding: 40px 20px;
    color: var(--gray);
    background: rgba(255, 255, 255, 0.9);
    border-radius: 16px;
    margin: 20px 0;
    display: none;
    box-shadow: var(--shadow);
}

/* Loading Animation */
.loading {
    text-align: center;
    padding: 40px;
    color: var(--gray);
}

.loading-spinner {
    border: 4px solid #f3f4f6;
    border-top: 4px solid var(--primary);
    border-radius: 50%;
    width: 40px;
    height: 40px;
    animation: spin 1s linear infinite;
    margin: 0 auto 16px;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Responsive Design */
@media (max-width: 1200px) {
    .cards-grid {
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    }
}

@media (max-width: 768px) {
    .container {
        padding: 16px;
    }
    
    .header {
        flex-direction: column;
        gap: 16px;
        text-align: center;
        padding: 20px;
    }
    
    .header-content h1 {
        font-size: 1.6rem;
    }
    
    .navbar-stats {
        grid-template-columns: 1fr;
        gap: 12px;
    }
    
    .semester-info-bar {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
        padding: 16px 20px;
    }
    
    .search-container {
        max-width: 100%;
        margin-bottom: 20px;
    }
    
    .cards-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .santri-card {
        padding: 20px;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
        gap: 12px;
    }

    .bulanan-grid {
        grid-template-columns: repeat(auto-fit, minmax(80px, 1fr));
    }
    
    .status-badges {
        grid-template-columns: 1fr;
    }
    
    .btn {
        padding: 12px 20px;
        font-size: 0.9rem;
    }
}

@media (max-width: 480px) {
    .container {
        padding: 12px;
    }
    
    .header {
        padding: 16px;
        margin-bottom: 20px;
    }
    
    .header-content h1 {
        font-size: 1.4rem;
    }
    
    .header-content .subtitle {
        font-size: 0.9rem;
    }
    
    .semester-info-bar {
        padding: 12px 16px;
    }
    
    .pelanggaran-summary {
        flex-direction: column;
        gap: 8px;
        text-align: center;
        padding: 8px 12px;
    }
    
    .card-header {
        gap: 12px;
    }
    
    .santri-photo, .photo-placeholder {
        width: 60px;
        height: 60px;
        font-size: 1.4rem;
    }
    
    .card-info h3 {
        font-size: 1.1rem;
    }
    
    .modal-content {
        padding: 20px;
    }
}

/* Smooth scrolling */
html {
    scroll-behavior: smooth;
}

/* Selection color */
::selection {
    background: rgba(59, 130, 246, 0.2);
    color: var(--dark);
}
</style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="header-content">
                <h1>🏛️ E - Mahkamah</h1>
                <div class="subtitle">Sistem Monitoring Pelanggaran Santri PTD Ar-Rahman</div>
            </div>
            <a href="<?= base_url('/login') ?>" class="btn btn-admin">
                <i class="fas fa-lock"></i> Admin Login
            </a>
        </div>

        <!-- Stats Navbar YANG SUDAH DIPERBAIKI -->
        <div class="navbar-stats">
            <div class="stat-item">
                <span class="stat-number"><?= $totalSantri ?? 0 ?></span>
                <div class="stat-label">Total Santri</div>
            </div>
            <div class="stat-item">
                <span class="stat-number"><?= $totalPoinGanjil + $totalPoinGenap ?? 0 ?></span>
                <div class="stat-label">Total Poin</div>
            </div>
            <div class="stat-item">
                <span class="stat-number"><?= $sp1CountGanjil + $sp1CountGenap ?? 0 ?></span>
                <div class="stat-label">SP1 Aktif</div>
            </div>
            <div class="stat-item">
                <span class="stat-number"><?= $sp2CountGanjil + $sp2CountGenap ?? 0 ?></span>
                <div class="stat-label">SP2 Aktif</div>
            </div>
            <div class="stat-item">
                <span class="stat-number"><?= $sp3CountGanjil + $sp3CountGenap ?? 0 ?></span>
                <div class="stat-label">SP3 Aktif</div>
            </div>
        </div>

        <!-- SEMESTER INFO BAR YANG SUDAH DIPERBAIKI -->
        <div class="semester-info-bar">
            <div class="semester-title">
                <i class="fas fa-calendar-alt"></i>
                Semester Ganjil 2025/2026
            </div>
            <div class="pelanggaran-summary">
    <?php
    // DEBUG: Cek data dari controller
    echo "<!-- DEBUG: ";
    print_r($poinGanjil);
    echo " -->";
    
    // Hitung total pelanggaran dan total poin dari semua santri untuk semester ganjil
    $totalPelanggaranGanjil = 0;
    $totalPoinGanjilAll = 0;
    
    if (!empty($santri)) {
        foreach ($santri as $row) {
            $nis = $row['nis'];
            $poinGanjilSantri = $poinGanjil[$nis] ?? 0;
            $totalPoinGanjilAll += $poinGanjilSantri;
            
            // DEBUG: Tampilkan per santri
            echo "<!-- DEBUG Santri {$nis}: {$poinGanjilSantri} poin -->";
            
            // Hitung jumlah pelanggaran
            if ($poinGanjilSantri > 0) {
                $totalPelanggaranGanjil++; 
            }
        }
    }
    
    // DEBUG: Tampilkan total
    echo "<!-- DEBUG Total: {$totalPelanggaranGanjil} pelanggaran, {$totalPoinGanjilAll} poin -->";
    ?>
    <span><?= $totalPelanggaranGanjil ?> pelanggaran (Total: <?= $totalPoinGanjilAll ?> Poin)</span>
</div>
        </div>

        <!-- Search Box -->
        <div class="search-container">
            <input type="text" id="searchInput" class="search-box" placeholder="Cari nama santri atau NIS..." autocomplete="off">
            <div class="search-icon">
                <i class="fas fa-search"></i>
            </div>
        </div>
        <div class="search-results" id="searchResults">
            Total <?= count($santri ?? []) ?> santri ditemukan
        </div>

        <!-- No Results Message -->
        <div id="noResults" class="no-results">
            <i class="fas fa-search" style="font-size: 2.5rem; margin-bottom: 12px; opacity: 0.5;"></i>
            <h3>Santri tidak ditemukan</h3>
            <p>Coba gunakan kata kunci yang berbeda</p>
        </div>

        <!-- Cards Grid -->
        <div class="cards-grid" id="cardsContainer">
            <?php if(!empty($santri)): ?>
                <?php foreach($santri as $row): ?>
                    <?php
                    $nis = $row['nis'];
                    $fotoFileName = $row['foto'] ?? null;
                    $hasPhoto = !empty($fotoFileName);
                    $initial = substr($row['nama_santri'], 0, 1);
                    $poinGanjilSantri = $poinGanjil[$nis] ?? 0;
                    $poinGenapSantri = $poinGenap[$nis] ?? 0;
                    
                    // Ambil data poin bulanan
                    $poinBulananGanjil = $poinBulanan[$nis]['ganjil'] ?? [];
                    $poinBulananGenap = $poinBulanan[$nis]['genap'] ?? [];
                    
                    // Tentukan status
                    $statusClassGanjil = ($poinGanjilSantri >= 300) ? 'status-sp3' : 
                                        (($poinGanjilSantri >= 200) ? 'status-sp2' : 
                                        (($poinGanjilSantri >= 100) ? 'status-sp1' : 'status-aman'));
                    $statusTextGanjil = ($poinGanjilSantri >= 300) ? 'SP3' : 
                                       (($poinGanjilSantri >= 200) ? 'SP2' : 
                                       (($poinGanjilSantri >= 100) ? 'SP1' : 'Aman'));
                    
                    $statusClassGenap = ($poinGenapSantri >= 300) ? 'status-sp3' : 
                                       (($poinGenapSantri >= 200) ? 'status-sp2' : 
                                       (($poinGenapSantri >= 100) ? 'status-sp1' : 'status-aman'));
                    $statusTextGenap = ($poinGenapSantri >= 300) ? 'SP3' : 
                                      (($poinGenapSantri >= 200) ? 'SP2' : 
                                      (($poinGenapSantri >= 100) ? 'SP1' : 'Aman'));
                    ?>
                    <div class="santri-card" data-nis="<?= $nis ?>" data-nama="<?= esc($row['nama_santri']) ?>">
                        <div class="card-header">
                            <div class="photo-container">
                                <?php if ($hasPhoto): 
                                    $photoPath = base_url('uploads/foto_santri/' . $fotoFileName);
                                ?>
                                    <img src="<?= $photoPath ?>" 
                                         alt="<?= esc($row['nama_santri']) ?>" 
                                         class="santri-photo"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <div class="photo-placeholder" style="display: none;">
                                        <?= $initial ?>
                                    </div>
                                <?php else: ?>
                                    <div class="photo-placeholder">
                                        <?= $initial ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="card-info">
                                <h3><?= esc($row['nama_santri']) ?></h3>
                                <div class="card-nis">NIS: <?= esc($row['nis']) ?></div>
                            </div>
                        </div>

                        <!-- Stats Grid -->
                        <div class="stats-grid">
                            <div class="stat-box">
                                <span class="stat-value"><?= $poinGanjilSantri ?></span>
                                <div class="stat-label">Poin Semester Ganjil</div>
                            </div>
                            <div class="stat-box">
                                <span class="stat-value"><?= $poinGenapSantri ?></span>
                                <div class="stat-label">Poin Semester Genap</div>
                            </div>
                        </div>

                        <!-- Poin Bulanan -->
                        <div class="poin-bulanan-section">
                            <div class="poin-bulanan-title">
                                <i class="fas fa-calendar-alt"></i> Poin Bulanan
                            </div>
                            
                            <!-- Semester Ganjil -->
                            <div class="semester-label" style="background: #3498db;">Ganjil</div>
                            <div class="bulanan-grid">
                                <?php 
                                $bulanGanjil = ['Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                                foreach ($bulanGanjil as $bulan): 
                                    $poin = $poinBulananGanjil[$bulan] ?? 0;
                                ?>
                                    <div class="bulanan-item">
                                        <div class="bulanan-bulan"><?= $bulan ?></div>
                                        <div class="bulanan-poin"><?= $poin ?></div>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <!-- Semester Genap -->
                            <div class="semester-label" style="background: #e74c3c; margin-top: 12px;">Genap</div>
                            <div class="bulanan-grid">
                                <?php 
                                $bulanGenap = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni'];
                                foreach ($bulanGenap as $bulan): 
                                    $poin = $poinBulananGenap[$bulan] ?? 0;
                                ?>
                                    <div class="bulanan-item">
                                        <div class="bulanan-bulan"><?= $bulan ?></div>
                                        <div class="bulanan-poin"><?= $poin ?></div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Status Badges -->
                        <div class="status-badges">
                            <div class="status-badge <?= $statusClassGanjil ?>">
                                Ganjil: <?= $statusTextGanjil ?>
                            </div>
                            <div class="status-badge <?= $statusClassGenap ?>">
                                Genap: <?= $statusTextGenap ?>
                            </div>
                        </div>

                        <!-- Detail Button -->
                        <button class="btn-detail" onclick="showDetail('<?= $nis ?>', '<?= esc($row['nama_santri']) ?>')">
                            <i class="fas fa-list-alt"></i> Lihat Detail Pelanggaran
                        </button>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-data">
                    <i class="fas fa-users-slash"></i>
                    <h3>Belum ada data santri</h3>
                    <p>Data santri akan muncul di sini</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modal Detail Pelanggaran -->
    <div id="detailModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="modalSantriName">Detail Pelanggaran</h2>
                <button class="close-btn" onclick="closeDetail()">&times;</button>
            </div>
            <div id="modalContent">
                <!-- Content akan diisi via JavaScript -->
            </div>
        </div>
    </div>

    <script>
    // JavaScript code remains the same as your original...
    // ===== SEARCH FUNCTIONALITY =====
    const searchInput = document.getElementById('searchInput');
    const searchResults = document.getElementById('searchResults');
    const noResults = document.getElementById('noResults');
    const cardsContainer = document.getElementById('cardsContainer');
    
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase().trim();
            const cards = document.querySelectorAll('.santri-card');
            let resultsCount = 0;
            
            cards.forEach(card => {
                const title = card.querySelector('.card-info h3')?.textContent.toLowerCase() || '';
                const nis = card.querySelector('.card-nis')?.textContent.toLowerCase() || '';
                
                if (title.includes(searchTerm) || nis.includes(searchTerm)) {
                    card.style.display = 'block';
                    resultsCount++;
                    
                    // Highlight matching text
                    if (searchTerm) {
                        highlightText(card.querySelector('.card-info h3'), searchTerm);
                        highlightText(card.querySelector('.card-nis'), searchTerm);
                    }
                } else {
                    card.style.display = 'none';
                }
            });
            
            // Update results counter
            if (searchResults) {
                searchResults.textContent = searchTerm ? 
                    `${resultsCount} santri ditemukan` : 
                    `Total ${resultsCount} santri ditemukan`;
                searchResults.style.color = searchTerm ? 
                    (resultsCount > 0 ? '#27ae60' : '#e74c3c') : 
                    'white';
            }
            
            // Show/hide no results message
            if (noResults) {
                if (searchTerm && resultsCount === 0) {
                    noResults.style.display = 'block';
                } else {
                    noResults.style.display = 'none';
                }
            }
            
            // Remove highlights when search is cleared
            if (!searchTerm) {
                removeHighlights();
            }
        });
        
        // Clear search on escape key
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                searchInput.value = '';
                searchInput.dispatchEvent(new Event('input'));
                searchInput.blur();
            }
        });
    }
    
    // Function to highlight text
    function highlightText(element, searchTerm) {
        const text = element.textContent;
        const regex = new RegExp(`(${searchTerm})`, 'gi');
        const highlighted = text.replace(regex, '<span class="highlight">$1</span>');
        element.innerHTML = highlighted;
    }
    
    // Function to remove all highlights
    function removeHighlights() {
        const highlights = document.querySelectorAll('.highlight');
        highlights.forEach(highlight => {
            const parent = highlight.parentNode;
            parent.textContent = parent.textContent;
        });
    }

    // ===== MODAL FUNCTIONS =====
    function showDetail(nis, nama) {
        console.log('Opening modal for:', nis, nama);
        
        document.getElementById('modalSantriName').textContent = `Detail Pelanggaran - ${nama}`;
        document.getElementById('modalContent').innerHTML = `
            <div class="loading">
                <div class="loading-spinner"></div>
                <p>Memuat data pelanggaran...</p>
            </div>
        `;
        document.getElementById('detailModal').style.display = 'flex';
        
        // Prevent background scroll
        document.body.style.overflow = 'hidden';
        
        // Fetch real data from API
        fetch(`/umum/getDetailPelanggaran/${nis}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log('Data received:', data);
                if (data.success) {
                    displayPelanggaranData(data.data, nis);
                } else {
                    document.getElementById('modalContent').innerHTML = `
                        <div class="no-data">
                            <i class="fas fa-exclamation-circle"></i>
                            <h3>Gagal memuat data</h3>
                            <p>${data.message || 'Silakan coba lagi'}</p>
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('modalContent').innerHTML = `
                    <div class="no-data">
                        <i class="fas fa-exclamation-circle"></i>
                        <h3>Error memuat data</h3>
                        <p>Terjadi kesalahan saat mengambil data. Pastikan koneksi internet stabil.</p>
                    </div>
                `;
            });
    }

    function closeDetail() {
        document.getElementById('detailModal').style.display = 'none';
        // Restore background scroll
        document.body.style.overflow = 'auto';
    }

    function displayPelanggaranData(pelanggaranData, nis) {
        if (!pelanggaranData || pelanggaranData.length === 0) {
            document.getElementById('modalContent').innerHTML = `
                <div class="no-data">
                    <i class="fas fa-check-circle" style="color: #27ae60;"></i>
                    <h3>Tidak ada pelanggaran</h3>
                    <p>Santri ini belum memiliki catatan pelanggaran</p>
                </div>
            `;
            return;
        }

        // Group by semester
        const groupedData = {
            ganjil: [],
            genap: []
        };

        pelanggaranData.forEach(item => {
            const semester = item.semester?.toLowerCase() || 'ganjil';
            if (semester === 'ganjil' || semester === 'genap') {
                groupedData[semester].push(item);
            } else {
                groupedData.ganjil.push(item); // default to ganjil
            }
        });

        let html = `
            <div class="pelanggaran-list">
                <div style="margin-bottom: 20px; padding: 15px; background: linear-gradient(135deg, #667eea, #764ba2); color: white; border-radius: 12px;">
                    <strong>Total Pelanggaran: ${pelanggaranData.length}</strong>
                </div>
        `;

        // Display by semester
        ['ganjil', 'genap'].forEach(semester => {
            const semesterData = groupedData[semester];
            if (semesterData.length > 0) {
                const semesterName = semester === 'ganjil' ? 'Semester Ganjil' : 'Semester Genap';
                const totalPoin = semesterData.reduce((sum, item) => sum + (item.poin || 0), 0);
                
                html += `
                    <div style="margin-bottom: 20px;">
                        <div style="padding: 10px; background: #f8f9fa; border-radius: 8px; margin-bottom: 10px;">
                            <strong>${semesterName}</strong> - ${semesterData.length} pelanggaran (Total: ${totalPoin} Poin)
                        </div>
                `;

                semesterData.forEach(item => {
                    const tanggal = new Date(item.tanggal).toLocaleDateString('id-ID', {
                        weekday: 'long',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    });
                    
                    // Determine poin color based on severity
                    const poin = item.poin || 0;
                    let poinColor = '#27ae60'; // green for low
                    if (poin >= 20) poinColor = '#e74c3c'; // red for high
                    else if (poin >= 10) poinColor = '#f39c12'; // orange for medium

                    html += `
                        <div class="pelanggaran-item">
                            <div class="pelanggaran-info">
                                <div class="pelanggaran-desc">${item.deskripsi || 'Pelanggaran'}</div>
                                <div class="pelanggaran-meta">
                                    <i class="fas fa-calendar"></i> ${tanggal}
                                    ${item.keterangan ? ` • <i class="fas fa-info-circle"></i> ${item.keterangan}` : ''}
                                    ${item.pelapor ? ` • <i class="fas fa-user"></i> ${item.pelapor}` : ''}
                                </div>
                            </div>
                            <div class="pelanggaran-poin" style="background: ${poinColor}">
                                ${poin} Poin
                            </div>
                        </div>
                    `;
                });

                html += `</div>`;
            }
        });

        html += `</div>`;
        document.getElementById('modalContent').innerHTML = html;
    }

    // ===== EVENT LISTENERS =====
    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('detailModal');
        if (event.target === modal) {
            closeDetail();
        }
    }

    // Close modal with Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeDetail();
        }
    });

    // Handle image loading errors
    document.addEventListener('DOMContentLoaded', function() {
        const images = document.querySelectorAll('.santri-photo');
        images.forEach(img => {
            img.addEventListener('error', function() {
                this.style.display = 'none';
                const placeholder = this.nextElementSibling;
                if (placeholder && placeholder.classList.contains('photo-placeholder')) {
                    placeholder.style.display = 'flex';
                }
            });
        });

        // Add animation to cards on load
        const cards = document.querySelectorAll('.santri-card');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                card.style.transition = 'all 0.5s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });
    });

    // Real-time clock update (jika diperlukan)
    function updateClock() {
        const now = new Date();
        const options = { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            timeZone: 'Asia/Jakarta'
        };
        const dateTimeStr = now.toLocaleDateString('id-ID', options);
        
        const clockElement = document.getElementById('currentDateTime');
        if (clockElement) {
            clockElement.textContent = dateTimeStr;
        }
    }

    // Initialize clock if element exists
    if (document.getElementById('currentDateTime')) {
        updateClock();
        setInterval(updateClock, 1000);
    }

    console.log('JavaScript loaded successfully');
    </script>
</body>
</html>