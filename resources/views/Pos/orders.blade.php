<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 64 64'%3E%3Crect width='64' height='64' rx='14' fill='%23198754'/%3E%3Cpath fill='%23ffffff' d='M18 22h28l-3 24H21l-3-24zm8-4a6 6 0 0 1 12 0h-4a2 2 0 0 0-4 0h-4z'/%3E%3C/svg%3E">
    <title>ប្រវត្តិការលក់ - SSMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Battambang:wght@400;700&family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', 'Battambang', system-ui, -apple-system, sans-serif;
            background-color: #f4f6f9;
            color: #1e293b;
        }
        .page-header-box {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 18px 22px;
            box-shadow: 0 2px 10px rgba(15, 23, 42, 0.04);
        }
        .action-btn {
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 14px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.18s ease;
        }
        .action-btn:hover {
            transform: translateY(-1px);
        }
        .stat-card {
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            background: #ffffff;
            box-shadow: 0 2px 10px rgba(15, 23, 42, 0.04);
            transition: transform 0.18s ease, box-shadow 0.18s ease;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(15, 23, 42, 0.07);
        }
        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
            flex-shrink: 0;
        }
        .table-card {
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            background: #ffffff;
            box-shadow: 0 2px 10px rgba(15, 23, 42, 0.04);
            overflow: hidden;
        }
        .table thead th {
            background-color: #f8fafc;
            color: #475569;
            font-size: 0.82rem;
            font-weight: 700;
            text-transform: none;
            border-bottom: 1px solid #e2e8f0;
            white-space: nowrap;
            padding: 12px 14px;
        }
        .table tbody td {
            padding: 12px 14px;
            vertical-align: middle;
            border-color: #f1f5f9;
        }
        .table tbody tr:hover {
            background-color: #f8fafc;
        }
        .invoice-code {
            font-family: 'Inter', monospace;
            font-weight: 700;
            color: #0d6efd;
            background: #eff6ff;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 0.82rem;
            white-space: nowrap;
            display: inline-block;
        }
        .badge-cash,
        .badge-qr {
            display: inline-block;
            min-width: 72px;
            text-align: center;
            font-weight: 700;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
        }
        .badge-cash {
            background-color: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }
        .badge-qr {
            background-color: #e0f2fe;
            color: #075985;
            border: 1px solid #bae6fd;
        }
        .item-line {
            font-size: 0.85rem;
            line-height: 1.45;
        }
    </style>
</head>
<body>
<div class="container py-4">
    {{-- Header & Action Buttons --}}
    <div class="page-header-box d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h4 class="mb-1 fw-bold">ប្រវត្តិការលក់ (Sales History)</h4>
            <div class="text-muted small">តាមដានវិក្កយបត្រ ចំណូល និងព័ត៌មានលម្អិតនៃការលក់ទាំងអស់</div>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="/pos" class="btn btn-primary action-btn shadow-sm">
                <span>+</span>
                <span>ទៅទំព័រ POS Cashier</span>
            </a>
            <a href="/admin/products" class="btn btn-outline-dark action-btn">
                <span>←</span>
                <span>គ្រប់គ្រងទំនិញ</span>
            </a>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="stat-card p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-success-subtle text-success fw-bold">$</div>
                    <div>
                        <div class="text-muted small">ចំណូលសរុប (Total Revenue)</div>
                        <div class="fs-4 fw-bold text-success">${{ number_format($totalRevenue, 2) }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="stat-card p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-primary-subtle text-primary fw-bold">#</div>
                    <div>
                        <div class="text-muted small">ចំនួនវិក្កយបត្រសរុប (Total Invoices)</div>
                        <div class="fs-4 fw-bold text-primary">{{ $totalOrders }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter & Table Card --}}
    <div class="table-card">
        <div class="p-3 border-bottom d-flex flex-wrap justify-content-between align-items-center gap-2 bg-white">
            <div class="fw-bold">បញ្ជីវិក្កយបត្រដែលបានលក់</div>
            <div class="d-flex flex-wrap gap-2">
                <input type="text" id="search-invoice" class="form-control form-control-sm" style="width: 220px;" placeholder="ស្វែងរកលេខវិក្កយបត្រ...">
                <select id="filter-method" class="form-select form-select-sm" style="width: 150px;">
                    <option value="all">វិធីទូទាត់៖ ទាំងអស់</option>
                    <option value="cash">CASH</option>
                    <option value="qr">QR</option>
                </select>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table align-middle mb-0" id="orders-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>លេខវិក្កយបត្រ</th>
                        <th>មុខទំនិញ</th>
                        <th>វិធីទូទាត់</th>
                        <th class="text-end">សរុប</th>
                        <th class="text-end">ប្រាក់ទទួល</th>
                        <th class="text-end">ប្រាក់អាប់</th>
                        <th>កាលបរិច្ឆេទ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $index => $order)
                        @php
                            $method = strtolower($order->payment_method ?? 'cash');
                        @endphp
                        <tr class="order-row"
                            data-invoice="{{ strtolower($order->invoice_no) }}"
                            data-method="{{ $method }}">
                            <td class="text-muted small fw-semibold">{{ $index + 1 }}</td>
                            <td><span class="invoice-code">{{ $order->invoice_no }}</span></td>
                            <td>
                                @foreach($order->items as $item)
                                    <div class="item-line mb-1">
                                        <span class="fw-semibold">{{ $item->product->product_name ?? ('ទំនិញ #' . $item->product_id) }}</span>
                                        <span class="text-muted">× {{ $item->quantity }}</span>
                                        <span class="text-secondary">(${{ number_format($item->subtotal, 2) }})</span>
                                    </div>
                                @endforeach
                            </td>
                            <td>
                                @if($method === 'qr')
                                    <span class="badge-qr">QR</span>
                                @else
                                    <span class="badge-cash">CASH</span>
                                @endif
                            </td>
                            <td class="text-end fw-bold text-dark">${{ number_format($order->total_amount, 2) }}</td>
                            <td class="text-end text-secondary">${{ number_format($order->paid_amount, 2) }}</td>
                            <td class="text-end text-success fw-semibold">${{ number_format($order->change_amount, 2) }}</td>
                            <td class="small text-muted" style="white-space: nowrap;">
                                {{ $order->created_at ? $order->created_at->format('Y-m-d H:i') : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-5">មិនទាន់មានទិន្នន័យការលក់នៅឡើយ</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    const searchInput = document.getElementById('search-invoice');
    const methodSelect = document.getElementById('filter-method');

    function filterOrders() {
        const query = (searchInput?.value || '').trim().toLowerCase();
        const method = methodSelect?.value || 'all';

        document.querySelectorAll('.order-row').forEach(row => {
            const invoice = row.dataset.invoice || '';
            const rowMethod = row.dataset.method || 'cash';
            const matchInvoice = !query || invoice.includes(query);
            const matchMethod = method === 'all' || rowMethod === method;
            row.style.display = (matchInvoice && matchMethod) ? '' : 'none';
        });
    }

    searchInput?.addEventListener('input', filterOrders);
    methodSelect?.addEventListener('change', filterOrders);
</script>
</body>
</html>