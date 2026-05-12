@extends('layouts.admin')

@section('title', 'Tạo mã giảm giá mới')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1 text-gray-800 fw-bold">Tạo mã giảm giá mới</h1>
        <p class="text-muted small mb-0">Thiết lập các thông số và điều kiện khuyến mãi áp dụng cho toàn shop.</p>
    </div>
    <a href="{{ route('admin.coupons.index') }}" class="btn btn-tech-outline">
        <i class="fas fa-arrow-left me-2"></i>Quay lại
    </a>
</div>

<form action="{{ route('admin.coupons.store') }}" method="POST" x-data="{ couponType: 'fixed' }">
    @csrf
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
                            <input type="text" name="code" class="form-control fw-bold" placeholder="VD: NHAPMOI2026" required value="{{ old('code') }}" style="letter-spacing: 1px;">
                            @error('code') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-uppercase text-muted">Tên chương trình</label>
                            <input type="text" name="name" class="form-control" placeholder="VD: Khuyến mãi khai trương" value="{{ old('name') }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold small text-uppercase text-muted">Mô tả</label>
                            <textarea name="description" class="form-control" rows="2" placeholder="Nhập mô tả ngắn gọn về ưu đãi này...">{{ old('description') }}</textarea>
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
                                    <input type="number" name="discount_amount" class="form-control fw-bold" placeholder="0" value="{{ old('discount_amount', 0) }}">
                                    <span class="input-group-text">₫</span>
                                </div>
                            </div>
                            <div x-show="couponType === 'percent'" x-cloak>
                                <label class="form-label fw-bold small text-uppercase text-muted">Phần trăm giảm (%) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" name="discount_percentage" class="form-control fw-bold" placeholder="0" min="0" max="100" value="{{ old('discount_percentage', 0) }}">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-uppercase text-muted">Giá trị đơn hàng tối thiểu</label>
                            <div class="input-group">
                                <input type="number" name="min_order_value" class="form-control" placeholder="0" value="{{ old('min_order_value', 0) }}">
                                <span class="input-group-text">₫</span>
                            </div>
                            <small class="text-muted italic">Đơn hàng đạt mức này mới được dùng mã.</small>
                        </div>

                        <div class="col-md-6" x-show="couponType === 'percent'" x-cloak>
                            <label class="form-label fw-bold small text-uppercase text-muted">Số tiền giảm tối đa</label>
                            <div class="input-group">
                                <input type="number" name="max_discount_amount" class="form-control" placeholder="0" value="{{ old('max_discount_amount', 0) }}">
                                <span class="input-group-text">₫</span>
                            </div>
                            <small class="text-muted italic">Để bằng 0 nếu không giới hạn.</small>
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
                        <input type="number" name="quantity" class="form-control" placeholder="0" required value="{{ old('quantity', 0) }}">
                        <small class="text-muted italic">Mã sẽ tự động khóa khi hết lượt. 0 = Không giới hạn.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-uppercase text-muted">Mỗi khách được dùng (lần)</label>
                        <input type="number" name="user_usage_limit" class="form-control" placeholder="1" required value="{{ old('user_usage_limit', 1) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-uppercase text-muted">Ngày bắt đầu</label>
                        <input type="datetime-local" name="start_date" class="form-control" value="{{ old('start_date') }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-uppercase text-muted">Ngày hết hạn</label>
                        <input type="datetime-local" name="expiry_date" class="form-control" value="{{ old('expiry_date') }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-uppercase text-muted">Độ ưu tiên</label>
                        <input type="number" name="priority" class="form-control" value="{{ old('priority', 0) }}">
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-uppercase text-muted">Trạng thái</label>
                        <select name="status" class="form-select">
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Công khai (Kích hoạt)</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Nháp (Tạm dừng)</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-3 fw-bold shadow-sm rounded-pill">
                        <i class="fas fa-save me-1"></i> Lưu mã giảm giá
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
