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
        /* ==== GLOBAL ==== */
        * { box-sizing: border-box; }
        body {
            font-family: "Segoe UI", sans-serif;
            background: #eef2f7;
            margin: 0;
            padding: 0;
            color: #333;
        }

        /* ==== CONTAINER ==== */
        .container {
            max-width: 800px;
            margin: 40px auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            padding: 30px 40px;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ==== HEADER ==== */
        h1 {
            font-size: 1.8rem;
            text-align: center;
            color: #2c3e50;
            margin-bottom: 25px;
        }

        /* ==== INFO BOX ==== */
        .info-box {
            background: #e9f6ff;
            border-left: 5px solid #3498db;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            font-size: 15px;
            line-height: 1.6;
        }

        /* ==== FORM ELEMENTS ==== */
        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: 600;
            margin-bottom: 6px;
            color: #34495e;
        }

        input, select, textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #d0d7de;
            border-radius: 6px;
            font-size: 14px;
            background: #fafbfc;
            transition: all 0.3s;
        }

        input:focus, select:focus, textarea:focus {
            border-color: #3498db;
            background: #fff;
            outline: none;
            box-shadow: 0 0 5px rgba(52,152,219,0.2);
        }

        textarea {
            resize: vertical;
            min-height: 120px;
        }

        /* ==== FORMAT HELP ==== */
        .format-help {
            background: #f9fafb;
            border-left: 3px solid #ddd;
            padding: 10px 12px;
            font-size: 12.5px;
            border-radius: 5px;
            margin-top: 6px;
            color: #555;
        }

        /* ==== BUTTONS ==== */
        .form-actions {
            text-align: center;
            margin-top: 30px;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-block;
            text-decoration: none;
            color: white;
            margin: 0 8px;
        }

        .btn-submit {
            background: #3498db;
        }

        .btn-submit:hover {
            background: #2980b9;
            transform: translateY(-1px);
        }

        .btn-cancel {
            background: #e74c3c;
        }

        .btn-cancel:hover {
            background: #c0392b;
            transform: translateY(-1px);
        }

        /* ==== RESPONSIVE ==== */
        @media (max-width: 600px) {
            .container {
                padding: 25px;
            }

            h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>📝 Input <?= esc($jenis_sp ?? 'Surat Peringatan') ?></h1>

        <div class="info-box">
            <strong>Data Santri:</strong><br>
            NIS: <?= esc($santri['nis']) ?><br>
            Nama: <?= esc($santri['nama_santri']) ?>
        </div>

        <form action="<?= base_url('/sp/simpan') ?>" method="post">
            <input type="hidden" name="nis" value="<?= esc($santri['nis']) ?>">
            <input type="hidden" name="nama_santri" value="<?= esc($santri['nama_santri']) ?>">
            <input type="hidden" name="jenis_sp" value="<?= esc($jenis_sp ?? 'SP1') ?>">

            <div class="form-group">
                <label>Semester:</label>
                <select name="semester" required>
                    <option value="ganjil">Semester 1 (Ganjil)</option>
                    <option value="genap">Semester 2 (Genap)</option>
                </select>
            </div>

            <div class="form-group">
                <label>Tahun Ajaran:</label>
                <input type="text" name="tahun_ajaran" value="2025/2026" required>
            </div>

            <div class="form-group">
                <label>Nomor Registrasi:</label>
                <input type="text" name="nomor_registrasi" placeholder="Contoh: 102/SP/X/2024" required>
            </div>

            <div class="form-group">
                <label>Tanggal Surat:</label>
                <input type="date" name="tanggal_surat" value="<?= date('Y-m-d') ?>" required>
            </div>

            <!-- Content Pembuka -->
            <div class="form-group">
                <label>Content Pembuka:</label>
                <textarea name="content_pembuka" required><?php 
                if(($jenis_sp ?? 'SP1') === 'SP1'): ?>
Berdasarkan catatan segenap dewan asatidz bahwa Ananda <?= esc($santri['nama_santri']) ?> memiliki poin pelanggaran melebihi 100 serta pertimbangan sikap dan perilaku.
<?php elseif(($jenis_sp ?? 'SP1') === 'SP2'): ?>
Berdasarkan catatan segenap dewan asatidz bahwa Ananda <?= esc($santri['nama_santri']) ?> memiliki poin pelanggaran melebihi 200 serta pertimbangan sikap dan perilaku.
<?php else: ?>
<?php endif; ?></textarea>
                <div class="format-help">
                    <strong>Format Teks:</strong><br>
                    - //b teks tebal //b<br>
                    - //i teks miring //i<br>
                    - //u teks bergaris bawah //u<br>
                    Contoh: //b Dalam kurun waktu //b //i 1 semester //i //u kedepan //u
                </div>
            </div>

            <!-- Isi Teguran -->
            <div class="form-group">
                <label>Isi Teguran (Indented Content):</label>
                <textarea name="isi_teguran" required><?php 
if(($jenis_sp ?? 'SP1') === 'SP1'): ?>
1. Wajib mengikuti program pembinaan khusus selama //b1 bulan//b
2. Dikenakan sanksi tambahan berupa //bpembersihan area pesantren//b selama 2 minggu
3. Jika dalam kurun waktu //b1 semester//b kedepan ananda masih memiliki poin pelanggaran di atas 100 serta sikap dan perilakunya belum berubah menjadi lebih baik, maka akan dikenakan sanksi berupa //bSurat Peringatan Kedua//b
<?php elseif(($jenis_sp ?? 'SP1') === 'SP2'): ?>
1. Wajib membayar SPP Normal sebesar //bRp.1.750.000,-//b selama satu bulan
2. Dikenakan sanksi //bpembersihan area pesantren//b selama 1 bulan
3. Jika dalam kurun waktu //b1 semester//b kedepan ananda masih memiliki poin pelanggaran di atas 200 serta sikap dan perilaku nya belum berubah menjadi lebih baik, maka akan dikenakan sanksi berupa //bSurat Peringatan Ketiga//b
<?php else: ?>
1. Wajib membayar denda sebesar //bRp. 3.000.000,-//b
2. Dikenakan skorsing selama //b2 minggu//b
3. Orang tua/wali diwajibkan menghadap pimpinan pesantren
4. Jika tidak menunjukkan perbaikan dalam //b1 bulan//b, akan dikenakan sanksi //bDO (Drop Out)//b
<?php endif; ?></textarea>

                <div class="format-help">
                    <strong>Format List:</strong><br>
                    - Gunakan angka 1., 2., dst (otomatis di-indent saat ditampilkan)<br>
                    - Gunakan //b untuk tebal, //i untuk miring, //u untuk garis bawah
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-submit">📄 Generate <?= esc($jenis_sp ?? 'Surat Peringatan') ?></button>
                <a href="<?= base_url('/rekapan') ?>" class="btn btn-cancel" onclick="return confirmCancel()">❌ Batal</a>
            </div>
        </form>
    </div>

    <script>
        function confirmCancel() {
            return confirm('Apakah Anda yakin ingin membatalkan? Data yang sudah diinput akan hilang.');
        }
    </script>

</body>
</html>
