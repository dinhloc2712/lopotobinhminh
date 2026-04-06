@extends('layouts.admin')

@section('title', 'Thêm chi nhánh mới')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800 fw-bold">Thêm chi nhánh mới</h1>
            <p class="mb-0 text-muted small">Tạo chi nhánh mới trong hệ thống</p>
        </div>
        <a href="{{ route('admin.branches.index') }}" class="btn btn-outline-secondary rounded-pill px-3">
            <i class="fas fa-arrow-left me-1"></i> Quay lại
        </a>
    </div>

    <form action="{{ route('admin.branches.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-lg-8">
                <div class="tech-card mb-4">
                    <div class="tech-header"
                        style="background: linear-gradient(135deg, #4e73df 0%, #224abe 100%); padding: 16px 20px;">
                        <h6 class="mb-0 fw-bold text-white"><i class="fas fa-building me-2"></i> Thông tin chi nhánh</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label fw-bold small text-uppercase text-muted">Tên chi nhánh <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="name"
                                    class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}"
                                    required placeholder="Ví dụ: Chi nhánh Hà Nội">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold small text-uppercase text-muted">Mã chi nhánh</label>
                                <input type="text" name="code"
                                    class="form-control @error('code') is-invalid @enderror" value="{{ old('code') }}"
                                    placeholder="Ví dụ: HN01">
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold small text-uppercase text-muted">Địa chỉ</label>
                                <input type="text" name="address" class="form-control" value="{{ old('address') }}"
                                    placeholder="Số nhà, đường, quận, thành phố...">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-uppercase text-muted">Số điện thoại</label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone') }}"
                                    placeholder="0xxx xxx xxx">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-uppercase text-muted">Email</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email') }}"
                                    placeholder="chinhanh@example.com">
                            </div>
                            <div class="col-md-8">
                                <label class="form-label fw-bold small text-uppercase text-muted">Người quản lý</label>
                                <input type="text" name="manager_name" class="form-control"
                                    value="{{ old('manager_name') }}" placeholder="Họ và tên người quản lý">
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                                        value="1" {{ old('is_active', '1') ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold" for="is_active">Đang hoạt động</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold small text-uppercase text-muted">Ghi chú</label>
                                <textarea name="notes" class="form-control" rows="3" placeholder="Ghi chú thêm...">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="tech-card mb-4">
                    <div class="card-body p-4">
                        <button type="submit" class="btn btn-success fw-bold w-100 mb-2">
                            <i class="fas fa-save me-1"></i> Lưu chi nhánh
                        </button>
                        <a href="{{ route('admin.branches.index') }}" class="btn btn-light w-100">Hủy bỏ</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
