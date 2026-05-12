<template x-if="block.type === 'text_grid'">
    <div class="mt-2 text-grid-admin" x-init="
        if (!block.content) block.content = {};
        
        // Initialize defaults contextually
        if (!block.content.title) block.content.title = 'TIN TỨC MỚI NHẤT';
        if (!block.content.view_all_text) block.content.view_all_text = 'XEM TẤT CẢ';
        if (!block.content.items_limit) block.content.items_limit = 5;
        if (!block.content.category_id) block.content.category_id = 2; // Default to 'Tin tức'
        
        // Typography Defaults
        if (!block.content.title_color) block.content.title_color = '#001d3d';
        if (!block.content.title_font_size) block.content.title_font_size = 20;

        if (!block.content.accent_color) block.content.accent_color = '#e31824';
        
        if (!block.content.post_title_color) block.content.post_title_color = '#0f172a';
        if (!block.content.post_title_hover_color) block.content.post_title_hover_color = block.content.accent_color;
        if (!block.content.post_title_font_size) block.content.post_title_font_size = 18;
        if (!block.content.post_title_small_font_size) block.content.post_title_small_font_size = 15;

        // Excerpt Defaults (Large Item Only)
        if (!block.content.excerpt_color) block.content.excerpt_color = '#64748b';
        if (!block.content.excerpt_font_size) block.content.excerpt_font_size = 14;
        if (!block.content.excerpt_line_clamp) block.content.excerpt_line_clamp = 3;
        if (!block.content.view_all_bg_color) block.content.view_all_bg_color = '#ffffff';
        if (!block.content.view_all_text_color) block.content.view_all_text_color = block.content.accent_color;
        if (!block.content.view_all_font_size) block.content.view_all_font_size = 11;
        if (!block.content.view_all_border_radius) block.content.view_all_border_radius = 50;

        // Frame & Display Defaults
        if (!block.content.container_max_width) block.content.container_max_width = '1200px';
        if (!block.content.box_shadow) block.content.box_shadow = 'shadow-sm';
        if (!block.content.frame_border_radius) block.content.frame_border_radius = '8px';
        if (!block.content.padding_top) block.content.padding_top = '40px';
        if (!block.content.padding_bottom) block.content.padding_bottom = '40px';
        if (!block.content.padding_left) block.content.padding_left = '0px';
        if (!block.content.padding_right) block.content.padding_right = '0px';
    ">
{{-- removed shared_styles --}}

        {{-- General Settings --}}
        <div class="bg-white p-3 border rounded-4 mb-3 shadow-sm">
            <div class="d-flex align-items-center mb-3">
                <div class="bg-primary bg-opacity-10 p-2 rounded-3 me-2">
                    <i class="fas fa-cog text-primary"></i>
                </div>
                <h6 class="fw-bold text-dark mb-0 small text-uppercase" style="letter-spacing: 0.5px;">Cấu hình chung</h6>
            </div>

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.6rem;">Tiêu đề khối</label>
                    <input type="text" x-model="block.content.title" class="form-control form-control-sm border-0 bg-light py-2 shadow-sm" placeholder="VD: TIN TỨC MỚI NHẤT">
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.6rem;">Typography tiêu đề (Màu & Size)</label>
                    <div class="d-flex align-items-center bg-light rounded-2 p-1 gap-1 shadow-sm" style="height: 38px;">
                        <input type="color" x-model="block.content.title_color" class="form-control-color border-0 bg-transparent p-0 ms-1" style="width: 24px; height: 24px;">
                        <input type="text" x-model="block.content.title_color" class="form-control form-control-sm border-0 bg-transparent p-0 fs-7 fw-bold px-1" style="width: 65px;">
                        <div class="d-flex gap-1 ms-1">
                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="copyColor(block.content.title_color)" title="Copy màu"><i class="fas fa-copy" style="font-size: 0.6rem;"></i></button>
                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="pasteColor(block.content, 'title_color')" title="Dán màu"><i class="fas fa-paste" style="font-size: 0.6rem;"></i></button>
                        </div>
                        <div class="vr mx-1 opacity-25" style="height: 20px;"></div>
                        <i class="fas fa-text-height text-muted ms-1" style="font-size: 0.7rem;"></i>
                        <input type="number" x-model="block.content.title_font_size" class="form-control form-control-sm border-0 bg-transparent p-0 fs-7 fw-bold px-1 text-center" style="width: 55px;">
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.6rem;">Màu line trang trí (Accent)</label>
                    <div class="d-flex align-items-center bg-light rounded-2 p-1 gap-1 shadow-sm" style="height: 38px;">
                        <input type="color" x-model="block.content.accent_color" class="form-control-color border-0 bg-transparent p-0 ms-1" style="width: 24px; height: 24px;">
                        <input type="text" x-model="block.content.accent_color" class="form-control form-control-sm border-0 bg-transparent p-0 fs-7 fw-bold px-1 flex-grow-1" style="width: 65px;" placeholder="#e31824">
                        <div class="d-flex gap-1 ms-1">
                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="copyColor(block.content.accent_color)" title="Copy màu"><i class="fas fa-copy" style="font-size: 0.6rem;"></i></button>
                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="pasteColor(block.content, 'accent_color')" title="Dán màu"><i class="fas fa-paste" style="font-size: 0.6rem;"></i></button>
                        </div>
                    </div>
                </div>

                {{-- Link Settings --}}
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.6rem;">Chữ Link "Xem tất cả"</label>
                    <input type="text" x-model="block.content.view_all_text" class="form-control form-control-sm border-0 bg-light py-2 shadow-sm" placeholder="XEM TẤT CẢ">
                </div>
                <div class="col-md-8">
                    <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.6rem;">Đường dẫn Link</label>
                    <input type="text" x-model="block.content.view_all_link" class="form-control form-control-sm border-0 bg-light py-2 shadow-sm" placeholder="/tin-tuc">
                </div>

                <div class="col-md-6">
                    <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.6rem;">Danh mục tin tức</label>
                    <select x-model="block.content.category_id" class="form-select form-select-sm border-0 bg-light py-2 shadow-sm">
                        <option value="">-- Tất cả tin tức --</option>
                        @foreach(\App\Models\PostCategory::all() as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.6rem;">Số lượng bài</label>
                    <input type="number" x-model="block.content.items_limit" class="form-control form-control-sm border-0 bg-light py-2 shadow-sm" placeholder="Mặc định: 5">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <div class="small text-muted mb-2"><i class="fas fa-info-circle me-1 opacity-75"></i> Gợi ý: Bội số của 5</div>
                </div>
            </div>
        </div>

        {{-- Typography & Button Card --}}
        <div class="row g-3 mb-1">
            {{-- View All Button Settings --}}
            <div class="col-md-6">
                <div class="bg-white p-3 border rounded-4 mb-3 shadow-sm h-100">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center">
                            <div class="bg-success bg-opacity-10 p-2 rounded-3 me-2">
                                <i class="fas fa-external-link-alt text-success" style="font-size: 0.8rem;"></i>
                            </div>
                            <h6 class="fw-bold text-dark mb-0 small text-uppercase" style="letter-spacing: 0.5px;">Nút "Xem Tất Cả"</h6>
                        </div>
                        <button type="button" class="btn btn-sm btn-light py-1 px-2 border rounded-pill" style="font-size: 0.55rem;" 
                            @click="block.content.view_all_text_color = block.content.accent_color">ĐẶT THEO MÀU LINE</button>
                    </div>
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.6rem;">Màu nền</label>
                            <div class="d-flex gap-1 align-items-center bg-light rounded-2 p-1 shadow-sm">
                                <input type="color" x-model="block.content.view_all_bg_color" class="form-control-color border-0 bg-transparent p-0 ms-1" style="width: 24px; height: 24px;">
                                <input type="text" x-model="block.content.view_all_bg_color" class="form-control form-control-sm border-0 bg-transparent p-0 fs-7 px-1 flex-grow-1" style="width: 65px;" placeholder="#ffffff">
                                <div class="d-flex gap-1">
                                    <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="copyColor(block.content.view_all_bg_color)">
                                        <i class="fas fa-copy" style="font-size: 0.6rem;"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="pasteColor(block.content, 'view_all_bg_color')">
                                        <i class="fas fa-paste" style="font-size: 0.6rem;"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.6rem;">Màu chữ / viền</label>
                            <div class="d-flex gap-1 align-items-center bg-light rounded-2 p-1 shadow-sm">
                                <input type="color" x-model="block.content.view_all_text_color" class="form-control-color border-0 bg-transparent p-0 ms-1" style="width: 24px; height: 24px;">
                                <input type="text" x-model="block.content.view_all_text_color" class="form-control form-control-sm border-0 bg-transparent p-0 fs-7 px-1 flex-grow-1" style="width: 65px;" placeholder="#000000">
                                <div class="d-flex gap-1">
                                    <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="copyColor(block.content.view_all_text_color)">
                                        <i class="fas fa-copy" style="font-size: 0.6rem;"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="pasteColor(block.content, 'view_all_text_color')">
                                        <i class="fas fa-paste" style="font-size: 0.6rem;"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mt-2">
                            <div class="row g-2">
                                <div class="col-6">
                                    <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.6rem;">Bo góc (px)</label>
                                    <input type="number" x-model="block.content.view_all_border_radius" class="form-control form-control-sm border-0 bg-light py-2 shadow-sm" placeholder="50">
                                </div>
                                <div class="col-6">
                                    <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.6rem;">Cỡ chữ (px)</label>
                                    <input type="number" x-model="block.content.view_all_font_size" class="form-control form-control-sm border-0 bg-light py-2 shadow-sm text-center" style="width: 55px;" placeholder="11">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- News Typography Settings --}}
            <div class="col-md-6">
                <div class="bg-white p-3 border rounded-4 mb-3 shadow-sm h-100">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-info bg-opacity-10 p-2 rounded-3 me-2">
                            <i class="fas fa-font text-info" style="font-size: 0.8rem;"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-0 small text-uppercase" style="letter-spacing: 0.5px;">Thiết kế Bài Viết</h6>
                    </div>
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.6rem;">Màu tiêu đề (Mặc định)</label>
                            <div class="d-flex align-items-center bg-light rounded-2 p-1 gap-1 shadow-sm" style="height: 38px;">
                                <input type="color" x-model="block.content.post_title_color" class="form-control-color border-0 bg-transparent p-0 ms-1" style="width: 24px; height: 24px;">
                                <input type="text" x-model="block.content.post_title_color" class="form-control form-control-sm border-0 bg-transparent p-0 fs-7 fw-bold px-1 flex-grow-1" style="width: 65px;" placeholder="#000">
                                <div class="d-flex gap-1 ms-1">
                                    <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="copyColor(block.content.post_title_color)" title="Copy màu"><i class="fas fa-copy" style="font-size: 0.6rem;"></i></button>
                                    <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="pasteColor(block.content, 'post_title_color')" title="Dán màu"><i class="fas fa-paste" style="font-size: 0.6rem;"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.6rem;">Màu tiêu đề (Khi di chuột)</label>
                            <div class="d-flex align-items-center bg-light rounded-2 p-1 gap-1 shadow-sm" style="height: 38px;">
                                <input type="color" x-model="block.content.post_title_hover_color" class="form-control-color border-0 bg-transparent p-0 ms-1" style="width: 24px; height: 24px;">
                                <input type="text" x-model="block.content.post_title_hover_color" class="form-control form-control-sm border-0 bg-transparent p-0 fs-7 fw-bold px-1 flex-grow-1" style="width: 65px;" placeholder="#e31824">
                                <div class="d-flex gap-1 ms-1">
                                    <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="copyColor(block.content.post_title_hover_color)" title="Copy màu"><i class="fas fa-copy" style="font-size: 0.6rem;"></i></button>
                                    <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="pasteColor(block.content, 'post_title_hover_color')" title="Dán màu"><i class="fas fa-paste" style="font-size: 0.6rem;"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 mt-2">
                            <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.6rem;">Size bài lớn (px)</label>
                            <input type="number" x-model="block.content.post_title_font_size" class="form-control form-control-sm border-0 bg-light py-2 shadow-sm text-center" placeholder="18">
                        </div>
                        <div class="col-6 mt-2">
                            <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.6rem;">Size bài nhỏ (px)</label>
                            <input type="number" x-model="block.content.post_title_small_font_size" class="form-control form-control-sm border-0 bg-light py-2 shadow-sm text-center" placeholder="15">
                        </div>

                        {{-- Excerpt Settings --}}
                        <div class="col-12 mt-3 border-top pt-3">
                            <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.6rem;">Màu & Cỡ chữ Mô tả (Chỉ bài lớn)</label>
                            <div class="d-flex align-items-center bg-light rounded-2 p-1 gap-1 shadow-sm" style="height: 38px;">
                                <input type="color" x-model="block.content.excerpt_color" class="form-control-color border-0 bg-transparent p-0 ms-1" style="width: 24px; height: 24px;">
                                <input type="text" x-model="block.content.excerpt_color" class="form-control form-control-sm border-0 bg-transparent p-0 fs-7 fw-bold px-1" style="width: 65px;" placeholder="#64748b">
                                <div class="d-flex gap-1 ms-1">
                                    <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="copyColor(block.content.excerpt_color)" title="Copy màu"><i class="fas fa-copy" style="font-size: 0.6rem;"></i></button>
                                    <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="pasteColor(block.content, 'excerpt_color')" title="Dán màu"><i class="fas fa-paste" style="font-size: 0.6rem;"></i></button>
                                </div>
                                <div class="vr mx-1 opacity-25" style="height: 20px;"></div>
                                <i class="fas fa-text-height text-muted ms-1" style="font-size: 0.7rem;"></i>
                                <input type="number" x-model="block.content.excerpt_font_size" class="form-control form-control-sm border-0 bg-transparent p-0 fs-7 fw-bold px-1 text-center" style="width: 55px;" placeholder="14">
                                <div class="vr mx-1 opacity-25" style="height: 20px;"></div>
                                <i class="fas fa-align-left text-muted ms-1" style="font-size: 0.7rem;" title="Số dòng hiển thị"></i>
                                <input type="number" x-model="block.content.excerpt_line_clamp" class="form-control form-control-sm border-0 bg-transparent p-0 fs-7 fw-bold px-1 text-center" style="width: 45px;" placeholder="3">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Frame & Display Settings --}}
        <div class="bg-white p-3 rounded-4 border mb-3 shadow-sm mt-3">
            <div class="d-flex align-items-center mb-3">
                <div class="bg-primary bg-opacity-10 p-2 rounded-3 me-2">
                    <i class="fas fa-expand-arrows-alt text-primary"></i>
                </div>
                <h6 class="fw-bold text-dark mb-0 small text-uppercase" style="letter-spacing: 0.5px;">Tùy chỉnh Khung & Hiển thị</h6>
            </div>

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.6rem;">
                        <i class="fas fa-arrows-alt-h me-1"></i> Chiều rộng tối đa
                    </label>
                    <input type="text" x-model="block.content.container_max_width" class="form-control form-control-sm border-0 bg-light py-2 shadow-sm" placeholder="VD: 1200px">
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.6rem;">
                        <i class="fas fa-clone me-1"></i> Đổ bóng từng Item
                    </label>
                    <select x-model="block.content.box_shadow" class="form-select form-select-sm border-0 bg-light py-2 shadow-sm">
                        <option value="none">Không có</option>
                        <option value="shadow-sm">Nhẹ nhàng</option>
                        <option value="shadow">Vừa phải</option>
                        <option value="shadow-lg">Đậm nét</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.6rem;">
                        <i class="fas fa-border-none me-1"></i> Bo góc từng Item
                    </label>
                    <select x-model="block.content.frame_border_radius" class="form-select form-select-sm border-0 bg-light py-2 shadow-sm">
                        <option value="0">0px</option>
                        <option value="8px">8px (Mặc định)</option>
                        <option value="16px">16px</option>
                        <option value="24px">24px</option>
                        <option value="32px">32px</option>
                    </select>
                </div>
                <div class="col-md-6 mt-2">
                    <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.6rem;">
                        <i class="fas fa-arrow-up me-1"></i> Khoảng cách trên
                    </label>
                    <input type="text" x-model="block.content.padding_top" class="form-control form-control-sm border-0 bg-light py-2 shadow-sm" placeholder="40px">
                </div>
                <div class="col-md-6 mt-2">
                    <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.6rem;">
                        <i class="fas fa-arrow-down me-1"></i> Khoảng cách dưới
                    </label>
                    <input type="text" x-model="block.content.padding_bottom" class="form-control form-control-sm border-0 bg-light py-2 shadow-sm" placeholder="40px">
                </div>
                <div class="col-md-6 mt-2">
                    <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.6rem;">
                        <i class="fas fa-arrow-left me-1"></i> Khoảng cách trái
                    </label>
                    <input type="text" x-model="block.content.padding_left" class="form-control form-control-sm border-0 bg-light py-2 shadow-sm" placeholder="0px">
                </div>
                <div class="col-md-6 mt-2">
                    <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.6rem;">
                        <i class="fas fa-arrow-right me-1"></i> Khoảng cách phải
                    </label>
                    <input type="text" x-model="block.content.padding_right" class="form-control form-control-sm border-0 bg-light py-2 shadow-sm" placeholder="0px">
                </div>
            </div>
        </div>
    </div>
</template>

<style>
.text-grid-admin input:focus, 
.text-grid-admin select:focus {
    box-shadow: none !important;
    border-color: #3b82f6 !important;
    background-color: #fff !important;
}
</style>
