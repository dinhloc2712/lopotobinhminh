@extends('layouts.admin')

@section('title', 'Chỉnh sửa mã giảm giá')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1 text-gray-800 fw-bold">Chỉnh sửa mã giảm giá</h1>
        <p class="text-muted small mb-0">Cập nhật thông tin mã: <span class="fw-bold text-primary">{{ $coupon->code }}</span></p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.coupons.index') }}" class="btn btn-tech-outline">
            <i class="fas fa-arrow-left me-2"></i>Quay lại
        </a>
    </div>
</div>

<form action="{{ route('admin.coupons.update', $coupon->id) }}" method="POST" x-data="{ couponType: '{{ $coupon->type }}' }">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-lg-8">
            {{-- Thông tin cơ bản --}}
            <div class="tech-card mb-4">
                <div class="tech-header">
                    <i class="fas fa-info-circle me-2"></i> Thông tin cơ bản
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-uppercase text-muted">Mã CODE <span class="text-danger">*</span></label>
                            <input type="text" name="code" class="form-control fw-bold" placeholder="VD: NHAPMOI2026" required value="{{ old('code', $coupon->code) }}" style="letter-spacing: 1px;">
                            @error('code') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-uppercase text-muted">Tên chương trình</label>
                            <input type="text" name="name" class="form-control" placeholder="VD: Khuyến mãi khai trương" value="{{ old('name', $coupon->name) }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold small text-uppercase text-muted">Mô tả</label>
                            <textarea name="description" class="form-control" rows="2" placeholder="Nhập mô tả ngắn gọn về ưu đãi này...">{{ old('description', $coupon->description) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Cấu hình giảm giá --}}
            <div class="tech-card mb-4">
                <div class="tech-header">
                    <i class="fas fa-percentage me-2"></i> Mức giảm & Điều kiện đơn hàng
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-uppercase text-muted">Loại giảm giá</label>
                            <select name="type" class="form-select" x-model="couponType">
                                <option value="fixed">Giảm số tiền cố định (đ)</option>
                                <option value="percent">Giảm theo phần trăm (%)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <div x-show="couponType === 'fixed'">
                                <label class="form-label fw-bold small text-uppercase text-muted">Số tiền giảm (đ) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" name="discount_amount" class="form-control fw-bold" placeholder="0" value="{{ old('discount_amount', $coupon->discount_amount) }}">
                                    <span class="input-group-text">₫</span>
                                </div>
                            </div>
                            <div x-show="couponType === 'percent'" x-cloak>
                                <label class="form-label fw-bold small text-uppercase text-muted">Phần trăm giảm (%) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" name="discount_percentage" class="form-control fw-bold" placeholder="0" min="0" max="100" value="{{ old('discount_percentage', $coupon->discount_percentage) }}">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-uppercase text-muted">Giá trị đơn hàng tối thiểu</label>
                            <div class="input-group">
                                <input type="number" name="min_order_value" class="form-control" placeholder="0" value="{{ old('min_order_value', $coupon->min_order_value) }}">
                                <span class="input-group-text">₫</span>
                            </div>
                        </div>

                        <div class="col-md-6" x-show="couponType === 'percent'" x-cloak>
                            <label class="form-label fw-bold small text-uppercase text-muted">Số tiền giảm tối đa</label>
                            <div class="input-group">
                                <input type="number" name="max_discount_amount" class="form-control" placeholder="0" value="{{ old('max_discount_amount', $coupon->max_discount_amount) }}">
                                <span class="input-group-text">₫</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            {{-- Giới hạn & Thời gian --}}
            <div class="tech-card mb-4">
                <div class="tech-header">
                    <i class="fas fa-clock me-2"></i> Giới hạn & Thời gian
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-uppercase text-muted">Tổng số lượt phát hành</label>
                        <input type="number" name="quantity" class="form-control" placeholder="0" required value="{{ old('quantity', $coupon->quantity) }}">
                    </div>

                    <div class="mb-3 text-center border rounded p-2 bg-light mb-4">
                        <div class="small text-muted text-uppercase fw-bold" style="font-size: 0.6rem;">Đã sử dụng</div>
                        <div class="h4 mb-0 fw-bold text-dark">{{ $coupon->used }}</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-uppercase text-muted">Mỗi khách được dùng (lần)</label>
                        <input type="number" name="user_usage_limit" class="form-control" placeholder="1" required value="{{ old('user_usage_limit', $coupon->user_usage_limit) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-uppercase text-muted">Ngày bắt đầu</label>
                        <input type="datetime-local" name="start_date" class="form-control" value="{{ old('start_date', $coupon->start_date ? $coupon->start_date->format('Y-m-d\TH:i') : '') }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-uppercase text-muted">Ngày hết hạn</label>
                        <input type="datetime-local" name="expiry_date" class="form-control" value="{{ old('expiry_date', $coupon->expiry_date ? $coupon->expiry_date->format('Y-m-d\TH:i') : '') }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-uppercase text-muted">Độ ưu tiên</label>
                        <input type="number" name="priority" class="form-control" value="{{ old('priority', $coupon->priority) }}">
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-uppercase text-muted">Trạng thái</label>
                        <select name="status" class="form-select">
                            <option value="active" {{ old('status', $coupon->status) == 'active' ? 'selected' : '' }}>Công khai (Kích hoạt)</option>
                            <option value="inactive" {{ old('status', $coupon->status) == 'inactive' ? 'selected' : '' }}>Nháp (Tạm dừng)</option>
                            <option value="expired" {{ old('status', $coupon->status) == 'expired' ? 'selected' : '' }}>Hết hạn (Thủ công)</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-3 fw-bold shadow-sm rounded-pill">
                        <i class="fas fa-save me-1"></i> Cập nhật thay đổi
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<style>
    [x-cloak] { display: none !important; }
</style>
@endsection

@section('scripts')
<script>
    // Scripts specialized for coupon editing
</script>
@endsection
