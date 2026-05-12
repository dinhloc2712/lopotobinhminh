<template x-if="block.type === 'product_description'">
    <div class="mt-2 text-product-description-admin"
        x-init="
            // Set defaults if missing to match product_detail
            if (!block.content.container_max_width) block.content.container_max_width = '1024px';
            if (!block.content.box_shadow) block.content.box_shadow = 'none';
            if (!block.content.border_radius) block.content.border_radius = '16px';
            if (block.content.show_title === undefined) block.content.show_title = true;
            if (!block.content.title) block.content.title = 'Thông tin chi tiết';
            if (!block.content.title_color) block.content.title_color = '#1a202c';
            if (block.content.title_font_size === undefined) block.content.title_font_size = 20;

            // Inner Padding Defaults (KC trong khung)
            if (block.content.padding_top === undefined) block.content.padding_top = 48;
            if (block.content.padding_bottom === undefined) block.content.padding_bottom = 48;
            if (block.content.padding_left === undefined) block.content.padding_left = 48;
            if (block.content.padding_right === undefined) block.content.padding_right = 48;

            // Outer Padding Defaults (KC lề ngoài khối)
            if (block.content.margin_top === undefined) block.content.margin_top = 40;
            if (block.content.margin_bottom === undefined) block.content.margin_bottom = 40;
            if (block.content.margin_left === undefined) block.content.margin_left = 0;
            if (block.content.margin_right === undefined) block.content.margin_right = 0;

            // Button Defaults
            if (!block.content.btn_text_color) block.content.btn_text_color = '#4e73df';
            if (!block.content.btn_text_color_hover) block.content.btn_text_color_hover = '#ffffff';
            if (!block.content.btn_bg_color) block.content.btn_bg_color = 'transparent';
            if (!block.content.btn_bg_color_hover) block.content.btn_bg_color_hover = '#4e73df';
            if (!block.content.btn_border_color) block.content.btn_border_color = '#4e73df';
            if (!block.content.btn_border_color_hover) block.content.btn_border_color_hover = '#4e73df';
            if (block.content.btn_font_size === undefined) block.content.btn_font_size = 14;
            if (block.content.btn_border_width === undefined) block.content.btn_border_width = 1;

            window.copyColor = (color) => {
                navigator.clipboard.writeText(color).then(() => {
                    if(window.toastr) toastr.success('Đã copy: ' + color);
                });
            };
            window.pasteColor = (obj, key) => {
                navigator.clipboard.readText().then(text => {
                    if (text.startsWith('#') || text === 'transparent') {
                        obj[key] = text;
                        if(window.toastr) toastr.success('Đã dán màu!');
                    } else {
                        if(window.toastr) toastr.error('Mã màu không hợp lệ!');
                    }
                });
            };
        ">
        
        {{-- CARD 1: SẢN PHẨM --}}
        <div class="bg-white p-3 border rounded-4 mb-3 shadow-sm">
            <div class="d-flex align-items-center mb-3">
                <div class="bg-primary bg-opacity-10 p-2 rounded-3 me-2">
                    <i class="fas fa-box-open text-primary"></i>
                </div>
                <h6 class="fw-bold text-dark mb-0 small text-uppercase" style="letter-spacing: 0.5px;">Cấu hình Sản phẩm</h6>
            </div>

            <div class="row g-3">
                <div class="col-md-12">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.75rem;">Chọn Sản phẩm hiển thị</label>
                    <div class="input-group input-group-sm rounded-2 overflow-hidden border shadow-xs bg-light">
                        <span class="input-group-text bg-transparent border-0"><i class="fas fa-search text-muted opacity-50"></i></span>
                        <select x-model="block.content.product_id" class="form-select border-0 bg-transparent py-2 shadow-none">
                            <option value="">-- Chọn sản phẩm --</option>
                            @foreach(\App\Models\Product::orderBy('name')->get() as $prod)
                                <option value="{{ $prod->id }}">{{ $prod->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        {{-- CARD 2: TIÊU ĐỀ --}}
        <div class="bg-white p-3 border rounded-4 mb-3 shadow-sm">
            <div class="d-flex align-items-center mb-3">
                <div class="bg-success bg-opacity-10 p-2 rounded-3 me-2">
                    <i class="fas fa-font text-success"></i>
                </div>
                <h6 class="fw-bold text-dark mb-0 small text-uppercase" style="letter-spacing: 0.5px;">Tùy chỉnh Tiêu đề khối</h6>
            </div>

            <div class="row g-3">
                <div class="col-md-12">
                    <div class="form-check form-switch p-2 bg-light rounded-3 border d-flex align-items-center justify-content-between px-3">
                        <label class="form-check-label fw-bold text-muted small mb-0">HIỂN THỊ TIÊU ĐỀ</label>
                        <input class="form-check-input ms-0 mt-0 shadow-none border-0 bg-secondary bg-opacity-25" type="checkbox" x-model="block.content.show_title">
                    </div>
                </div>
                
                <template x-if="block.content.show_title">
                    <div class="col-12 mt-2">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.75rem;">Nội dung tiêu đề</label>
                                <input type="text" x-model="block.content.title" class="form-control form-control-sm border-0 bg-light py-2 shadow-sm rounded-2 fw-bold" placeholder="VD: Thông tin chi tiết...">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.7rem;">Màu tiêu đề</label>
                                <div class="d-flex align-items-center bg-light rounded-2 p-1 gap-1 shadow-sm border" style="height: 38px;">
                                    <input type="color" x-model="block.content.title_color" class="form-control-color border-0 bg-transparent p-0 ms-1" style="width: 24px; height: 24px;">
                                    <input type="text" x-model="block.content.title_color" class="form-control form-control-sm border-0 bg-transparent p-0 fs-7 fw-bold flex-grow-1" style="width: 60px;">
                                    <div class="d-flex gap-1 ms-auto pe-1 border-start ps-1">
                                        <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="copyColor(block.content.title_color)"><i class="fas fa-copy" style="font-size: 0.6rem;"></i></button>
                                        <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="pasteColor(block.content, 'title_color')"><i class="fas fa-paste" style="font-size: 0.6rem;"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.7rem;">Cỡ chữ (px)</label>
                                <input type="number" x-model="block.content.title_font_size" class="form-control form-control-sm border-0 bg-light py-2 shadow-sm rounded-2 text-center fw-bold">
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        {{-- CARD 3: BỐ CỤC --}}
        <div class="bg-white p-3 border rounded-4 mb-3 shadow-sm">
            <div class="d-flex align-items-center mb-3">
                <div class="bg-indigo bg-opacity-10 p-2 rounded-3 me-2" style="background-color: rgba(99, 102, 241, 0.1);">
                    <i class="fas fa-vector-square" style="color: #6366f1;"></i>
                </div>
                <h6 class="fw-bold text-dark mb-0 small text-uppercase" style="letter-spacing: 0.5px;">Bố cục & Hiển thị khung (Card)</h6>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.75rem;">Chiều rộng tối đa khung trắng</label>
                    <input type="text" x-model="block.content.container_max_width" class="form-control form-control-sm border-0 bg-light py-2 shadow-sm rounded-2 text-center fw-bold" placeholder="VD: 1024px hoặc 100%">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.75rem;">Đổ bóng</label>
                    <select x-model="block.content.box_shadow" class="form-select form-select-sm border-0 bg-light py-2 shadow-sm rounded-2">
                        <option value="none">Không có</option>
                        <option value="shadow-sm">Nhẹ</option>
                        <option value="shadow">Vừa</option>
                        <option value="shadow-lg">Đậm</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.75rem;">Bo góc</label>
                    <select x-model="block.content.border_radius" class="form-select form-select-sm border-0 bg-light py-2 shadow-sm rounded-2">
                        <option value="0">0px</option>
                        <option value="8px">8px</option>
                        <option value="12px">12px</option>
                        <option value="16px">16px</option>
                        <option value="24px">24px</option>
                        <option value="32px">32px</option>
                    </select>
                </div>

                <!-- LỀ NGOÀI (OUTER SETTINGS) -->
                <div class="col-md-3">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.65rem;">Lề ngoài TRÊN (px)</label>
                    <input type="number" x-model="block.content.margin_top" class="form-control form-control-sm border-0 bg-light py-2 shadow-sm rounded-2 text-center fw-bold">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.65rem;">Lề ngoài DƯỚI (px)</label>
                    <input type="number" x-model="block.content.margin_bottom" class="form-control form-control-sm border-0 bg-light py-2 shadow-sm rounded-2 text-center fw-bold">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.65rem;">Lề ngoài TRÁI (px)</label>
                    <input type="number" x-model="block.content.margin_left" class="form-control form-control-sm border-0 bg-light py-2 shadow-sm rounded-2 text-center fw-bold">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.65rem;">Lề ngoài PHẢI (px)</label>
                    <input type="number" x-model="block.content.margin_right" class="form-control form-control-sm border-0 bg-light py-2 shadow-sm rounded-2 text-center fw-bold">
                </div>

                <!-- LỀ TRONG (INNER SETTINGS) -->
                <div class="col-md-3">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.65rem;">Lề trong TRÊN (px)</label>
                    <input type="number" x-model="block.content.padding_top" class="form-control form-control-sm border-0 bg-light py-2 shadow-sm rounded-2 text-center fw-bold">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.65rem;">Lề trong DƯỚI (px)</label>
                    <input type="number" x-model="block.content.padding_bottom" class="form-control form-control-sm border-0 bg-light py-2 shadow-sm rounded-2 text-center fw-bold">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.65rem;">Lề trong TRÁI (px)</label>
                    <input type="number" x-model="block.content.padding_left" class="form-control form-control-sm border-0 bg-light py-2 shadow-sm rounded-2 text-center fw-bold">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.65rem;">Lề trong PHẢI (px)</label>
                    <input type="number" x-model="block.content.padding_right" class="form-control form-control-sm border-0 bg-light py-2 shadow-sm rounded-2 text-center fw-bold">
                </div>
            </div>
        </div>

        {{-- CARD 4: XEM THÊM (CƠ BẢN) --}}
        <div class="bg-white p-3 border rounded-4 mb-3 shadow-sm">
            <div class="d-flex align-items-center mb-3">
                <div class="bg-warning bg-opacity-10 p-2 rounded-3 me-2">
                    <i class="fas fa-eye text-warning"></i>
                </div>
                <h6 class="fw-bold text-dark mb-0 small text-uppercase" style="letter-spacing: 0.5px;">Tính năng Rút gọn nội dung</h6>
            </div>

            <div class="row g-3">
                <div class="col-md-12">
                    <div class="form-check form-switch p-2 bg-light rounded-3 border d-flex align-items-center justify-content-between px-3">
                        <label class="form-check-label fw-bold text-muted small mb-0">RÚT GỌN NỘI DUNG (READ MORE)</label>
                        <input class="form-check-input ms-0 mt-0 shadow-none border-0 bg-secondary bg-opacity-25" type="checkbox" x-model="block.content.enable_read_more">
                    </div>
                </div>
                <div class="col-md-12 mt-2" x-show="block.content.enable_read_more">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.75rem;">Chiều cao tối đa khi rút gọn (px)</label>
                    <input type="number" x-model="block.content.max_height" class="form-control form-control-sm border-0 bg-light py-2 shadow-sm rounded-2 text-center fw-bold" placeholder="400">
                </div>
            </div>
        </div>

        {{-- CARD 5: TÙY CHỈNH NÚT (Nâng cao) --}}
        <div class="bg-white p-3 border rounded-4 mb-3 shadow-sm" x-show="block.content.enable_read_more">
            <div class="d-flex align-items-center mb-3">
                <div class="bg-danger bg-opacity-10 p-2 rounded-3 me-2">
                    <i class="fas fa-mouse-pointer text-danger"></i>
                </div>
                <h6 class="fw-bold text-dark mb-0 small text-uppercase" style="letter-spacing: 0.5px;">Tùy chỉnh Nút Xem thêm</h6>
            </div>

            <div class="row g-3">
                {{-- Màu chữ --}}
                <div class="col-md-6">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.7rem;">Màu chữ (Thường)</label>
                    <div class="d-flex align-items-center bg-light rounded-2 p-1 gap-1 shadow-sm border" style="height: 38px;">
                        <input type="color" x-model="block.content.btn_text_color" class="form-control-color border-0 bg-transparent p-0 ms-1" style="width: 24px; height: 24px;">
                        <input type="text" x-model="block.content.btn_text_color" class="form-control form-control-sm border-0 bg-transparent p-0 fs-7 fw-bold flex-grow-1">
                        <div class="d-flex gap-1 ms-auto pe-1 border-start ps-1">
                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="copyColor(block.content.btn_text_color)"><i class="fas fa-copy" style="font-size: 0.6rem;"></i></button>
                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="pasteColor(block.content, 'btn_text_color')"><i class="fas fa-paste" style="font-size: 0.6rem;"></i></button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.7rem;">Màu chữ (Hover)</label>
                    <div class="d-flex align-items-center bg-light rounded-2 p-1 gap-1 shadow-sm border" style="height: 38px;">
                        <input type="color" x-model="block.content.btn_text_color_hover" class="form-control-color border-0 bg-transparent p-0 ms-1" style="width: 24px; height: 24px;">
                        <input type="text" x-model="block.content.btn_text_color_hover" class="form-control form-control-sm border-0 bg-transparent p-0 fs-7 fw-bold flex-grow-1">
                        <div class="d-flex gap-1 ms-auto pe-1 border-start ps-1">
                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="copyColor(block.content.btn_text_color_hover)"><i class="fas fa-copy" style="font-size: 0.6rem;"></i></button>
                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="pasteColor(block.content, 'btn_text_color_hover')"><i class="fas fa-paste" style="font-size: 0.6rem;"></i></button>
                        </div>
                    </div>
                </div>

                {{-- Màu nền --}}
                <div class="col-md-6">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.7rem;">Màu nền (Thường)</label>
                    <div class="d-flex align-items-center bg-light rounded-2 p-1 gap-1 shadow-sm border" style="height: 38px;">
                        <input type="color" x-model="block.content.btn_bg_color" class="form-control-color border-0 bg-transparent p-0 ms-1" style="width: 24px; height: 24px;">
                        <input type="text" x-model="block.content.btn_bg_color" class="form-control form-control-sm border-0 bg-transparent p-0 fs-7 fw-bold flex-grow-1">
                        <div class="d-flex gap-1 ms-auto pe-1 border-start ps-1">
                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="copyColor(block.content.btn_bg_color)"><i class="fas fa-copy" style="font-size: 0.6rem;"></i></button>
                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="pasteColor(block.content, 'btn_bg_color')"><i class="fas fa-paste" style="font-size: 0.6rem;"></i></button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.7rem;">Màu nền (Hover)</label>
                    <div class="d-flex align-items-center bg-light rounded-2 p-1 gap-1 shadow-sm border" style="height: 38px;">
                        <input type="color" x-model="block.content.btn_bg_color_hover" class="form-control-color border-0 bg-transparent p-0 ms-1" style="width: 24px; height: 24px;">
                        <input type="text" x-model="block.content.btn_bg_color_hover" class="form-control form-control-sm border-0 bg-transparent p-0 fs-7 fw-bold flex-grow-1">
                        <div class="d-flex gap-1 ms-auto pe-1 border-start ps-1">
                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="copyColor(block.content.btn_bg_color_hover)"><i class="fas fa-copy" style="font-size: 0.6rem;"></i></button>
                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="pasteColor(block.content, 'btn_bg_color_hover')"><i class="fas fa-paste" style="font-size: 0.6rem;"></i></button>
                        </div>
                    </div>
                </div>

                {{-- Màu viền --}}
                <div class="col-md-6">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.7rem;">Màu viền (Thường)</label>
                    <div class="d-flex align-items-center bg-light rounded-2 p-1 gap-1 shadow-sm border" style="height: 38px;">
                        <input type="color" x-model="block.content.btn_border_color" class="form-control-color border-0 bg-transparent p-0 ms-1" style="width: 24px; height: 24px;">
                        <input type="text" x-model="block.content.btn_border_color" class="form-control form-control-sm border-0 bg-transparent p-0 fs-7 fw-bold flex-grow-1">
                        <div class="d-flex gap-1 ms-auto pe-1 border-start ps-1">
                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="copyColor(block.content.btn_border_color)"><i class="fas fa-copy" style="font-size: 0.6rem;"></i></button>
                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="pasteColor(block.content, 'btn_border_color')"><i class="fas fa-paste" style="font-size: 0.6rem;"></i></button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.7rem;">Màu viền (Hover)</label>
                    <div class="d-flex align-items-center bg-light rounded-2 p-1 gap-1 shadow-sm border" style="height: 38px;">
                        <input type="color" x-model="block.content.btn_border_color_hover" class="form-control-color border-0 bg-transparent p-0 ms-1" style="width: 24px; height: 24px;">
                        <input type="text" x-model="block.content.btn_border_color_hover" class="form-control form-control-sm border-0 bg-transparent p-0 fs-7 fw-bold flex-grow-1">
                        <div class="d-flex gap-1 ms-auto pe-1 border-start ps-1">
                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="copyColor(block.content.btn_border_color_hover)"><i class="fas fa-copy" style="font-size: 0.6rem;"></i></button>
                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="pasteColor(block.content, 'btn_border_color_hover')"><i class="fas fa-paste" style="font-size: 0.6rem;"></i></button>
                        </div>
                    </div>
                </div>

                {{-- Kích cỡ --}}
                <div class="col-md-6">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.7rem;">Cỡ chữ (px)</label>
                    <input type="number" x-model="block.content.btn_font_size" class="form-control form-control-sm border-0 bg-light py-2 shadow-sm rounded-2 text-center fw-bold">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.7rem;">Độ dày viền (px)</label>
                    <input type="number" x-model="block.content.btn_border_width" class="form-control form-control-sm border-0 bg-light py-2 shadow-sm rounded-2 text-center fw-bold">
                </div>
            </div>
        </div>
    </div>
</template>

<style>
.text-product-description-admin .form-check-input:checked {
    background-color: #3b82f6 !important;
}
.text-product-description-admin input:focus, 
.text-product-description-admin select:focus {
    box-shadow: none !important;
    border-color: #3b82f6 !important;
    background-color: #fff !important;
}
</style>
