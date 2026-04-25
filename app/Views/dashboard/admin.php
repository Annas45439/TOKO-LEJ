<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('content') ?>
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Total Produk</div>
            <div class="stat-value"><?= esc((string) ($stats['products'] ?? 0)) ?></div>
            <div class="stat-foot">Produk aktif terdaftar</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Transaksi Hari Ini</div>
            <div class="stat-value"><?= esc((string) ($stats['transactionsToday'] ?? 0)) ?></div>
            <div class="stat-foot">Transaksi tanggal hari ini</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Stok Hampir Habis</div>
            <div class="stat-value"><?= esc((string) ($stats['lowStock'] ?? 0)) ?></div>
            <div class="stat-foot">Perlu restok segera</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Total Pelanggan</div>
            <div class="stat-value"><?= esc((string) ($stats['customers'] ?? 0)) ?></div>
            <div class="stat-foot">Data pelanggan tersimpan</div>
        </div>
    </div>

    <div class="panel mb-3">
        <div class="panel-title mb-2">Grafik Transaksi Bulanan</div>
        <p class="panel-muted mb-3">Visual transaksi 6 bulan terakhir.</p>
        <div style="height: 220px;">
            <canvas id="dashboardChartAdmin"></canvas>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-12 col-xl-4">
            <div class="panel h-100">
                <div class="panel-title mb-2">Aksi Cepat</div>
                <p class="panel-muted mb-3">Shortcut untuk operasional harian admin.</p>
                <div class="d-grid gap-2">
                    <a href="<?= base_url('/produk/create') ?>" class="btn btn-info">Tambah Produk</a>
                    <a href="<?= base_url('/stok-masuk') ?>" class="btn btn-outline-light">Input Stok Masuk</a>
                    <a href="<?= base_url('/prediksi') ?>" class="btn btn-outline-light">Buka Prediksi</a>
                    <a href="<?= base_url('/pengguna') ?>" class="btn btn-outline-light">Kelola Pengguna</a>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-4">
            <div class="panel h-100">
                <div class="panel-title mb-2">Stok Perlu Restok</div>
                <p class="panel-muted mb-3">5 produk dengan stok terendah.</p>
                <?php if (empty($stats['lowStockItems'])): ?>
                    <p class="panel-muted mb-0">Semua stok masih aman.</p>
                <?php else: ?>
                    <div class="d-flex flex-column gap-2">
                        <?php foreach ($stats['lowStockItems'] as $item): ?>
                            <div class="d-flex justify-content-between align-items-center p-2 rounded" style="background: rgba(255,255,255,0.04); border:1px solid rgba(255,255,255,0.08);">
                                <div>
                                    <div class="fw-semibold"><?= esc((string) $item['name']) ?></div>
                                    <small class="text-secondary">Min: <?= esc((string) $item['min_stock']) ?></small>
                                </div>
                                <span class="badge text-bg-warning">Stok <?= esc((string) $item['stock']) ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-12 col-xl-4">
            <div class="panel h-100">
                <div class="panel-title mb-2">Transaksi Terbaru</div>
                <p class="panel-muted mb-3">5 transaksi terakhir.</p>
                <?php if (empty($stats['recentTransactions'])): ?>
                    <p class="panel-muted mb-0">Belum ada transaksi.</p>
                <?php else: ?>
                    <div class="d-flex flex-column gap-2">
                        <?php foreach ($stats['recentTransactions'] as $trx): ?>
                            <div class="p-2 rounded" style="background: rgba(255,255,255,0.04); border:1px solid rgba(255,255,255,0.08);">
                                <div class="d-flex justify-content-between">
                                    <span class="fw-semibold"><?= esc((string) ($trx['invoice_no'] ?? '-')) ?></span>
                                    <span class="text-info">Rp <?= number_format((float) ($trx['total'] ?? 0), 0, ',', '.') ?></span>
                                </div>
                                <small class="text-secondary"><?= esc((string) ($trx['payment_method'] ?? '-')) ?> | <?= esc((string) ($trx['date'] ?? $trx['transaction_date'] ?? '-')) ?></small>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const chartLabelsAdmin = <?= json_encode($stats['monthlyLabels'] ?? []) ?>;
        const chartValuesAdmin = <?= json_encode($stats['monthlyTotals'] ?? []) ?>;

        const ctxAdmin = document.getElementById('dashboardChartAdmin');
        if (ctxAdmin) {
            new Chart(ctxAdmin, {
                type: 'line',
                data: {
                    labels: chartLabelsAdmin,
                    datasets: [{
                        label: 'Transaksi',
                        data: chartValuesAdmin,
                        borderColor: '#00d4ff',
                        backgroundColor: 'rgba(0, 212, 255, 0.18)',
                        fill: true,
                        tension: 0.35,
                        pointRadius: 3,
                        pointHoverRadius: 5,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            labels: {
                                color: '#f0f4ff'
                            }
                        }
                    },
                    scales: {
                        x: {
                            ticks: {
                                color: '#9aa5c4'
                            },
                            grid: {
                                color: 'rgba(255,255,255,0.08)'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                color: '#9aa5c4',
                                precision: 0,
                            },
                            grid: {
                                color: 'rgba(255,255,255,0.08)'
                            }
                        }
                    }
                }
            });
        }
    </script>
<?= $this->endSection() ?>