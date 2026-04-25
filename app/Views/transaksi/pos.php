<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('content') ?>
    <?php if (! empty($lowStockProducts ?? [])): ?>
        <div class="alert alert-warning d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3" role="alert">
            <div>
                <strong>Peringatan stok menipis.</strong>
                Ada <?= esc((string) count($lowStockProducts)) ?> produk yang sudah mencapai batas minimum.
            </div>
            <a href="<?= base_url('/stok-masuk') ?>" class="btn btn-sm btn-outline-dark">Input Restok</a>
        </div>
    <?php endif; ?>

    <div class="row g-3">
        <div class="col-12 col-xl-8">
            <div class="panel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="panel-title mb-0">Kasir POS</div>
                    <a href="<?= base_url('/transaksi/riwayat') ?>" class="btn btn-outline-light btn-sm">Lihat Riwayat</a>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-12 col-md-6">
                        <input id="searchProduct" type="text" class="form-control" placeholder="Cari produk...">
                    </div>
                </div>

                <div id="productGrid" class="row g-2">
                    <?php foreach ($products as $product): ?>
                        <?php $isLow = (int) ($product['stock'] ?? 0) <= (int) ($product['min_stock'] ?? 0); ?>
                        <div class="col-12 col-md-6">
                            <button
                                type="button"
                                class="btn w-100 text-start border <?= $isLow ? 'border-warning-subtle' : 'border-secondary-subtle' ?> product-btn"
                                data-id="<?= esc((string) $product['id']) ?>"
                                data-name="<?= esc($product['name']) ?>"
                                data-price="<?= esc((string) $product['price']) ?>"
                                data-stock="<?= esc((string) $product['stock']) ?>"
                                data-minstock="<?= esc((string) ($product['min_stock'] ?? 0)) ?>">
                                <div class="fw-semibold"><?= esc($product['name']) ?></div>
                                <small class="text-secondary"><?= esc((string) $product['category_name']) ?> | <?= esc((string) $product['unit_name']) ?></small>
                                <div class="mt-1 text-info fw-semibold">Rp <?= number_format((float) $product['price'], 0, ',', '.') ?></div>
                                <small>
                                    Stok: <?= esc((string) $product['stock']) ?>
                                    <?php if ($isLow): ?>
                                        <span class="badge text-bg-warning ms-1">Menipis</span>
                                    <?php endif; ?>
                                </small>
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-4">
            <div class="panel">
                <div class="panel-title">Keranjang Belanja</div>

                <form method="post" action="<?= base_url('/transaksi/store') ?>" id="posForm">
                    <?= csrf_field() ?>
                    <input type="hidden" name="items_json" id="itemsJson">

                    <div class="mb-2">
                        <label class="form-label">Pelanggan</label>
                        <select class="form-select" name="customer_id" required>
                            <option value="">Pilih pelanggan</option>
                            <?php foreach ($customers as $customer): ?>
                                <option value="<?= esc((string) $customer['id']) ?>"><?= esc($customer['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Metode Pembayaran</label>
                        <select class="form-select" name="payment_method" id="paymentMethod" required>
                            <option value="Tunai">Tunai</option>
                            <option value="Kartu">Kartu</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nominal Bayar</label>
                        <input type="number" class="form-control" name="payment_amount" id="paymentAmount" min="0" step="0.01" value="0" required>
                    </div>

                    <div class="table-responsive mb-2">
                        <table class="table table-sm table-dark align-middle" style="--bs-table-bg: transparent;">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Qty</th>
                                    <th>Sub</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="cartBody">
                                <tr><td colspan="4" class="text-secondary text-center">Belum ada item</td></tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <span>Total</span>
                        <strong id="totalLabel">Rp 0</strong>
                    </div>

                    <div class="rounded-3 p-3 mb-3" style="background: rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.1);">
                        <div class="d-flex justify-content-between small text-secondary mb-1">
                            <span>Jenis item</span>
                            <span id="itemCountLabel">0</span>
                        </div>
                        <div class="d-flex justify-content-between small text-secondary">
                            <span>Total qty</span>
                            <span id="qtyCountLabel">0</span>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-info">Simpan Transaksi</button>
                        <button type="button" id="resetCart" class="btn btn-outline-light">Reset</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const cart = [];
        const cartBody = document.getElementById('cartBody');
        const totalLabel = document.getElementById('totalLabel');
        const itemsJson = document.getElementById('itemsJson');
        const paymentMethod = document.getElementById('paymentMethod');
        const paymentAmount = document.getElementById('paymentAmount');
        const posForm = document.getElementById('posForm');
        const itemCountLabel = document.getElementById('itemCountLabel');
        const qtyCountLabel = document.getElementById('qtyCountLabel');

        function formatRupiah(value) {
            return 'Rp ' + Number(value).toLocaleString('id-ID');
        }

        function totalCart() {
            return cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
        }

        function syncPaymentField() {
            if (paymentMethod.value === 'Kartu') {
                paymentAmount.value = totalCart();
                paymentAmount.readOnly = true;
            } else {
                paymentAmount.readOnly = false;
                if (Number(paymentAmount.value) < totalCart()) {
                    paymentAmount.value = totalCart();
                }
            }
        }

        function renderCart() {
            if (cart.length === 0) {
                cartBody.innerHTML = '<tr><td colspan="4" class="text-secondary text-center">Belum ada item</td></tr>';
            } else {
                cartBody.innerHTML = cart.map((item, index) => `
                    <tr>
                        <td>${item.name}</td>
                        <td>
                            <input type="number" min="1" max="${item.stock}" value="${item.qty}" class="form-control form-control-sm qty-input" data-index="${index}">
                        </td>
                        <td>${formatRupiah(item.price * item.qty)}</td>
                        <td><button type="button" class="btn btn-sm btn-outline-danger remove-btn" data-index="${index}">x</button></td>
                    </tr>
                `).join('');
            }

            totalLabel.textContent = formatRupiah(totalCart());
            itemCountLabel.textContent = cart.length;
            qtyCountLabel.textContent = cart.reduce((sum, item) => sum + item.qty, 0);
            itemsJson.value = JSON.stringify(cart.map(item => ({ product_id: item.id, qty: item.qty })));
            syncPaymentField();
        }

        document.querySelectorAll('.product-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = Number(btn.dataset.id);
                const existing = cart.find(item => item.id === id);
                const stock = Number(btn.dataset.stock);

                if (stock <= 0) {
                    alert('Stok produk habis. Tidak bisa ditambahkan ke keranjang.');
                    return;
                }

                if (existing) {
                    if (existing.qty < stock) {
                        existing.qty += 1;
                    }
                } else {
                    cart.push({
                        id,
                        name: btn.dataset.name,
                        price: Number(btn.dataset.price),
                        stock,
                        qty: 1
                    });
                }

                renderCart();
            });
        });

        cartBody.addEventListener('input', (e) => {
            if (!e.target.classList.contains('qty-input')) return;
            const index = Number(e.target.dataset.index);
            const qty = Math.max(1, Math.min(Number(e.target.value), cart[index].stock));
            cart[index].qty = qty;
            renderCart();
        });

        cartBody.addEventListener('click', (e) => {
            if (!e.target.classList.contains('remove-btn')) return;
            const index = Number(e.target.dataset.index);
            cart.splice(index, 1);
            renderCart();
        });

        paymentMethod.addEventListener('change', syncPaymentField);

        document.getElementById('resetCart').addEventListener('click', () => {
            cart.splice(0, cart.length);
            renderCart();
        });

        document.getElementById('searchProduct').addEventListener('input', (e) => {
            const keyword = e.target.value.toLowerCase().trim();
            document.querySelectorAll('#productGrid .product-btn').forEach(btn => {
                const isMatch = btn.dataset.name.toLowerCase().includes(keyword);
                btn.closest('.col-12').style.display = isMatch ? '' : 'none';
            });
        });

        posForm.addEventListener('submit', (e) => {
            if (cart.length === 0) {
                e.preventDefault();
                alert('Keranjang masih kosong.');
                return;
            }

            if (Number(paymentAmount.value) < totalCart()) {
                e.preventDefault();
                alert('Nominal pembayaran kurang dari total transaksi.');
            }
        });
    </script>
<?= $this->endSection() ?>