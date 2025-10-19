<?php
date_default_timezone_set('Asia/Jakarta');

function parseTextFormat($text) {
    $text = htmlspecialchars($text);
    // Gunakan modifier 's' untuk match across line breaks
    $text = preg_replace('/\/\/b(.*?)\/\/b/s', '<strong>$1</strong>', $text);
    $text = preg_replace('/\/\/i(.*?)\/\/i/s', '<em>$1</em>', $text);
    $text = preg_replace('/\/\/u(.*?)\/\/u/s', '<u>$1</u>', $text);
    return nl2br($text);
}

// FUNGSI KHUSUS UNTUK SP2 - Format list otomatis
function formatSP2Content($text) {
    $text = htmlspecialchars($text);
    
    // Jika mengandung format list (angka dengan titik)
    if (preg_match('/^\d+\./', $text)) {
        $lines = explode("\n", $text);
        $output = '';
        
        foreach ($lines as $line) {
            $line = trim($line);
            if (!empty($line)) {
                // Parse formatting tags
                $line = preg_replace('/\/\/b(.*?)\/\/b/s', '<strong>$1</strong>', $line);
                $line = preg_replace('/\/\/i(.*?)\/\/i/s', '<em>$1</em>', $line);
                $line = preg_replace('/\/\/u(.*?)\/\/u/s', '<u>$1</u>', $line);
                
                // Jika line dimulai dengan angka dan titik, format sebagai list item
                if (preg_match('/^(\d+)\.\s*(.*)/', $line, $matches)) {
                    $output .= '<div style="margin-bottom: 8px; text-align: justify;">';
                    $output .= '<strong>' . $matches[1] . '.</strong> ' . $matches[2];
                    $output .= '</div>';
                } else {
                    $output .= '<div style="margin-bottom: 8px; text-align: justify;">' . $line . '</div>';
                }
            }
        }
        return $output;
    } else {
        // Jika bukan list, gunakan parsing biasa
        return parseTextFormat($text);
    }
}
?>
<?php
function formatTanggalIndonesia($tanggal) {
    $bulanIndo = [
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];

    $tanggalObj = date_create($tanggal);
    $hari = date_format($tanggalObj, 'd');
    $bulan = $bulanIndo[(int)date_format($tanggalObj, 'm')];
    $tahun = date_format($tanggalObj, 'Y');

    return $hari . ' ' . $bulan . ' ' . $tahun;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SURAT PERINGATAN - <?= esc($sp['nama_santri']) ?></title>
    <style>
        @page { 
            margin: 1cm;
            size: A4 portrait;
        }
        body { 
            font-family: "Times New Roman", serif; 
            margin: 0; 
            padding: 0;
            font-size: 11pt;
            line-height: 1.5;
            color: #000;
            background-color: #fff;
        }
        .kop-surat {
            border-bottom: 3px double #333;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 8px;
        }
        .logo {
            width: 170px;
            height: 70px;
            object-fit: contain;
            margin-right: 12px;
            border-radius: 4px;
        }
        .header-text {
            text-align: center;
            flex: 1;
        }
        .arabic { 
            font-family: "Traditional Arabic", "Times New Roman", serif;
            font-size: 12pt;
            margin-bottom: 4px;
            font-weight: bold;
            line-height: 1.3;
        }
        .title { 
            font-size: 11pt; 
            font-weight: bold;
            margin-bottom: 4px;
            color: #000000;
        }
        .alamat {
            font-size: 9pt;
            line-height: 1.3;
        }
        .nomor-surat {
            text-align: center;
            margin: 15px 0;
            font-size: 13pt;
        }
        .content {
            text-align: justify;
            margin-bottom: 15px;
            padding: 0 8px;
        }
        .alamat-tujuan {
            margin-bottom: 20px;
            padding: 0 8px;
        }
        .ttd-section {
            margin-top: 60px;
        }
        .ttd-container {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            padding: 0 8px;
        }
        .ttd-kiri, .ttd-kanan {
            text-align: center;
            width: 45%;
        }
        .ttd-barcode {
            width: 100px;
            height: 100px;
            object-fit: contain;
            margin-bottom: 8px;
            border: 1px solid #ddd;
            padding: 4px;
        }
        .ttd-name {
            font-weight: bold;
            font-size: 10pt;
            margin-top: 4px;
            text-decoration: underline;
        }
        .jabatan {
            font-size: 9pt;
        }
        .sanksi-box {
            text-align: center;
            margin: 15px 0;
            font-weight: bold;
            font-size: 12pt;
            text-decoration: underline;
            padding: 0 8px;
        }
        .no-print { 
            display: block; 
        }
        
        /* Perbaikan untuk elemen teks yang diformat */
        strong {
            font-weight: bold;
        }
        em {
            font-style: italic;
        }
        u {
            text-decoration: underline;
        }
        
        @media print {
            .no-print { 
                display: none !important; 
            }
            body { 
                margin: 1cm;
                background-color: #fff;
            }
            .logo {
                filter: grayscale(100%);
            }
        }
        
        /* Tambahan untuk tampilan yang lebih baik */
        .content p {
            margin-bottom: 8px;
        }
        
        /* Styling untuk form edit */
        .edit-form {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 6px;
            margin: 15px 0;
            border: 1px solid #dee2e6;
        }
        .form-group {
            margin-bottom: 12px;
        }
        .form-group label {
            display: block;
            margin-bottom: 4px;
            font-weight: bold;
            font-size: 12px;
        }
        .form-control {
            width: 100%;
            padding: 6px 10px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 13px;
        }
        textarea.form-control {
            min-height: 100px;
            resize: vertical;
        }
        .btn {
            display: inline-block;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
            text-decoration: none;
            text-align: center;
        }
        .btn-primary {
            background-color: #007bff;
            color: white;
        }
        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
        .btn-success {
            background-color: #28a745;
            color: white;
        }
        .button-group {
            display: flex;
            gap: 8px;
            margin-top: 15px;
        }
        .format-info {
            font-size: 11px;
            color: #666;
            margin-top: 4px;
        }
    </style>
</head>
<body>
    <!-- Tombol Navigasi -->
    <div class="no-print" style="text-align: center; margin: 15px;">
        <button onclick="window.print()" class="btn btn-primary">🖨️ Cetak Surat</button>
        <button id="editBtn" class="btn btn-success">✏️ Edit Surat</button>
        <a href="<?= base_url('/rekapan') ?>" class="btn btn-secondary">📊 Kembali ke Rekapan</a>
    </div>

    <!-- Form Edit (Awalnya Tersembunyi) -->
    <div id="editForm" class="edit-form no-print" style="display: none;">
        <h3 style="margin-top: 0; font-size: 16px;">Edit Isi Surat Peringatan</h3>
        <form id="suratForm" method="post" action="<?= base_url('surat/update') ?>">
            <!-- CSRF Protection -->
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
            <input type="hidden" name="id" value="<?= esc($sp['id']) ?>">
            
            <div class="form-group">
                <label for="nama_santri">Nama Santri:</label>
                <input type="text" id="nama_santri" name="nama_santri" class="form-control" value="<?= esc($sp['nama_santri']) ?>" required>
            </div>
            
            <div class="form-group">
                <label for="tanggal_surat">Tanggal Surat:</label>
                <input type="date" id="tanggal_surat" name="tanggal_surat" class="form-control" value="<?= esc($sp['tanggal_surat']) ?>" required>
            </div>
            
            <div class="form-group">
                <label for="isi_teguran">Isi Teguran:</label>
                <textarea id="isi_teguran" name="isi_teguran" class="form-control" required><?= esc($sp['isi_teguran']) ?></textarea>
                <div class="format-info">
                    Format: //bteks//b untuk <strong>tebal</strong>, //iteks//i untuk <em>miring</em>, //uteks//u untuk <u>garis bawah</u>
                </div>
            </div>
            
            <div class="button-group">
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <button type="button" id="cancelBtn" class="btn btn-secondary">Batal</button>
            </div>
        </form>
    </div>

    <!-- Kop Surat dengan Logo -->
    <div class="kop-surat">
        <div class="logo-container">
            <img src="<?= base_url('assets/img/logo.png') ?>" alt="Logo" class="logo" />
            <div class="header-text">
                <div class="arabic">معهد التربية الإسلامية الرحمن لتحفيظ القرآن و التكنولوجيا</div>
                <div class="title">PESANTREN TAHFIDZ QUR'AN & DIGITAL AR-RAHMAN</div>
                <div class="alamat">
                    Ruko Hexa Green Blok C8 - C9 Jl. Raya Kalimalang, Kel. Jatimulya,<br>
                    Kec. Tambun Selatan Kab. Bekasi - Jawa Barat 17510 Telp.081283612352
                </div>
            </div>
        </div>
    </div>

    <!-- Nomor Surat -->
    <div class="nomor-surat">
        <strong><u>SURAT PERINGATAN</u></strong><br>
        No : <?= esc($sp['nomor_registrasi']) ?>
    </div>

    <!-- Alamat Tujuan -->
    <div class="alamat-tujuan">
        <strong>Kepada Ykh,</strong><br>
        <strong>Orang tua/Wali santri <?= esc($sp['nama_santri']) ?></strong><br>
        <strong>Di Tempat</strong>
    </div>

    <!-- Content - SEMUA DARI INPUTAN -->
    <div class="content">
    <p>Dengan hormat,</p>
    
    <!-- CONTENT PEMBUKA dari input pertama -->
    <?= parseTextFormat($sp['content_pembuka']) ?>
    
    <p>Maka, terhitung mulai tanggal (<?= date('d F Y', strtotime($sp['tanggal_surat'])) ?>) dikenakan sanksi administratif berupa:</p>
    
    <div class="sanksi-box">
        <strong>SURAT PERINGATAN <?= ($sp['jenis_sp'] === 'SP1') ? 'PERTAMA' : 'KEDUA' ?></strong>
    </div>
    
    <p>Dengan Surat Teguran ini, maka:</p>
    
    <!-- CONTENT DI-INDENT dari input kedua -->
    <div class="indented-block">
        <?= formatSP2Content($sp['isi_teguran']) ?>
    </div>
    
    <p>Demikian Surat Teguran ini dibuat untuk menjadi bahan evaluasi bersama.</p>
</div>

    <!-- Tanda Tangan dengan Barcode -->
    <div class="ttd-section">
        <div style="text-align: right; margin-bottom: 40px;">
            <div>Bekasi, <?= date('d F Y', strtotime($sp['tanggal_surat'])) ?></div>
        </div>
        
        <div class="ttd-container">
            <div class="ttd-kiri">
                <img src="<?= base_url('assets/img/barcode.gif') ?>" alt="Barcode TTD" class="ttd-barcode">
                <div class="ttd-name">Ziyad Khairy Al-Hafidz</div>
                <div class="jabatan">Kepala Pesantren</div>
            </div>
            
            <div class="ttd-kanan">
                <img src="<?= base_url('assets/img/barcode2.gif') ?>" alt="Barcode TTD" class="ttd-barcode">
                <div class="ttd-name">Murtadho Tumari, S.Pd</div>
                <div class="jabatan">Waka Kepesantrenan</div>
            </div>
        </div>
    </div>

    <script>
        // Fungsi untuk toggle form edit
        document.getElementById('editBtn').addEventListener('click', function() {
            document.getElementById('editForm').style.display = 'block';
        });
        
        document.getElementById('cancelBtn').addEventListener('click', function() {
            document.getElementById('editForm').style.display = 'none';
        });
        
        // Validasi form sebelum submit
        document.getElementById('suratForm').addEventListener('submit', function(e) {
            const namaSantri = document.getElementById('nama_santri').value.trim();
            const tanggalSurat = document.getElementById('tanggal_surat').value;
            const isiTeguran = document.getElementById('isi_teguran').value.trim();
            
            if (!namaSantri || !tanggalSurat || !isiTeguran) {
                e.preventDefault();
                alert('Semua field harus diisi!');
                return false;
            }
            
            return true;
        });

         // Notifikasi jika ada pesan sukses
    <?php if(isset($success)): ?>
        alert('<?= $success ?>');
    <?php endif; ?>

    // Atau jika menggunakan session flashdata
    <?php if(session()->getFlashdata('success')): ?>
        alert('<?= session()->getFlashdata('success') ?>');
    <?php endif; ?>
    </script>
</body>
</html>