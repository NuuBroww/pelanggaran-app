<?php
date_default_timezone_set('Asia/Jakarta');

// Tentukan semester dan label berdasarkan parameter
$semester = $_GET['semester'] ?? 'ganjil';
$semesterLabel = $semester === 'ganjil' ? '1 (Ganjil)' : '2 (Genap)';
$tahunAjaran = '2025/2026';
?>
<?php
// Cek jika semester 2 diakses
if ($semester === 'genap') {
    $currentMonth = date('n'); // 1-12
    $currentYear = date('Y');
    
    // Jika belum Januari 2026, redirect ke semester 1
    if ($currentYear < 2026 || ($currentYear == 2025 && $currentMonth < 12)) {
        echo "<script>
            alert('Data Semester 2 akan tersedia mulai Januari 2026. Dialihkan ke Semester 1.');
            window.location.href = '?semester=ganjil';
        </script>";
        // atau bisa juga redirect langsung
        // header('Location: ?semester=ganjil');
        // exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Rekapan Pelanggaran Santri - Semester <?= $semesterLabel ?></title>
  <style>
    table { border-collapse: collapse; width: 100%; }
    th, td { border:1px solid #333; padding:8px; text-align:center; }

    .red {
      background-color: #ef9a9a;
      color: #b71c1c;
      font-weight: bold;
      border-bottom: 2px solid #c62828;
    }

    .header {
      display: flex;
      align-items: center;
      margin-bottom: 20px;
      gap: 15px;
    }

    .header img.logo {
      max-height: 120px;
      width: auto;
      display: block;
    }

    .header .text-container {
      flex-grow: 1;
      text-align: center;
    }

    .header .arabic {
      font-family: 'Traditional Arabic', serif;
      font-size: 22px;
      margin-bottom: 4px;
    }

    .header .title {
      font-weight: bold;
      font-size: 20px;
      margin-bottom: 6px;
    }

    button, .btn {
      padding:5px 10px;
      cursor:pointer;
      background:#3498db;
      color:#fff;
      border:none;
      border-radius:6px;
      text-decoration:none;
      display: inline-block;
      margin: 2px;
      font-size: 12px;
    }
    
    .btn-sp1 { background: #f39c12; }
    .btn-sp2 { background: #e74c3c; }
    .btn-sp3 { background: #c0392b; }
    .btn-semester { background: #16a085; }
    
    button:hover, .btn:hover {
      opacity: 0.8;
    }

    .semester-selector {
      text-align: center;
      margin: 15px 0;
      padding: 10px;
      background: #f8f9fa;
      border-radius: 8px;
    }

    .semester-active {
      background: #2ecc71 !important;
      font-weight: bold;
    }

    @media print {
      .header img.logo {
        max-height: 60px;
        width: auto;
        display: block;
        margin: 0 auto 10px auto;
      }

      .no-print {
        display: none !important;
      }

      .semester-selector {
        display: none;
      }

      /* TAMBAHKAN INI UNTUK WARNA MERAH SAAT CETAK */
      .red {
        background-color: #ef9a9a !important;
        color: #b71c1c !important;
        font-weight: bold !important;
        border-bottom: 2px solid #c62828 !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
      }

      /* Untuk browser yang mendukung */
      tr.red td {
        background-color: #ef9a9a !important;
        color: #b71c1c !important;
        font-weight: bold !important;
      }
      
      /* Force background colors in print */
      table {
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
      }
    }
    /* Style untuk modal */
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
    z-index: 1000;
    justify-content: center;
    align-items: center;
}

.modal-content {
    background: white;
    padding: 30px;
    border-radius: 10px;
    text-align: center;
    max-width: 400px;
    margin: 20px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}
  </style>
</head>
<body>

<div class="header">
  <img src="<?= base_url('assets/img/logo.png') ?>" alt="Logo" class="logo" />
  <div class="text-container">
    <div class="arabic">معهد التربية الإسلامية الرحمن لتحفيظ القرآن والتكنولوجيا</div>
    <div class="title">PESANTREN TAHFIDZ QUR'AN & DIGITAL AR-RAHMAN</div>
    <div style="font-size: 13px;">
      Ruko Hexa Green Blok C8 - C9 Jl. Raya Kalimalang, Kel. Jatimulya,<br>
      Kec. Tambun Selatan Kab. Bekasi - Jawa Barat 17510<br>
      Telp. 0812 8361 2352
    </div>
  </div>
</div>

<hr style="margin-top: 20px;">

<div class="semester-selector no-print">
    <form method="get" action="" id="rekapanForm" style="display: flex; gap: 10px; align-items: center; justify-content: center; flex-wrap: wrap;">
        <div>
            <label><strong>Tahun Ajaran:</strong></label>
            <select name="tahun_ajaran" id="tahunAjaranSelect" style="padding: 8px; border-radius: 5px; border: 1px solid #ddd; min-width: 150px;">
                <?php 
                if (!empty($availableTahunAjaran) && is_array($availableTahunAjaran)):
                    foreach($availableTahunAjaran as $tahun):
                        $tahunValue = is_array($tahun) ? ($tahun['tahun_ajaran'] ?? $tahun[0] ?? $tahun) : $tahun;
                        $tahunText = $tahunValue;
                ?>
                    <option value="<?= $tahunValue ?>" <?= $tahun_ajaran == $tahunValue ? 'selected' : '' ?>>
                        <?= $tahunText ?>
                    </option>
                <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
        
        <div>
            <label><strong>Semester:</strong></label>
            <select name="semester" id="semesterSelect" style="padding: 8px; border-radius: 5px; border: 1px solid #ddd;">
                <option value="ganjil" <?= $semester == 'ganjil' ? 'selected' : '' ?>>Semester Ganjil</option>
                <option value="genap" <?= $semester == 'genap' ? 'selected' : '' ?>>Semester Genap</option>
            </select>
        </div>
        
        <button type="submit" style="padding: 8px 15px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;">
            Terapkan
        </button>
        
        <!-- Tombol untuk generate tahun ajaran baru -->
        <!-- <button type="button" id="generateTahunBaru" style="padding: 8px 15px; background: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer;">
            Generate Tahun 2026/2027
        </button> -->
    </form>
</div>


<h3 style="text-align:center;">Rekapan Point Semester <?= $semesterLabel ?> Tahun Ajaran <?= $tahunAjaran ?></h3>

<!-- Info Semester -->
<div style="text-align: center; margin-bottom: 15px; font-style: italic;">
  Periode: 
  <?= $semester === 'ganjil' ? 'Juli - Desember 2025' : 'Januari - Juni 2026' ?>
</div>

<!-- Tombol kembali -->
<div class="no-print" style="margin: 20px 0; text-align: center;">
  <a href="<?= base_url('/dashboard') ?>" class="btn">⬅️ Back to Dashboard</a>
   <?php if ($id_role == 2): ?>
    <button onclick="window.print()" class="btn">🖨️ Cetak Rekapan</button>
  <?php endif; ?>
</div>

<table>
  <thead>
    <tr>
      <th>No</th>
      <th>NIS</th>
      <th>Nama Santri</th>
      <th>Total Poin</th>
      <th>Seluruh Pelanggaran</th>
      <th>Bulan Poin Terbanyak</th>
      <th>Level SP</th>
      <?php if ($id_role == 2): ?>
      <th class="no-print">Aksi</th>
      <?php endif; ?>
    </tr>
  </thead>
  <tbody>
    <?php if (!empty($rekapan)): ?>
      <?php 
      $no = 1;
      foreach ($rekapan as $row): 
        // PERBAIKAN: Ambil data berdasarkan semester yang dipilih
        if ($semester === 'ganjil') {
          $totalPoin = $row['total_poin'] ?? 0;
          $jenisSP = $row['jenis_sp'] ?? '-';
        } else {
          // Untuk semester genap, ambil dari data yang dikirim controller
          $totalPoin = $poinData[$row['nis']] ?? 0; // GANTI: poinGenap menjadi poinData
          $jenisSP = $spData[$row['nis']] ?? '-';   // GANTI: spDataGenap menjadi spData
        }
      ?>
        <tr class="<?= ($totalPoin >= 100) ? 'red' : '' ?>">
          <td><?= $no++; ?></td>
          <td><?= esc($row['nis']); ?></td>
          <td style="text-align: left;"><?= esc($row['nama_santri']); ?></td>
          <td><strong><?= $totalPoin ?></strong></td>
          <td style="text-align: left; max-width: 300px;">
            <?php 
            if ($semester === 'ganjil') {
              echo esc($row['seluruh_pelanggaran'] ?? '-');
            } else {
              // Untuk semester genap, tampilkan pelanggaran dari bulan Januari-Juni
              echo esc($row['pelanggaran_genap'] ?? '-');
            }
            ?>
          </td>
          <td>
            <?php 
            if ($semester === 'ganjil') {
              echo esc($row['bulan_terbanyak'] ?? '-');
            } else {
              // Untuk semester genap, cari bulan dengan poin terbanyak dari Jan-Juni
              echo esc($row['bulan_terbanyak_genap'] ?? '-');
            }
            ?>
          </td>
          <td>
            <?php if ($jenisSP !== '-'): ?>
              <span class="sp-badge <?= strtolower($jenisSP) ?>"><?= $jenisSP ?></span>
            <?php else: ?>
              -
            <?php endif; ?>
          </td>
          <!-- Di bagian AKSI - Handle tombol cetak berdasarkan role -->
           <?php if($id_role == 2) : ?>
<td class="no-print">
    <?php if ($row['total_poin'] >= 100): ?>
        <?php if ($row['total_poin'] >= 100 && $row['total_poin'] < 200): ?>
            <?php if ($id_role == 2): ?>
                <!-- Role 2: Bisa cetak SP1 -->
                <a href="<?= base_url('/sp1/' . esc($row['nis'])) ?>?semester=<?= $semester ?>&tahun_ajaran=<?= $tahun_ajaran ?>" class="btn btn-sp1">Buat SP1</a>
            <?php else: ?>
                <!-- Role 1 & 3: Hanya lihat status -->
                <span class="sp-badge sp1">SP1</span>
            <?php endif; ?>
        <?php elseif ($row['total_poin'] >= 200 && $row['total_poin'] < 300): ?>
            <?php if ($id_role == 2): ?>
                <!-- Role 2: Bisa cetak SP2 -->
                <a href="<?= base_url('/sp2/' . esc($row['nis'])) ?>?semester=<?= $semester ?>&tahun_ajaran=<?= $tahun_ajaran ?>" class="btn btn-sp2">Buat SP2</a>
            <?php else: ?>
                <!-- Role 1 & 3: Hanya lihat status -->
                <span class="sp-badge sp2">SP2</span>
            <?php endif; ?>
        <?php elseif ($row['total_poin'] >= 300): ?>
            <?php if ($id_role == 2): ?>
                <!-- Role 2: Bisa cetak SP3 -->
                <a href="<?= base_url('/sp3/' . esc($row['nis'])) ?>?semester=<?= $semester ?>&tahun_ajaran=<?= $tahun_ajaran ?>" class="btn btn-sp3">Buat SP3</a>
            <?php else: ?>
                <!-- Role 1 & 3: Hanya lihat status -->
                <span class="sp-badge sp3">SP3</span>
            <?php endif; ?>
        <?php endif; ?>
    <?php endif; ?>
    <?php endif; ?>
</td>
        </tr>
      <?php endforeach; ?>
    <?php else: ?>
      <tr><td colspan="8" style="text-align:center;">Belum ada data rekapan untuk semester <?= $semesterLabel ?>.</td></tr>
    <?php endif; ?>
  </tbody>
</table>
<!-- Modal untuk Semester 2 -->
<div id="semester2Modal" class="modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); z-index: 1000; justify-content: center; align-items: center;">
    <div style="background: white; padding: 30px; border-radius: 10px; text-align: center; max-width: 400px; margin: 20px;">
        <h3 style="color: #e74c3c; margin-bottom: 15px;">⏳ Semester 2 Belum Tersedia</h3>
        <p style="margin-bottom: 20px; line-height: 1.5;">
            Data untuk Semester 2 (Genap) akan tersedia mulai <strong>Januari 2026</strong>.
            Silakan kembali ke Semester 1 untuk melihat data saat ini.
        </p>
        <button onclick="closeModal()" style="background: #3498db; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;">
            Mengerti
        </button>
    </div>
</div>

<div class="no-print" style="text-align: center; margin-top: 20px; color: #666; font-size: 12px;">
  <p>Dicetak pada: <?= date('d F Y H:i:s') ?> oleh <?= $namaAdmin ?? 'Admin' ?></p>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tahunAjaranSelect = document.getElementById('tahunAjaranSelect');
    const semesterSelect = document.getElementById('semesterSelect');
    const generateBtn = document.getElementById('generateTahunBaru');

    // **HANDLE GENERATE TAHUN BARU**
    generateBtn.addEventListener('click', function() {
        if (confirm('Generate data untuk tahun ajaran 2026/2027? Data poin akan dimulai dari 0.')) {
            // Redirect ke URL generate
            window.location.href = '<?= base_url('/rekapan/create_tahun_ajaran') ?>?tahun_ajaran=2026/2027';
        }
    });

    // **CEK JIKA TAHUN 2026/2027 MASIH KOSONG, TAMPILKAN WARNING**
    const selectedTahun = '<?= $tahun_ajaran ?>';
    if (selectedTahun === '2026/2027') {
        // Cek apakah ada data untuk tahun ini
        const hasData = <?= !empty($rekapan) && count($rekapan) > 0 && $rekapan[0]['total_poin'] > 0 ? 'true' : 'false' ?>;
        
        if (!hasData) {
            // Tampilkan modal/pemberitahuan
            showGenerateModal();
        }
    }

    function showGenerateModal() {
        // Buat modal sederhana
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
            <div style="background: white; padding: 20px; border-radius: 10px; text-align: center; max-width: 400px;">
                <h3>Data Belum Tersedia</h3>
                <p>Tahun ajaran 2026/2027 belum memiliki data. Generate data sekarang?</p>
                <div style="margin-top: 20px;">
                    <button onclick="generateTahun2027()" style="padding: 10px 20px; background: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer; margin-right: 10px;">
                        Ya, Generate
                    </button>
                    <button onclick="closeModal()" style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 5px; cursor: pointer;">
                        Nanti
                    </button>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        
        window.generateTahun2027 = function() {
            window.location.href = '<?= base_url('/rekapan/create_tahun_ajaran') ?>?tahun_ajaran=2026/2027';
        };
        
        window.closeModal = function() {
            document.body.removeChild(modal);
            // Kembali ke tahun sebelumnya
            window.location.href = '<?= base_url('/rekapan') ?>?tahun_ajaran=2025/2026&semester=ganjil';
        };
    }
});

// Fungsi untuk menampilkan modal semester 2
function showSemester2Modal() {
    document.getElementById('semester2Modal').style.display = 'flex';
}

// Fungsi untuk menutup modal
function closeModal() {
    document.getElementById('semester2Modal').style.display = 'none';
}

// Tutup modal ketika klik di luar konten
window.onclick = function(event) {
    const modal = document.getElementById('semester2Modal');
    if (event.target === modal) {
        closeModal();
    }
}
</script>

</body>
</html>