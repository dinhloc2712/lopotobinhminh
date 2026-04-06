@extends('layouts.admin')

@section('title', 'Quản lý Trang & Bài viết')

@section('content')
{{-- Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1 text-gray-800 fw-bold">Trình thiết kế Landing Page</h1>
        <p class="mb-0 text-muted small">Quản lý nội dung và bố cục của các trang/bài viết</p>
    </div>
</div>

<div class="tech-card h-100">
    {{-- Card Header --}}
    <div class="tech-header" style="background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%); padding: 20px 25px;">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <h6 class="mb-0 fw-bold text-white d-flex align-items-center">
                <i class="fas fa-pager me-2 bg-white bg-opacity-25 rounded-circle p-2"
                   style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;"></i>
                Danh sách Bài viết
            </h6>

            <form method="GET" action="{{ route('admin.posts.index') }}" class="d-flex align-items-center flex-wrap gap-2">
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
                               placeholder="Tìm tiêu đề hoặc đường dẫn..." value="{{ request('search') }}">
                    </div>
                </div>

                {{-- Filter Dropdown --}}
                <div class="dropdown">
                    <button class="btn bg-white rounded-pill fw-bold text-dark px-3 py-2 shadow-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-filter me-1 text-secondary"></i>
                        @if(request('status') || request('category_id'))
                            <span class="text-success">Đã lọc</span>
                        @else
                            Lọc
                        @endif
                    </button>
                    <div class="dropdown-menu shadow-lg border-0 mt-2 p-3" style="width: 280px;">
                        <h6 class="dropdown-header px-0 text-uppercase fw-bold mb-2 small text-muted">Bộ lọc tìm kiếm</h6>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Trạng thái</label>
                            <select name="status" class="form-select form-select-sm">
                                <option value="">-- Tất cả --</option>
                                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Bản nháp</option>
                                <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Đã xuất bản</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Chuyên mục</label>
                            <select name="category_id" class="form-select form-select-sm">
                                <option value="">-- Tất cả --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn btn-success btn-sm w-100 fw-bold">Áp dụng</button>
                        @if(request('status') || request('category_id') || request('search'))
                            <a href="{{ route('admin.posts.index') }}" class="btn btn-link btn-sm w-100 mt-1 text-decoration-none text-muted">Xóa bộ lọc</a>
                        @endif
                    </div>
                </div>

                {{-- Add Button --}}
                <a href="{{ route('admin.posts.create') }}" class="text-white fw-bold px-2 text-decoration-none d-flex align-items-center">
                    <i class="fas fa-plus me-1"></i> Thiết kế mới
                </a>
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
                        <x-admin.table-header key="title" label="Tiêu đề & Đường dẫn" :sortColumn="$sortColumn" :sortOrder="$sortOrder" />
                        <th style="width: 150px;">Chuyên mục</th>
                        <x-admin.table-header key="blocks_count" label="Số khối" :sortColumn="$sortColumn" :sortOrder="$sortOrder" class="text-center" />
                        <x-admin.table-header key="status" label="Trạng thái" :sortColumn="$sortColumn" :sortOrder="$sortOrder" class="text-center" />
                        <x-admin.table-header key="created_at" label="Ngày tạo" :sortColumn="$sortColumn" :sortOrder="$sortOrder" />
                        <th class="text-end pe-4" style="width: 180px;">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($posts as $post)
                    <tr>
                        <td class="ps-4 text-muted small">{{ $loop->iteration + ($posts->currentPage() - 1) * $posts->perPage() }}</td>
                        <td>
                            <div class="fw-bold text-dark">{{ $post->title }}</div>
                            <code class="small text-muted">{{ $post->slug }}</code>
                        </td>
                        <td>
                            @if($post->category)
                                <span class="badge bg-light text-dark border">{{ $post->category->name }}</span>
                            @else
                                <span class="text-muted small">Chưa phân loại</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="badge bg-info bg-opacity-10 text-info rounded-pill px-3">{{ $post->blocks_count }} blocks</span>
                        </td>
                        <td class="text-center">
                            @if($post->status === 'published')
                                <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">Đã xuất bản</span>
                            @else
                                <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 rounded-pill">Bản nháp</span>
                            @endif
                        </td>
                        <td>
                            <div class="small text-muted">{{ $post->created_at->format('d/m/Y H:i') }}</div>
                        </td>
                        <td class="text-end pe-4">
                            <form action="{{ route('admin.posts.duplicate', $post->id) }}" method="POST" class="d-inline-block">
                                @csrf
                                <button type="submit"
                                        class="btn btn-sm btn-outline-success rounded-circle d-inline-flex align-items-center justify-content-center me-1"
                                        style="width:32px;height:32px;" title="Nhân bản">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </form>
                            <a href="{{ route('admin.posts.edit', $post->id) }}"
                               class="btn btn-sm btn-outline-primary rounded-circle d-inline-flex align-items-center justify-content-center"
                               style="width:32px;height:32px;" title="Thiết kế nội dung">
                                <i class="fas fa-magic"></i>
                            </a>
                            <form action="{{ route('admin.posts.destroy', $post->id) }}"
                                  method="POST" class="d-inline-block" id="delete-form-{{ $post->id }}">
                                @csrf @method('DELETE')
                                <button type="button"
                                        class="btn btn-sm btn-outline-danger rounded-circle d-inline-flex align-items-center justify-content-center"
                                        style="width:32px;height:32px;" title="Xóa"
                                        onclick="confirmDelete({{ $post->id }}, '{{ addslashes($post->title) }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">Không tìm thấy dữ liệu phù hợp.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-3 border-top">
            {{ $posts->links() }}
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function confirmDelete(id, name) {
    Swal.fire({
        title: 'Bạn có chắc chắn?',
        html: `Xóa bài viết/trang <strong>${name}</strong>? Hành động này không thể hoàn tác!`,
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
