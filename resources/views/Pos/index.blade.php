<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 64 64'%3E%3Crect width='64' height='64' rx='14' fill='%23198754'/%3E%3Cpath fill='%23ffffff' d='M18 22h28l-3 24H21l-3-24zm8-4a6 6 0 0 1 12 0h-4a2 2 0 0 0-4 0h-4z'/%3E%3C/svg%3E">
    <title>POS Cashier - SSMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Battambang:wght@400;700&family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', 'Battambang', system-ui, -apple-system, sans-serif;
            background-color: #f4f6f9;
            color: #1e293b;
        }
        .pos-btn {
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.85rem;
            padding: 7px 14px;
            transition: all 0.18s ease;
        }
        .pos-btn:hover {
            transform: translateY(-1px);
        }
        .category-btn {
            white-space: nowrap;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.84rem;
            padding: 6px 15px;
            min-width: 76px;
            transition: all 0.18s ease;
        }
        .category-btn:hover {
            transform: translateY(-1px);
        }
        .search-box {
            border-radius: 10px;
            padding: 8px 12px;
            font-size: 0.875rem;
        }
        .product-card .card {
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            transition: transform 0.16s ease, box-shadow 0.16s ease;
        }
        .product-card .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(15, 23, 42, 0.07) !important;
        }
        .receipt-paper {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 18px 16px;
            font-family: 'Inter', 'Battambang', system-ui, -apple-system, sans-serif;
        }
        .receipt-dash {
            border-top: 1px dashed #cbd5e1;
            margin: 12px 0;
        }
        .receipt-row {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            margin-bottom: 6px;
            font-size: 0.9rem;
        }
        .receipt-total {
            font-size: 1.05rem;
            font-weight: 700;
            color: #111827;
        }
        @media print {
            body * { visibility: hidden; }
            #receipt-modal, #receipt-modal * { visibility: visible; }
            #receipt-modal {
                position: fixed;
                left: 0;
                top: 0;
                width: 100%;
                margin: 0;
                padding: 0;
            }
            .modal-dialog { max-width: 320px; margin: 0 auto; }
            .modal-content { border: none !important; box-shadow: none !important; }
            .receipt-paper { border: none !important; padding: 8px !important; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>
<div class="container-fluid py-4 px-lg-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <h3 class="mb-0 fw-bold">ទំព័រលក់ទំនិញ (POS Cashier)</h3>
        <div class="d-flex flex-wrap gap-2">
            <a href="/pos/orders" class="btn btn-outline-primary pos-btn">ប្រវត្តិការលក់</a>
            <a href="/admin/products" class="btn btn-outline-dark pos-btn">← ត្រឡប់ទៅគ្រប់គ្រងទំនិញ</a>
        </div>
    </div>

    <div class="row g-4">
        {{-- បញ្ជីទំនិញ --}}
        <div class="col-lg-8">
            <div class="row g-2 mb-3 align-items-center">
                <div class="col-md-4">
                    <input type="text" id="search-product" class="form-control search-box bg-white" placeholder="ស្វែងរកតាមឈ្មោះទំនិញ ឬ SKU...">
                </div>
                <div class="col-md-8 d-flex flex-wrap gap-2">
                    <button type="button" class="btn btn-dark category-btn active" data-category="all">ទាំងអស់</button>
                    @foreach($categories as $category)
                        <button type="button" class="btn btn-outline-dark category-btn" data-category="{{ $category->id }}">
                            {{ $category->category_name }}
                        </button>
                    @endforeach
                </div>
            </div>

            <div class="row g-3" id="products-grid">
                @foreach($products as $product)
                    @php
                        $price = $product->selling_price ?? 0;
                        $productName = $product->product_name ?? ('ទំនិញ #' . $product->id);
                        $skuText = $product->sku ?? '-';
                    @endphp
                    <div class="col-md-4 col-sm-6 product-card"
                         data-category="{{ $product->category_id }}"
                         data-name="{{ mb_strtolower($productName) }}"
                         data-sku="{{ mb_strtolower($skuText) }}">
                        <div class="card h-100 shadow-sm bg-white">
                            <div class="card-body d-flex flex-column justify-content-between">
                                <div>
                                    <div class="d-flex justify-content-between small text-muted mb-1">
                                        <span>SKU: {{ $skuText }}</span>
                                        <span>ស្តុក: <strong class="stock-label">{{ $product->stock }}</strong></span>
                                    </div>
                                    <h6 class="card-title mb-2 fw-bold">{{ $productName }}</h6>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <span class="fw-bold text-primary">${{ number_format($price, 2) }}</span>
                                    <button type="button"
                                            class="btn btn-primary pos-btn py-1 px-3 add-to-cart"
                                            data-id="{{ $product->id }}"
                                            data-name="{{ $productName }}"
                                            data-price="{{ $price }}"
                                            data-stock="{{ $product->stock }}">
                                        + ដាក់កន្ត្រក
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- កន្ត្រកទំនិញ និងគិតលុយ --}}
        <div class="col-lg-4">
            <div class="card shadow-sm border-0" style="border-radius: 14px; overflow: hidden;">
                <div class="card-header bg-primary text-white py-3">
                    <strong>កន្ត្រកទំនិញ (Cart)</strong>
                </div>
                <div class="card-body bg-white">
                    <div class="table-responsive" style="max-height: 280px; overflow-y: auto;">
                        <table class="table table-sm align-middle">
                            <thead>
                                <tr>
                                    <th>ទំនិញ</th>
                                    <th class="text-center">ចំនួន</th>
                                    <th class="text-end">សរុប</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="cart-body">
                                <tr id="empty-cart-row">
                                    <td colspan="4" class="text-center text-muted py-4">មិនទាន់មានទំនិញក្នុងកន្ត្រក</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <hr>
                    <div class="d-flex justify-content-between mb-2">
                        <span>តម្លៃសរុប៖</span>
                        <strong class="fs-5 text-danger" id="total-amount">$0.00</strong>
                    </div>

                    <div class="mb-2">
                        <label class="form-label small mb-1">វិធីទូទាត់</label>
                        <select id="payment-method" class="form-select form-select-sm">
                            <option value="cash">សាច់ប្រាក់ (Cash)</option>
                            <option value="qr">ស្កេន QR (KHQR / Bank)</option>
                        </select>
                    </div>

                    <div class="mb-2">
                        <label class="form-label small mb-1">ប្រាក់ទទួលបាន ($)</label>
                        <input type="number" step="0.01" min="0" id="paid-amount" class="form-control" placeholder="0.00">
                        <div class="d-flex flex-wrap gap-1 mt-2">
                            <button type="button" class="btn btn-outline-secondary btn-sm quick-cash" data-mode="exact">សមល្មម</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm quick-cash" data-amount="10">$10</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm quick-cash" data-amount="20">$20</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm quick-cash" data-amount="50">$50</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm quick-cash" data-amount="100">$100</button>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <span>ប្រាក់អាប់៖</span>
                        <strong class="text-success" id="change-amount">$0.00</strong>
                    </div>

                    <button type="button" id="checkout-btn" class="btn btn-success pos-btn w-100 py-2" disabled>
                        បញ្ជាក់ការលក់ (Checkout)
                    </button>
                    <div id="pos-alert" class="mt-3"></div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Receipt Modal --}}
<div class="modal fade" id="receipt-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 360px;">
        <div class="modal-content border-0 shadow">
            <div class="modal-header py-2 px-3 bg-light no-print">
                <span class="fw-bold small">វិក្កយបត្រ (Receipt)</span>
                <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                <div class="receipt-paper" id="receipt-content"></div>
            </div>
            <div class="modal-footer py-2 px-3 bg-light justify-content-between no-print">
                <button type="button" class="btn btn-secondary pos-btn py-1" id="close-receipt-btn" data-bs-dismiss="modal">បិទ</button>
                <button type="button" class="btn btn-primary pos-btn py-1 px-3" onclick="window.print()">ព្រីនវិក្កយបត្រ</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    let cart = [];
    let activeCategory = 'all';
    const receiptModal = new bootstrap.Modal(document.getElementById('receipt-modal'));

    function formatMoney(num) {
        return '$' + Number(num || 0).toFixed(2);
    }

    function getTotal() {
        return cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
    }

    function renderCart() {
        const tbody = document.getElementById('cart-body');
        const totalEl = document.getElementById('total-amount');
        const paidInput = document.getElementById('paid-amount');
        const changeEl = document.getElementById('change-amount');
        const checkoutBtn = document.getElementById('checkout-btn');

        if (cart.length === 0) {
            tbody.innerHTML = `<tr id="empty-cart-row"><td colspan="4" class="text-center text-muted py-4">មិនទាន់មានទំនិញក្នុងកន្ត្រក</td></tr>`;
            totalEl.textContent = '$0.00';
            changeEl.textContent = '$0.00';
            checkoutBtn.disabled = true;
            return;
        }

        tbody.innerHTML = cart.map((item, idx) => `
            <tr>
                <td>
                    <div class="fw-semibold small">${item.name}</div>
                    <div class="text-muted small">${formatMoney(item.price)}</div>
                </td>
                <td class="text-center">
                    <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-outline-secondary" onclick="changeQty(${idx}, -1)">-</button>
                        <span class="btn btn-light disabled">${item.quantity}</span>
                        <button type="button" class="btn btn-outline-secondary" onclick="changeQty(${idx}, 1)">+</button>
                    </div>
                </td>
                <td class="text-end small fw-semibold">${formatMoney(item.price * item.quantity)}</td>
                <td class="text-end">
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeItem(${idx})">×</button>
                </td>
            </tr>
        `).join('');

        const total = getTotal();
        totalEl.textContent = formatMoney(total);

        const paid = parseFloat(paidInput.value || 0);
        const change = paid >= total ? paid - total : 0;
        changeEl.textContent = formatMoney(change);
        checkoutBtn.disabled = paid < total || total <= 0;
    }

    function filterProducts() {
        const keyword = document.getElementById('search-product').value.trim().toLowerCase();
        document.querySelectorAll('.product-card').forEach(card => {
            const matchCat = (activeCategory === 'all' || card.dataset.category === activeCategory);
            const matchText = (!keyword || card.dataset.name.includes(keyword) || card.dataset.sku.includes(keyword));
            card.style.display = (matchCat && matchText) ? '' : 'none';
        });
    }

    function showReceipt(order) {
        const nowText = new Date().toLocaleString('en-GB');
        const methodLabel = (order.payment_method || 'cash').toUpperCase();

        const rows = (order.items || []).map(item => {
            const name = item.product?.product_name || ('ទំនិញ #' + item.product_id);
            return `
                <div class="mb-2">
                    <div class="fw-semibold small text-dark">${name}</div>
                    <div class="d-flex justify-content-between small text-muted">
                        <span>${item.quantity} × ${formatMoney(item.unit_price)}</span>
                        <span class="text-dark fw-semibold">${formatMoney(item.subtotal)}</span>
                    </div>
                </div>
            `;
        }).join('');

        document.getElementById('receipt-content').innerHTML = `
            <div class="text-center">
                <div class="fw-bold fs-6 tracking-wide">SMALL MART POS</div>
                <div class="text-muted" style="font-size: 0.75rem;">វិក្កយបត្រលក់ទំនិញ</div>
                <div class="text-muted mt-1" style="font-size: 0.72rem;">#${order.invoice_no}</div>
                <div class="text-muted" style="font-size: 0.72rem;">${nowText}</div>
            </div>
            <div class="receipt-dash"></div>
            <div>${rows}</div>
            <div class="receipt-dash"></div>
            <div class="receipt-row">
                <span class="text-muted">វិធីទូទាត់</span>
                <span class="badge bg-light text-dark border">${methodLabel}</span>
            </div>
            <div class="receipt-row receipt-total">
                <span>សរុប</span>
                <span>${formatMoney(order.total_amount)}</span>
            </div>
            <div class="receipt-row">
                <span class="text-muted">ប្រាក់ទទួល</span>
                <span>${formatMoney(order.paid_amount)}</span>
            </div>
            <div class="receipt-row">
                <span class="text-muted">ប្រាក់អាប់</span>
                <span class="fw-bold text-success">${formatMoney(order.change_amount)}</span>
            </div>
            <div class="receipt-dash"></div>
            <div class="text-center text-muted" style="font-size: 0.75rem;">អរគុណសម្រាប់ការគាំទ្រ!</div>
        `;
        receiptModal.show();
    }

    window.changeQty = function(idx, delta) {
        const item = cart[idx];
        const nextQty = item.quantity + delta;
        if (nextQty <= 0) {
            cart.splice(idx, 1);
        } else if (nextQty <= item.stock) {
            item.quantity = nextQty;
        } else {
            alert('ចំនួនលើសពីស្តុកដែលមាន (' + item.stock + ')');
        }
        renderCart();
    };

    window.removeItem = function(idx) {
        cart.splice(idx, 1);
        renderCart();
    };

    document.querySelectorAll('.add-to-cart').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = Number(btn.dataset.id);
            const name = btn.dataset.name;
            const price = Number(btn.dataset.price);
            const stock = Number(btn.dataset.stock);

            const existing = cart.find(i => i.product_id === id);
            if (existing) {
                if (existing.quantity + 1 > stock) {
                    alert('ចំនួនលើសពីស្តុកដែលមាន (' + stock + ')');
                    return;
                }
                existing.quantity += 1;
            } else {
                cart.push({ product_id: id, name, price, stock, quantity: 1 });
            }
            renderCart();
        });
    });

    document.querySelectorAll('.quick-cash').forEach(btn => {
        btn.addEventListener('click', () => {
            const total = getTotal();
            if (total <= 0) return;

            const paidInput = document.getElementById('paid-amount');
            if (btn.dataset.mode === 'exact') {
                paidInput.value = total.toFixed(2);
            } else {
                paidInput.value = Number(btn.dataset.amount).toFixed(2);
            }
            renderCart();
        });
    });

    document.getElementById('paid-amount').addEventListener('input', renderCart);
    document.getElementById('search-product').addEventListener('input', filterProducts);

    document.querySelectorAll('.category-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.category-btn').forEach(b => {
                b.classList.remove('btn-dark', 'active');
                b.classList.add('btn-outline-dark');
            });
            btn.classList.remove('btn-outline-dark');
            btn.classList.add('btn-dark', 'active');
            activeCategory = btn.dataset.category;
            filterProducts();
        });
    });

    document.getElementById('checkout-btn').addEventListener('click', async () => {
        const alertBox = document.getElementById('pos-alert');
        alertBox.innerHTML = '';

        const payload = {
            items: cart.map(i => ({ product_id: i.product_id, quantity: i.quantity })),
            paid_amount: parseFloat(document.getElementById('paid-amount').value || 0),
            payment_method: document.getElementById('payment-method').value
        };

        const res = await fetch('/pos/checkout', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(payload)
        });

        const data = await res.json();
        if (!res.ok) {
            alertBox.innerHTML = `<div class="alert alert-danger py-2 small">${data.message || 'មានបញ្ហាក្នុងការលក់'}</div>`;
            return;
        }

        alertBox.innerHTML = `<div class="alert alert-success py-2 small">${data.message}<br>វិក្កយបត្រ៖ <strong>${data.order.invoice_no}</strong></div>`;
        cart = [];
        document.getElementById('paid-amount').value = '';
        renderCart();
        showReceipt(data.order);
    });

    document.getElementById('close-receipt-btn').addEventListener('click', () => {
        window.location.reload();
    });
</script>
</body>
</html>