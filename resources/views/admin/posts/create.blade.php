@extends('layouts.admin')

@section('title', 'Tạo bài viết mới')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800 fw-bold">Tạo mới Landing Page / Bài viết</h1>
            <p class="text-muted small mb-0">Sau khi tạo, bạn sẽ có thể thiết kế nội dung chi tiết bằng công cụ builder.</p>
        </div>
        <a href="{{ route('admin.posts.index') }}" class="btn btn-tech-outline">
            <i class="fas fa-arrow-left me-2"></i>Quay lại
        </a>
    </div>

    <form action="{{ route('admin.posts.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-lg-8">
                <div class="tech-card mb-4">
                    <div class="tech-header">
                        <i class="fas fa-file-invoice me-2"></i>
                        Thông tin chung
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-uppercase text-muted">Tiêu đề bài viết <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" class="form-control form-control-lg"
                                placeholder="Nhập tiêu đề..." required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-uppercase text-muted">Slug (Đường dẫn) <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="slug" id="slug" class="form-control"
                                placeholder="ví dụ: giới-thiệu-vinayuuki" required>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-bold small text-uppercase text-muted">Tóm tắt ngắn (SEO)</label>
                            <textarea name="summary" class="form-control" rows="4" placeholder="Nhập tóm tắt thu hút người xem..."></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="tech-card">
                    <div class="tech-header">
                        <i class="fas fa-file-invoice me-2"></i>
                        Cấu hình bài viết
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <label class="form-label fw-bold small text-uppercase text-muted">Chuyên mục</label>
                            <select name="category_id" class="form-select">
                                <option value="">-- Không có --</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold small text-uppercase text-muted">Trạng thái ban đầu</label>
                            <select name="status" class="form-select">
                                <option value="draft" selected>Bản nháp</option>
                                <option value="published">Công khai</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 py-3 fw-bold shadow-sm rounded-pill">
                            <i class="fas fa-magic me-1"></i> Tiếp tục thiết kế
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('scripts')
    <script>
        document.getElementById('title').addEventListener('input', function() {
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
        });
    </script>
@endsection
