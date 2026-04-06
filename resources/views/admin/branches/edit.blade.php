@extends('layouts.admin')

@section('title', 'Chỉnh sửa chi nhánh')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1 text-gray-800 fw-bold">Chỉnh sửa chi nhánh</h1>
        <p class="mb-0 text-muted small">{{ $branch->name }}</p>
    </div>
    <a href="{{ route('admin.branches.index') }}" class="btn btn-outline-secondary rounded-pill px-3">
        <i class="fas fa-arrow-left me-1"></i> Quay lại
    </a>
</div>

<form action="{{ route('admin.branches.update', $branch) }}" method="POST">
    @csrf @method('PUT')
    <div class="row">
        <div class="col-lg-8">
            <div class="tech-card mb-4">
                <div class="tech-header" style="background: linear-gradient(135deg, #4e73df 0%, #224abe 100%); padding: 16px 20px;">
                    <h6 class="mb-0 fw-bold text-white"><i class="fas fa-building me-2"></i> Thông tin chi nhánh</h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label fw-bold small text-uppercase text-muted">Tên chi nhánh <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $branch->name) }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-uppercase text-muted">Mã chi nhánh</label>
                            <input type="text" name="code" class="form-control @error('code') is-invalid @enderror"
                                   value="{{ old('code', $branch->code) }}">
                            @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold small text-uppercase text-muted">Địa chỉ</label>
                            <input type="text" name="address" class="form-control" value="{{ old('address', $branch->address) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-uppercase text-muted">Số điện thoại</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone', $branch->phone) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-uppercase text-muted">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $branch->email) }}">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label fw-bold small text-uppercase text-muted">Người quản lý</label>
                            <input type="text" name="manager_name" class="form-control" value="{{ old('manager_name', $branch->manager_name) }}">
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $branch->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="is_active">Đang hoạt động</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold small text-uppercase text-muted">Ghi chú</label>
                            <textarea name="notes" class="form-control" rows="3">{{ old('notes', $branch->notes) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="tech-card mb-4">
                <div class="card-body p-4">
                    <button type="submit" class="btn btn-primary fw-bold w-100 mb-2">
                        <i class="fas fa-save me-1"></i> Cập nhật chi nhánh
                    </button>
                    <a href="{{ route('admin.branches.index') }}" class="btn btn-light w-100">Hủy bỏ</a>
                </div>
            </div>
            {{-- Departments in this branch --}}
            @if($branch->departments_count ?? $branch->departments->count())
            <div class="tech-card">
                <div class="card-body p-3">
                    <p class="fw-bold small text-muted mb-2"><i class="fas fa-sitemap me-1"></i> Phòng ban thuộc chi nhánh</p>
                    @foreach($branch->departments as $dept)
                    <div class="d-flex align-items-center mb-1">
                        <i class="fas fa-circle fa-xs text-primary me-2"></i>
                        <span class="small">{{ $dept->name }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</form>
@endsection
