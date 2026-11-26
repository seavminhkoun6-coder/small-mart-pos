<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>គ្រប់គ្រងទំនិញ - SSMS Admin</title>
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 64 64'%3E%3Crect width='64' height='64' rx='14' fill='%23198754'/%3E%3Cpath fill='%23ffffff' d='M18 22h28l-3 24H21l-3-24zm8-4a6 6 0 0 1 12 0h-4a2 2 0 0 0-4 0h-4z'/%3E%3C/svg%3E">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold mb-0">បញ្ជីទំនិញ (Manage Products)</h3>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addProductModal">
            + បន្ថែមទំនិញថ្មី
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>SKU</th>
                        <th>ឈ្មោះទំនិញ</th>
                        <th>ប្រភេទ</th>
                        <th>ថ្លៃដើម</th>
                        <th>តម្លៃលក់</th>
                        <th>ស្តុក</th>
                        <th>ថ្ងៃផុតកំណត់</th>
                        <th>ស្ថានភាព</th>
                        <th>សកម្មភាព</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $index => $product)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $product->sku }}</td>
                            <td>{{ $product->product_name }}</td>
                            <td>{{ $product->category->name ?? 'ទូទៅ' }}</td>
                            <td>${{ number_format($product->cost_price, 2) }}</td>
                            <td>${{ number_format($product->selling_price, 2) }}</td>
                            <td>
                                {{ $product->stock }}
                                @if($product->stock <= $product->min_stock)
                                    <span class="badge bg-warning text-dark ms-1">ជិតអស់ស្តុក</span>
                                @endif
                            </td>
                            <td>{{ $product->expiry_date ?? '-' }}</td>
                            <td>
                                <span class="badge {{ $product->status === 'Active' ? 'bg-success' : 'bg-danger' }}">
                                    {{ $product->status }}
                                </span>
                            </td>
                            <td class="text-nowrap">
                                <button
                                    type="button"
                                    class="btn btn-sm btn-warning edit-product-btn"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editProductModal"
                                    data-update-url="{{ route('admin.products.update', $product) }}"
                                    data-sku="{{ $product->sku }}"
                                    data-product-name="{{ $product->product_name }}"
                                    data-category-id="{{ $product->category_id }}"
                                    data-cost-price="{{ $product->cost_price }}"
                                    data-selling-price="{{ $product->selling_price }}"
                                    data-stock="{{ $product->stock }}"
                                    data-min-stock="{{ $product->min_stock }}"
                                    data-expiry-date="{{ $product->expiry_date }}"
                                >
                                    កែសម្រួល
                                </button>

                                <form
                                    action="{{ route('admin.products.destroy', $product) }}"
                                    method="POST"
                                    class="d-inline"
                                    onsubmit="return confirm('តើអ្នកពិតជាចង់លុបទំនិញ «{{ $product->product_name }}» ចេញពីប្រព័ន្ធមែនទេ?');"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">លុប</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted py-4">មិនទាន់មានទំនិញក្នុងប្រព័ន្ធនៅឡើយទេ។</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Add Product -->
<div class="modal fade" id="addProductModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('admin.products.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title fw-bold">បន្ថែមទំនិញថ្មី</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">លេខកូដ SKU *</label>
                    <input type="text" name="sku" class="form-control" value="{{ old('sku') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">ឈ្មោះទំនិញ *</label>
                    <input type="text" name="product_name" class="form-control" value="{{ old('product_name') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">ប្រភេទទំនិញ</label>
                    <select name="category_id" class="form-select">
                        <option value="">-- ជ្រើសរើសប្រភេទ --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">ថ្លៃដើម ($) *</label>
                        <input type="number" step="0.01" name="cost_price" class="form-control" value="{{ old('cost_price', 0) }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">តម្លៃលក់ ($) *</label>
                        <input type="number" step="0.01" name="selling_price" class="form-control" value="{{ old('selling_price', 0) }}" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">ចំនួនក្នុងស្តុក *</label>
                        <input type="number" name="stock" class="form-control" value="{{ old('stock', 0) }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">កម្រិតស្តុកទាប (Min Stock)</label>
                        <input type="number" name="min_stock" class="form-control" value="{{ old('min_stock', 5) }}">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">ថ្ងៃផុតកំណត់ (Expiry Date)</label>
                    <input type="date" name="expiry_date" class="form-control" value="{{ old('expiry_date') }}">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">បិទ</button>
                <button type="submit" class="btn btn-primary">រក្សាទុក</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Product -->
<div class="modal fade" id="editProductModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="editProductForm" action="" method="POST" class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title fw-bold">កែសម្រួលទំនិញ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">លេខកូដ SKU *</label>
                    <input type="text" id="edit_sku" name="sku" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">ឈ្មោះទំនិញ *</label>
                    <input type="text" id="edit_product_name" name="product_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">ប្រភេទទំនិញ</label>
                    <select id="edit_category_id" name="category_id" class="form-select">
                        <option value="">-- ជ្រើសរើសប្រភេទ --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">ថ្លៃដើម ($) *</label>
                        <input type="number" step="0.01" id="edit_cost_price" name="cost_price" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">តម្លៃលក់ ($) *</label>
                        <input type="number" step="0.01" id="edit_selling_price" name="selling_price" class="form-control" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">ចំនួនក្នុងស្តុក *</label>
                        <input type="number" id="edit_stock" name="stock" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">កម្រិតស្តុកទាប (Min Stock)</label>
                        <input type="number" id="edit_min_stock" name="min_stock" class="form-control">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">ថ្ងៃផុតកំណត់ (Expiry Date)</label>
                    <input type="date" id="edit_expiry_date" name="expiry_date" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">បិទ</button>
                <button type="submit" class="btn btn-primary">រក្សាការកែប្រែ</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.querySelectorAll('.edit-product-btn').forEach(button => {
        button.addEventListener('click', function () {
            const form = document.getElementById('editProductForm');
            form.action = this.dataset.updateUrl;

            document.getElementById('edit_sku').value = this.dataset.sku || '';
            document.getElementById('edit_product_name').value = this.dataset.productName || '';
            document.getElementById('edit_category_id').value = this.dataset.categoryId || '';
            document.getElementById('edit_cost_price').value = this.dataset.costPrice || 0;
            document.getElementById('edit_selling_price').value = this.dataset.sellingPrice || 0;
            document.getElementById('edit_stock').value = this.dataset.stock || 0;
            document.getElementById('edit_min_stock').value = this.dataset.minStock || 5;
            document.getElementById('edit_expiry_date').value = this.dataset.expiryDate || '';
        });
    });
</script>
</body>
</html>