@extends('layouts.admin')

@section('title', 'Quản lý Sản phẩm')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1 text-gray-800 fw-bold">Sản phẩm</h1>
        <p class="mb-0 text-muted small">Quản lý chi tiết các sản phẩm trong hệ thống</p>
    </div>
</div>

<div class="tech-card h-100">
    <div class="tech-header" style="background: linear-gradient(135deg, #4e73df 0%, #224abe 100%); padding: 20px 25px;">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <h6 class="mb-0 fw-bold text-white d-flex align-items-center">
                <i class="fas fa-boxes me-2 bg-white bg-opacity-25 rounded-circle p-2" style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;"></i>
                Danh sách Sản phẩm
            </h6>

            <form method="GET" action="{{ route('admin.products.index') }}" class="d-flex align-items-center flex-wrap gap-2 text-white">
                <div class="d-flex align-items-center bg-white rounded-pill px-3 py-2 shadow-sm">
                    <small class="text-muted fw-bold me-2 text-uppercase" style="font-size: 0.65rem;">Hiển thị</small>
                    <select name="per_page" class="form-select form-select-sm border-0 bg-transparent fw-bold text-dark py-0 pe-4"
                            style="width: auto; box-shadow: none; cursor: pointer;" onchange="this.form.submit()">
                        <option value="15" {{ request('per_page', 15) == 15 ? 'selected' : '' }}>15</option>
                        <option value="30" {{ request('per_page') == 30 ? 'selected' : '' }}>30</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                    </select>
                </div>

                <div class="bg-white rounded-pill shadow-sm" style="flex: 1; min-width: 200px; max-width: 300px;">
                    <div class="position-relative">
                        <i class="fas fa-search position-absolute top-50 start-0 translate-middle-y text-muted ms-3" style="z-index: 5;"></i>
                        <input type="text" name="search" class="form-control form-select-sm border-0 bg-transparent rounded-pill ps-5 pe-3 py-2"
                               placeholder="Tìm tên, mã sản phẩm..." value="{{ request('search') }}">
                    </div>
                </div>

                <div class="dropdown">
                    <button class="btn bg-white rounded-pill fw-bold text-dark px-3 py-2 shadow-sm" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-filter me-1 text-secondary"></i> Lọc
                    </button>
                    <div class="dropdown-menu shadow-lg border-0 mt-2 p-3" style="width: 280px;">
                        <h6 class="dropdown-header px-0 text-uppercase fw-bold mb-2 small text-muted">Bộ lọc nâng cao</h6>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Bộ sưu tập (Danh mục)</label>
                            <select name="category_id" class="form-select form-select-sm">
                                <option value="">Tất cả danh mục</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Trạng thái</label>
                            <select name="status" class="form-select form-select-sm">
                                <option value="">Tất cả</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Đang bán</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Ngừng bán</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm w-100 fw-bold">Áp dụng</button>
                        @if(request('category_id') || request('status') || request('search'))
                            <a href="{{ route('admin.products.index') }}" class="btn btn-link btn-sm w-100 mt-1 text-decoration-none text-muted">Xóa lọc</a>
                        @endif
                    </div>
                </div>

                <a href="{{ route('admin.products.create') }}" class="text-white fw-bold px-2 text-decoration-none d-flex align-items-center ms-2">
                    <i class="fas fa-plus me-1"></i> Thêm mới
                </a>
            </form>
        </div>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-modern mb-0">
                <thead>
                    <tr>
                        <th class="ps-4" style="width: 60px;">#</th>
                        <th>Hình ảnh</th>
                        <x-admin.table-header key="name" label="Tên sản phẩm" :sortColumn="$sortColumn" :sortOrder="$sortOrder" />
                        <x-admin.table-header key="price" label="Giá bán" :sortColumn="$sortColumn" :sortOrder="$sortOrder" />
                        <x-admin.table-header key="stock" label="Tồn kho" :sortColumn="$sortColumn" :sortOrder="$sortOrder" />
                        <th>Danh mục</th>
                        <th>Trạng thái</th>
                        <th class="text-end pe-4" style="width: 120px;">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td class="ps-4 text-muted small">{{ $loop->iteration + ($products->currentPage() - 1) * $products->perPage() }}</td>
                        <td>
                            @if($product->thumbnail)
                                <img src="{{ Storage::url($product->thumbnail) }}" alt="{{ $product->name }}" class="rounded shadow-sm border" style="width: 48px; height: 48px; object-fit: cover;">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center text-muted border" style="width: 48px; height: 48px;">
                                    <i class="fas fa-image"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <div class="fw-bold text-dark">{{ $product->name }}</div>
                            <div class="small text-muted">Mã: {{ $product->code }}</div>
                        </td>
                        <td>
                            <div class="text-danger fw-bold">{{ $product->formatted_price }}</div>
                            @if($product->is_on_sale)
                                <div class="small text-muted text-decoration-line-through">{{ number_format($product->price, 0, ',', '.') }}₫</div>
                            @endif
                        </td>
                        <td><span class="{{ $product->stock <= 5 ? 'text-danger fw-bold' : '' }}">{{ $product->stock }}</span></td>
                        <td>
                            <span class="badge bg-secondary bg-opacity-10 text-secondary">{{ $product->category->name ?? 'Không' }}</span>
                        </td>
                        <td>
                            @if($product->status == 'active')
                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2"><i class="fas fa-circle me-1" style="font-size: 8px;"></i> Đang bán</span>
                            @else
                                <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-2"><i class="fas fa-circle me-1" style="font-size: 8px;"></i> Ngừng bán</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-outline-info rounded-circle d-inline-flex align-items-center justify-content-center animate-hover" style="width:32px;height:32px;" title="Sửa">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="d-inline-block" id="delete-form-{{ $product->id }}">
                                @csrf @method('DELETE')
                                <button type="button" class="btn btn-sm btn-outline-danger rounded-circle d-inline-flex align-items-center justify-content-center animate-hover" style="width:32px;height:32px;" title="Xóa" onclick="confirmDelete({{ $product->id }}, '{{ addslashes($product->name) }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <div class="d-flex flex-column align-items-center">
                                <div class="bg-light rounded-circle p-4 mb-3">
                                    <i class="fas fa-box-open fa-3x text-secondary opacity-50"></i>
                                </div>
                                <h6 class="text-muted fw-bold">Không tìm thấy sản phẩm nào</h6>
                                <p class="text-muted small mb-0">Thử thay đổi bộ lọc hoặc thêm mới.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-3 border-top">
            {{ $products->links() }}
        </div>
    </div>
</div>

<style>
    .animate-hover { transition: all 0.2s; }
    .animate-hover:hover { transform: translateY(-2px); filter: brightness(1.1); }
</style>
@endsection

@section('scripts')
<script>
function confirmDelete(id, name) {
    Swal.fire({
        title: 'Bạn có chắc chắn?',
        html: `Xóa sản phẩm <strong>${name}</strong>? Hành động này không thể hoàn tác!`,
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
