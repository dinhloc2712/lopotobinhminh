<template x-if="block.type === 'product_detail'">
    <div class="mt-2 text-product-detail-admin"
        x-init="
            // Set defaults if missing
            if (!block.content.container_max_width) block.content.container_max_width = '1024px';
            if (!block.content.box_shadow) block.content.box_shadow = 'none';
            if (!block.content.border_radius) block.content.border_radius = '16px';
            if (!block.content.gallery_active_color) block.content.gallery_active_color = '#C92127';
            if (block.content.show_rating === undefined) block.content.show_rating = true;
            if (block.content.show_sku === undefined) block.content.show_sku = true;
            
            // Typography & Price Defaults
            if (!block.content.title_color) block.content.title_color = '#333333';
            if (!block.content.title_font_size) block.content.title_font_size = 26;
            if (!block.content.price_color) block.content.price_color = '#c62828';
            if (!block.content.price_font_size) block.content.price_font_size = 32;
            if (!block.content.old_price_color) block.content.old_price_color = '#888888';
            if (!block.content.old_price_font_size) block.content.old_price_font_size = 20;
            
            // Discount Tag Defaults
            if (!block.content.discount_bg_color) block.content.discount_bg_color = '#c62828';
            if (!block.content.discount_text_color) block.content.discount_text_color = '#ffffff';
            if (!block.content.discount_font_size) block.content.discount_font_size = 14;

            // Inner Padding Defaults (KC lề trong khung)
            if (block.content.padding_top === undefined) block.content.padding_top = 40;
            if (block.content.padding_bottom === undefined) block.content.padding_bottom = 40;
            if (block.content.padding_left === undefined) block.content.padding_left = 40;
            if (block.content.padding_right === undefined) block.content.padding_right = 40;

            // Outer Spacing Defaults (KC lề ngoài khối)
            if (block.content.margin_top === undefined) block.content.margin_top = 40;
            if (block.content.margin_bottom === undefined) block.content.margin_bottom = 40;
            if (block.content.margin_left === undefined) block.content.margin_left = 0;
            if (block.content.margin_right === undefined) block.content.margin_right = 0;

            // Cart Button Defaults
            if (!block.content.cart_btn_bg_color) block.content.cart_btn_bg_color = '#ffffff';
            if (!block.content.cart_btn_text_color) block.content.cart_btn_text_color = '#C92127';
            if (!block.content.cart_btn_font_size) block.content.cart_btn_font_size = 15;
            if (!block.content.cart_btn_bg_hover) block.content.cart_btn_bg_hover = '#C92127';
            if (!block.content.cart_btn_text_hover) block.content.cart_btn_text_hover = '#ffffff';
            if (block.content.cart_btn_border_width === undefined) block.content.cart_btn_border_width = 2;
            if (!block.content.cart_btn_border_color) block.content.cart_btn_border_color = '#C92127';
            if (!block.content.cart_btn_border_color_hover) block.content.cart_btn_border_color_hover = '#C92127';

            // Buy Button Defaults
            if (!block.content.buy_btn_bg_color) block.content.buy_btn_bg_color = '#C92127';
            if (!block.content.buy_btn_text_color) block.content.buy_btn_text_color = '#ffffff';
            if (!block.content.buy_btn_font_size) block.content.buy_btn_font_size = 15;
            if (!block.content.buy_btn_bg_hover) block.content.buy_btn_bg_hover = '#a31a1e';
            if (!block.content.buy_btn_text_hover) block.content.buy_btn_text_hover = '#ffffff';

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
        
{{-- removed shared_styles --}}

        {{-- CARD 1: CẤU HÌNH SẢN PHẨM --}}
        <div class="bg-white p-3 border rounded-4 mb-3 shadow-sm">
            <div class="d-flex align-items-center mb-3">
                <div class="bg-primary bg-opacity-10 p-2 rounded-3 me-2">
                    <i class="fas fa-box-open text-primary"></i>
                </div>
                <h6 class="fw-bold text-dark mb-0 small text-uppercase" style="letter-spacing: 0.5px;">1. Cấu hình Sản phẩm chính</h6>
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

                <div class="col-md-6 pt-1">
                    <div class="form-check form-switch p-2 bg-light rounded-3 border d-flex align-items-center justify-content-between px-3">
                        <label class="form-check-label fw-bold text-muted small mb-0" :for="'show_rating_' + index">HIỆN SAO ĐÁNH GIÁ</label>
                        <input class="form-check-input ms-0 mt-0 shadow-none border-0 bg-secondary bg-opacity-25" type="checkbox" x-model="block.content.show_rating" :id="'show_rating_' + index">
                    </div>
                </div>
                <div class="col-md-6 pt-1">
                    <div class="form-check form-switch p-2 bg-light rounded-3 border d-flex align-items-center justify-content-between px-3">
                        <label class="form-check-label fw-bold text-muted small mb-0" :for="'show_sku_' + index">HIỆN TỒN KHO & MÃ SP</label>
                        <input class="form-check-input ms-0 mt-0 shadow-none border-0 bg-secondary bg-opacity-25" type="checkbox" x-model="block.content.show_sku" :id="'show_sku_' + index">
                    </div>
                </div>
            </div>
        </div>

        {{-- CARD 2: TYPOGRAPHY & GIÁ --}}
        <div class="bg-white p-3 border rounded-4 mb-3 shadow-sm">
            <div class="d-flex align-items-center mb-3">
                <div class="bg-success bg-opacity-10 p-2 rounded-3 me-2">
                    <i class="fas fa-font text-success"></i>
                </div>
                <h6 class="fw-bold text-dark mb-0 small text-uppercase" style="letter-spacing: 0.5px;">2. Tùy chỉnh Tiêu đề & Giá</h6>
            </div>

            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.75rem;">Màu tiêu đề sản phẩm</label>
                    <div class="d-flex align-items-center bg-light rounded-2 p-1 gap-1 shadow-sm border" style="height: 38px;">
                        <input type="color" x-model="block.content.title_color" class="form-control-color border-0 bg-transparent p-0 ms-1" style="width: 24px; height: 24px;">
                        <input type="text" x-model="block.content.title_color" class="form-control form-control-sm border-0 bg-transparent p-0 fs-7 fw-bold px-1 flex-grow-1">
                        <div class="d-flex gap-1 ms-auto pe-1 border-start ps-1">
                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="copyColor(block.content.title_color)"><i class="fas fa-copy" style="font-size: 0.6rem;"></i></button>
                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="pasteColor(block.content, 'title_color')"><i class="fas fa-paste" style="font-size: 0.6rem;"></i></button>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.75rem;">Cỡ chữ tiêu đề</label>
                    <input type="number" x-model="block.content.title_font_size" class="form-control form-control-sm border-0 bg-light py-2 shadow-sm rounded-2 text-center fw-bold" style="height: 38px;">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.75rem;">Màu giá chính</label>
                    <div class="d-flex align-items-center bg-light rounded-2 p-1 gap-1 shadow-sm border" style="height: 38px;">
                        <input type="color" x-model="block.content.price_color" class="form-control-color border-0 bg-transparent p-0 ms-1" style="width: 24px; height: 24px;">
                        <input type="text" x-model="block.content.price_color" class="form-control form-control-sm border-0 bg-transparent p-0 fs-7 fw-bold px-1 flex-grow-1">
                        <div class="d-flex gap-1 ms-auto pe-1 border-start ps-1">
                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="copyColor(block.content.price_color)"><i class="fas fa-copy" style="font-size: 0.6rem;"></i></button>
                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="pasteColor(block.content, 'price_color')"><i class="fas fa-paste" style="font-size: 0.6rem;"></i></button>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.75rem;">Cỡ giá</label>
                    <input type="number" x-model="block.content.price_font_size" class="form-control form-control-sm border-0 bg-light py-2 shadow-sm rounded-2 text-center fw-bold" style="height: 38px;">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.75rem;">Màu giá gốc (Gạch)</label>
                    <div class="d-flex align-items-center bg-light rounded-2 p-1 gap-1 shadow-sm border" style="height: 38px;">
                        <input type="color" x-model="block.content.old_price_color" class="form-control-color border-0 bg-transparent p-0 ms-1" style="width: 24px; height: 24px;">
                        <input type="text" x-model="block.content.old_price_color" class="form-control form-control-sm border-0 bg-transparent p-0 fs-7 fw-bold px-1 flex-grow-1">
                        <div class="d-flex gap-1 ms-auto pe-1 border-start ps-1">
                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="copyColor(block.content.old_price_color)"><i class="fas fa-copy" style="font-size: 0.6rem;"></i></button>
                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="pasteColor(block.content, 'old_price_color')"><i class="fas fa-paste" style="font-size: 0.6rem;"></i></button>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.75rem;">Cỡ giá cũ</label>
                    <input type="number" x-model="block.content.old_price_font_size" class="form-control form-control-sm border-0 bg-light py-2 shadow-sm rounded-2 text-center fw-bold" style="height: 38px;">
                </div>

                <div class="col-md-4 border-top pt-3">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.75rem;">Màu nền Tag giảm giá</label>
                    <div class="d-flex align-items-center bg-light rounded-2 p-1 gap-1 shadow-sm border" style="height: 38px;">
                        <input type="color" x-model="block.content.discount_bg_color" class="form-control-color border-0 bg-transparent p-0 ms-1" style="width: 24px; height: 24px;">
                        <input type="text" x-model="block.content.discount_bg_color" class="form-control form-control-sm border-0 bg-transparent p-0 fs-7 fw-bold px-1 flex-grow-1">
                        <div class="d-flex gap-1 ms-auto pe-1 border-start ps-1">
                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="copyColor(block.content.discount_bg_color)"><i class="fas fa-copy" style="font-size: 0.6rem;"></i></button>
                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="pasteColor(block.content, 'discount_bg_color')"><i class="fas fa-paste" style="font-size: 0.6rem;"></i></button>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 border-top pt-3">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.75rem;">Màu chữ Tag</label>
                    <div class="d-flex align-items-center bg-light rounded-2 p-1 gap-1 shadow-sm border" style="height: 38px;">
                        <input type="color" x-model="block.content.discount_text_color" class="form-control-color border-0 bg-transparent p-0 ms-1" style="width: 24px; height: 24px;">
                        <input type="text" x-model="block.content.discount_text_color" class="form-control form-control-sm border-0 bg-transparent p-0 fs-7 fw-bold px-1 flex-grow-1">
                        <div class="d-flex gap-1 ms-auto pe-1 border-start ps-1">
                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="copyColor(block.content.discount_text_color)"><i class="fas fa-copy" style="font-size: 0.6rem;"></i></button>
                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="pasteColor(block.content, 'discount_text_color')"><i class="fas fa-paste" style="font-size: 0.6rem;"></i></button>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 border-top pt-3">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.75rem;">Cỡ chữ Tag</label>
                    <input type="number" x-model="block.content.discount_font_size" class="form-control form-control-sm border-0 bg-light py-2 shadow-sm rounded-2 text-center fw-bold" style="height: 38px;">
                </div>
            </div>
        </div>

        {{-- CARD 3: NÚT THÊM VÀO GIỎ --}}
        <div class="bg-white p-3 border rounded-4 mb-3 shadow-sm">
            <div class="d-flex align-items-center mb-3">
                <div class="bg-info bg-opacity-10 p-2 rounded-3 me-2" style="background-color: rgba(6, 182, 212, 0.1);">
                    <i class="fas fa-cart-plus" style="color: #06b6d4;"></i>
                </div>
                <h6 class="fw-bold text-dark mb-0 small text-uppercase" style="letter-spacing: 0.5px;">3. Nút Thêm vào giỏ</h6>
            </div>

            <div class="row g-3">
                <div class="col-md-5">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.75rem;">Màu nền (Bình thường)</label>
                    <div class="d-flex align-items-center bg-light rounded-2 p-1 gap-1 shadow-sm border" style="height: 38px;">
                        <input type="color" x-model="block.content.cart_btn_bg_color" class="form-control-color border-0 bg-transparent p-0 ms-1" style="width: 24px; height: 24px;">
                        <input type="text" x-model="block.content.cart_btn_bg_color" class="form-control form-control-sm border-0 bg-transparent p-0 fs-7 fw-bold px-1 flex-grow-1">
                        <div class="d-flex gap-1 ms-auto pe-1 border-start ps-1">
                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="copyColor(block.content.cart_btn_bg_color)"><i class="fas fa-copy" style="font-size: 0.6rem;"></i></button>
                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="pasteColor(block.content, 'cart_btn_bg_color')"><i class="fas fa-paste" style="font-size: 0.6rem;"></i></button>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.75rem;">Màu chữ (Bình thường)</label>
                    <div class="d-flex align-items-center bg-light rounded-2 p-1 gap-1 shadow-sm border" style="height: 38px;">
                        <input type="color" x-model="block.content.cart_btn_text_color" class="form-control-color border-0 bg-transparent p-0 ms-1" style="width: 24px; height: 24px;">
                        <input type="text" x-model="block.content.cart_btn_text_color" class="form-control form-control-sm border-0 bg-transparent p-0 fs-7 fw-bold px-1 flex-grow-1">
                        <div class="d-flex gap-1 ms-auto pe-1 border-start ps-1">
                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="copyColor(block.content.cart_btn_text_color)"><i class="fas fa-copy" style="font-size: 0.6rem;"></i></button>
                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="pasteColor(block.content, 'cart_btn_text_color')"><i class="fas fa-paste" style="font-size: 0.6rem;"></i></button>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.75rem;">Cỡ chữ</label>
                    <input type="number" x-model="block.content.cart_btn_font_size" class="form-control form-control-sm border-0 bg-light py-2 shadow-sm rounded-2 text-center fw-bold" style="height: 38px;">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.75rem;">Màu viền (Thường)</label>
                    <div class="d-flex align-items-center bg-light rounded-2 p-1 gap-1 shadow-sm border" style="height: 38px;">
                        <input type="color" x-model="block.content.cart_btn_border_color" class="form-control-color border-0 bg-transparent p-0 ms-1" style="width: 24px; height: 24px;">
                        <input type="text" x-model="block.content.cart_btn_border_color" class="form-control form-control-sm border-0 bg-transparent p-0 fs-7 fw-bold px-1 flex-grow-1">
                        <div class="d-flex gap-1 ms-auto pe-1 border-start ps-1">
                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="copyColor(block.content.cart_btn_border_color)"><i class="fas fa-copy" style="font-size: 0.6rem;"></i></button>
                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="pasteColor(block.content, 'cart_btn_border_color')"><i class="fas fa-paste" style="font-size: 0.6rem;"></i></button>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.75rem;">Độ dày viền</label>
                    <input type="number" x-model="block.content.cart_btn_border_width" class="form-control form-control-sm border-0 bg-light py-2 shadow-sm rounded-2 text-center fw-bold" style="height: 38px;">
                </div>

                <div class="col-md-4 bg-light bg-opacity-50 p-2 rounded-3">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.7rem;"><i class="fas fa-mouse-pointer me-1"></i> Màu nền khi HOVER</label>
                    <div class="d-flex align-items-center bg-white rounded-2 p-1 gap-1 shadow-sm border" style="height: 38px;">
                        <input type="color" x-model="block.content.cart_btn_bg_hover" class="form-control-color border-0 bg-transparent p-0 ms-1" style="width: 24px; height: 24px;">
                        <input type="text" x-model="block.content.cart_btn_bg_hover" class="form-control form-control-sm border-0 bg-transparent p-0 fs-7 fw-bold px-1 flex-grow-1">
                        <div class="d-flex gap-1 ms-auto pe-1 border-start ps-1">
                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="copyColor(block.content.cart_btn_bg_hover)"><i class="fas fa-copy" style="font-size: 0.6rem;"></i></button>
                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="pasteColor(block.content, 'cart_btn_bg_hover')"><i class="fas fa-paste" style="font-size: 0.6rem;"></i></button>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 bg-light bg-opacity-50 p-2 rounded-3">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.7rem;"><i class="fas fa-mouse-pointer me-1"></i> Màu chữ khi HOVER</label>
                    <div class="d-flex align-items-center bg-white rounded-2 p-1 gap-1 shadow-sm border" style="height: 38px;">
                        <input type="color" x-model="block.content.cart_btn_text_hover" class="form-control-color border-0 bg-transparent p-0 ms-1" style="width: 24px; height: 24px;">
                        <input type="text" x-model="block.content.cart_btn_text_hover" class="form-control form-control-sm border-0 bg-transparent p-0 fs-7 fw-bold px-1 flex-grow-1">
                        <div class="d-flex gap-1 ms-auto pe-1 border-start ps-1">
                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="copyColor(block.content.cart_btn_text_hover)"><i class="fas fa-copy" style="font-size: 0.6rem;"></i></button>
                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="pasteColor(block.content, 'cart_btn_text_hover')"><i class="fas fa-paste" style="font-size: 0.6rem;"></i></button>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 bg-light bg-opacity-50 p-2 rounded-3">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.7rem;"><i class="fas fa-mouse-pointer me-1"></i> Màu viền khi HOVER</label>
                    <div class="d-flex align-items-center bg-white rounded-2 p-1 gap-1 shadow-sm border" style="height: 38px;">
                        <input type="color" x-model="block.content.cart_btn_border_color_hover" class="form-control-color border-0 bg-transparent p-0 ms-1" style="width: 24px; height: 24px;">
                        <input type="text" x-model="block.content.cart_btn_border_color_hover" class="form-control form-control-sm border-0 bg-transparent p-0 fs-7 fw-bold px-1 flex-grow-1">
                        <div class="d-flex gap-1 ms-auto pe-1 border-start ps-1">
                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="copyColor(block.content.cart_btn_border_color_hover)"><i class="fas fa-copy" style="font-size: 0.6rem;"></i></button>
                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="pasteColor(block.content, 'cart_btn_border_color_hover')"><i class="fas fa-paste" style="font-size: 0.6rem;"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- CARD 4: NÚT MUA NGAY --}}
        <div class="bg-white p-3 border rounded-4 mb-3 shadow-sm">
            <div class="d-flex align-items-center mb-3">
                <div class="bg-danger bg-opacity-10 p-2 rounded-3 me-2">
                    <i class="fas fa-bolt text-danger"></i>
                </div>
                <h6 class="fw-bold text-dark mb-0 small text-uppercase" style="letter-spacing: 0.5px;">4. Nút Mua ngay</h6>
            </div>

            <div class="row g-3">
                <div class="col-md-5">
                    <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.65rem;">Màu nền (Bình thường)</label>
                    <div class="d-flex align-items-center bg-light rounded-2 p-1 gap-1 shadow-sm border" style="height: 34px;">
                        <input type="color" x-model="block.content.buy_btn_bg_color" class="form-control-color border-0 bg-transparent p-0 ms-1" style="width: 20px; height: 20px;">
                        <input type="text" x-model="block.content.buy_btn_bg_color" class="form-control form-control-sm border-0 bg-transparent p-0 fs-7 fw-bold px-1 flex-grow-1" style="font-size: 0.7rem;">
                    </div>
                </div>
                <div class="col-md-5">
                    <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.65rem;">Màu chữ (Bình thường)</label>
                    <div class="d-flex align-items-center bg-light rounded-2 p-1 gap-1 shadow-sm border" style="height: 34px;">
                        <input type="color" x-model="block.content.buy_btn_text_color" class="form-control-color border-0 bg-transparent p-0 ms-1" style="width: 20px; height: 20px;">
                        <input type="text" x-model="block.content.buy_btn_text_color" class="form-control form-control-sm border-0 bg-transparent p-0 fs-7 fw-bold px-1 flex-grow-1" style="font-size: 0.7rem;">
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.65rem;">Cỡ chữ</label>
                    <input type="number" x-model="block.content.buy_btn_font_size" class="form-control form-control-sm border-0 bg-light py-2 shadow-sm rounded-2 text-center fw-bold" style="height: 34px; font-size: 0.75rem;">
                </div>
            </div>
        </div>

        {{-- Gallery Active Color --}}
        <div class="bg-white p-3 border rounded-4 mb-3 shadow-sm">
             <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.75rem;">Màu tiêu điểm Slider (Gallery)</label>
             <div class="d-flex align-items-center bg-light rounded-2 p-1 gap-1 shadow-sm border" style="height: 38px;">
                 <input type="color" x-model="block.content.gallery_active_color" class="form-control-color border-0 bg-transparent p-0 ms-1" style="width: 24px; height: 24px;">
                 <input type="text" x-model="block.content.gallery_active_color" class="form-control form-control-sm border-0 bg-transparent p-0 fs-7 fw-bold px-1 flex-grow-1">
             </div>
        </div>

        {{-- CARD 6: NỘI DUNG CHUYÊN SÂU --}}
        <div class="bg-white p-3 border rounded-4 mb-3 shadow-sm">
            <div class="d-flex align-items-center mb-3">
                <div class="bg-purple bg-opacity-10 p-2 rounded-3 me-2" style="background-color: rgba(168, 85, 247, 0.1);">
                    <i class="fas fa-gift" style="color: #a855f7;"></i>
                </div>
                <h6 class="fw-bold text-dark mb-0 small text-uppercase" style="letter-spacing: 0.5px;">6. Quà tặng & Khuyến mãi (Rich Text)</h6>
            </div>
            
            <div class="rounded-3 overflow-hidden border">
                <textarea :id="'editor-' + index" x-model="block.content.body" class="form-control" rows="8"></textarea>
            </div>
            <div class="form-text mt-2" style="font-size: 0.7rem; color: #94a3b8;">* Nội dung này sẽ hiển thị bên dưới các nút mua hàng chính.</div>
        </div>
    </div>
</template>

<style>
.text-product-detail-admin .form-check-input:checked {
    background-color: #3b82f6 !important;
}
.text-product-detail-admin input:focus, 
.text-product-detail-admin select:focus {
    box-shadow: none !important;
    border-color: #3b82f6 !important;
    background-color: #fff !important;
}
</style>
