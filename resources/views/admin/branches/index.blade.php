@extends('layouts.admin')

@section('title', 'Quản lý Chi nhánh')

@section('content')
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800 fw-bold">Quản lý Chi nhánh</h1>
            <p class="mb-0 text-muted small">Danh sách các chi nhánh trong hệ thống</p>
        </div>
    </div>

    <div class="tech-card h-100">
        <div class="tech-header" style="background: linear-gradient(135deg, #4e73df 0%, #224abe 100%); padding: 20px 25px;">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <h6 class="mb-0 fw-bold text-white d-flex align-items-center">
                    <i class="fas fa-building me-2 bg-white bg-opacity-25 rounded-circle p-2"
                        style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;"></i>
                    Danh sách Chi nhánh
                </h6>

                <form method="GET" action="{{ route('admin.branches.index') }}"
                    class="d-flex align-items-center flex-wrap gap-2">
                    {{-- Per Page --}}
                    <div class="d-flex align-items-center bg-white rounded-pill px-3 py-2 shadow-sm">
                        <small class="text-muted fw-bold me-2 text-uppercase" style="font-size: 0.65rem;">Hiển thị</small>
                        <select name="per_page"
                            class="form-select form-select-sm border-0 bg-transparent fw-bold text-dark py-0 pe-4"
                            style="width: auto; box-shadow: none; cursor: pointer;" onchange="this.form.submit()">
                            <option value="15" {{ request('per_page', 15) == 15 ? 'selected' : '' }}>15</option>
                            <option value="30" {{ request('per_page') == 30 ? 'selected' : '' }}>30</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                        </select>
                    </div>

                    {{-- Search --}}
                    <div class="bg-white rounded-pill shadow-sm" style="flex: 1; min-width: 200px; max-width: 300px;">
                        <div class="position-relative">
                            <i class="fas fa-search position-absolute top-50 start-0 translate-middle-y text-muted ms-3"
                                style="z-index: 5;"></i>
                            <input type="text" name="search"
                                class="form-control form-select-sm border-0 bg-transparent rounded-pill ps-5 pe-3 py-2"
                                placeholder="Tìm tên, mã, địa chỉ..." value="{{ request('search') }}">
                        </div>
                    </div>

                    {{-- Filter Dropdown --}}
                    <div class="dropdown">
                        <button class="btn bg-white rounded-pill fw-bold text-dark px-3 py-2 shadow-sm" type="button"
                            data-bs-toggle="dropdown">
                            <i class="fas fa-filter me-1 text-secondary"></i>
                            @if (request('is_active') !== null && request('is_active') !== '')
                                <span class="text-primary">Đã lọc</span>
                            @else
                                Lọc
                            @endif
                        </button>
                        <div class="dropdown-menu shadow-lg border-0 mt-2 p-3" style="width: 280px;">
                            <h6 class="dropdown-header px-0 text-uppercase fw-bold mb-2 small text-muted">Bộ lọc tìm kiếm
                            </h6>

                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">Trạng thái</label>
                                <select name="is_active" class="form-select form-select-sm">
                                    <option value="">-- Tất cả --</option>
                                    <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Đang hoạt
                                        động</option>
                                    <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Tạm ngừng
                                    </option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary btn-sm w-100 fw-bold">Áp dụng</button>
                            @if (request('is_active') !== null && request('is_active') !== '')
                                <a href="{{ route('admin.branches.index') }}"
                                    class="btn btn-link btn-sm w-100 mt-1 text-decoration-none text-muted">Xóa bộ lọc</a>
                            @endif
                        </div>
                    </div>

                    {{-- Add Button --}}
                    @can('create_branch')
                        <a href="{{ route('admin.branches.create') }}"
                            class="text-white fw-bold px-2 text-decoration-none d-flex align-items-center">
                            <i class="fas fa-plus me-1"></i> Thêm mới
                        </a>
                    @endcan
                </form>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-modern mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">ID</th>
                            <x-admin.table-header key="name" label="Tên chi nhánh" :sortColumn="$sortColumn"
                                :sortOrder="$sortOrder" />
                            <x-admin.table-header key="code" label="Mã" :sortColumn="$sortColumn" :sortOrder="$sortOrder" />
                            <th>Địa chỉ</th>
                            <th>Liên hệ</th>
                            <th>Người quản lý</th>
                            <th class="text-center" style="width: 80px;">P.ban</th>
                            <x-admin.table-header key="is_active" label="Trạng thái" :sortColumn="$sortColumn" :sortOrder="$sortOrder" />
                            <x-admin.table-header key="created_at" label="Ngày tạo" :sortColumn="$sortColumn" :sortOrder="$sortOrder" />
                            <th class="text-end pe-4">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($branches as $branch)
                            <tr>
                                <td class="ps-4 text-muted">{{ $branch->id }}</td>
                                <td class="fw-bold text-dark">
                                    {{ $branch->name }}
                                    @if ($branch->notes)
                                        <div class="text-muted small text-truncate" style="max-width:200px;">
                                            {{ $branch->notes }}</div>
                                    @endif
                                </td>
                                <td><code class="small">{{ $branch->code ?? '-' }}</code></td>
                                <td class="text-muted small" style="max-width: 160px;">{{ $branch->address ?? '-' }}</td>
                                <td class="small">
                                    @if ($branch->phone)
                                        <div><i class="fas fa-phone fa-xs me-1 text-muted"></i>{{ $branch->phone }}</div>
                                    @endif
                                    @if ($branch->email)
                                        <div><i class="fas fa-envelope fa-xs me-1 text-muted"></i>{{ $branch->email }}
                                        </div>
                                    @endif
                                    @if (!$branch->phone && !$branch->email)
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="small">{{ $branch->manager_name ?? '-' }}</td>
                                <td class="text-center">
                                    <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-2 fw-bold">
                                        {{ $branch->departments_count }}
                                    </span>
                                </td>
                                <td>
                                    @if ($branch->is_active)
                                        <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill"
                                            style="font-weight: 600; font-size: 0.75rem;">Hoạt động</span>
                                    @else
                                        <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill"
                                            style="font-weight: 600; font-size: 0.75rem;">Tạm ngừng</span>
                                    @endif
                                </td>
                                <td class="text-muted small">{{ $branch->created_at->format('d/m/Y') }}</td>
                                <td class="text-end pe-4">
                                    @can('update_branch')
                                        <a href="{{ route('admin.branches.edit', $branch) }}"
                                            class="btn btn-sm btn-outline-info rounded-circle d-inline-flex align-items-center justify-content-center"
                                            style="width: 32px; height: 32px;" title="Sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endcan
                                    @can('delete_branch')
                                        <form action="{{ route('admin.branches.destroy', $branch) }}" method="POST"
                                            class="d-inline-block" id="delete-branch-{{ $branch->id }}">
                                            @csrf @method('DELETE')
                                            <button type="button"
                                                class="btn btn-sm btn-outline-danger rounded-circle d-inline-flex align-items-center justify-content-center"
                                                style="width: 32px; height: 32px;" title="Xóa"
                                                onclick="confirmDelete({{ $branch->id }}, 'branch')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-5">
                                    <div class="d-flex flex-column align-items-center">
                                        <div class="bg-light rounded-circle p-4 mb-3">
                                            <i class="fas fa-building fa-3x text-secondary"></i>
                                        </div>
                                        <h6 class="text-muted fw-bold">Không tìm thấy chi nhánh nào</h6>
                                        <p class="text-muted small mb-0">Thử thay đổi bộ lọc hoặc thêm chi nhánh mới.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-3 border-top">
                {{ $branches->links() }}
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function confirmDelete(id, type) {
            Swal.fire({
                title: 'Bạn có chắc chắn?',
                text: "Hành động này không thể hoàn tác!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Vâng, xóa nó!',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-' + type + '-' + id).submit();
                }
            });
        }
    </script>
@endsection
