<template x-if="block.type === 'text'">
    <div class="mt-2">
        {{-- removed shared_styles --}}
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

        {{-- Lựa chọn hiển thị trong khung --}}
        <div class="row align-items-center mb-3 ms-1">
            <div class="col-auto">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" x-model="block.content.has_card" :id="'text-card-' + index">
                    <label class="form-check-label small text-muted fw-bold" :for="'text-card-' + index">Hiển thị trong Khung (Card)</label>
                </div>
            </div>
            <div class="col-auto">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" x-model="block.content.full_width" :id="'text-full-' + index">
                    <label class="form-check-label small text-muted fw-bold" :for="'text-full-' + index">Toàn màn hình (Full Width)</label>
                </div>
            </div>
        </div>

        {{-- Thumbnail cho bài viết --}}
        <div class="mx-1 mb-3 p-3 bg-white rounded-4 border shadow-sm">
            <label class="small text-muted mb-2 d-block fw-bold text-uppercase" style="font-size: 0.65rem;">
                <i class="fas fa-image me-1 text-primary"></i> Ảnh đại diện bài viết (Thumbnail)
            </label>
            <div class="d-flex gap-3 align-items-center">
                <div class="flex-shrink-0" style="width: 120px; height: 80px;">
                    <div class="w-100 h-100 rounded-3 bg-light d-flex align-items-center justify-content-center overflow-hidden position-relative border"
                        style="cursor: pointer;"
                        @click="openMediaPicker(index, 'post_thumbnail')" title="Nhấn để chọn ảnh">
                        <template x-if="block.content.post_thumbnail">
                            <img :src="block.content.post_thumbnail" class="w-100 h-100" style="object-fit: cover;">
                        </template>
                        <template x-if="!block.content.post_thumbnail">
                            <div class="text-center text-muted">
                                <i class="fas fa-plus fa-lg"></i>
                            </div>
                        </template>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <input type="text" x-model="block.content.post_thumbnail" 
                        class="form-control form-control-sm border-0 bg-light mb-2" 
                        placeholder="Đường dẫn ảnh hoặc chọn từ thư viện...">
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-sm btn-outline-primary" @click="openMediaPicker(index, 'post_thumbnail')">
                            Chọn ảnh
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger" x-show="block.content.post_thumbnail" @click="block.content.post_thumbnail = ''">
                            Xóa
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Cổng cấu hình Sidebar --}}
        <div x-transition class="bg-white p-3 rounded-4 border mb-3 shadow-sm mx-1">
            <h6 class="fw-bold text-dark small text-uppercase mb-3">
                <i class="fas fa-list-ul me-1 text-primary"></i> Cấu hình side bar
            </h6>
            
            <div class="d-flex flex-column gap-3">
                {{-- Bài viết mới --}}
                <div class="p-2 border rounded-3 bg-light">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="form-check form-switch h-100 d-flex align-items-center mb-0">
                                <input class="form-check-input me-2" type="checkbox" x-model="block.content.show_new_posts" :id="'show-new-posts-' + index" style="cursor: pointer;">
                                <label class="form-check-label small text-dark fw-bold mb-0" :for="'show-new-posts-' + index" style="font-size: 0.75rem; cursor: pointer;">Hiện Bài viết mới</label>
                            </div>
                        </div>
                        <div class="col-md-6" x-show="block.content.show_new_posts" x-transition>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text border-0 bg-transparent text-muted px-0 me-2" style="font-size: 0.65rem;">
                                    <i class="fas fa-list-ol me-1"></i> Số lượng:
                                </span>
                                <input type="number" x-model="block.content.new_posts_limit" x-init="if(!block.content.new_posts_limit) block.content.new_posts_limit = 5"
                                    class="form-control form-control-sm border rounded-pill px-3 bg-white text-center" style="max-width: 80px;" placeholder="5">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Sản phẩm mới --}}
                <div class="p-2 border rounded-3 bg-light">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="form-check form-switch h-100 d-flex align-items-center mb-0">
                                <input class="form-check-input me-2" type="checkbox" x-model="block.content.show_new_products" :id="'show-new-products-' + index" style="cursor: pointer;">
                                <label class="form-check-label small text-dark fw-bold mb-0" :for="'show-new-products-' + index" style="font-size: 0.75rem; cursor: pointer;">Hiện Sản phẩm mới</label>
                            </div>
                        </div>
                        <div class="col-md-6" x-show="block.content.show_new_products" x-transition>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text border-0 bg-transparent text-muted px-0 me-2" style="font-size: 0.65rem;">
                                    <i class="fas fa-box me-1"></i> Số lượng:
                                </span>
                                <input type="number" x-model="block.content.new_products_limit" x-init="if(!block.content.new_products_limit) block.content.new_products_limit = 5"
                                    class="form-control form-control-sm border rounded-pill px-3 bg-white text-center" style="max-width: 80px;" placeholder="5">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <textarea :id="'editor-' + index" x-model="block.content.body" class="form-control border-0 bg-light" rows="4"
            placeholder="Nhập nội dung văn bản..."></textarea>
    </div>
</template>
