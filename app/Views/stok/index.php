<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('content') ?>
    <?php if (! empty($lowStockProducts ?? [])): ?>
        <div class="panel mb-3">
            <div class="d-flex flex-column flex-lg-row justify-content-between gap-2 mb-3">
                <div>
                    <div class="panel-title mb-1">Peringatan Stok Menipis</div>
                    <p class="panel-muted mb-0">Prioritaskan restok untuk produk berikut.</p>
                </div>
                <span class="badge text-bg-warning align-self-start"><?= esc((string) count($lowStockProducts)) ?> produk</span>
            </div>

            <div class="row g-2">
                <?php foreach ($lowStockProducts as $low): ?>
                    <?php
                    $stockNow = (int) ($low['stock'] ?? 0);
                    $minStock = (int) ($low['min_stock'] ?? 0);
                    $suggestQty = max(0, ($minStock * 2) - $stockNow);
                    ?>
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="p-2 rounded-3 d-flex justify-content-between align-items-center" style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08);">
                            <div>
                                <div class="fw-semibold"><?= esc((string) $low['name']) ?></div>
                                <small class="text-secondary">Stok <?= esc((string) $stockNow) ?> | Min <?= esc((string) $minStock) ?> | Saran Restok <?= esc((string) $suggestQty) ?></small>
                            </div>
                            <?php if ($isAdmin): ?>
                                <button type="button" class="btn btn-sm btn-outline-light pilih-produk-btn" data-product-id="<?= esc((string) $low['id']) ?>" data-suggest-qty="<?= esc((string) $suggestQty) ?>">Restok</button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="row g-3">
        <div class="col-12 col-xl-4">
            <div class="panel">
                <div class="panel-title">Input Stok Masuk</div>
                <p class="panel-muted mb-3">Catat pembelian barang dari supplier.</p>

                <?php if (! $isAdmin): ?>
                    <div class="alert alert-warning mb-0">Mode Read Only untuk kasir. Hanya admin yang dapat menambah stok masuk.</div>
                <?php else: ?>
                    <form method="post" action="<?= base_url('/stok-masuk/store') ?>" class="row g-2">
                        <?= csrf_field() ?>

                        <div class="col-12">
                            <label class="form-label">Produk</label>
                            <select name="product_id" class="form-select" required id="productSelect">
                                <option value="">Pilih produk</option>
                                <?php foreach ($products as $product): ?>
                                    <?php
                                    $stockValue = (int) ($product['stock'] ?? 0);
                                    $minValue = (int) ($product['min_stock'] ?? 0);
                                    $status = $stockValue <= 0 ? 'HABIS' : ($stockValue <= $minValue ? 'MENIPIS' : 'AMAN');
                                    $suggestQty = max(0, ($minValue * 2) - $stockValue);
                                    ?>
                                    <option value="<?= esc((string) $product['id']) ?>">
                                        [<?= esc($status) ?>] <?= esc($product['name']) ?> (Stok: <?= esc((string) $stockValue) ?>, Min: <?= esc((string) $minValue) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="mt-2 d-flex align-items-center gap-2" id="productStatusInfo" style="display:none;">
                                <span id="productStatusBadge" class="badge text-bg-secondary">-</span>
                                <small class="text-secondary" id="productSuggestText">Saran restok: -</small>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Supplier</label>
                            <select name="supplier_id" class="form-select" required>
                                <option value="">Pilih supplier</option>
                                <?php foreach ($suppliers as $supplier): ?>
                                    <option value="<?= esc((string) $supplier['id']) ?>"><?= esc($supplier['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-6">
                            <label class="form-label">Qty</label>
                            <input type="number" min="1" name="qty" class="form-control" required value="<?= esc(old('qty', '')) ?>" id="qtyInput">
                        </div>

                        <div class="col-6">
                            <label class="form-label">Harga Beli</label>
                            <input type="number" min="1" step="0.01" name="buy_price" class="form-control" required value="<?= esc(old('buy_price', '')) ?>">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Tanggal</label>
                            <input type="date" name="date" class="form-control" required value="<?= esc(old('date', date('Y-m-d'))) ?>">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Catatan</label>
                            <textarea name="notes" class="form-control" rows="3" placeholder="Opsional"><?= esc(old('notes', '')) ?></textarea>
                        </div>

                        <div class="col-12 d-grid mt-2">
                            <button type="submit" class="btn btn-info">Simpan Stok Masuk</button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-12 col-xl-8">
            <div class="panel">
                <div class="d-flex flex-column flex-lg-row justify-content-between gap-2 mb-3">
                    <div>
                        <div class="panel-title mb-1">Riwayat Stok Masuk</div>
                        <p class="panel-muted mb-0">Monitoring pembelian dan penambahan stok.</p>
                    </div>

                    <form method="get" action="<?= base_url('/stok-masuk') ?>" class="d-flex gap-2">
                        <input type="date" name="date" value="<?= esc((string) ($dateFilter ?? '')) ?>" class="form-control">
                        <button type="submit" class="btn btn-outline-light">Filter</button>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="table table-dark table-hover align-middle" style="--bs-table-bg: transparent; --bs-table-hover-bg: rgba(255,255,255,0.06);">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Produk</th>
                                <th>Supplier</th>
                                <th>Qty</th>
                                <th>Harga Beli</th>
                                <th>Total</th>
                                <th>Petugas</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($history)): ?>
                                <tr>
                                    <td colspan="8" class="text-center text-secondary py-4">Belum ada data stok masuk.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($history as $row): ?>
                                    <tr>
                                        <td><?= esc((string) $row['date']) ?></td>
                                        <td><?= esc((string) ($row['product_name'] ?? '-')) ?></td>
                                        <td><?= esc((string) ($row['supplier_name'] ?? '-')) ?></td>
                                        <td><?= esc((string) $row['qty']) ?></td>
                                        <td>Rp <?= number_format((float) $row['buy_price'], 0, ',', '.') ?></td>
                                        <td>Rp <?= number_format((float) $row['total_price'], 0, ',', '.') ?></td>
                                        <td><?= esc((string) ($row['username'] ?? '-')) ?></td>
                                        <td><?= esc((string) ($row['notes'] ?: '-')) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        const productSelect = document.getElementById('productSelect');
        const qtyInput = document.getElementById('qtyInput');
        const productStatusInfo = document.getElementById('productStatusInfo');
        const productStatusBadge = document.getElementById('productStatusBadge');
        const productSuggestText = document.getElementById('productSuggestText');

        function parseStatusFromText(text) {
            if (text.startsWith('[HABIS]')) return 'HABIS';
            if (text.startsWith('[MENIPIS]')) return 'MENIPIS';
            return 'AMAN';
        }

        function parseSuggestQtyFromText(text) {
            const minMatch = text.match(/Min:\s*(\d+)/i);
            const stockMatch = text.match(/Stok:\s*(\d+)/i);
            if (!minMatch || !stockMatch) return 1;
            const min = Number(minMatch[1]);
            const stock = Number(stockMatch[1]);
            return Math.max(1, (min * 2) - stock);
        }

        function updateProductStatusInfo() {
            if (!productSelect) return;

            const selectedOption = productSelect.options[productSelect.selectedIndex];
            if (!selectedOption || !selectedOption.value) {
                productStatusInfo.style.display = 'none';
                return;
            }

            const optionText = selectedOption.textContent || '';
            const status = parseStatusFromText(optionText);
            const suggestQty = parseSuggestQtyFromText(optionText);

            productStatusInfo.style.display = 'flex';
            productStatusBadge.className = 'badge';

            if (status === 'HABIS') {
                productStatusBadge.classList.add('text-bg-danger');
            } else if (status === 'MENIPIS') {
                productStatusBadge.classList.add('text-bg-warning');
            } else {
                productStatusBadge.classList.add('text-bg-success');
            }

            productStatusBadge.textContent = status;
            productSuggestText.textContent = 'Saran restok: ' + suggestQty;

            if (qtyInput && (!qtyInput.value || Number(qtyInput.value) <= 0)) {
                qtyInput.value = suggestQty;
            }
        }

        if (productSelect) {
            productSelect.addEventListener('change', updateProductStatusInfo);
            updateProductStatusInfo();
        }

        document.querySelectorAll('.pilih-produk-btn').forEach((button) => {
            button.addEventListener('click', () => {
                if (!productSelect) return;
                productSelect.value = button.dataset.productId;
                productSelect.dispatchEvent(new Event('change'));

                const suggested = Number(button.dataset.suggestQty || '1');
                if (qtyInput) {
                    qtyInput.value = Math.max(1, suggested);
                }

                productSelect.scrollIntoView({ behavior: 'smooth', block: 'center' });
            });
        });
    </script>
<?= $this->endSection() ?>