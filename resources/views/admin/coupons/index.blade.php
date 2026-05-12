@extends('layouts.admin')

@section('title', 'Quản lý mã giảm giá')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1 text-gray-800 fw-bold">Mã giảm giá</h1>
        <p class="mb-0 text-muted small">Tạo và quản lý các chương trình khuyến mãi của bạn</p>
    </div>
</div>

<div class="tech-card h-100">
    <div class="tech-header" style="background: linear-gradient(135deg, #4e73df 0%, #224abe 100%); padding: 20px 25px;">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <h6 class="mb-0 fw-bold text-white d-flex align-items-center">
                <i class="fas fa-ticket-alt me-2 bg-white bg-opacity-25 rounded-circle p-2" style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;"></i>
                Danh sách Mã giảm giá
            </h6>

            <form method="GET" action="{{ route('admin.coupons.index') }}" class="d-flex align-items-center flex-wrap gap-2 text-white">
                <div class="d-flex align-items-center bg-white rounded-pill px-3 py-2 shadow-sm">
                    <small class="text-muted fw-bold me-2 text-uppercase" style="font-size: 0.65rem;">Hiển thị</small>
                    <select name="per_page" class="form-select form-select-sm border-0 bg-transparent fw-bold text-dark py-0 pe-4"
                            style="width: auto; box-shadow: none; cursor: pointer;" onchange="this.form.submit()">
                        <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                    </select>
                </div>

                <div class="bg-white rounded-pill shadow-sm" style="flex: 1; min-width: 200px; max-width: 300px;">
                    <div class="position-relative">
                        <i class="fas fa-search position-absolute top-50 start-0 translate-middle-y text-muted ms-3" style="z-index: 5;"></i>
                        <input type="text" name="search" class="form-control form-select-sm border-0 bg-transparent rounded-pill ps-5 pe-3 py-2"
                               placeholder="Tìm theo mã hoặc tên..." value="{{ request('search') }}">
                    </div>
                </div>

                <div class="dropdown">
                    <button class="btn bg-white rounded-pill fw-bold text-dark px-3 py-2 shadow-sm" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-filter me-1 text-secondary"></i> Lọc
                    </button>
                    <div class="dropdown-menu shadow-lg border-0 mt-2 p-3" style="width: 280px;">
                        <h6 class="dropdown-header px-0 text-uppercase fw-bold mb-2 small text-muted">Bộ lọc nâng cao</h6>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Loại giảm giá</label>
                            <select name="type" class="form-select form-select-sm">
                                <option value="">Tất cả</option>
                                <option value="fixed" {{ request('type') == 'fixed' ? 'selected' : '' }}>Cố định (đ)</option>
                                <option value="percent" {{ request('type') == 'percent' ? 'selected' : '' }}>Phần trăm (%)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Trạng thái</label>
                            <select name="status" class="form-select form-select-sm">
                                <option value="">Tất cả</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Đang chạy</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tạm dừng</option>
                                <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Hết hạn</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm w-100 fw-bold">Áp dụng</button>
                    </div>
                </div>

                <a href="{{ route('admin.coupons.create') }}" class="text-white fw-bold px-2 text-decoration-none d-flex align-items-center ms-2">
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
                        <x-admin.table-header key="code" label="Mã CODE" :sortColumn="$sortColumn" :sortOrder="$sortOrder" />
                        <x-admin.table-header key="name" label="Tền chương trình" :sortColumn="$sortColumn" :sortOrder="$sortOrder" />
                        <th>Giá trị giảm</th>
                        <th>Sử dụng</th>
                        <th>Hiệu lực</th>
                        <x-admin.table-header key="status" label="Trạng thái" :sortColumn="$sortColumn" :sortOrder="$sortOrder" />
                        <th class="text-end pe-4" style="width: 120px;">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($coupons as $coupon)
                    <tr>
                        <td class="ps-4 text-muted small">{{ $loop->iteration + ($coupons->currentPage() - 1) * $coupons->perPage() }}</td>
                        <td>
                            <span class="badge bg-primary bg-opacity-10 text-primary fw-bold font-monospace px-2 py-1 border border-primary border-opacity-25" style="letter-spacing: 1px;">
                                {{ $coupon->code }}
                            </span>
                        </td>
                        <td><div class="fw-bold text-dark">{{ $coupon->name ?? '---' }}</div></td>
                        <td>
                            @if($coupon->type == 'fixed')
                                <div class="fw-bold text-success">{{ number_format($coupon->discount_amount) }}đ</div>
                            @else
                                <div class="fw-bold text-info">{{ $coupon->discount_percentage }}%</div>
                                @if($coupon->max_discount_amount > 0)
                                    <div class="small text-muted">(Max {{ number_format($coupon->max_discount_amount) }}đ)</div>
                                @endif
                            @endif
                        </td>
                        <td>
                            <div class="small fw-bold">{{ $coupon->used }} / {{ $coupon->quantity > 0 ? $coupon->quantity : '∞' }}</div>
                            <div class="progress mt-1" style="height: 4px; width: 60px;">
                                <div class="progress-bar bg-{{ $coupon->quantity > 0 && ($coupon->used / $coupon->quantity) > 0.8 ? 'danger' : 'success' }}" 
                                     role="progressbar" 
                                     style="width: {{ $coupon->quantity > 0 ? ($coupon->used / $coupon->quantity) * 100 : 0 }}%"></div>
                            </div>
                        </td>
                        <td>
                            <div class="small text-muted"><i class="far fa-calendar-alt me-1"></i> {{ $coupon->start_date ? $coupon->start_date->format('d/m/Y') : '---' }}</div>
                            <div class="small text-muted"><i class="far fa-calendar-times me-1"></i> {{ $coupon->expiry_date ? $coupon->expiry_date->format('d/m/Y') : '---' }}</div>
                        </td>
                        <td>
                            @php
                                $statusMap = [
                                    'active' => ['class' => 'success', 'label' => 'Đang chạy'],
                                    'inactive' => ['class' => 'secondary', 'label' => 'Tạm dừng'],
                                    'expired' => ['class' => 'danger', 'label' => 'Hết hạn']
                                ];
                                $st = $statusMap[$coupon->status] ?? ['class' => 'light', 'label' => 'Không rõ'];
                                
                                if ($coupon->status == 'active' && $coupon->expiry_date && $coupon->expiry_date->isPast()) {
                                    $st = ['class' => 'danger', 'label' => 'Hết hạn'];
                                }
                            @endphp
                            <span class="badge bg-{{ $st['class'] }} bg-opacity-10 text-{{ $st['class'] }} rounded-pill px-3 py-2">
                                <i class="fas fa-circle me-1" style="font-size: 8px;"></i> {{ $st['label'] }}
                            </span>
                        </td>
                        <td class="text-end pe-4">
                            <a href="{{ route('admin.coupons.edit', $coupon->id) }}" class="btn btn-sm btn-outline-info rounded-circle d-inline-flex align-items-center justify-content-center animate-hover" style="width:32px;height:32px;" title="Sửa">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST" class="d-inline-block" id="delete-form-{{ $coupon->id }}">
                                @csrf @method('DELETE')
                                <button type="button" class="btn btn-sm btn-outline-danger rounded-circle d-inline-flex align-items-center justify-content-center animate-hover" style="width:32px;height:32px;" title="Xóa" onclick="confirmDelete({{ $coupon->id }}, '{{ $coupon->code }}')">
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
                                    <i class="fas fa-ticket-alt fa-3x text-secondary opacity-50"></i>
                                </div>
                                <h6 class="text-muted fw-bold">Không tìm thấy mã giảm giá nào</h6>
                                <p class="text-muted small mb-0">Thử thay đổi bộ lọc hoặc thêm mã mới.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-3 border-top">
            {{ $coupons->links() }}
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
        html: `Xóa mã giảm giá <strong>${name}</strong>? Hành động này không thể hoàn tác!`,
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
