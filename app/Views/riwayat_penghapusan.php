<?php
date_default_timezone_set('Asia/Jakarta');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Riwayat Penghapusan Poin') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .badge-ringan { background-color: #27ae60; }
        .badge-sedang { background-color: #f39c12; }
        .badge-berat { background-color: #e74c3c; }
        .card-header { background-color: #3498db; color: white; }
    </style>
</head>
<body>
    <div class="container mt-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="fas fa-history"></i> Riwayat Penghapusan Poin</h1>
            <div>
                <?php if ($nis): ?>
                    <!-- PERBAIKAN: Hapus $bulan, redirect ke bulan default -->
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
                    <div class="alert alert-info mt-3">
                        <i class="fas fa-info-circle"></i> 
                        <strong>Total Poin yang Dihapus:</strong> 
                        <span class="badge bg-danger fs-6"><?= $total_poin_dihapus ?> poin</span>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i> 
                Pilih santri untuk melihat riwayat penghapusan poin.
            </div>
        <?php endif; ?>

        <!-- Riwayat Penghapusan -->
        <?php if (!empty($riwayat)): ?>
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list"></i> Daftar Riwayat Penghapusan
                        <span class="badge bg-primary"><?= count($riwayat) ?> data</span>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Tanggal Hapus</th>
                                    <th>Poin Dihapus</th>
                                    <th>Keterangan Poin</th>
                                    <th>Kategori</th>
                                    <th>Bulan</th>
                                    <th>Alasan Penghapusan</th>
                                    <th>Dihapus Oleh</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($riwayat as $r): ?>
                                <tr>
                                    <td>
                                        <small><?= date('d/m/Y', strtotime($r['tanggal_dihapus'])) ?></small><br>
                                        <small class="text-muted"><?= date('H:i', strtotime($r['tanggal_dihapus'])) ?></small>
                                    </td>
                                    <td>
                                        <span class="badge bg-danger fs-6">-<?= $r['poin_dihapus'] ?> poin</span>
                                    </td>
                                    <td><?= esc($r['keterangan_poin']) ?></td>
                                    <td>
                                        <span class="badge badge-<?= $r['kategori_poin'] ?>">
                                            <?= ucfirst($r['kategori_poin']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary"><?= esc($r['bulan_poin']) ?></span>
                                    </td>
                                    <td>
                                        <?php if ($r['alasan_penghapusan'] && $r['alasan_penghapusan'] !== 'Tidak ada alasan'): ?>
                                            <div class="fst-italic text-dark">
                                                "<?= esc($r['alasan_penghapusan']) ?>"
                                            </div>
                                        <?php else: ?>
                                            <em class="text-muted">Tidak ada alasan</em>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <small><?= esc($r['dihapus_oleh']) ?></small>
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
                <i class="fas fa-info-circle fa-3x mb-3 text-primary"></i>
                <h4>Tidak ada riwayat penghapusan poin</h4>
                <p class="mb-0">Belum ada poin yang dihapus untuk santri ini.</p>
            </div>
        <?php endif; ?>

        <!-- Pilih Santri Lain -->
        <?php if (!$nis): ?>
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="fas fa-search"></i> Cari Riwayat Santri Lain</h5>
                </div>
                <div class="card-body">
                    <p>Masukkan NIS santri untuk melihat riwayat penghapusan:</p>
                    <form action="<?= base_url('pelanggaran/lihat_riwayat_penghapusan') ?>" method="get" class="d-flex gap-2">
                        <input type="number" name="nis" class="form-control" placeholder="Contoh: 28101" required>
                        <input type="hidden" name="tahun_ajaran" value="<?= $tahun_ajaran ?>">
                        <input type="hidden" name="semester" value="<?= $semester ?>">
                        <button type="submit" class="btn btn-primary">
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