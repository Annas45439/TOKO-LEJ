<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('content') ?>

<div class="container-fluid px-4 py-5 prediksi-theme">
    <!-- Header -->
    <div class="row mb-5">
        <div class="col-12">
            <h1 class="h2 mb-2">📚 Panduan Fitur Prediksi Penjualan</h1>
            <p class="text-muted fs-5">Pelajari cara menggunakan sistem prediksi penjualan untuk bisnis Anda</p>
        </div>
    </div>

    <!-- Quick Start Section -->
    <div class="row g-4 mb-5">
        <!-- Card 1: Apa itu Prediksi Penjualan? -->
        <div class="col-lg-6">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start gap-3 mb-3">
                        <div class="fs-3">🔮</div>
                        <h5 class="card-title">Apa itu Prediksi Penjualan?</h5>
                    </div>
                    <p class="card-text text-muted">
                        Prediksi penjualan adalah estimasi jumlah produk yang akan terjual di masa depan berdasarkan data penjualan masa lalu. Sistem kami menggunakan teknologi AI untuk menganalisis pola penjualan Anda.
                    </p>
                    <div class="alert alert-info small mb-0">
                        <strong>Manfaat:</strong> Membantu Anda mempersiapkan stok produk, merencanakan pembelian, dan mengambil keputusan bisnis yang lebih baik.
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2: Bagaimana Cara Kerjanya? -->
        <div class="col-lg-6">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start gap-3 mb-3">
                        <div class="fs-3">⚙️</div>
                        <h5 class="card-title">Bagaimana Cara Kerjanya?</h5>
                    </div>
                    <p class="card-text text-muted">
                        Sistem kami:
                    </p>
                    <ol class="small text-muted">
                        <li>Mengumpulkan data penjualan Anda selama berbulan-bulan</li>
                        <li>Menganalisis tren dan pola penjualan</li>
                        <li>Mendeteksi musiman (periode penjualan tinggi/rendah)</li>
                        <li>Membuat prediksi untuk bulan depan</li>
                        <li>Memberikan rekomendasi pembelian stok</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Features Section -->
    <div class="row g-4 mb-5">
        <div class="col-12">
            <h4 class="mb-4">📊 Fitur Utama Sistem</h4>
        </div>

        <!-- Feature 1 -->
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="fs-2 mb-3">📈</div>
                    <h6 class="card-title">1. Quick Overview</h6>
                    <p class="small text-muted">
                        Ringkasan cepat total penjualan, rekomendasi stok, dan produk terlaris tanpa perlu memilih produk.
                    </p>
                    <ul class="small text-muted">
                        <li>Total penjualan bulan depan</li>
                        <li>Produk yang perlu dipesan</li>
                        <li>Produk terlaris</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Feature 2 -->
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="fs-2 mb-3">🎯</div>
                    <h6 class="card-title">2. Prediksi Per Produk</h6>
                    <p class="small text-muted">
                        Lihat prediksi detail untuk setiap produk dengan grafik, tren, dan akurasi.
                    </p>
                    <ul class="small text-muted">
                        <li>Prediksi penjualan bulan depan</li>
                        <li>Grafik trend penjualan</li>
                        <li>Tingkat akurasi prediksi</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Feature 3 -->
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="fs-2 mb-3">📦</div>
                    <h6 class="card-title">3. Rekomendasi Stok</h6>
                    <p class="small text-muted">
                        Dapatkan rekomendasi berapa jumlah stok yang harus dipesan berdasarkan prediksi.
                    </p>
                    <ul class="small text-muted">
                        <li>Stok aman minimum</li>
                        <li>Jumlah yang perlu dipesan</li>
                        <li>Prioritas pemesanan</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Feature 4 -->
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="fs-2 mb-3">📊</div>
                    <h6 class="card-title">4. Dashboard Lengkap</h6>
                    <p class="small text-muted">
                        Visualisasi komprehensif dengan chart dan tabel untuk analisis mendalam.
                    </p>
                    <ul class="small text-muted">
                        <li>4 grafik interaktif</li>
                        <li>Tabel produk terlaris</li>
                        <li>Daftar anomali</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Feature 5 -->
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="fs-2 mb-3">⚠️</div>
                    <h6 class="card-title">5. Deteksi Anomali</h6>
                    <p class="small text-muted">
                        Sistem otomatis mendeteksi penjualan abnormal yang perlu perhatian khusus.
                    </p>
                    <ul class="small text-muted">
                        <li>Lonjakan penjualan</li>
                        <li>Penurunan drastis</li>
                        <li>Pola tidak normal</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Feature 6 -->
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="fs-2 mb-3">📥</div>
                    <h6 class="card-title">6. Export Data</h6>
                    <p class="small text-muted">
                        Ekspor prediksi dan rekomendasi ke file CSV untuk dokumentasi atau analisis lebih lanjut.
                    </p>
                    <ul class="small text-muted">
                        <li>Export prediksi dashboard</li>
                        <li>Export rekomendasi stok</li>
                        <li>Format CSV terstandar</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- How to Use Section -->
    <div class="row g-4 mb-5">
        <div class="col-12">
            <h4 class="mb-4">🎓 Cara Menggunakan Sistem</h4>
        </div>

        <!-- Step 1 -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-start gap-3 mb-3">
                        <span class="badge bg-primary rounded-circle p-2" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">1</span>
                        <h6 class="card-title mt-1">Buka Halaman Prediksi</h6>
                    </div>
                    <p class="small text-muted">
                        Klik menu "Prediksi" di sidebar. Anda akan melihat ringkasan cepat jika belum memilih produk spesifik.
                    </p>
                    <div class="alert alert-light small">
                        <strong>Catatan:</strong> Pastikan data penjualan Anda sudah terekam selama minimal 3 bulan untuk hasil yang akurat.
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 2 -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-start gap-3 mb-3">
                        <span class="badge bg-primary rounded-circle p-2" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">2</span>
                        <h6 class="card-title mt-1">Pilih Produk (Opsional)</h6>
                    </div>
                    <p class="small text-muted">
                        Gunakan dropdown "Pilih Produk" untuk melihat prediksi detail produk tertentu dengan grafik trend dan rekomendasi khusus.
                    </p>
                    <div class="alert alert-light small">
                        <strong>Tip:</strong> Pilih produk dengan penjualan tinggi untuk mendapatkan prediksi yang lebih akurat.
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 3 -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-start gap-3 mb-3">
                        <span class="badge bg-primary rounded-circle p-2" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">3</span>
                        <h6 class="card-title mt-1">Baca Hasil Prediksi</h6>
                    </div>
                    <p class="small text-muted">
                        Lihat prediksi penjualan bulan depan, tingkat akurasi, dan confidence interval. Semakin rendah MAPE, semakin akurat prediksi.
                    </p>
                    <div class="alert alert-light small">
                        <strong>Akurasi MAPE:</strong><br>
                        < 20% = Sangat Akurat<br>
                        20-50% = Cukup Akurat<br>
                        > 50% = Perlu Data Lebih Banyak
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 4 -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-start gap-3 mb-3">
                        <span class="badge bg-primary rounded-circle p-2" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">4</span>
                        <h6 class="card-title mt-1">Ambil Keputusan Bisnis</h6>
                    </div>
                    <p class="small text-muted">
                        Gunakan rekomendasi stok untuk menentukan berapa jumlah yang harus dipesan. Perhatikan juga anomali untuk peluang atau risiko.
                    </p>
                    <div class="alert alert-light small">
                        <strong>Contoh:</strong> Jika prediksi meningkat 50% dari biasanya, pertimbangkan untuk membeli stok lebih banyak.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Understanding Results Section -->
    <div class="row g-4 mb-5">
        <div class="col-12">
            <h4 class="mb-4">🔍 Memahami Hasil Prediksi</h4>
        </div>

        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="row g-4">
                        <!-- Result 1 -->
                        <div class="col-md-6">
                            <h6 class="mb-3">📊 Prediksi Penjualan (Next Month Forecast)</h6>
                            <p class="small text-muted mb-2">
                                Estimasi jumlah unit produk yang akan terjual bulan depan berdasarkan trend historis.
                            </p>
                            <div class="example-box p-3 bg-light rounded small">
                                <strong>Contoh:</strong> Prediksi = 150 unit<br>
                                Artinya: Bulan depan diperkirakan akan terjual 150 unit
                            </div>
                        </div>

                        <!-- Result 2 -->
                        <div class="col-md-6">
                            <h6 class="mb-3">✅ Tingkat Akurasi (MAPE %)</h6>
                            <p class="small text-muted mb-2">
                                Persentase rata-rata penyimpangan prediksi dari data aktual. Semakin rendah semakin baik.
                            </p>
                            <div class="example-box p-3 bg-light rounded small">
                                <strong>Contoh:</strong> MAPE = 15%<br>
                                Artinya: Prediksi mungkin meleset ±15% dari nilai sebenarnya
                            </div>
                        </div>

                        <!-- Result 3 -->
                        <div class="col-md-6">
                            <h6 class="mb-3">📈 Trend Penjualan</h6>
                            <p class="small text-muted mb-2">
                                Arah kecenderungan penjualan: naik (upward), turun (downward), atau stabil (flat).
                            </p>
                            <div class="example-box p-3 bg-light rounded small">
                                <strong>Contoh:</strong> Trend = Upward<br>
                                Artinya: Penjualan cenderung meningkat, persiapkan stok lebih banyak
                            </div>
                        </div>

                        <!-- Result 4 -->
                        <div class="col-md-6">
                            <h6 class="mb-3">🎯 Rekomendasi Stok (To Buy)</h6>
                            <p class="small text-muted mb-2">
                                Jumlah unit yang direkomendasikan untuk dipesan agar stok selalu mencukupi.
                            </p>
                            <div class="example-box p-3 bg-light rounded small">
                                <strong>Contoh:</strong> To Buy = 80 unit<br>
                                Artinya: Pesan 80 unit untuk menjaga stok aman
                            </div>
                        </div>

                        <!-- Result 5 -->
                        <div class="col-md-6">
                            <h6 class="mb-3">🔔 Prioritas Pembelian</h6>
                            <p class="small text-muted mb-2">
                                Level urgensi pemesanan: Critical (paling penting), High, Normal.
                            </p>
                            <div class="example-box p-3 bg-light rounded small">
                                <strong>Contoh:</strong> Priority = Critical<br>
                                Artinya: Segera pesan, stok akan habis dalam waktu dekat
                            </div>
                        </div>

                        <!-- Result 6 -->
                        <div class="col-md-6">
                            <h6 class="mb-3">⚠️ Confidence Interval</h6>
                            <p class="small text-muted mb-2">
                                Range/rentang nilai prediksi yang kemungkinan akan terjadi (95% confidence).
                            </p>
                            <div class="example-box p-3 bg-light rounded small">
                                <strong>Contoh:</strong> CI = 130-170 unit<br>
                                Artinya: 95% kemungkinan penjualan antara 130-170 unit
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FAQ Section -->
    <div class="row g-4 mb-5">
        <div class="col-12">
            <h4 class="mb-4">❓ Pertanyaan Umum (FAQ)</h4>
        </div>

        <div class="col-12">
            <div class="accordion" id="faqAccordion">
                <!-- FAQ 1 -->
                <div class="accordion-item border-0 mb-3 shadow-sm rounded">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                            <strong>Berapa lama data yang dibutuhkan untuk prediksi akurat?</strong>
                        </button>
                    </h2>
                    <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-muted small">
                            Minimal 3 bulan data transaksi. Semakin panjang data historis (6-12 bulan), semakin akurat prediksinya. Jika produk Anda baru diluncurkan, tunggu beberapa bulan untuk mengumpulkan data yang cukup.
                        </div>
                    </div>
                </div>

                <!-- FAQ 2 -->
                <div class="accordion-item border-0 mb-3 shadow-sm rounded">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                            <strong>Apa itu "Anomali" dalam prediksi?</strong>
                        </button>
                    </h2>
                    <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-muted small">
                            Anomali adalah penjualan yang tidak biasa (terlalu tinggi atau terlalu rendah) dibanding rata-rata. Ini bisa karena promosi khusus, hari libur, atau situasi tak terduga. Sistem akan memberitahu Anda jika ada anomali untuk dipertimbangkan dalam perencanaan.
                        </div>
                    </div>
                </div>

                <!-- FAQ 3 -->
                <div class="accordion-item border-0 mb-3 shadow-sm rounded">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                            <strong>Bagaimana jika prediksi tidak akurat?</strong>
                        </button>
                    </h2>
                    <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-muted small">
                            Prediksi berbasis data historis, jadi jika bisnis Anda sangat baru atau ada perubahan besar, akurasi mungkin kurang. Semakin banyak data dan semakin konsisten pola penjualan, semakin akurat prediksinya. Cek MAPE% untuk mengukur tingkat akurasi.
                        </div>
                    </div>
                </div>

                <!-- FAQ 4 -->
                <div class="accordion-item border-0 mb-3 shadow-sm rounded">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                            <strong>Bagaimana menggunakan rekomendasi "To Buy"?</strong>
                        </button>
                    </h2>
                    <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-muted small">
                            Angka "To Buy" adalah jumlah minimal yang harus dipesan agar tidak kehabisan stok. Jika budget memungkinkan, beli lebih banyak untuk antisipasi permintaan yang tidak terduga. Prioritas "Critical" dan "High" harus diprioritaskan terlebih dahulu.
                        </div>
                    </div>
                </div>

                <!-- FAQ 5 -->
                <div class="accordion-item border-0 mb-3 shadow-sm rounded">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">
                            <strong>Apakah sistem ini memperhitungkan musiman?</strong>
                        </button>
                    </h2>
                    <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-muted small">
                            Ya! Sistem otomatis mendeteksi pola musiman (contoh: penjualan tinggi di bulan Desember atau Ramadan). Seasonal Patterns chart menunjukkan faktor musiman untuk setiap bulan. Gunakan informasi ini untuk mempersiapkan stok dengan lebih baik.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Best Practices Section -->
    <div class="row g-4 mb-5">
        <div class="col-12">
            <h4 class="mb-4">💡 Tips & Best Practices</h4>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="card-title mb-3">✅ Hal yang Harus Dilakukan</h6>
                    <ul class="small">
                        <li class="mb-2">📌 Periksa prediksi setiap bulan untuk perencanaan pembelian</li>
                        <li class="mb-2">📌 Perhatikan produk dengan "Critical" priority</li>
                        <li class="mb-2">📌 Gunakan confidence interval untuk buffer stok</li>
                        <li class="mb-2">📌 Analisis anomali untuk peluang bisnis</li>
                        <li class="mb-2">📌 Catat feedback akurasi untuk improvement</li>
                        <li class="mb-2">📌 Export data untuk dokumentasi keputusan bisnis</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="card-title mb-3">❌ Hal yang Harus Dihindari</h6>
                    <ul class="small">
                        <li class="mb-2">🚫 Jangan percaya 100% tanpa verifikasi data</li>
                        <li class="mb-2">🚫 Jangan abaikan anomali tanpa investigasi</li>
                        <li class="mb-2">🚫 Jangan menggunakan data < 3 bulan</li>
                        <li class="mb-2">🚫 Jangan pesan stok kurang dari "To Buy" jika possible</li>
                        <li class="mb-2">🚫 Jangan ignore trend downward tanpa analisis</li>
                        <li class="mb-2">🚫 Jangan update data transaksi tidak konsisten</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 bg-light p-4 rounded">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h6 class="mb-1">Siap untuk mulai?</h6>
                        <p class="small text-muted mb-0">Buka halaman prediksi untuk melihat prediksi penjualan real-time Anda</p>
                    </div>
                    <a href="<?= base_url('prediksi') ?>" class="btn btn-primary btn-lg">
                        🚀 Buka Prediksi Sekarang
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
