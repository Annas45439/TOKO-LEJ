<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('content') ?>

<div class="container-fluid px-4 py-4 prediksi-theme">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start gap-3">
                <div>
                    <h1 class="h3 mb-2">
                        <i class="bi bi-graph-up"></i> Prediksi Penjualan (Linear Regression)
                    </h1>
                    <p class="text-muted mb-0">
                        Tampilan prediksi yang dibuat sederhana agar mudah dipahami, dengan metode perhitungan tetap menggunakan Linear Regression.
                    </p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="<?= base_url('/prediksi/guide') ?>" class="btn btn-outline-primary" title="Pelajari cara menggunakan sistem">
                        <i class="bi bi-book"></i> Panduan
                    </a>
                    <a href="<?= base_url('/prediksi/dashboard') ?>" class="btn btn-primary">
                        <i class="bi bi-bar-chart-fill"></i> Dashboard Lengkap
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Alert -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-info border-0 shadow-sm">
                <div class="d-flex gap-3">
                    <div class="fs-5">💡</div>
                    <div>
                        <strong>Bagaimana Sistem Ini Bekerja?</strong><br>
                        <small>
                            Sistem membaca riwayat penjualan produk, lalu menghitung prediksi bulan depan dengan
                            <strong>metode Linear Regression</strong>. Hasilnya diterjemahkan menjadi angka yang mudah dipakai
                            untuk keputusan stok harian: mana yang aman, mana yang perlu segera dipesan.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if (!empty($userNotice)): ?>
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-warning border-0 shadow-sm mb-0" role="alert">
                    <strong>Catatan Prediksi:</strong> <?= esc((string) $userNotice) ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Product Selection Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="bi bi-box-seam"></i> Pilih Produk untuk Melihat Prediksi Detail
                    </h5>
                    
                    <form method="get" action="<?= base_url('/prediksi') ?>" class="row g-3 align-items-end">
                        <div class="col-12 col-lg-6">
                            <label for="productSelect" class="form-label fw-5">Produk</label>
                            <select id="productSelect" name="product_id" class="form-select form-select-lg" required>
                                <option value="">
                                    📌 Lihat Ringkasan Cepat (tanpa pilih produk)
                                </option>
                                <?php if (!empty($products)): ?>
                                    <?php foreach ($products as $product): ?>
                                        <option value="<?= (int) $product['id'] ?>" 
                                            <?= ((int) $selectedProductId === (int) $product['id']) ? 'selected' : '' ?>>
                                            📦 <?= esc((string) $product['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <option value="" disabled>Tidak ada produk tersedia</option>
                                <?php endif; ?>
                            </select>
                            <small class="d-block mt-2 text-muted">
                                <i class="bi bi-info-circle"></i>
                                Pilih produk jika Anda ingin rekomendasi stok yang lebih spesifik untuk produk tersebut
                            </small>
                        </div>
                        <div class="col-12 col-lg-6 d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-search"></i> Lihat Prediksi
                            </button>
                        </div>
                    </form>

                    <hr class="my-4">

                    <!-- Quick Tips -->
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="d-flex gap-3">
                                <div class="fs-5">✅</div>
                                <div>
                                    <h6 class="mb-1">Data Minimal</h6>
                                    <small class="text-muted">
                                        Minimal 3 bulan riwayat penjualan agar prediksi lebih stabil
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex gap-3">
                                <div class="fs-5">📊</div>
                                <div>
                                    <h6 class="mb-1">Butuh Detail Lebih Lengkap?</h6>
                                    <small class="text-muted">
                                        Buka Dashboard Lengkap untuk melihat grafik, pola, dan ranking produk
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mt-1">
                        <div class="col-12">
                            <div class="rounded-3 p-3 prediksi-soft-block">
                                <h6 class="mb-2"><i class="bi bi-signpost-split"></i> Cara membaca hasil dalam 3 langkah</h6>
                                <div class="row g-2 small text-muted">
                                    <div class="col-md-4">
                                        <strong>1) Lihat Prediksi Bulan Depan</strong><br>
                                        Angka ini jadi patokan awal kebutuhan stok.
                                    </div>
                                    <div class="col-md-4">
                                        <strong>2) Cek Akurasi (MAPE)</strong><br>
                                        Makin kecil persen MAPE, biasanya makin bisa diandalkan.
                                    </div>
                                    <div class="col-md-4">
                                        <strong>3) Ikuti Rekomendasi Stok</strong><br>
                                        Prioritaskan item dengan status critical atau high.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Overview Section (shown when no product selected) -->
    <?php if ($selectedProductId === 0 && $overview !== null): ?>
        <div class="row mb-4">
            <div class="col-12">
                <h4 class="mb-3">
                    <i class="bi bi-lightning-charge"></i> Quick Overview - Ringkasan Cepat Bisnis Anda
                </h4>
            </div>

            <!-- KPI Cards Row 1 -->
            <div class="col-12 col-md-6 col-lg-3 mb-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h6 class="card-title mb-0">Total Penjualan Prediksi</h6>
                            <span class="badge bg-info">Bulan Depan</span>
                        </div>
                        <?php if ($overview['total_forecast'] && $overview['total_forecast']['valid']): ?>
                            <div class="fs-3 fw-bold text-primary mb-2">
                                <?= number_format((int)$overview['total_forecast']['next_prediction'], 0, ',', '.') ?>
                            </div>
                            <small class="text-muted">Unit</small>
                            <div class="mt-3 pt-3 border-top">
                                <small class="text-secondary">
                                    <strong>Akurasi:</strong> 
                                    <span class="badge" style="background-color: <?= ((float)$overview['total_forecast']['metrics']['mape'] < 20) ? '#28a745' : (((float)$overview['total_forecast']['metrics']['mape'] < 50) ? '#ffc107' : '#dc3545') ?>">
                                        <?= htmlspecialchars($overview['total_forecast']['metrics']['mape']) ?>% MAPE
                                    </span>
                                </small>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-warning small mb-0">
                                Data penjualan belum cukup untuk membuat prediksi
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- KPI Cards Row 2 -->
            <div class="col-12 col-md-6 col-lg-3 mb-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h6 class="card-title mb-0">Perlu Pesan Segera</h6>
                            <span class="badge bg-danger">🔴 Critical</span>
                        </div>
                        <div class="fs-3 fw-bold text-danger mb-2">
                            <?= htmlspecialchars($overview['critical_recommendations']) ?>
                        </div>
                        <small class="text-muted">Produk perlu dipesan ASAP</small>
                        <div class="mt-3 pt-3 border-top">
                            <a href="<?= base_url('/prediksi/dashboard') ?>" class="btn btn-sm btn-outline-danger w-100">
                                Lihat Detail →
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- KPI Cards Row 3 -->
            <div class="col-12 col-md-6 col-lg-3 mb-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h6 class="card-title mb-0">Perlu Disiapkan</h6>
                            <span class="badge bg-warning text-dark">🟡 High</span>
                        </div>
                        <div class="fs-3 fw-bold text-warning mb-2">
                            <?= htmlspecialchars($overview['high_recommendations']) ?>
                        </div>
                        <small class="text-muted">Produk perlu dipesan dalam waktu dekat</small>
                        <div class="mt-3 pt-3 border-top">
                            <a href="<?= base_url('/prediksi/dashboard') ?>" class="btn btn-sm btn-outline-warning w-100">
                                Lihat Detail →
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- KPI Cards Row 4 -->
            <div class="col-12 col-md-6 col-lg-3 mb-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h6 class="card-title mb-0">Grafik & Analisis</h6>
                            <span class="badge bg-success">Dashboard</span>
                        </div>
                        <div class="fs-3 fw-bold text-success mb-2">
                            4 Grafik
                        </div>
                        <small class="text-muted">Visualisasi trend & pola penjualan</small>
                        <div class="mt-3 pt-3 border-top">
                            <a href="<?= base_url('/prediksi/dashboard') ?>" class="btn btn-sm btn-success w-100">
                                Buka Dashboard →
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Products Table -->
            <div class="col-12 mb-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i class="bi bi-star-fill"></i> Top 5 Produk Terlaris (Berdasarkan Prediksi)
                        </h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Produk</th>
                                    <th class="text-center">Prediksi Bulan Depan</th>
                                    <th class="text-center">Trend</th>
                                    <th class="text-center">Akurasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($overview['top_products'])): ?>
                                    <?php foreach ($overview['top_products'] as $idx => $product): ?>
                                        <tr>
                                            <td>
                                                <strong><?= htmlspecialchars($product['product_name']) ?></strong>
                                                <br>
                                                <small class="text-muted">ID: <?= (int) $product['product_id'] ?></small>
                                            </td>
                                            <td class="text-center">
                                                <h6 class="mb-0">
                                                    <?= number_format((int)$product['next_forecast'], 0, ',', '.') ?>
                                                </h6>
                                                <small class="text-muted">units</small>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge" style="background-color: <?= ($product['trend'] === 'upward' ? '#28a745' : ($product['trend'] === 'downward' ? '#dc3545' : '#6c757d')) ?>">
                                                    <?= ($product['trend'] === 'upward' ? '📈 Naik' : ($product['trend'] === 'downward' ? '📉 Turun' : '➡️ Stabil')) ?>
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge" style="background-color: <?= ($product['mape'] < 20 ? '#28a745' : ($product['mape'] < 50 ? '#ffc107' : '#dc3545')) ?>">
                                                    <?= htmlspecialchars($product['mape']) ?>%
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-3">
                                            Tidak ada data produk terlaris
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Anomalies Section -->
            <?php if (!empty($overview['anomaly_products'])): ?>
                <div class="col-12">
                    <div class="card border-0 shadow-sm border-warning">
                        <div class="card-header bg-warning bg-opacity-10 border-warning">
                            <h6 class="mb-0">
                                <i class="bi bi-exclamation-triangle"></i> ⚠️ Anomali Terdeteksi - Perhatian Khusus Diperlukan
                            </h6>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Produk</th>
                                        <th class="text-center">Penjualan Abnormal</th>
                                        <th class="text-center">Jenis Anomali</th>
                                        <th class="text-center">Nilai Aktual</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($overview['anomaly_products'] as $product): ?>
                                        <tr>
                                            <td>
                                                <strong><?= htmlspecialchars($product['product_name']) ?></strong>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-warning text-dark">ANOMALI</span>
                                            </td>
                                            <td class="text-center">
                                                <small>
                                                    <?= htmlspecialchars($product['anomaly_type'] ?? 'Unknown') ?>
                                                </small>
                                            </td>
                                            <td class="text-center">
                                                <?= number_format((int)($product['anomaly_value'] ?? 0), 0, ',', '.') ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer bg-light">
                            <small class="text-muted">
                                <i class="bi bi-lightbulb"></i>
                                <strong>Catatan:</strong> Anomali menunjukkan penjualan yang tidak biasa (terlalu tinggi atau rendah). 
                                Investigasi lebih lanjut untuk memahami penyebabnya (promosi khusus, stok habis, dll).
                            </small>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Action Card -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card border-0 bg-primary bg-opacity-10">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <div>
                                <h6 class="mb-1">Ingin Analisis Lebih Mendalam?</h6>
                                <p class="small text-muted mb-0">
                                    Klik Dashboard untuk melihat grafik interaktif, analisis trend, musiman, dan rekomendasi pembelian detail
                                </p>
                            </div>
                            <a href="<?= base_url('/prediksi/dashboard') ?>" class="btn btn-primary btn-lg flex-shrink-0">
                                📊 Buka Dashboard Lengkap
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <!-- Product-Specific Forecast Section -->
    <?php elseif ($selectedProductId > 0): ?>
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="bi bi-graph-up"></i> Prediksi Detail - <?= esc((string) ($selectedProductName ?? 'Produk')) ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (isset($result) && $result['valid']): ?>
                            <!-- KPI Cards -->
                            <div class="row g-3 mb-4">
                                <div class="col-md-6 col-lg-3">
                                    <div class="card bg-light border-0">
                                        <div class="card-body">
                                            <h6 class="card-title small text-muted">Prediksi Bulan Depan</h6>
                                            <h3 class="fw-bold text-primary">
                                                <?= number_format((int) $result['next_prediction'], 0, ',', '.') ?>
                                            </h3>
                                            <small>unit</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-3">
                                    <div class="card bg-light border-0">
                                        <div class="card-body">
                                            <h6 class="card-title small text-muted">Akurasi Prediksi (MAPE, % error rata-rata)</h6>
                                            <h3 class="fw-bold" style="color: <?= ($result['metrics']['mape'] < 20 ? '#28a745' : ($result['metrics']['mape'] < 50 ? '#ffc107' : '#dc3545')) ?>">
                                                <?= htmlspecialchars($result['metrics']['mape']) ?>%
                                            </h3>
                                            <small><?= ($result['metrics']['mape'] < 20 ? '✅ Sangat Akurat' : ($result['metrics']['mape'] < 50 ? '⚠️ Cukup Akurat' : '⚠️ Perlu Data')) ?></small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-3">
                                    <div class="card bg-light border-0">
                                        <div class="card-body">
                                            <h6 class="card-title small text-muted">Trend Penjualan</h6>
                                            <h3 class="fw-bold" style="color: <?= ($result['trend_analysis']['trend'] === 'upward' ? '#28a745' : ($result['trend_analysis']['trend'] === 'downward' ? '#dc3545' : '#6c757d')) ?>">
                                                <?= ($result['trend_analysis']['trend'] === 'upward' ? '📈 Naik' : ($result['trend_analysis']['trend'] === 'downward' ? '📉 Turun' : '➡️ Stabil')) ?>
                                            </h3>
                                            <small>Berdasarkan trend historis</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-3">
                                    <div class="card bg-light border-0">
                                        <div class="card-body">
                                            <h6 class="card-title small text-muted">Rentang Aman Prediksi (95%)</h6>
                                            <h6 class="fw-bold">
                                                <?= number_format((int) $result['confidence_interval']['lower'], 0, ',', '.') ?> - 
                                                <?= number_format((int) $result['confidence_interval']['upper'], 0, ',', '.') ?>
                                            </h6>
                                            <small>Perkiraan batas bawah dan batas atas penjualan</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php
                                $trend = (string) ($result['trend_analysis']['trend'] ?? 'stable');
                                $mape = (float) ($result['metrics']['mape'] ?? 0);
                                $nextPrediction = (int) ($result['next_prediction'] ?? 0);
                                $forecastLower = (int) ($result['confidence_interval']['lower'] ?? 0);
                                $forecastUpper = (int) ($result['confidence_interval']['upper'] ?? 0);
                                $recommendStock = (int) ($advancedForecast['recommended_stock'] ?? 0);

                                $trendLabel = $trend === 'upward' ? 'naik' : ($trend === 'downward' ? 'turun' : 'stabil');
                                $accuracyLabel = $mape < 20 ? 'tinggi' : ($mape < 50 ? 'cukup' : 'rendah');
                            ?>

                            <div class="alert alert-primary border-0 shadow-sm">
                                <strong>Ringkasan untuk Anda (bahasa sederhana)</strong>
                                <div class="small mt-2">
                                    Untuk produk ini, penjualan bulan depan diperkirakan sekitar
                                    <strong><?= number_format($nextPrediction, 0, ',', '.') ?> unit</strong>,
                                    dengan kecenderungan <strong><?= htmlspecialchars($trendLabel) ?></strong>.
                                    Tingkat kepercayaan model saat ini <strong><?= htmlspecialchars($accuracyLabel) ?></strong>
                                    (MAPE <?= htmlspecialchars($result['metrics']['mape']) ?>%).
                                    Siapkan stok dalam rentang
                                    <strong><?= number_format($forecastLower, 0, ',', '.') ?> - <?= number_format($forecastUpper, 0, ',', '.') ?> unit</strong>
                                    agar lebih aman.
                                    <?php if ($recommendStock > 0): ?>
                                        Rekomendasi awal: pertimbangkan stok sekitar
                                        <strong><?= number_format($recommendStock, 0, ',', '.') ?> unit</strong>.
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Info Box -->
                            <div class="alert alert-info border-0">
                                <strong>💡 Interpretasi Hasil:</strong><br>
                                <small>
                                    Prediksi penjualan produk ini untuk bulan depan adalah 
                                    <strong><?= number_format((int) $result['next_prediction'], 0, ',', '.') ?> unit</strong> dengan tingkat akurasi 
                                    <strong><?= htmlspecialchars($result['metrics']['mape']) ?>%</strong>. 
                                    Artinya prediksi dapat meleset kurang lebih 
                                    <strong><?= htmlspecialchars($result['metrics']['mape']) ?>%</strong> dari nilai sebenarnya. 
                                    Range confidence interval <?= htmlspecialchars($result['confidence_interval']['margin_of_error']) ?> menunjukkan batas aman perencanaan stok Anda.
                                </small>
                            </div>

                            <!-- Stock Recommendation -->
                            <?php if (isset($advancedForecast) && !empty($advancedForecast)): ?>
                                <div class="card bg-success bg-opacity-10 border-success">
                                    <div class="card-body">
                                        <h6 class="card-title">📦 Rekomendasi Stok</h6>
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <h6 class="small text-muted">Stok Aman Minimum</h6>
                                                <h5 class="fw-bold text-success">
                                                    <?= number_format((int) ($advancedForecast['safety_stock'] ?? 0), 0, ',', '.') ?> unit
                                                </h5>
                                            </div>
                                            <div class="col-md-4">
                                                <h6 class="small text-muted">Rekomendasi Pesan</h6>
                                                <h5 class="fw-bold text-info">
                                                    <?= number_format((int) ($advancedForecast['recommended_stock'] ?? 0), 0, ',', '.') ?> unit
                                                </h5>
                                            </div>
                                            <div class="col-md-4">
                                                <h6 class="small text-muted">Prioritas</h6>
                                                <span class="badge" style="background-color: <?= ($advancedForecast['priority'] === 'critical' ? '#dc3545' : ($advancedForecast['priority'] === 'high' ? '#ffc107' : '#6c757d')) ?>;font-size: 0.9rem;">
                                                    <?= ucfirst((string) ($advancedForecast['priority'] ?? 'normal')) ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                        <?php else: ?>
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle"></i>
                                <strong>Data Tidak Cukup</strong><br>
                                <small>Produk ini belum memiliki data penjualan yang cukup untuk membuat prediksi. Tunggu sampai ada minimal 3 bulan data penjualan.</small>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

</div>

<?= $this->endSection() ?>
