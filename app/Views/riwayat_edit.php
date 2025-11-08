<?php
date_default_timezone_set('Asia/Jakarta');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Riwayat Edit Pelanggaran') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .badge-ringan { background-color: #27ae60; }
        .badge-sedang { background-color: #f39c12; }
        .badge-berat { background-color: #e74c3c; }
        .card-header { background-color: #f39c12; color: white; }
        .change-badge { 
            background-color: #3498db; 
            color: white;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 0.8rem;
        }
        .text-old { color: #e74c3c; text-decoration: line-through; }
        .text-new { color: #27ae60; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container mt-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="fas fa-edit"></i> Riwayat Edit Pelanggaran</h1>
            <div>
                <?php if ($nis): ?>
                    <a href="<?= base_url("pelanggaran/update_pelanggaran/{$nis}/Januari?tahun_ajaran={$tahun_ajaran}&semester={$semester}") ?>" class="btn btn-primary">
                        <i class="fas fa-arrow-left"></i> Kembali ke Pelanggaran
                    </a>
                <?php endif; ?>
                <a href="<?= base_url('/dashboard') ?>" class="btn btn-secondary">
                    <i class="fas fa-home"></i> Dashboard
                </a>
            </div>
        </div>

        <!-- Info Santri -->
        <?php if ($santri): ?>
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="fas fa-user"></i> Data Santri</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>NIS:</strong> <?= esc($santri['nis']) ?></p>
                            <p><strong>Nama:</strong> <?= esc($santri['nama_santri']) ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Tahun Ajaran:</strong> <?= esc($tahun_ajaran) ?></p>
                            <p><strong>Semester:</strong> <?= esc($semester) ?></p>
                        </div>
                    </div>
                    <div class="alert alert-warning mt-3">
                        <i class="fas fa-info-circle"></i> 
                        <strong>Total Edit:</strong> 
                        <span class="badge bg-warning fs-6"><?= count($riwayat) ?> kali edit</span>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i> 
                Pilih santri untuk melihat riwayat edit pelanggaran.
            </div>
        <?php endif; ?>

        <!-- Riwayat Edit -->
        <?php if (!empty($riwayat)): ?>
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list"></i> Daftar Riwayat Edit
                        <span class="badge bg-warning"><?= count($riwayat) ?> data</span>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-warning">
                                <tr>
                                    <th>Tanggal Edit</th>
                                    <th>Perubahan Poin</th>
                                    <th>Keterangan</th>
                                    <th>Kategori</th>
                                    <th>Detail</th>
                                    <th>Bulan</th>
                                    <th>Alasan Edit</th>
                                    <th>Diedit Oleh</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($riwayat as $r): ?>
                                <tr>
                                    <td>
                                        <small><?= date('d/m/Y', strtotime($r['tanggal_edit'])) ?></small><br>
                                        <small class="text-muted"><?= date('H:i', strtotime($r['tanggal_edit'])) ?></small>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge bg-danger"><?= $r['poin_sebelum'] ?></span>
                                            <i class="fas fa-arrow-right text-muted"></i>
                                            <span class="badge bg-success"><?= $r['poin_sesudah'] ?></span>
                                            <?php if ($r['poin_sebelum'] != $r['poin_sesudah']): ?>
                                                <span class="change-badge">
                                                    <?= $r['poin_sesudah'] - $r['poin_sebelum'] > 0 ? '+' : '' ?><?= $r['poin_sesudah'] - $r['poin_sebelum'] ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="small">
                                            <div class="text-old">
                                                <?= esc($r['keterangan_sebelum']) ?>
                                            </div>
                                            <div class="text-new">
                                                <?= esc($r['keterangan_sesudah']) ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="small">
                                            <div>
                                                <span class="badge badge-<?= $r['kategori_sebelum'] ?>">
                                                    <?= ucfirst($r['kategori_sebelum']) ?>
                                                </span>
                                            </div>
                                            <div class="mt-1">
                                                <span class="badge badge-<?= $r['kategori_sesudah'] ?>">
                                                    <?= ucfirst($r['kategori_sesudah']) ?>
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="small">
                                            <?php if ($r['detail_sebelum'] != $r['detail_sesudah']): ?>
                                                <div class="text-old">
                                                    <small><?= esc($r['detail_sebelum']) ?: '-' ?></small>
                                                </div>
                                            <?php endif; ?>
                                            <div class="text-new">
                                                <small><?= esc($r['detail_sesudah']) ?: '-' ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary"><?= esc($r['bulan_poin']) ?></span>
                                    </td>
                                    <td>
                                        <?php if ($r['alasan_edit'] && $r['alasan_edit'] !== 'Tidak ada alasan'): ?>
                                            <div class="fst-italic text-dark small">
                                                "<?= esc($r['alasan_edit']) ?>"
                                            </div>
                                        <?php else: ?>
                                            <em class="text-muted small">Tidak ada alasan</em>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <small><?= esc($r['diedit_oleh']) ?></small>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php elseif ($nis): ?>
            <div class="alert alert-info text-center py-5">
                <i class="fas fa-edit fa-3x mb-3 text-warning"></i>
                <h4>Tidak ada riwayat edit</h4>
                <p class="mb-0">Belum ada data pelanggaran yang diedit untuk santri ini.</p>
            </div>
        <?php endif; ?>

        <!-- Pilih Santri Lain -->
        <?php if (!$nis): ?>
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="fas fa-search"></i> Cari Riwayat Santri Lain</h5>
                </div>
                <div class="card-body">
                    <p>Masukkan NIS santri untuk melihat riwayat edit:</p>
                    <form action="<?= base_url('pelanggaran/lihat_riwayat_edit') ?>" method="get" class="d-flex gap-2">
                        <input type="number" name="nis" class="form-control" placeholder="Contoh: 28101" required>
                        <input type="hidden" name="tahun_ajaran" value="<?= $tahun_ajaran ?>">
                        <input type="hidden" name="semester" value="<?= $semester ?>">
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-search"></i> Cari
                        </button>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 