<template x-if="block.type === 'text'">
    <div class="mt-2">
        @include('admin.posts.partials.edit.blocks.shared_styles')
        <input type="text" x-model="block.content.heading"
            class="form-control fw-bold border-0 bg-light mb-2"
            placeholder="Tiêu đề (Không bắt buộc)">
            
        <div class="row align-items-center mb-2 ms-1">
            <div class="col-auto">
                <div class="form-check form-switch mt-1">
                    <input class="form-check-input" type="checkbox" x-model="block.content.show_toc" :id="'text-toc-' + index">
                    <label class="form-check-label small text-muted fw-bold" :for="'text-toc-' + index">Hiển thị Mục lục (TOC)</label>
                </div>
            </div>
            <div class="col-auto" x-show="block.content.show_toc" style="display: none;">
                <select x-model="block.content.toc_position" class="form-select form-select-sm border-0 bg-light">
                    <option value="top">Vị trí: Bên trên nội dung</option>
                    <option value="left">Vị trí: Cột bên Trái</option>
                    <option value="right">Vị trí: Cột bên Phải</option>
                </select>
            </div>
        </div>

        <div class="row align-items-center mb-2 ms-1">
            <div class="col-auto">
                <div class="form-check form-switch mt-1">
                    <input class="form-check-input" type="checkbox" x-model="block.content.is_article_layout" :id="'text-article-' + index">
                    <label class="form-check-label small text-muted fw-bold" :for="'text-article-' + index">Giao diện bài viết chi tiết (Auto Hình ảnh, Tiêu đề H1 & Sidebar)</label>
                </div>
            </div>
            <div class="col-auto" x-show="block.content.is_article_layout" style="display: none;">
                <select x-model="block.content.related_category_id" class="form-select form-select-sm border-0 bg-light">
                    <option value="">-- Danh mục bài viết liên quan (Tất cả) --</option>
                    @foreach(\App\Models\PostCategory::all() as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <textarea :id="'editor-' + index" x-model="block.content.body" class="form-control border-0 bg-light" rows="4"
            placeholder="Nhập nội dung văn bản..."></textarea>
    </div>
</template>
