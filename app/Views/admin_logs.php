<?php
date_default_timezone_set('Asia/Jakarta');
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>E - Mahkamah | Admin Logs</title>
<style>
/* Copy CSS dari dashboard yang sudah ada */
body { font-family: "Segoe UI", sans-serif; margin:0; background-color:#f4f5f7; color:#333; transition: background-color 0.3s, color 0.3s; }
body.dark-mode { background-color:#1e1e2e; color:#ddd; }

.navbar-top { 
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 15px 20px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    position: sticky;
    top: 0;
    z-index: 1000;
}
body.dark-mode .navbar-top { background: linear-gradient(135deg, #2a2a3d 0%, #3a3a4f 100%); }

.navbar-container { display: flex; justify-content: space-between; align-items: center; max-width: 1200px; margin: 0 auto; }
.navbar-stats { display: flex; gap: 15px; align-items: center; flex-wrap: wrap; }
.stat-item { background: rgba(255,255,255,0.15); padding: 10px 15px; border-radius: 10px; text-align: center; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2); transition: all 0.3s ease; min-width: 100px; }
.stat-item:hover { background: rgba(255,255,255,0.25); transform: translateY(-2px); }
.stat-number { display: block; font-size: 1.3rem; font-weight: bold; color: white; }

header { background-color:#fff; padding:15px 20px; display:flex; flex-wrap:wrap; justify-content:space-between; align-items:center; box-shadow:0 2px 8px rgba(0,0,0,0.1); transition: background-color 0.3s; }
body.dark-mode header { background-color:#2a2a3d; box-shadow:0 2px 8px rgba(0,0,0,0.5); }
header h1 { margin:0; font-size:1.2rem; flex:1 1 200px; min-width:150px; }
.top-actions { display:flex; flex-wrap:wrap; gap:8px; }
.btn { background-color:#3498db; color:#fff; border:none; padding:8px 16px; border-radius:6px; cursor:pointer; font-size:0.9rem; display:inline-flex; align-items:center; gap:5px; min-width:60px; justify-content:center; transition: all 0.2s; }
.btn:hover { background-color:#2980b9; }
.btn-danger { background-color:#e74c3c; }
.btn-danger:hover { background-color:#c0392b; }

main { padding:20px; transition: background-color 0.3s; }
h2 { margin-bottom:15px; font-size:1.1rem; }

.table-wrapper { overflow-x:auto; border-radius:10px; }
table { width:100%; border-collapse:collapse; background:#fff; box-shadow:0 2px 6px rgba(0,0,0,0.1); min-width:600px; transition: background 0.3s, color 0.3s; }
body.dark-mode table { background:#2a2a3d; color:#ddd; box-shadow:0 2px 8px rgba(0,0,0,0.6); }
th, td { padding:12px 15px; text-align:left; border-bottom:1px solid #e0e0e0; vertical-align:middle; }
th { background:#f7f7f7; color:#555; font-weight:600; transition: background 0.3s, color 0.3s; }
body.dark-mode th { background:#3a3a4f; color:#ccc; }
tr:hover { background:#f1f9ff; }
body.dark-mode tr:hover { background:#44445a; }

/* Badge Actions */
.log-action {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.8rem;
    font-weight: bold;
    text-transform: uppercase;
}

.action-login { background: #d4edda; color: #155724; }
.action-logout { background: #f8d7da; color: #721c24; }
.action-update { background: #fff3cd; color: #856404; }
.action-create { background: #d1ecf1; color: #0c5460; }
.action-delete { background: #f5c6cb; color: #721c24; }

body.dark-mode .action-login { background: #1e4620; color: #a3d9a5; }
body.dark-mode .action-logout { background: #4a1c1c; color: #f5a3a3; }
body.dark-mode .action-update { background: #4a3c10; color: #ffd54f; }
body.dark-mode .action-create { background: #1a3a3f; color: #81d4fa; }
body.dark-mode .action-delete { background: #4a1c1c; color: #f5a3a3; }

/* Pagination */
.pagination {
    display: flex;
    justify-content: center;
    gap: 5px;
    margin-top: 20px;
    flex-wrap: wrap;
}

.pagination a, .pagination span {
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 5px;
    text-decoration: none;
    color: #333;
}

body.dark-mode .pagination a, 
body.dark-mode .pagination span {
    background: #2a2a3d;
    color: #ddd;
    border-color: #444;
}

.pagination .active {
    background: #3498db;
    color: white;
    border-color: #3498db;
}

.pagination a:hover {
    background: #f0f0f0;
}

body.dark-mode .pagination a:hover {
    background: #3a3a4f;
}

footer { text-align:center; padding:15px; font-size:0.85rem; margin-top:30px; color:#888; transition: color 0.3s; }
body.dark-mode footer { color:#bbb; }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<!-- Header -->
<header>
    <h1>📊 Admin Logs - E - Mahkamah</h1>
    <div class="top-actions" style="align-items:center; gap:10px;">
        <a href="<?= base_url('/rekapan') ?>" class="btn">📄 Rekapan</a>
        <form action="<?= base_url('/logout') ?>" method="post" style="margin:0;">
            <button type="submit" class="btn btn-danger">🚪 Logout</button>
        </form>
    </div>
</header>

<!-- Navbar -->
<nav class="navbar-top">
    <div class="navbar-container">
        <div class="navbar-stats">
            <div class="stat-item">
                <span class="stat-number"><?= $totalLogs ?></span>
                <span class="stat-label">Total Logs</span>
            </div>
            
            <div class="stat-item">
                <span class="stat-number"><?= $todayLogs ?></span>
                <span class="stat-label">Hari Ini</span>
            </div>
            
            <div class="stat-item" style="cursor: pointer; background: rgba(155, 89, 182, 0.2);">
                <a href="<?= base_url('/dashboard') ?>" style="text-decoration: none; color: white; display: block;">
                    <span class="stat-number">
                        <i class="fas fa-tachometer-alt"></i>
                    </span>
                    <span class="stat-label">Dashboard</span>
                </a>
            </div>  
        </div>
    </div>
</nav>

<main>
    <h2>📋 Log Aktivitas Admin</h2>

    <!-- Tabel Logs -->
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Admin</th>
                    <th>Aksi</th>
                    <th>Target ID</th>
                    <th>Waktu</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($logs)): ?>
                    <?php foreach ($logs as $log): ?>
                    <tr>
                        <td>
                            <strong><?= esc($adminNames[$log['admin_id']] ?? 'Ustadz Admin') ?></strong>
                        </td>
                        <td>
                            <span class="log-action action-<?= $log['action'] ?>">
                                <?= strtoupper($log['action']) ?>
                            </span>
                        </td>
                        <td>
                            <?= $log['target_id'] ? esc($log['target_id']) : '-' ?>
                        </td>
                        <td>
                            <?= date('Y-m-d H:i:s') ?>
                            <br>
                            <small style="color: #666;">
                                <?= date('H:i:s', strtotime($log['created_at'])) ?>
                            </small>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" style="text-align:center; padding: 30px; color: #777;">
                            <i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 10px; opacity: 0.5;"></i>
                            <br>
                            Belum ada log aktivitas.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if (isset($pager)): ?>
    <div class="pagination">
        <?= $pager->links() ?>
    </div>
    <?php endif; ?>
</main>

<footer>
    &copy; <?= date('Y') ?> E - Mahkamah | Admin Logs Monitoring
    <br>
    <small>Total Records: <?= $totalLogs ?> | Ditampilkan: <?= count($logs) ?></small>
</footer>

<script>
// Theme toggle
const themeToggle = document.getElementById('themeToggle');
const themeIcon = document.getElementById('themeIcon');
const themeLabel = document.getElementById('themeLabel');

function toggleTheme() {
    const isDark = document.body.classList.toggle('dark-mode');
    localStorage.setItem('themeMode', isDark ? 'dark' : 'light');
    updateThemeIcon(isDark);
}

function updateThemeIcon(isDark) {
    if (isDark) {
        themeIcon.className = 'fas fa-sun';
        themeLabel.textContent = 'Pindah Ke Mode Terang';
    } else {
        themeIcon.className = 'fas fa-moon';
        themeLabel.textContent = 'Pindah Ke Mode Gelap';
    }
}

// Initialize theme
document.addEventListener('DOMContentLoaded', () => {
    const savedTheme = localStorage.getItem('themeMode') || 'light';
    if (savedTheme === 'dark') {
        document.body.classList.add('dark-mode');
    }
    updateThemeIcon(savedTheme === 'dark');
});

themeToggle.addEventListener('click', toggleTheme);
</script>
</body>
</html>
