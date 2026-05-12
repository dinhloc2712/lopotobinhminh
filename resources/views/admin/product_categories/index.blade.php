@extends('layouts.admin')

@section('title', 'Bộ sưu tập sản phẩm')

@section('content')
{{-- Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1 text-gray-800 fw-bold">Bộ sưu tập / Danh mục sản phẩm</h1>
        <p class="mb-0 text-muted small">Quản lý các bộ sưu tập để phân loại sản phẩm</p>
    </div>
</div>

<div class="tech-card h-100">
    {{-- Card Header --}}
    <div class="tech-header" style="background: linear-gradient(135deg, #224abe 0%, #4e73df 100%); padding: 20px 25px;">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <h6 class="mb-0 fw-bold text-white d-flex align-items-center">
                <i class="fas fa-layer-group me-2 bg-white bg-opacity-25 rounded-circle p-2"
                   style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;"></i>
                Danh sách Bộ sưu tập
            </h6>

            <form method="GET" action="{{ route('admin.product-categories.index') }}" class="d-flex align-items-center flex-wrap gap-2">
                {{-- Per Page --}}
                <div class="d-flex align-items-center bg-white rounded-pill px-3 py-2 shadow-sm">
                    <small class="text-muted fw-bold me-2 text-uppercase" style="font-size: 0.65rem;">Hiển thị</small>
                    <select name="per_page" class="form-select form-select-sm border-0 bg-transparent fw-bold text-dark py-0 pe-4"
                            style="width: auto; box-shadow: none; cursor: pointer;" onchange="this.form.submit()">
                        <option value="15" {{ request('per_page', 15) == 15 ? 'selected' : '' }}>15</option>
                        <option value="30" {{ request('per_page') == 30 ? 'selected' : '' }}>30</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                    </select>
                </div>

                {{-- Search --}}
                <div class="bg-white rounded-pill shadow-sm" style="flex: 1; min-width: 200px; max-width: 300px;">
                    <div class="position-relative">
                        <i class="fas fa-search position-absolute top-50 start-0 translate-middle-y text-muted ms-3" style="z-index: 5;"></i>
                        <input type="text" name="search" class="form-control form-select-sm border-0 bg-transparent rounded-pill ps-5 pe-3 py-2"
                               placeholder="Tìm tên hoặc slug..." value="{{ request('search') }}">
                    </div>
                </div>

                {{-- Add Button --}}
                <button type="button" class="btn text-white fw-bold px-2 d-flex align-items-center border-0 bg-transparent"
                        data-bs-toggle="modal" data-bs-target="#categoryModal" onclick="resetModal()">
                    <i class="fas fa-plus me-1"></i> Thêm mới
                </button>
            </form>
        </div>
    </div>

    {{-- Table --}}
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-modern mb-0">
                <thead>
                    <tr>
                        <th class="ps-4" style="width: 60px;">#</th>
                        <x-admin.table-header key="name" label="Tên bộ sưu tập" :sortColumn="$sortColumn" :sortOrder="$sortOrder" />
                        <x-admin.table-header key="slug" label="Đường dẫn (Slug)" :sortColumn="$sortColumn" :sortOrder="$sortOrder" />
                        <x-admin.table-header key="products_count" label="Số sản phẩm" :sortColumn="$sortColumn" :sortOrder="$sortOrder" class="text-center" />
                        <x-admin.table-header key="created_at" label="Ngày tạo" :sortColumn="$sortColumn" :sortOrder="$sortOrder" />
                        <th class="text-end pe-4" style="width: 150px;">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                    <tr>
                        <td class="ps-4 text-muted small">{{ $loop->iteration + ($categories->currentPage() - 1) * $categories->perPage() }}</td>
                        <td>
                            <div class="fw-bold text-dark">{{ $category->name }}</div>
                            @if($category->description)
                                <div class="small text-muted text-truncate" style="max-width: 300px;">{{ $category->description }}</div>
                            @endif
                        </td>
                        <td>
                             <code class="small bg-light px-2 py-1 rounded text-primary">{{ $category->slug }}</code>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3">{{ $category->products_count }}</span>
                        </td>
                        <td>
                            <div class="small text-muted">{{ $category->created_at->format('d/m/Y H:i') }}</div>
                        </td>
                        <td class="text-end pe-4">
                            <button type="button" class="btn btn-sm btn-outline-info rounded-circle d-inline-flex align-items-center justify-content-center"
                               style="width:32px;height:32px;" title="Sửa"
                               data-bs-toggle="modal" data-bs-target="#categoryModal"
                               onclick="editCategory({{ json_encode($category) }})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form action="{{ route('admin.product-categories.destroy', $category->id) }}"
                                  method="POST" class="d-inline-block" id="delete-form-{{ $category->id }}">
                                @csrf @method('DELETE')
                                <button type="button"
                                        class="btn btn-sm btn-outline-danger rounded-circle d-inline-flex align-items-center justify-content-center"
                                        style="width:32px;height:32px;" title="Xóa"
                                        onclick="confirmDelete({{ $category->id }}, '{{ addslashes($category->name) }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">Không tìm thấy dữ liệu phù hợp.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-3 border-top">
            {{ $categories->links() }}
        </div>
    </div>
</div>

{{-- Category Modal --}}
<div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title fw-bold" id="categoryModalLabel">Thêm bộ sưu tập mới</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="categoryForm" action="{{ route('admin.product-categories.store') }}" method="POST">
                @csrf
                <div id="methodField"></div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-uppercase text-muted">Tên bộ sưu tập <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control rounded-3" placeholder="Ví dụ: Áo thun, Quần jean..." required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-uppercase text-muted">Slug (Đường dẫn) <span class="text-danger">*</span></label>
                        <input type="text" name="slug" id="slug" class="form-control rounded-3" placeholder="vi-du-ao-thun" required title="Chỉ dùng chữ cái không dấu, số và dấu gạch ngang">
                        <div class="form-text small">Tự động tạo từ tên hoặc nhập thủ công.</div>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-bold small text-uppercase text-muted">Mô tả</label>
                        <textarea name="description" id="description" class="form-control rounded-3" rows="3" placeholder="Mô tả ngắn..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold">
                        <i class="fas fa-save me-1"></i> Lưu lại
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Auto-generate slug from name
document.getElementById('name').addEventListener('input', function() {
    if (document.getElementById('methodField').innerHTML === '') { // Only auto-fill if creating new
        let name = this.value;
        let slug = name.toLowerCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .replace(/[đĐ]/g, 'd')
            .replace(/([^0-9a-z-\s])/g, '-')
            .replace(/(\s+)/g, '-')
            .replace(/-+/g, '-')
            .replace(/^-+|-+$/g, '');
        document.getElementById('slug').value = slug;
    }
});

function resetModal() {
    document.getElementById('categoryModalLabel').innerText = 'Thêm bộ sưu tập mới';
    document.getElementById('categoryForm').action = "{{ route('admin.product-categories.store') }}";
    document.getElementById('methodField').innerHTML = '';
    document.getElementById('name').value = '';
    document.getElementById('slug').value = '';
    document.getElementById('description').value = '';
}

function editCategory(category) {
    document.getElementById('categoryModalLabel').innerText = 'Chỉnh sửa bộ sưu tập';
    document.getElementById('categoryForm').action = `/admin/product-categories/${category.id}`;
    document.getElementById('methodField').innerHTML = '@method("PUT")';
    document.getElementById('name').value = category.name;
    document.getElementById('slug').value = category.slug;
    document.getElementById('description').value = category.description || '';
}

function confirmDelete(id, name) {
    Swal.fire({
        title: 'Bạn có chắc chắn?',
        html: `Xóa bộ sưu tập <strong>${name}</strong>? Hành động này không thể hoàn tác!`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Vâng, xóa!',
        cancelButtonText: 'Hủy'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + id).submit();
        }
    });
}
</script>
@endsection
