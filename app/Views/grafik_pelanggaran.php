<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E - Mahkamah | Grafik Pelanggaran Santri</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* CSS tetap sama seperti sebelumnya */
        :root {
            --primary: #3498db;
            --secondary: #2c3e50;
            --success: #27ae60;
            --warning: #f39c12;
            --danger: #e74c3c;
            --light: #f8f9fa;
            --dark: #343a40;
            --gray: #6c757d;
            --border: #e9ecef;
            --shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            --gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #f5f7fa;
            min-height: 100vh;
            color: var(--dark);
            line-height: 1.5;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 15px;
        }

        /* Header Mobile Friendly */
        .header {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: var(--shadow);
            margin-bottom: 20px;
            border-left: 4px solid var(--primary);
        }

        .header-content h1 {
            font-size: 1.4rem;
            color: var(--secondary);
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .header-content .subtitle {
            color: var(--gray);
            font-size: 0.85rem;
            margin-bottom: 10px;
        }

        .badge-role {
            display: inline-block;
            padding: 4px 10px;
            background: var(--gradient);
            color: white;
            border-radius: 15px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .btn {
            padding: 10px 16px;
            background: var(--primary);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.9rem;
        }

        .btn-back {
            background: var(--gray);
            width: 100%;
            justify-content: center;
            margin-top: 10px;
        }

        /* Photo Styles dengan Zoom */
        .mobile-photo {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            object-fit: cover;
            border: 2px solid white;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .mobile-photo:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }

        .photo-placeholder {
            width: 50px;
            height: 50px;
            background: var(--gradient);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.2rem;
            border: 2px solid white;
            cursor: pointer;
        }

        /* Mobile Cards */
        .mobile-cards {
            display: none;
            flex-direction: column;
            gap: 12px;
        }

        .mobile-card {
            background: white;
            border-radius: 12px;
            padding: 16px;
            box-shadow: var(--shadow);
            border-left: 4px solid var(--primary);
            transition: all 0.3s ease;
        }

        .mobile-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .mobile-card-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
        }

        .mobile-santri-info h3 {
            font-size: 1rem;
            color: var(--secondary);
            margin-bottom: 2px;
        }

        .mobile-santri-info .nis {
            font-size: 0.8rem;
            color: var(--gray);
            margin-bottom: 4px;
        }

        .foto-status {
            font-size: 0.7rem;
            padding: 2px 6px;
            border-radius: 8px;
            display: inline-block;
        }

        .foto-ada {
            background: rgba(39, 174, 96, 0.1);
            color: var(--success);
        }

        .foto-tidak-ada {
            background: rgba(231, 76, 60, 0.1);
            color: var(--danger);
        }

        .mobile-actions {
            display: flex;
            gap: 8px;
            margin-top: 12px;
        }

        .mobile-btn {
            flex: 1;
            padding: 8px 12px;
            border: none;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
            transition: all 0.3s ease;
        }

        .view-btn {
            background: var(--primary);
            color: white;
        }

        .upload-btn {
            background: var(--warning);
            color: white;
        }

        /* Desktop Table (Hidden on Mobile) */
        .desktop-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: var(--shadow);
        }

        .desktop-table th {
            background: var(--gradient);
            color: white;
            padding: 12px 10px;
            text-align: left;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .desktop-table td {
            padding: 12px 10px;
            border-bottom: 1px solid var(--border);
        }

        /* Search Styles */
        .search-container {
            margin-bottom: 15px;
            position: relative;
        }

        .search-box {
            width: 100%;
            padding: 12px 40px 12px 12px;
            border: 2px solid var(--border);
            border-radius: 20px;
            font-size: 0.9rem;
            background: white;
        }

        .search-icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray);
        }

        .search-results {
            font-size: 0.8rem;
            color: var(--gray);
            margin-top: 5px;
        }

        .no-results {
            text-align: center;
            padding: 30px 20px;
            color: var(--gray);
            background: white;
            border-radius: 10px;
            margin: 10px 0;
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
            backdrop-filter: blur(5px);
            z-index: 1000;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .modal-content {
            background: white;
            border-radius: 20px;
            width: 95%;
            max-width: 900px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
            position: relative;
            display: flex;
            flex-direction: column;
        }

        .modal-header {
            padding: 25px 30px 0 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-shrink: 0;
        }

        .modal-title {
            font-size: 1.4rem;
            color: var(--secondary);
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .modal-body {
            padding: 20px 30px;
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .close-btn {
            background: none;
            border: none;
            font-size: 24px;
            color: var(--gray);
            cursor: pointer;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .close-btn:hover {
            background: rgba(0,0,0,0.1);
        }

        /* Chart Container */
        .chart-container {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--border);
            height: 500px;
            min-height: 500px;
            position: relative;
        }

        .chart-container canvas {
            width: 100% !important;
            height: 100% !important;
        }

        /* Semester Info */
        .semester-info {
            display: flex;
            justify-content: center;
            gap: 15px;
            padding: 15px;
            background: var(--light);
            border-radius: 12px;
            border: 1px solid var(--border);
        }

        .semester-badge {
            padding: 10px 18px;
            border-radius: 25px;
            font-size: 0.9rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .semester-ganjil {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
        }

        .semester-genap {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
        }

        /* Loading States */
        .loading {
            text-align: center;
            padding: 30px;
            color: var(--gray);
        }

        .loading-spinner {
            border: 3px solid #f3f3f3;
            border-top: 3px solid var(--primary);
            border-radius: 50%;
            width: 30px;
            height: 30px;
            animation: spin 1s linear infinite;
            margin: 0 auto 10px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* No Data State */
        .no-data {
            text-align: center;
            padding: 40px 20px;
            color: var(--gray);
            background: white;
            border-radius: 10px;
            margin: 10px 0;
        }

        /* Foto Modal */
        .foto-modal .modal-content {
            max-width: 90vw;
            max-height: 90vh;
            width: auto;
            background: transparent;
            box-shadow: none;
        }

        .foto-full {
            max-width: 100%;
            max-height: 80vh;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .foto-info {
            margin-top: 15px;
            color: white;
            text-align: center;
        }

        .foto-info h4 {
            margin: 0 0 5px 0;
            font-size: 1.2rem;
        }

        .foto-info p {
            margin: 0;
            opacity: 0.8;
        }

        /* Responsive Breakpoints */
        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }

            .header {
                padding: 15px;
                margin-bottom: 15px;
            }

            .header-content h1 {
                font-size: 1.2rem;
            }

            /* Hide desktop table on mobile */
            .desktop-table {
                display: none;
            }

            /* Show mobile cards on mobile */
            .mobile-cards {
                display: flex;
            }

            /* Modal mobile */
            .modal {
                padding: 10px;
            }
            
            .modal-content {
                width: 100%;
                max-height: 95vh;
                border-radius: 15px;
            }
            
            .modal-header {
                padding: 20px 20px 0 20px;
            }
            
            .modal-body {
                padding: 15px 20px;
            }
            
            .chart-container {
                height: 400px;
                min-height: 400px;
                padding: 20px;
            }
            
            .semester-info {
                flex-direction: column;
                gap: 10px;
            }
            
            .semester-badge {
                justify-content: center;
                text-align: center;
            }
        }

        @media (max-width: 480px) {
            .chart-container {
                height: 350px;
                min-height: 350px;
                padding: 15px;
            }
            
            .modal-title {
                font-size: 1.2rem;
            }

            .mobile-actions {
                flex-direction: column;
            }

            .mobile-btn {
                width: 100%;
            }
        }

        /* For very small screens */
        @media (max-width: 360px) {
            .mobile-photo,
            .photo-placeholder {
                width: 45px;
                height: 45px;
                font-size: 1rem;
            }

            .mobile-santri-info h3 {
                font-size: 0.9rem;
            }

            .mobile-btn {
                font-size: 0.75rem;
                padding: 6px 8px;
            }

            .chart-container {
                height: 300px;
                min-height: 300px;
            }
        }

        /* Desktop styles */
        @media (min-width: 769px) {
            .mobile-cards {
                display: none;
            }

            .desktop-table {
                display: table;
            }

            .header {
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .btn-back {
                width: auto;
                margin-top: 0;
            }

            .chart-container {
                height: 500px;
                min-height: 500px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="header-content">
                <h1><i class="fas fa-chart-line"></i> Grafik Pelanggaran Santri</h1>
                <div class="subtitle">Pantau perkembangan pelanggaran santri secara visual</div>
                <div class="badge-role">
                    <i class="fas fa-user-shield"></i>
                    <?php if ($id_role == 2): ?>
                        Akses: Ustadz Mizan & Ustadz Dodo
                    <?php elseif ($id_role == 3): ?>
                        Akses: Yayasan
                    <?php elseif ($id_role == 1): ?>
                        Akses: Ustadz - Ustadz PTD
                    <?php endif; ?>
                </div>
            </div>
            <a href="<?= base_url('/dashboard') ?>" class="btn btn-back">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

        <?php if (empty($santri)): ?>
            <div class="no-data">
                <i class="fas fa-users-slash"></i>
                <h3>Tidak Ada Data Santri</h3>
                <p>Belum ada data santri yang tersedia</p>
            </div>
        <?php else: ?>
            <!-- Search Box -->
            <div class="search-container">
                <input type="text" id="searchInput" class="search-box" placeholder="Cari nama santri atau NIS..." autocomplete="off">
                <div class="search-results" id="searchResults">
                    Total <?= count($santri) ?> santri ditemukan
                </div>
            </div>

            <!-- No Results Message -->
            <div id="noResults" class="no-results" style="display: none;">
                <i class="fas fa-search"></i>
                <h3>Santri tidak ditemukan</h3>
                <p>Coba gunakan kata kunci yang berbeda</p>
            </div>

            <!-- Desktop Table -->
            <table class="desktop-table">
                <thead>
                    <tr>
                        <th width="100">Foto</th>
                        <th>Informasi Santri</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($santri as $santri_item): ?>
                        <?php
                        $fotoFileName = $santri_item['foto'] ?? null;
                        $hasPhoto = !empty($fotoFileName);
                        $initial = substr($santri_item['nama_santri'], 0, 1);
                        ?>
                        <tr>
                            <td style="text-align: center;">
                                <?php if ($hasPhoto): 
                                    $photoPath = base_url('uploads/foto_santri/' . $fotoFileName);
                                ?>
                                    <img src="<?= $photoPath ?>" 
                                         alt="<?= esc($santri_item['nama_santri']) ?>" 
                                         class="mobile-photo"
                                         onclick="tampilkanFoto('<?= $photoPath ?>', '<?= esc($santri_item['nama_santri']) ?>', '<?= esc($santri_item['nis']) ?>')"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <div class="photo-placeholder" style="display: none;" 
                                         onclick="tampilkanFoto('<?= $photoPath ?>', '<?= esc($santri_item['nama_santri']) ?>', '<?= esc($santri_item['nis']) ?>')">
                                        <?= $initial ?>
                                    </div>
                                <?php else: ?>
                                    <div class="photo-placeholder">
                                        <?= $initial ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <div>
                                        <h3 style="margin: 0; font-size: 1rem;"><?= esc($santri_item['nama_santri']) ?></h3>
                                        <div style="color: var(--gray); font-size: 0.85rem;">
                                            <i class="fas fa-id-card"></i> NIS: <?= esc($santri_item['nis']) ?>
                                        </div>
                                        <span class="foto-status <?= $hasPhoto ? 'foto-ada' : 'foto-tidak-ada' ?>">
                                            <i class="fas <?= $hasPhoto ? 'fa-check-circle' : 'fa-times-circle' ?>"></i>
                                            <?= $hasPhoto ? 'Foto Tersedia' : 'Belum Ada Foto' ?>
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div style="display: flex; gap: 8px;">
                                    <button class="mobile-btn view-btn" onclick="lihatGrafik('<?= $santri_item['nis'] ?>')">
                                        <i class="fas fa-chart-bar"></i> Grafik
                                    </button>
                                    <?php if ($id_role == 2): ?>
                                    <button class="mobile-btn upload-btn" onclick="uploadFoto('<?= $santri_item['nis'] ?>', '<?= esc($santri_item['nama_santri']) ?>')">
                                        <i class="fas fa-camera"></i> Foto
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Mobile Cards -->
            <div class="mobile-cards">
                <?php foreach ($santri as $santri_item): ?>
                    <?php
                    $fotoFileName = $santri_item['foto'] ?? null;
                    $hasPhoto = !empty($fotoFileName);
                    $initial = substr($santri_item['nama_santri'], 0, 1);
                    ?>
                    <div class="mobile-card">
                        <div class="mobile-card-header">
                            <?php if ($hasPhoto): 
                                $photoPath = base_url('uploads/foto_santri/' . $fotoFileName);
                            ?>
                                <img src="<?= $photoPath ?>" 
                                     alt="<?= esc($santri_item['nama_santri']) ?>" 
                                     class="mobile-photo"
                                     onclick="tampilkanFoto('<?= $photoPath ?>', '<?= esc($santri_item['nama_santri']) ?>', '<?= esc($santri_item['nis']) ?>')"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="photo-placeholder" style="display: none;" 
                                     onclick="tampilkanFoto('<?= $photoPath ?>', '<?= esc($santri_item['nama_santri']) ?>', '<?= esc($santri_item['nis']) ?>')">
                                    <?= $initial ?>
                                </div>
                            <?php else: ?>
                                <div class="photo-placeholder">
                                    <?= $initial ?>
                                </div>
                            <?php endif; ?>
                            <div class="mobile-santri-info">
                                <h3><?= esc($santri_item['nama_santri']) ?></h3>
                                <div class="nis">
                                    <i class="fas fa-id-card"></i> NIS: <?= esc($santri_item['nis']) ?>
                                </div>
                                <span class="foto-status <?= $hasPhoto ? 'foto-ada' : 'foto-tidak-ada' ?>">
                                    <i class="fas <?= $hasPhoto ? 'fa-check-circle' : 'fa-times-circle' ?>"></i>
                                    <?= $hasPhoto ? 'Foto Tersedia' : 'Belum Ada Foto' ?>
                                </span>
                            </div>
                        </div>
                        <div class="mobile-actions">
                            <button class="mobile-btn view-btn" onclick="lihatGrafik('<?= $santri_item['nis'] ?>')">
                                <i class="fas fa-chart-bar"></i> Lihat Grafik
                            </button>
                            <?php if ($id_role == 2): ?>
                            <button class="mobile-btn upload-btn" onclick="uploadFoto('<?= $santri_item['nis'] ?>', '<?= esc($santri_item['nama_santri']) ?>')">
                                <i class="fas fa-camera"></i> Upload Foto
                            </button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Modal untuk Grafik -->
    <div id="grafikModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="modalSantriName">
                    <i class="fas fa-chart-line"></i> Grafik Pelanggaran
                </h2>
                <button class="close-btn" onclick="tutupModal()">&times;</button>
            </div>
            
            <div class="modal-body">
                <div class="semester-info">
                    <span class="semester-badge semester-ganjil">
                        <i class="fas fa-sun"></i> Semester Ganjil: Juli - Desember
                    </span>
                    <span class="semester-badge semester-genap">
                        <i class="fas fa-snowflake"></i> Semester Genap: Januari - Juni
                    </span>
                </div>

                <div class="chart-container">
                    <!-- Chart akan dimuat di sini -->
                </div>
                
                <div style="text-align: center; margin-top: 10px;">
                    <button class="btn btn-back" onclick="tutupModal()" style="padding: 12px 30px;">
                        <i class="fas fa-times"></i> Tutup Grafik
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal untuk Foto -->
    <div id="fotoModal" class="modal foto-modal">
        <div class="modal-content">
            <div class="modal-body" style="text-align: center; padding: 0;">
                <img id="fotoFull" class="foto-full" src="" alt="Foto Santri">
                <div class="foto-info">
                    <h4 id="fotoSantriName"></h4>
                    <p id="fotoSantriNis"></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal untuk Upload Foto - DIPINDAHKAN ke sini -->
    <div id="uploadModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">
                    <i class="fas fa-camera"></i> Upload Foto Santri
                </h2>
                <button class="close-btn" onclick="tutupUploadModal()">&times;</button>
            </div>
            
            <div class="modal-body">
                <form id="uploadForm" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="nis" id="uploadNis">
                    
                    <div style="margin-bottom: 20px;">
                        <label style="font-weight: 600; margin-bottom: 8px; display: block;">Santri:</label>
                        <div id="uploadSantriName" style="padding: 12px; background: var(--light); border-radius: 8px; font-weight: 600; color: var(--secondary);">
                            <!-- Nama santri akan diisi via JavaScript -->
                        </div>
                    </div>
                    
                    <div style="margin-bottom: 20px;">
                        <label style="font-weight: 600; margin-bottom: 8px; display: block;">Pilih Foto:</label>
                        <input type="file" name="foto" id="fileInput" accept="image/jpeg,image/jpg,image/png" required 
                               style="width: 100%; padding: 10px; border: 2px solid var(--border); border-radius: 8px;"
                               onchange="previewImage(this)">
                        <small style="color: var(--gray); margin-top: 5px; display: block;">
                            <i class="fas fa-info-circle"></i> Format: JPG, JPEG, PNG | Maksimal: 2MB
                        </small>
                    </div>

                    <div id="imagePreview" style="display: none; text-align: center; margin: 20px 0;">
                        <p style="margin: 0 0 10px 0; font-weight: 600; color: var(--secondary);">Preview Foto:</p>
                        <img id="previewImg" style="max-width: 200px; max-height: 200px; border-radius: 10px; border: 3px solid var(--border);" src="" alt="Preview Foto">
                    </div>
                    
                    <div style="text-align: center; margin-top: 25px;">
                        <button type="submit" class="btn" style="background: var(--success);">
                            <i class="fas fa-upload"></i> Upload Foto
                        </button>
                        <button type="button" class="btn btn-back" onclick="tutupUploadModal()">
                            <i class="fas fa-times"></i> Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    let grafikChart = null;

    // Search functionality
    const searchInput = document.getElementById('searchInput');
    const searchResults = document.getElementById('searchResults');
    const noResults = document.getElementById('noResults');

    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase().trim();
            const desktopRows = document.querySelectorAll('.desktop-table tbody tr');
            const mobileCards = document.querySelectorAll('.mobile-card');
            let resultsCount = 0;
            
            // Search in desktop table
            desktopRows.forEach(row => {
                const nama = row.querySelector('h3')?.textContent.toLowerCase() || '';
                const nis = row.querySelector('.nis')?.textContent.toLowerCase() || '';
                
                if (nama.includes(searchTerm) || nis.includes(searchTerm)) {
                    row.style.display = '';
                    resultsCount++;
                } else {
                    row.style.display = 'none';
                }
            });
            
            // Search in mobile cards
            mobileCards.forEach(card => {
                const nama = card.querySelector('.mobile-santri-info h3')?.textContent.toLowerCase() || '';
                const nis = card.querySelector('.mobile-santri-info .nis')?.textContent.toLowerCase() || '';
                
                if (nama.includes(searchTerm) || nis.includes(searchTerm)) {
                    card.style.display = 'flex';
                    resultsCount++;
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
                    '#6c757d';
            }
            
            // Show/hide no results message
            if (noResults) {
                if (searchTerm && resultsCount === 0) {
                    noResults.style.display = 'block';
                } else {
                    noResults.style.display = 'none';
                }
            }
        });
    }

    // Fungsi untuk menampilkan foto dalam modal
    function tampilkanFoto(photoPath, nama, nis) {
        document.getElementById('fotoFull').src = photoPath;
        document.getElementById('fotoSantriName').textContent = nama;
        document.getElementById('fotoSantriNis').textContent = 'NIS: ' + nis;
        document.getElementById('fotoModal').style.display = 'flex';
    }

    function tutupFotoModal() {
        document.getElementById('fotoModal').style.display = 'none';
    }

    function lihatGrafik(nis) {
        document.getElementById('modalSantriName').innerHTML = '<i class="fas fa-chart-line"></i> Memuat data...';
        document.getElementById('grafikModal').style.display = 'flex';
        
        // Destroy existing chart
        if (grafikChart) {
            grafikChart.destroy();
            grafikChart = null;
        }

        // Show loading state
        const chartContainer = document.querySelector('.chart-container');
        chartContainer.innerHTML = '<div class="loading"><div class="loading-spinner"></div>Memuat grafik...</div>';

        fetch(`/dashboard/get_data_grafik/${nis}?tahun_ajaran=<?= $tahun_ajaran ?? '2025/2026' ?>`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    chartContainer.innerHTML = '<div class="no-data"><i class="fas fa-exclamation-circle"></i><p>' + data.error + '</p></div>';
                    return;
                }
                
                document.getElementById('modalSantriName').innerHTML = `<i class="fas fa-chart-line"></i> ${data.nama_santri}`;
                
                // Clear container and create new canvas
                chartContainer.innerHTML = '<canvas id="grafikChart"></canvas>';
                const ctx = document.getElementById('grafikChart').getContext('2d');
                
                grafikChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.bulan,
                        datasets: [{
                            label: 'Poin Pelanggaran',
                            data: data.pelanggaran,
                            borderColor: '#e74c3c',
                            backgroundColor: 'rgba(231, 76, 60, 0.15)',
                            borderWidth: 3,
                            tension: 0.3,
                            fill: true,
                            pointBackgroundColor: '#ffffff',
                            pointBorderColor: '#e74c3c',
                            pointBorderWidth: 2,
                            pointRadius: 6,
                            pointHoverRadius: 8,
                            pointHoverBackgroundColor: '#e74c3c',
                            pointHoverBorderColor: '#ffffff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top',
                                labels: {
                                    color: '#2c3e50',
                                    font: {
                                        size: 14,
                                        weight: '600'
                                    },
                                    padding: 20
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(44, 62, 80, 0.9)',
                                titleColor: '#ffffff',
                                bodyColor: '#ffffff',
                                borderColor: '#3498db',
                                borderWidth: 1,
                                cornerRadius: 8,
                                displayColors: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.1)'
                                },
                                ticks: {
                                    color: '#6c757d',
                                    font: {
                                        size: 12
                                    },
                                    stepSize: 10
                                },
                                title: {
                                    display: true,
                                    text: 'Jumlah Poin Pelanggaran',
                                    color: '#2c3e50',
                                    font: {
                                        size: 14,
                                        weight: '600'
                                    }
                                }
                            },
                            x: {
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.1)'
                                },
                                ticks: {
                                    color: '#6c757d',
                                    font: {
                                        size: 12
                                    }
                                },
                                title: {
                                    display: true,
                                    text: 'Bulan',
                                    color: '#2c3e50',
                                    font: {
                                        size: 14,
                                        weight: '600'
                                    }
                                }
                            }
                        },
                        interaction: {
                            mode: 'index',
                            intersect: false
                        },
                        animation: {
                            duration: 1000,
                            easing: 'easeOutQuart'
                        }
                    }
                });
            })
            .catch(error => {
                console.error('Error:', error);
                chartContainer.innerHTML = '<div class="no-data"><i class="fas fa-exclamation-circle"></i><p>Gagal memuat grafik</p></div>';
            });
    }

    function tutupModal() {
        document.getElementById('grafikModal').style.display = 'none';
    }

    // Function untuk upload foto - DIPERBAIKI
    function uploadFoto(nis, nama) {
        console.log('🔄 Membuka modal upload untuk:', nama, 'NIS:', nis);
        
        document.getElementById('uploadNis').value = nis;
        document.getElementById('uploadSantriName').textContent = nama + ' (NIS: ' + nis + ')';
        document.getElementById('uploadModal').style.display = 'flex';
        
        // Reset form
        document.getElementById('imagePreview').style.display = 'none';
        document.getElementById('fileInput').value = '';
    }

    function tutupUploadModal() {
        document.getElementById('uploadModal').style.display = 'none';
        document.getElementById('uploadForm').reset();
        document.getElementById('imagePreview').style.display = 'none';
    }

    function previewImage(input) {
        const preview = document.getElementById('imagePreview');
        const previewImg = document.getElementById('previewImg');
        
        if (input.files && input.files[0]) {
            const file = input.files[0];
            
            // Validasi ukuran file (max 2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran file terlalu besar! Maksimal 2MB.');
                input.value = '';
                preview.style.display = 'none';
                return;
            }
            
            // Validasi tipe file
            const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
            if (!validTypes.includes(file.type)) {
                alert('Format file tidak didukung! Gunakan JPG, JPEG, atau PNG.');
                input.value = '';
                preview.style.display = 'none';
                return;
            }
            
            const reader = new FileReader();
            
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                preview.style.display = 'block';
            }
            
            reader.readAsDataURL(file);
        } else {
            preview.style.display = 'none';
        }
    }

    // Handle form submission untuk upload foto
    document.getElementById('uploadForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const nis = document.getElementById('uploadNis').value;
        
        console.log('🔄 Upload attempt - NIS:', nis);
        
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        // Tampilkan loading state
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Uploading...';
        submitBtn.disabled = true;
        
        fetch('<?= base_url('/dashboard/upload_foto') ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            console.log('📨 Response status:', response.status);
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log('📊 Server response:', data);
            
            if (data.success) {
                alert('✅ ' + data.message);
                tutupUploadModal();
                // Refresh halaman setelah 1 detik
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                alert('❌ ' + (data.error || 'Gagal upload foto'));
            }
        })
        .catch(error => {
            console.error('❌ Upload error:', error);
            alert('❌ Error: ' + error.message);
        })
        .finally(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        });
    });

    // Update event listener untuk close modal
    window.addEventListener('click', function(event) {
        if (event.target === document.getElementById('grafikModal')) {
            tutupModal();
        }
        if (event.target === document.getElementById('fotoModal')) {
            tutupFotoModal();
        }
        if (event.target === document.getElementById('uploadModal')) {
            tutupUploadModal();
        }
    });

    // Update escape key handler
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            tutupModal();
            tutupFotoModal();
            tutupUploadModal();
        }
    });
    </script>
</body>
</html>