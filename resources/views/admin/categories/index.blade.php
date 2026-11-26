<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>គ្រប់គ្រងប្រភេទទំនិញ - SSMS Admin</title>
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 64 64'%3E%3Crect width='64' height='64' rx='14' fill='%23198754'/%3E%3Cpath fill='%23ffffff' d='M18 22h28l-3 24H21l-3-24zm8-4a6 6 0 0 1 12 0h-4a2 2 0 0 0-4 0h-4z'/%3E%3C/svg%3E">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="fw-bold mb-0">ប្រភេទទំនិញ (Manage Categories)</h3>
            <a href="{{ route('admin.products.index') }}" class="small text-decoration-none">← ទៅកាន់ទំព័រគ្រប់គ្រងទំនិញ</a>
        </div>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
            + បន្ថែមប្រភេទថ្មី
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
                        <th>ឈ្មោះប្រភេទ</th>
                        <th>ការពិពណ៌នា</th>
                        <th>ចំនួនទំនិញ</th>
                        <th>ស្ថានភាព</th>
                        <th>សកម្មភាព</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $index => $category)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="fw-semibold">{{ $category->category_name }}</td>
                            <td>{{ $category->description ?? '-' }}</td>
                            <td>{{ $category->products_count }}</td>
                            <td>
                                <span class="badge bg-success">Active</span>
                            </td>
                            <td class="text-nowrap">
                                <button
                                    type="button"
                                    class="btn btn-sm btn-warning edit-category-btn"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editCategoryModal"
                                    data-update-url="{{ route('admin.categories.update', $category) }}"
                                    data-name="{{ $category->category_name }}"
                                    data-description="{{ $category->description }}"
                                    data-status="Active"
                                >
                                    កែសម្រួល
                                </button>

                                <form
                                    action="{{ route('admin.categories.destroy', $category) }}"
                                    method="POST"
                                    class="d-inline"
                                    onsubmit="return confirm('តើអ្នកពិតជាចង់លុបប្រភេទទំនិញ «{{ $category->category_name }}» ចេញពីប្រព័ន្ធមែនទេ?');"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">លុប</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">មិនទាន់មានប្រភេទទំនិញនៅឡើយទេ។</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Add Category -->
<div class="modal fade" id="addCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('admin.categories.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title fw-bold">បន្ថែមប្រភេទទំនិញថ្មី</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">ឈ្មោះប្រភេទ *</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="ឧទាហរណ៍៖ ភេសជ្ជៈ, នំស្រួយ" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">ការពិពណ៌នា</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">ស្ថានភាព</label>
                    <select name="status" class="form-select">
                        <option value="Active" selected>Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">បិទ</button>
                <button type="submit" class="btn btn-primary">រក្សាទុក</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Category -->
<div class="modal fade" id="editCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="editCategoryForm" action="" method="POST" class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title fw-bold">កែសម្រួលប្រភេទទំនិញ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">ឈ្មោះប្រភេទ *</label>
                    <input type="text" id="edit_category_name" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">ការពិពណ៌នា</label>
                    <textarea id="edit_category_description" name="description" class="form-control" rows="3"></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">ស្ថានភាព</label>
                    <select id="edit_category_status" name="status" class="form-select">
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
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
    document.querySelectorAll('.edit-category-btn').forEach(button => {
        button.addEventListener('click', function () {
            const form = document.getElementById('editCategoryForm');
            form.action = this.dataset.updateUrl;

            document.getElementById('edit_category_name').value = this.dataset.name || '';
            document.getElementById('edit_category_description').value = this.dataset.description || '';
            document.getElementById('edit_category_status').value = this.dataset.status || 'Active';
        });
    });
</script>
</body>
</html>