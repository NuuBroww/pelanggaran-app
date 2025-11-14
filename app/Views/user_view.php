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
    margin-bottom: 14px;
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

/* ===== MOBILE NAVBAR IMPROVEMENTS ===== */
@media (max-width: 768px) {
    .container {
        padding: 12px;
    }
    
    .header {
        flex-direction: column;
        gap: 16px;
        text-align: center;
        padding: 16px;
        margin-bottom: 16px;
    }
    
    .header-content h1 {
        font-size: 1.4rem;
        line-height: 1.3;
    }
    
    .header-content .subtitle {
        font-size: 0.85rem;
    }
    
    .btn {
        width: 100%;
        justify-content: center;
        padding: 12px 16px;
        font-size: 0.9rem;
    }
    
    .navbar-stats {
        grid-template-columns: repeat(3, 1fr);
        gap: 8px;
        margin-bottom: 16px;
    }
    
    .stat-item {
        padding: 12px 8px;
        border-radius: 12px;
    }
    
    .stat-number {
        font-size: 1.3rem;
    }
    
    .stat-label {
        font-size: 0.7rem;
    }
    
    .semester-info-bar {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
        padding: 14px 16px;
        margin-bottom: 16px;
    }
    
    .semester-title {
        font-size: 0.95rem;
    }
    
    .pelanggaran-summary {
        padding: 8px 12px;
        font-size: 0.85rem;
        width: 100%;
        justify-content: center;
    }
    
    .total-poin-badge {
        font-size: 0.8rem;
        padding: 4px 10px;
    }
    
    .search-container {
        margin-bottom: 16px;
    }
    
    .search-box {
        padding: 14px 45px 14px 16px;
        font-size: 0.9rem;
    }
    
    .search-icon {
        right: 16px;
    }
    
    .cards-grid {
        grid-template-columns: 1fr;
        gap: 16px;
        margin-bottom: 20px;
    }
    
    .santri-card {
        padding: 16px;
        border-radius: 16px;
    }
    
    .card-header {
        gap: 12px;
        margin-bottom: 16px;
        padding-bottom: 12px;
    }
    
    .santri-photo, .photo-placeholder {
        width: 60px;
        height: 60px;
        font-size: 1.3rem;
    }
    
    .card-info h3 {
        font-size: 1.1rem;
    }
    
    .card-nis {
        font-size: 0.8rem;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
        gap: 10px;
        margin-bottom: 16px;
    }
    
    .stat-box {
        padding: 12px;
    }
    
    .stat-value {
        font-size: 1.3rem;
    }
    
    .poin-bulanan-section {
        padding: 12px;
        margin-bottom: 16px;
    }
    
    .bulanan-grid {
        grid-template-columns: repeat(auto-fit, minmax(70px, 1fr));
        gap: 6px;
    }
    
    .bulanan-item {
        padding: 8px 4px;
    }
    
    .bulanan-bulan {
        font-size: 0.7rem;
    }
    
    .bulanan-poin {
        font-size: 0.9rem;
    }
    
    .semester-label {
        font-size: 0.65rem;
        padding: 3px 6px;
    }
    
    .status-badges {
        grid-template-columns: 1fr;
        gap: 8px;
        margin-bottom: 16px;
    }
    
    .status-badge {
        padding: 10px;
        font-size: 0.75rem;
    }
    
    .btn-detail {
        padding: 12px;
        font-size: 0.85rem;
    }
    
    .modal-content {
        padding: 20px;
        width: 95%;
        margin: 10px;
    }
    
    .modal-header {
        margin-bottom: 16px;
        padding-bottom: 12px;
    }
    
    .modal-title {
        font-size: 1.2rem;
    }
}

@media (max-width: 480px) {
    .navbar-stats {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .header {
        padding: 12px;
    }
    
    .header-content h1 {
        font-size: 1.3rem;
    }
    
    .semester-info-bar {
        padding: 12px;
    }
    
    .pelanggaran-summary {
        flex-direction: column;
        gap: 6px;
        text-align: center;
    }
    
    .santri-card {
        padding: 14px;
    }
    
    .bulanan-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

/* ===== PIN MODAL STYLES ===== */
.pin-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.8);
    backdrop-filter: blur(8px);
    z-index: 1500;
    justify-content: center;
    align-items: center;
    padding: 20px;
}

.pin-modal-content {
    background: white;
    padding: 30px;
    border-radius: 20px;
    width: 90%;
    max-width: 400px;
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
    text-align: center;
}

.pin-header {
    margin-bottom: 24px;
}

.pin-title {
    font-size: 1.4rem;
    font-weight: 700;
    color: var(--dark);
    margin-bottom: 8px;
}

.pin-subtitle {
    color: var(--gray);
    font-size: 0.95rem;
}

.pin-input-container {
    margin: 24px 0;
}

.pin-input {
    width: 100%;
    padding: 16px 20px;
    border: 2px solid var(--border);
    border-radius: 12px;
    font-size: 1.1rem;
    text-align: center;
    font-weight: 600;
    letter-spacing: 2px;
    transition: all 0.3s ease;
    background: var(--light);
}

.pin-input:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    background: white;
}

.pin-input::placeholder {
    letter-spacing: normal;
    font-weight: normal;
}

.pin-buttons {
    display: flex;
    gap: 12px;
    margin-top: 20px;
}

.btn-pin {
    flex: 1;
    padding: 14px 20px;
    border: none;
    border-radius: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.btn-pin-confirm {
    background: var(--gradient-primary);
    color: white;
    box-shadow: 0 4px 14px rgba(59, 130, 246, 0.4);
}

.btn-pin-confirm:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(59, 130, 246, 0.5);
}

.btn-pin-cancel {
    background: var(--light);
    color: var(--gray);
    border: 2px solid var(--border);
}

.btn-pin-cancel:hover {
    background: #f1f5f9;
    color: var(--dark);
}

.pin-error {
    color: var(--accent);
    font-size: 0.9rem;
    margin-top: 12px;
    display: none;
}

.pin-hint {
    font-size: 0.8rem;
    color: var(--gray);
    margin-top: 8px;
}

/* Mobile styles for PIN modal */
@media (max-width: 768px) {
    .pin-modal-content {
        padding: 24px 20px;
    }
    
    .pin-title {
        font-size: 1.2rem;
    }
    
    .pin-subtitle {
        font-size: 0.9rem;
    }
    
    .pin-input {
        padding: 14px 16px;
        font-size: 1rem;
    }
    
    .pin-buttons {
        flex-direction: column;
        gap: 10px;
    }
    
    .btn-pin {
        padding: 12px 16px;
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
/* ===== FOTO MODAL & ZOOM STYLES ===== */
.foto-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.95);
    backdrop-filter: blur(10px);
    z-index: 2000;
    justify-content: center;
    align-items: center;
    padding: 20px;
    animation: fadeIn 0.3s ease;
}

.foto-modal .modal-content {
    background: transparent;
    max-width: 95vw;
    max-height: 95vh;
    width: auto;
    box-shadow: none;
    border-radius: 0;
    padding: 0;
}

.foto-full {
    max-width: 100%;
    max-height: 85vh;
    border-radius: 15px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
    cursor: zoom-in;
    transition: transform 0.3s ease;
}

.foto-full.zoomed {
    cursor: zoom-out;
    transform: scale(1.8);
    box-shadow: 0 30px 80px rgba(0, 0, 0, 0.7);
}

.foto-info {
    margin-top: 20px;
    color: white;
    text-align: center;
    padding: 0 20px;
}

.foto-info h4 {
    margin: 0 0 8px 0;
    font-size: 1.3rem;
    font-weight: 700;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
}

.foto-info p {
    margin: 0;
    opacity: 0.9;
    font-size: 1rem;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
}

.foto-controls {
    position: absolute;
    top: 20px;
    right: 20px;
    display: flex;
    gap: 10px;
    z-index: 2001;
}

.foto-control-btn {
    width: 45px;
    height: 45px;
    background: rgba(255, 255, 255, 0.15);
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    color: white;
    font-size: 1.2rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(10px);
    transition: all 0.3s ease;
}

.foto-control-btn:hover {
    background: rgba(255, 255, 255, 0.25);
    transform: scale(1.1);
}

.foto-close-btn {
    background: rgba(231, 76, 60, 0.8);
    border-color: rgba(231, 76, 60, 0.5);
}

.foto-close-btn:hover {
    background: rgba(231, 76, 60, 0.9);
}

/* Loading untuk foto */
.foto-loading {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: white;
    gap: 15px;
}

.foto-loading-spinner {
    border: 3px solid rgba(255, 255, 255, 0.3);
    border-top: 3px solid white;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    animation: spin 1s linear infinite;
}

/* Animasi untuk foto */
@keyframes fadeInScale {
    from {
        opacity: 0;
        transform: scale(0.8);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

.foto-full {
    animation: fadeInScale 0.4s ease;
}

/* Responsive untuk mobile */
@media (max-width: 768px) {
    .foto-modal {
        padding: 10px;
    }
    
    .foto-full {
        max-height: 75vh;
    }
    
    .foto-full.zoomed {
        transform: scale(1.5);
    }
    
    .foto-controls {
        top: 10px;
        right: 10px;
    }
    
    .foto-control-btn {
        width: 40px;
        height: 40px;
        font-size: 1.1rem;
    }
    
    .foto-info h4 {
        font-size: 1.1rem;
    }
    
    .foto-info p {
        font-size: 0.9rem;
    }
}

@media (max-width: 480px) {
    .foto-full.zoomed {
        transform: scale(1.3);
    }
    
    .foto-control-btn {
        width: 35px;
        height: 35px;
        font-size: 1rem;
    }
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
                 data-nama="<?= esc($row['nama_santri']) ?>"
                 data-nis="<?= esc($row['nis']) ?>"
                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
            <div class="photo-placeholder" style="display: none;"
                 data-nama="<?= esc($row['nama_santri']) ?>"
                 data-nis="<?= esc($row['nis']) ?>">
                <?= $initial ?>
            </div>
        <?php else: ?>
            <div class="photo-placeholder"
                 data-nama="<?= esc($row['nama_santri']) ?>"
                 data-nis="<?= esc($row['nis']) ?>">
                <?= $initial ?>
            </div>
        <?php endif; ?>
    </div>
    <div class="card-info">
        <h3><?= esc($row['nama_santri']) ?></h3>
    </div>
</div>
       <button class="btn-detail" onclick="requestPin('<?= $nis ?>', '<?= esc($row['nama_santri']) ?>')">
    <i class="fas fa-lock"></i> Lihat Detail Pelanggaran
</button>

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
<!-- Modal PIN -->
    <div id="pinModal" class="modal pin-modal">
        <div class="pin-modal-content">
            <div class="pin-header">
                <h2 class="pin-title">🔒 Masukkan NIS</h2>
                <p class="pin-subtitle">Masukkan NIS santri untuk melihat detail pelanggaran</p>
            </div>
            
            <div class="pin-input-container">
                <input type="text" id="pinInput" class="pin-input" placeholder="Masukkan NIS..." maxlength="10" autocomplete="off">
                <div id="pinError" class="pin-error">
                    <i class="fas fa-exclamation-circle"></i> <span id="pinErrorText">NIS tidak valid!</span>
                </div>
                <div class="pin-hint">
                    NIS terdiri dari angka (contoh: 12345)
                </div>
            </div>
            
            <div class="pin-buttons">
                <button class="btn-pin btn-pin-cancel" onclick="closePinModal()">
                    <i class="fas fa-times"></i> Batal
                </button>
                <button class="btn-pin btn-pin-confirm" onclick="verifyPin()">
                    <i class="fas fa-unlock"></i> Masuk
                </button>
            </div>
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

    <!-- Modal Foto dengan Zoom -->
    <div id="fotoModal" class="modal foto-modal">
        <div class="modal-content">
            <div class="foto-controls">
                <button class="foto-control-btn" onclick="zoomFoto()" id="zoomBtn" title="Zoom In/Out">
                    <i class="fas fa-search-plus"></i>
                </button>
                <button class="foto-control-btn foto-close-btn" onclick="tutupFotoModal()" title="Tutup">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="modal-body" style="text-align: center; padding: 0; display: flex; flex-direction: column; align-items: center;">
                <div id="fotoLoading" class="foto-loading" style="display: none;">
                    <div class="foto-loading-spinner"></div>
                    <p>Memuat foto...</p>
                </div>
                <img id="fotoFull" class="foto-full" src="" alt="Foto Santri" style="display: none;" onclick="toggleZoom()">
                <div class="foto-info">
                    <h4 id="fotoSantriName"></h4>
                    <p id="fotoSantriNis"></p>
                </div>
            </div>
        </div>
    </div>

    <script>
// ===== GLOBAL VARIABLES =====
let currentNis = '';
let currentNama = '';
let isFotoZoomed = false;

// ===== PIN FUNCTIONALITY =====
function requestPin(nis, nama) {
    console.log('🔐 Request PIN called with:', nis, nama);
    
    // Pastikan variabel diisi dengan benar
    currentNis = nis;
    currentNama = nama;
    
    console.log('🔐 Current NIS set to:', currentNis);
    console.log('🔐 Current Nama set to:', currentNama);
    
    // Reset PIN modal
    document.getElementById('pinInput').value = '';
    document.getElementById('pinError').style.display = 'none';
    
    // Show PIN modal
    document.getElementById('pinModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
    
    // Focus on input
    setTimeout(() => {
        document.getElementById('pinInput').focus();
    }, 100);
}

function closePinModal() {
    document.getElementById('pinModal').style.display = 'none';
    document.body.style.overflow = 'auto';
    // Jangan reset currentNis di sini, karena masih diperlukan untuk showDetail
}

function verifyPin() {
    const pinInput = document.getElementById('pinInput');
    const enteredPin = pinInput.value.trim();
    const pinError = document.getElementById('pinError');
    
    console.log('🔍 Verifying PIN:', enteredPin);
    console.log('🔍 Expected NIS:', currentNis);
    console.log('🔍 Current Nama:', currentNama);
    
    if (!enteredPin) {
        pinError.textContent = '⚠️ Harap masukkan NIS!';
        pinError.style.display = 'block';
        pinInput.focus();
        return;
    }
    
    if (!/^\d+$/.test(enteredPin)) {
        pinError.textContent = '❌ NIS harus berupa angka!';
        pinError.style.display = 'block';
        pinInput.focus();
        return;
    }
    
    if (enteredPin === currentNis) {
        console.log('✅ PIN verified successfully');
        console.log('✅ Opening detail for:', currentNama, 'NIS:', currentNis);
        closePinModal();
        showDetail(currentNis, currentNama);
    } else {
        pinError.textContent = '❌ NIS tidak sesuai!';
        pinError.style.display = 'block';
        pinInput.focus();
        pinInput.select();
        
        pinInput.style.animation = 'shake 0.5s ease-in-out';
        setTimeout(() => {
            pinInput.style.animation = '';
        }, 500);
    }
}

// ===== MODAL FUNCTIONS =====
function showDetail(nis, nama) {
    console.log('🎯 showDetail called with:', nis, nama);
    console.log('🎯 Global currentNis:', currentNis);
    console.log('🎯 Global currentNama:', currentNama);
    
    // Update modal title
    document.getElementById('modalSantriName').textContent = `Detail Pelanggaran - ${nama}`;
    document.getElementById('modalContent').innerHTML = `
        <div class="loading">
            <div class="loading-spinner"></div>
            <p>Memuat data pelanggaran...</p>
        </div>
    `;
    document.getElementById('detailModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
    
    // Gunakan base_url dari PHP
    const baseUrl = '<?= base_url() ?>';
    console.log('🌐 Base URL:', baseUrl);
    
    // Coba endpoint yang berhasil berdasarkan test manual
    const endpoints = [
        `${baseUrl}getDetailPelanggaran/${nis}`,
        `${baseUrl}umum/getDetailPelanggaran/${nis}`,
        `/getDetailPelanggaran/${nis}`,
        `/umum/getDetailPelanggaran/${nis}`
    ];
    
    console.log('🔄 Endpoints to try:', endpoints);
    
    let currentEndpointIndex = 0;
    
    function tryEndpoint() {
        if (currentEndpointIndex >= endpoints.length) {
            showErrorModal('Semua endpoint gagal. Periksa konfigurasi routes.');
            return;
        }
        
        const endpoint = endpoints[currentEndpointIndex];
        console.log(`🔄 Mencoba endpoint ${currentEndpointIndex + 1}/${endpoints.length}: ${endpoint}`);
        
        // Show which endpoint is being tried
        document.getElementById('modalContent').innerHTML = `
            <div class="loading">
                <div class="loading-spinner"></div>
                <p>Memuat data pelanggaran...</p>
                <p style="font-size: 12px; margin-top: 10px;">
                    Mencoba: ${endpoint}<br>
                    Santri: ${nama} (NIS: ${nis})
                </p>
            </div>
        `;
        
        fetch(endpoint)
            .then(response => {
                console.log('📡 Response status:', response.status, response.statusText);
                console.log('📡 Response URL:', response.url);
                
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('✅ Data received successfully:', data);
                if (data.success) {
                    displayPelanggaranData(data.data, data.santri);
                } else {
                    showErrorModal(data.message || 'Data tidak ditemukan');
                }
            })
            .catch(error => {
                console.error(`❌ Error dari ${endpoint}:`, error);
                currentEndpointIndex++;
                
                if (currentEndpointIndex < endpoints.length) {
                    // Coba endpoint berikutnya setelah delay
                    setTimeout(tryEndpoint, 500);
                } else {
                    showErrorModal(`Semua endpoint gagal. Error: ${error.message}`);
                }
            });
    }
    
    // Mulai dengan endpoint pertama
    tryEndpoint();
}

function showErrorModal(message) {
    console.log('❌ Showing error modal:', message);
    
    document.getElementById('modalContent').innerHTML = `
        <div class="no-data">
            <i class="fas fa-exclamation-circle"></i>
            <h3>Error memuat data</h3>
            <p>${message}</p>
            <div style="margin-top: 20px; font-size: 12px; text-align: left; background: #f8f9fa; padding: 15px; border-radius: 8px;">
                <strong>Debug Info:</strong><br>
                - Base URL: <?= base_url() ?><br>
                - Current NIS: ${currentNis}<br>
                - Current Nama: ${currentNama}<br>
                - Coba akses manual:<br>
                &nbsp;&nbsp;<a href="<?= base_url() ?>getDetailPelanggaran/${currentNis}" target="_blank">
                    <?= base_url() ?>getDetailPelanggaran/${currentNis}
                </a>
                <br><br>
                <strong>Langkah troubleshooting:</strong><br>
                1. Buka link manual di atas di tab baru<br>
                2. Pastikan menampilkan data JSON<br>
                3. Jika error, periksa routes dan controller
            </div>
        </div>
    `;
}

function closeDetail() {
    document.getElementById('detailModal').style.display = 'none';
    document.body.style.overflow = 'auto';
    // Reset variabel setelah modal ditutup
    currentNis = '';
    currentNama = '';
}

function displayPelanggaranData(pelanggaranData, santriInfo) {
    console.log('📊 Displaying pelanggaran data:', pelanggaranData);
    
    if (!pelanggaranData || pelanggaranData.length === 0) {
        document.getElementById('modalContent').innerHTML = `
            <div class="no-data">
                <i class="fas fa-check-circle" style="color: #27ae60;"></i>
                <h3>Tidak ada pelanggaran</h3>
                <p>Santri ${santriInfo.nama} (NIS: ${santriInfo.nis}) belum memiliki catatan pelanggaran</p>
            </div>
        `;
        return;
    }

    const groupedData = {
        ganjil: [],
        genap: []
    };

    pelanggaranData.forEach(item => {
        const semester = item.semester?.toLowerCase() || 'ganjil';
        groupedData[semester].push(item);
    });

    let html = `
        <div class="pelanggaran-list">
            <div style="margin-bottom: 20px; padding: 15px; background: linear-gradient(135deg, #667eea, #764ba2); color: white; border-radius: 12px;">
                <strong>${santriInfo.nama} - NIS: ${santriInfo.nis}</strong><br>
                <small>Total Pelanggaran: ${pelanggaranData.length}</small>
            </div>
    `;

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
                
                const poin = item.poin || 0;
                let poinColor = '#27ae60';
                if (poin >= 20) poinColor = '#e74c3c';
                else if (poin >= 10) poinColor = '#f39c12';

                html += `
                    <div class="pelanggaran-item">
                        <div class="pelanggaran-info">
                            <div class="pelanggaran-desc">${item.deskripsi || 'Pelanggaran'}</div>
                            <div class="pelanggaran-meta">
                                <i class="fas fa-calendar"></i> ${tanggal}
                                ${item.keterangan ? ` • <i class="fas fa-info-circle"></i> ${item.keterangan}` : ''}
                                ${item.pelapor ? ` • <i class="fas fa-user"></i> ${item.pelapor}` : ''}
                                ${item.semester ? ` • <i>Semester</i> ${item.semester}` : ''}
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

// ===== SEARCH FUNCTIONALITY =====
const searchInput = document.getElementById('searchInput');
const searchResults = document.getElementById('searchResults');
const noResults = document.getElementById('noResults');

if (searchInput) {
    searchInput.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase().trim();
        const cards = document.querySelectorAll('.santri-card');
        let resultsCount = 0;
        
        cards.forEach(card => {
            const title = card.querySelector('.card-info h3')?.textContent.toLowerCase() || '';
            const nis = card.getAttribute('data-nis') || '';
            
            if (title.includes(searchTerm) || nis.includes(searchTerm)) {
                card.style.display = 'block';
                resultsCount++;
                
                if (searchTerm) {
                    highlightText(card.querySelector('.card-info h3'), searchTerm);
                }
            } else {
                card.style.display = 'none';
            }
        });
        
        if (searchResults) {
            searchResults.textContent = searchTerm ? 
                `${resultsCount} santri ditemukan` : 
                `Total ${resultsCount} santri ditemukan`;
        }
        
        if (noResults) {
            noResults.style.display = (searchTerm && resultsCount === 0) ? 'block' : 'none';
        }
        
        if (!searchTerm) {
            removeHighlights();
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

function highlightText(element, searchTerm) {
    const text = element.textContent;
    const regex = new RegExp(`(${searchTerm})`, 'gi');
    const highlighted = text.replace(regex, '<span class="highlight">$1</span>');
    element.innerHTML = highlighted;
}

function removeHighlights() {
    const highlights = document.querySelectorAll('.highlight');
    highlights.forEach(highlight => {
        const parent = highlight.parentNode;
        parent.textContent = parent.textContent;
    });
}

// ===== FOTO FUNCTIONALITY =====
function tampilkanFoto(photoPath, nama, nis) {
    const modal = document.getElementById('fotoModal');
    const fotoFull = document.getElementById('fotoFull');
    const fotoLoading = document.getElementById('fotoLoading');
    
    isFotoZoomed = false;
    fotoFull.classList.remove('zoomed');
    document.getElementById('zoomBtn').innerHTML = '<i class="fas fa-search-plus"></i>';
    
    document.getElementById('fotoSantriName').textContent = nama;
    
    modal.style.display = 'flex';
    fotoLoading.style.display = 'flex';
    fotoFull.style.display = 'none';
    
    const img = new Image();
    img.onload = function() {
        fotoFull.src = photoPath;
        fotoLoading.style.display = 'none';
        fotoFull.style.display = 'block';
    };
    
    img.onerror = function() {
        fotoLoading.innerHTML = `
            <i class="fas fa-exclamation-circle"></i>
            <p>Gagal memuat foto</p>
            <button class="btn" onclick="tutupFotoModal()">
                <i class="fas fa-times"></i> Tutup
            </button>
        `;
    };
    
    img.src = photoPath;
}

function tutupFotoModal() {
    document.getElementById('fotoModal').style.display = 'none';
    isFotoZoomed = false;
    const fotoFull = document.getElementById('fotoFull');
    fotoFull.classList.remove('zoomed');
    document.getElementById('zoomBtn').innerHTML = '<i class="fas fa-search-plus"></i>';
}

function toggleZoom() {
    const fotoFull = document.getElementById('fotoFull');
    const zoomBtn = document.getElementById('zoomBtn');
    
    isFotoZoomed = !isFotoZoomed;
    fotoFull.classList.toggle('zoomed', isFotoZoomed);
    
    if (isFotoZoomed) {
        zoomBtn.innerHTML = '<i class="fas fa-search-minus"></i>';
    } else {
        zoomBtn.innerHTML = '<i class="fas fa-search-plus"></i>';
    }
}

function zoomFoto() {
    toggleZoom();
}

// ===== INITIALIZATION =====
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Application initialized');
    
    // Initialize photos
    updatePhotoContainers();
    enhanceCardPhotos();
    
    // Animate cards
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

    // Handle image errors
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

    // PIN modal events
    document.getElementById('pinInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            verifyPin();
        }
    });

    // Escape key untuk semua modal
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (document.getElementById('pinModal').style.display === 'flex') {
                closePinModal();
            } else if (document.getElementById('detailModal').style.display === 'flex') {
                closeDetail();
            } else if (document.getElementById('fotoModal').style.display === 'flex') {
                tutupFotoModal();
            }
        }
    });

    // Close modal when clicking outside
    document.getElementById('pinModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closePinModal();
        }
    });

    window.addEventListener('click', function(event) {
        const modals = ['detailModal', 'fotoModal'];
        modals.forEach(modalId => {
            const modal = document.getElementById(modalId);
            if (event.target === modal) {
                if (modalId === 'detailModal') closeDetail();
                if (modalId === 'fotoModal') tutupFotoModal();
            }
        });
    });
});

function updatePhotoContainers() {
    const photoContainers = document.querySelectorAll('.photo-container');
    
    photoContainers.forEach(container => {
        const img = container.querySelector('.santri-photo');
        const placeholder = container.querySelector('.photo-placeholder');
        const card = container.closest('.santri-card');
        const nama = card.querySelector('.card-info h3')?.textContent || '';
        const nis = card.getAttribute('data-nis') || '';
        
        if (img && img.src && !img.src.includes('undefined')) {
            img.style.cursor = 'pointer';
            img.addEventListener('click', function() {
                tampilkanFoto(this.src, nama, nis);
            });
        }
        
        if (placeholder) {
            placeholder.style.cursor = 'pointer';
            placeholder.addEventListener('click', function() {
                const imgSrc = container.querySelector('.santri-photo')?.src;
                if (imgSrc && !imgSrc.includes('undefined')) {
                    tampilkanFoto(imgSrc, nama, nis);
                } else {
                    alert('Foto tidak tersedia untuk ' + nama);
                }
            });
        }
    });
}

function enhanceCardPhotos() {
    const cards = document.querySelectorAll('.santri-card');
    
    cards.forEach(card => {
        const photoContainer = card.querySelector('.photo-container');
        const img = photoContainer?.querySelector('.santri-photo');
        const placeholder = photoContainer?.querySelector('.photo-placeholder');
        
        if (img) {
            img.style.transition = 'all 0.3s ease';
            img.parentElement.addEventListener('mouseenter', function() {
                if (img.style.display !== 'none') {
                    img.style.transform = 'scale(1.05)';
                }
            });
            img.parentElement.addEventListener('mouseleave', function() {
                if (img.style.display !== 'none') {
                    img.style.transform = 'scale(1)';
                }
            });
        }
        
        if (placeholder) {
            placeholder.style.transition = 'all 0.3s ease';
            placeholder.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.05)';
            });
            placeholder.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1)';
            });
        }
    });
}

// Shake animation
const style = document.createElement('style');
style.textContent = `
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }
`;
document.head.appendChild(style);

console.log('✅ All JavaScript loaded');
</script>
</body>
</html>