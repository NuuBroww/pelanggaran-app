<?php
date_default_timezone_set('Asia/Jakarta');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pelanggaran - <?= esc($pelanggaran['nis'] ?? '') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .card-header { background-color: #3498db; color: white; }
        .form-container { max-width: 600px; margin: 0 auto; }
        .badge-ringan { background-color: #27ae60; }
        .badge-sedang { background-color: #f39c12; }
        .badge-berat { background-color: #e74c3c; }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="form-container">
            <!-- Header -->
            <div class="card mb-4">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-edit"></i> Edit Pelanggaran
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>NIS:</strong> <?= esc($pelanggaran['nis'] ?? '') ?></p>
                            <p><strong>Bulan:</strong> <?= esc($pelanggaran['bulan'] ?? '') ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Tanggal Input:</strong> <?= date('d/m/Y', strtotime($pelanggaran['tanggal'] ?? '')) ?></p>
                            <p><strong>Diedit Oleh:</strong> <?= esc($nama ?? 'System') ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notifikasi -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <!-- Form Edit -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Form Edit Data</h5>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('pelanggaran/update_poin') ?>" method="post">
                        <input type="hidden" name="id" value="<?= esc($pelanggaran['id'] ?? '') ?>">
                        <input type="hidden" name="nis" value="<?= esc($pelanggaran['nis'] ?? '') ?>">
                        <input type="hidden" name="bulan" value="<?= esc($pelanggaran['bulan'] ?? '') ?>">
                        <input type="hidden" name="tahun_ajaran" value="<?= esc($tahun_ajaran ?? '2025/2026') ?>">
                        <input type="hidden" name="semester" value="<?= esc($semester ?? 'ganjil') ?>">

                        <div class="mb-3">
                            <label for="poin" class="form-label">
                                <i class="fas fa-star"></i> Poin Pelanggaran
                            </label>
                            <input type="number" class="form-control" id="poin" name="poin" 
                                   value="<?= esc($pelanggaran['poin'] ?? '') ?>" 
                                   min="1" max="100" required>
                            <div class="form-text">Poin harus antara 1-100</div>
                        </div>

                        <div class="mb-3">
                            <label for="keterangan" class="form-label">
                                <i class="fas fa-align-left"></i> Keterangan
                            </label>
                            <input type="text" class="form-control" id="keterangan" name="keterangan" 
                                   value="<?= esc($pelanggaran['keterangan'] ?? '') ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="detail_pelanggaran" class="form-label">
                                <i class="fas fa-info-circle"></i> Detail Pelanggaran
                            </label>
                            <textarea class="form-control" id="detail_pelanggaran" name="detail_pelanggaran" 
                                      rows="3"><?= esc($pelanggaran['detail_pelanggaran'] ?? '') ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="kategori" class="form-label">
                                <i class="fas fa-tag"></i> Kategori
                            </label>
                            <select class="form-select" id="kategori" name="kategori" required>
                                <option value="">-- Pilih Kategori --</option>
                                <option value="ringan" <?= ($pelanggaran['kategori'] ?? '') == 'ringan' ? 'selected' : '' ?>>🟢 Ringan</option>
                                <option value="sedang" <?= ($pelanggaran['kategori'] ?? '') == 'sedang' ? 'selected' : '' ?>>🟡 Sedang</option>
                                <option value="berat" <?= ($pelanggaran['kategori'] ?? '') == 'berat' ? 'selected' : '' ?>>🔴 Berat</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="alasan_edit" class="form-label">
                                <i class="fas fa-sticky-note"></i> Alasan Edit (Opsional)
                            </label>
                            <textarea class="form-control" id="alasan_edit" name="alasan_edit" 
                                      rows="3" placeholder="Contoh: Koreksi data, penyesuaian poin, dll..."></textarea>
                            <div class="form-text">Catatan ini akan disimpan di riwayat edit</div>
                        </div>

                        <div class="d-flex gap-2">
                            <a href="<?= base_url("pelanggaran/update_pelanggaran/{$pelanggaran['nis']}/{$pelanggaran['bulan']}?tahun_ajaran={$tahun_ajaran}&semester={$semester}") ?>" 
                               class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Pelanggaran
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>