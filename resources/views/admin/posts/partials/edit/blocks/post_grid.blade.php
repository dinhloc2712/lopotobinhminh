<template x-if="block.type === 'post_grid'">
    <div class="mt-2">
        @include('admin.posts.partials.edit.blocks.shared_styles')

        <div class="row align-items-center mb-3">
            <div class="col-md-6 mb-2">
                <label class="form-label small fw-bold text-muted mb-1">Kiểu hiển thị</label>
                <select x-model="block.content.display_type" class="form-select border-0 bg-light">
                    <option value="">Mặc định (Bài viết)</option>
                    <option value="course">Khóa học</option>
                    <option value="job">Tuyển dụng</option>
                </select>
                <div class="small text-muted mt-1">Chọn kiểu giao diện cho các thẻ bài viết.</div>
            </div>

            <div class="col-md-6 mb-2">
                <label class="form-label small fw-bold text-muted mb-1">Cột / Danh mục hiển thị</label>
                <select x-model="block.content.category_id" class="form-select border-0 bg-light">
                    <option value="">-- Tất cả danh mục --</option>
                    @foreach(\App\Models\PostCategory::all() as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
                <div class="small text-muted mt-1">Lọc bài viết theo danh mục. Bỏ trống để hiển thị tất cả bài viết.</div>
            </div>

            <div class="col-md-6 mb-2">
                <label class="form-label small fw-bold text-muted mb-1">Số bài hiển thị mỗi trang</label>
                <input type="number" x-model="block.content.items_per_page" class="form-control border-0 bg-light" placeholder="Mặc định: 9" min="1">
                <div class="small text-muted mt-1">Để trống hoặc 0 để không phân trang (hiện toàn bộ).</div>
            </div>

            <div class="col-md-6 mb-2">
                <label class="form-label small fw-bold text-muted mb-1">Tổng số bài tối đa</label>
                <input type="number" x-model="block.content.items_limit" class="form-control border-0 bg-light" placeholder="Ví dụ: 20">
                <div class="small text-muted mt-1">Giới hạn tổng số bài được lấy ra. Để trống để lấy tất cả.</div>
            </div>

            <div class="col-md-12 mb-2">
                <div class="form-check form-switch mt-2">
                    <input class="form-check-input" type="checkbox" x-model="block.content.ajax_pagination" id="ajax_switch_{{ $block->id ?? rand(0,999) }}">
                    <label class="form-check-label small fw-bold text-muted" for="ajax_switch_{{ $block->id ?? rand(0,999) }}">Sử dụng phân trang AJAX (Không load lại trang)</label>
                </div>
            </div>
            
            <div class="col-md-6 mb-2 mt-2">
                <label class="form-label small fw-bold text-muted mb-1">Màu chữ cho Tiêu đề bài viết</label>
                <div class="d-flex align-items-center gap-2">
                    <input type="color" x-model="block.content.text_color" class="form-control form-control-color border-0 bg-light p-1" style="width: 50px;">
                    <input type="text" x-model="block.content.text_color" class="form-control border-0 bg-light w-50" placeholder="#004a80">
                </div>
            </div>
        </div>

        <div class="alert alert-info border-0 bg-info bg-opacity-10 mt-3">
            Khối này sẽ tự động tải các bài viết mới nhất (đã xuất bản) và tạo thanh phân trang (Pagination) bên dưới. Giao diện bài viết sẽ được hiển thị dạng thẻ Grid.
        </div>
    </div>
</template>
