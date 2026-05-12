<template x-if="block.type === 'coupons'">
    <div class="mt-2 text-coupons-admin"
        x-init="
            if (!block.content.title) block.content.title = 'ƯU ĐÃI LIÊN QUAN';
            if (!block.content.coupon_ids) block.content.coupon_ids = [];

            // General & Accent
            if (!block.content.title_color) block.content.title_color = '#001d3d';
            if (!block.content.title_font_size) block.content.title_font_size = 20;
            if (!block.content.accent_color) block.content.accent_color = '#e31824';
            if (!block.content.link_icon_color) block.content.link_icon_color = '#3b82f6';

            // Item Typography
            if (!block.content.code_color) block.content.code_color = '#1a202c';
            if (!block.content.code_font_size) block.content.code_font_size = 14;
            if (!block.content.desc_color) block.content.desc_color = '#718096';
            if (!block.content.desc_font_size) block.content.desc_font_size = 12;

            // Card Style Defaults
            if (!block.content.box_shadow) block.content.box_shadow = 'shadow-sm';
            if (!block.content.item_box_shadow) block.content.item_box_shadow = 'shadow-sm';
            if (!block.content.border_radius) block.content.border_radius = '16px';
            if (!block.content.item_border_radius) block.content.item_border_radius = '12px';
            if (!block.content.card_bg_color) block.content.card_bg_color = '#ffffff';

            // Spacing defaults
            if (block.content.margin_top === undefined) block.content.margin_top = 40;
            if (block.content.margin_bottom === undefined) block.content.margin_bottom = 40;
            if (block.content.margin_left === undefined) block.content.margin_left = 0;
            if (block.content.margin_right === undefined) block.content.margin_right = 0;
            
            if (block.content.padding_top === undefined) block.content.padding_top = 24;
            if (block.content.padding_bottom === undefined) block.content.padding_bottom = 24;
            if (block.content.padding_left === undefined) block.content.padding_left = 24;
            if (block.content.padding_right === undefined) block.content.padding_right = 24;

            if (!block.content.container_max_width) block.content.container_max_width = '1200px';

            // Popup Defaults
            if (!block.content.popup_title) block.content.popup_title = 'Chi tiết ưu đãi';
            if (!block.content.popup_button_text) block.content.popup_button_text = 'SAO CHÉP MÃ NGAY';
            if (!block.content.popup_accent_color) block.content.popup_accent_color = '#e31824';
            if (!block.content.popup_title_color) block.content.popup_title_color = '#001d3d';
            if (!block.content.list_popup_title) block.content.list_popup_title = 'Tất cả mã giảm giá';
            if (!block.content.list_popup_search_placeholder) block.content.list_popup_search_placeholder = 'Tìm kiếm mã hoặc ưu đãi...';
            if (!block.content.list_popup_accent_color) block.content.list_popup_accent_color = '#3b82f6';
            if (!block.content.list_popup_icon_color) block.content.list_popup_icon_color = '#3b82f6';

            window.copyColor = (color) => {
                navigator.clipboard.writeText(color).then(() => {
                    if(window.toastr) toastr.success('Đã copy: ' + color);
                });
            };
            window.pasteColor = (obj, key) => {
                navigator.clipboard.readText().then(text => {
                    if (text.startsWith('#')) {
                        obj[key] = text;
                        if(window.toastr) toastr.success('Đã dán màu!');
                    } else {
                        if(window.toastr) toastr.error('Mã màu không hợp lệ!');
                    }
                });
            };
        ">

        {{-- CARD 1: CẤU HÌNH TIÊU ĐỀ & MÀU SẮC --}}
        <div class="bg-white p-3 border rounded-4 mb-3 shadow-sm text-product-category-admin">
            <div class="d-flex align-items-center mb-3">
                <div class="bg-primary bg-opacity-10 p-2 rounded-3 me-2">
                    <i class="fas fa-cog text-primary"></i>
                </div>
                <h6 class="fw-bold text-dark mb-0 small text-uppercase" style="letter-spacing: 0.5px;">Cấu hình khối Ưu đãi</h6>
            </div>

            <div class="row g-3">
                <div class="col-md-5">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.8rem;">Tiêu đề chính</label>
                    <input type="text" x-model="block.content.title" class="form-control form-control-sm border-0 bg-light py-2 shadow-sm rounded-2 fw-bold" placeholder="ƯU ĐÃI LIÊN QUAN">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.8rem;">Màu chữ tiêu đề</label>
                    <div class="d-flex align-items-center bg-light rounded-2 p-1 gap-1 shadow-sm border" style="height: 38px;">
                        <input type="color" x-model="block.content.title_color" class="form-control-color border-0 bg-transparent p-0 ms-1" style="width: 24px; height: 24px;">
                        <input type="text" x-model="block.content.title_color" class="form-control form-control-sm border-0 bg-transparent p-0 fs-7 fw-bold px-1 flex-grow-1">
                        <div class="d-flex gap-1 ms-auto pe-1 border-start ps-1">
                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="copyColor(block.content.title_color)" title="Copy"><i class="fas fa-copy" style="font-size: 0.6rem;"></i></button>
                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="pasteColor(block.content, 'title_color')" title="Dán"><i class="fas fa-paste" style="font-size: 0.6rem;"></i></button>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.8rem;">Cỡ chữ tiêu đề</label>
                    <input type="number" x-model="block.content.title_font_size" class="form-control form-control-sm border-0 bg-light py-2 shadow-sm rounded-2 text-center fw-bold">
                </div>
            </div>
        </div>

        {{-- CARD 2: TYPOGRAPHY ƯU ĐÃI --}}
        <div class="bg-white p-3 border rounded-4 mb-3 shadow-sm text-product-category-admin">
            <div class="d-flex align-items-center mb-3">
                <div class="bg-secondary bg-opacity-10 p-2 rounded-3 me-2">
                    <i class="fas fa-font text-secondary"></i>
                </div>
                <h6 class="fw-bold text-dark mb-0 small text-uppercase" style="letter-spacing: 0.5px;">Typography Item Ưu đãi</h6>
            </div>

            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.8rem;">Màu Mã Code</label>
                    <div class="d-flex align-items-center bg-light rounded-2 p-1 gap-1 shadow-sm border" style="height: 38px;">
                        <input type="color" x-model="block.content.code_color" class="form-control-color border-0 bg-transparent p-0 ms-1" style="width: 24px; height: 24px;">
                        <input type="text" x-model="block.content.code_color" class="form-control form-control-sm border-0 bg-transparent p-0 fs-7 fw-bold px-1 flex-grow-1">
                        <div class="d-flex gap-1 ms-auto pe-1 border-start ps-1">
                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="copyColor(block.content.code_color)" title="Copy"><i class="fas fa-copy" style="font-size: 0.6rem;"></i></button>
                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="pasteColor(block.content, 'code_color')" title="Dán"><i class="fas fa-paste" style="font-size: 0.6rem;"></i></button>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.8rem;">Cỡ chữ Mã Code</label>
                    <input type="number" x-model="block.content.code_font_size" class="form-control form-control-sm border-0 bg-light py-2 shadow-sm rounded-2 text-center fw-bold">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.8rem;">Màu Mô tả mã</label>
                    <div class="d-flex align-items-center bg-light rounded-2 p-1 gap-1 shadow-sm border" style="height: 38px;">
                        <input type="color" x-model="block.content.desc_color" class="form-control-color border-0 bg-transparent p-0 ms-1" style="width: 24px; height: 24px;">
                        <input type="text" x-model="block.content.desc_color" class="form-control form-control-sm border-0 bg-transparent p-0 fs-7 fw-bold px-1 flex-grow-1">
                        <div class="d-flex gap-1 ms-auto pe-1 border-start ps-1">
                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="copyColor(block.content.desc_color)" title="Copy"><i class="fas fa-copy" style="font-size: 0.6rem;"></i></button>
                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="pasteColor(block.content, 'desc_color')" title="Dán"><i class="fas fa-paste" style="font-size: 0.6rem;"></i></button>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.8rem;">Cỡ chữ mô tả</label>
                    <input type="number" x-model="block.content.desc_font_size" class="form-control form-control-sm border-0 bg-light py-2 shadow-sm rounded-2 text-center fw-bold">
                </div>

                <div class="col-md-6 pt-2 border-top">
                     <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.75rem;">Màu sắc trang trí (Accent)</label>
                     <div class="d-flex align-items-center bg-light rounded-2 p-1 gap-1 shadow-sm border" style="height: 38px;">
                        <input type="color" x-model="block.content.accent_color" class="form-control-color border-0 bg-transparent p-0 ms-1" style="width: 24px; height: 24px;">
                        <input type="text" x-model="block.content.accent_color" class="form-control form-control-sm border-0 bg-transparent p-0 fs-7 fw-bold px-1 flex-grow-1">
                        <div class="d-flex gap-1 ms-auto pe-1 border-start ps-1">
                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="copyColor(block.content.accent_color)" title="Copy"><i class="fas fa-copy" style="font-size: 0.6rem;"></i></button>
                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="pasteColor(block.content, 'accent_color')" title="Dán"><i class="fas fa-paste" style="font-size: 0.6rem;"></i></button>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 pt-2 border-top">
                     <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.75rem;">Màu Icon sao chép & Chi tiết</label>
                     <div class="d-flex align-items-center bg-light rounded-2 p-1 gap-1 shadow-sm border" style="height: 38px;">
                        <input type="color" x-model="block.content.link_icon_color" class="form-control-color border-0 bg-transparent p-0 ms-1" style="width: 24px; height: 24px;">
                        <input type="text" x-model="block.content.link_icon_color" class="form-control form-control-sm border-0 bg-transparent p-0 fs-7 fw-bold px-1 flex-grow-1">
                        <div class="d-flex gap-1 ms-auto pe-1 border-start ps-1">
                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="copyColor(block.content.link_icon_color)" title="Copy"><i class="fas fa-copy" style="font-size: 0.6rem;"></i></button>
                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="pasteColor(block.content, 'link_icon_color')" title="Dán"><i class="fas fa-paste" style="font-size: 0.6rem;"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- CARD 3: BỐ CỤC & KHOẢNG CÁCH --}}
        <div class="bg-white p-3 border rounded-4 mb-3 shadow-sm">
            <div class="d-flex align-items-center mb-3">
                <div class="bg-dark bg-opacity-10 p-2 rounded-3 me-2">
                    <i class="fas fa-vector-square text-dark"></i>
                </div>
                <h6 class="fw-bold text-dark mb-0 small text-uppercase" style="letter-spacing: 0.5px;">Bố cục & Khoảng cách</h6>
            </div>

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.8rem;">Chiều rộng tối đa</label>
                    <input type="text" x-model="block.content.container_max_width" class="form-control form-control-sm border-0 bg-light py-2 shadow-sm rounded-2 fw-bold" placeholder="1200px">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.8rem;">Đổ bóng khung</label>
                    <select x-model="block.content.box_shadow" class="form-select form-select-sm border-0 bg-light py-2 shadow-sm rounded-2 fw-bold">
                        <option value="none">Không đổ bóng</option>
                        <option value="shadow-sm">Đổ bóng nhẹ</option>
                        <option value="shadow">Đổ bóng vừa</option>
                        <option value="shadow-lg">Đổ bóng mạnh</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.8rem;">Đổ bóng ITEM</label>
                    <select x-model="block.content.item_box_shadow" class="form-select form-select-sm border-0 bg-light py-2 shadow-sm rounded-2 fw-bold">
                        <option value="none">Không đổ bóng</option>
                        <option value="shadow-sm">Đổ bóng nhẹ</option>
                        <option value="shadow">Đổ bóng vừa</option>
                        <option value="shadow-lg">Đổ bóng mạnh</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.8rem;">Bo góc khung</label>
                    <select x-model="block.content.border_radius" class="form-select form-select-sm border-0 bg-light py-2 shadow-sm rounded-2 fw-bold" style="height: 38px;">
                        <option value="0">0px</option>
                        <option value="8px">8px</option>
                        <option value="12px">12px</option>
                        <option value="16px">16px (Chuẩn)</option>
                        <option value="24px">24px</option>
                        <option value="32px">32px</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.8rem;">Bo góc ITEM</label>
                    <select x-model="block.content.item_border_radius" class="form-select form-select-sm border-0 bg-light py-2 shadow-sm rounded-2 fw-bold" style="height: 38px;">
                        <option value="0">0px</option>
                        <option value="8px">8px</option>
                        <option value="12px">12px (Chuẩn)</option>
                        <option value="16px">16px</option>
                        <option value="20px">20px</option>
                        <option value="24px">24px</option>
                    </select>
                </div>
                <div class="col-md-6 text-product-category-admin">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.8rem;">Màu nền khung</label>
                    <div class="d-flex align-items-center bg-light rounded-2 p-1 gap-1 shadow-sm border" style="height: 38px;">
                        <input type="color" x-model="block.content.card_bg_color" class="form-control-color border-0 bg-transparent p-0 ms-1" style="width: 24px; height: 24px;">
                        <input type="text" x-model="block.content.card_bg_color" class="form-control form-control-sm border-0 bg-transparent p-0 fs-7 fw-bold px-1 flex-grow-1">
                        <div class="d-flex gap-1 ms-auto pe-1 border-start ps-1">
                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="copyColor(block.content.card_bg_color)" title="Copy"><i class="fas fa-copy" style="font-size: 0.6rem;"></i></button>
                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="pasteColor(block.content, 'card_bg_color')" title="Dán"><i class="fas fa-paste" style="font-size: 0.6rem;"></i></button>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.65rem;">Lề ngoài TRÊN (px)</label>
                    <input type="number" x-model="block.content.margin_top" class="form-control form-control-sm border-0 bg-light py-2 shadow-sm rounded-2 text-center fw-bold" style="height: 38px;">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.65rem;">Lề ngoài DƯỚI (px)</label>
                    <input type="number" x-model="block.content.margin_bottom" class="form-control form-control-sm border-0 bg-light py-2 shadow-sm rounded-2 text-center fw-bold" style="height: 38px;">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.65rem;">Lề ngoài TRÁI (px)</label>
                    <input type="number" x-model="block.content.margin_left" class="form-control form-control-sm border-0 bg-light py-2 shadow-sm rounded-2 text-center fw-bold" style="height: 38px;">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.65rem;">Lề ngoài PHẢI (px)</label>
                    <input type="number" x-model="block.content.margin_right" class="form-control form-control-sm border-0 bg-light py-2 shadow-sm rounded-2 text-center fw-bold" style="height: 38px;">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.65rem;">Lề trong TRÊN (px)</label>
                    <input type="number" x-model="block.content.padding_top" class="form-control form-control-sm border-0 bg-light py-2 shadow-sm rounded-2 text-center fw-bold" style="height: 38px;">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.65rem;">Lề trong DƯỚI (px)</label>
                    <input type="number" x-model="block.content.padding_bottom" class="form-control form-control-sm border-0 bg-light py-2 shadow-sm rounded-2 text-center fw-bold" style="height: 38px;">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.65rem;">Lề trong TRÁI (px)</label>
                    <input type="number" x-model="block.content.padding_left" class="form-control form-control-sm border-0 bg-light py-2 shadow-sm rounded-2 text-center fw-bold" style="height: 38px;">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.65rem;">Lề trong PHẢI (px)</label>
                    <input type="number" x-model="block.content.padding_right" class="form-control form-control-sm border-0 bg-light py-2 shadow-sm rounded-2 text-center fw-bold" style="height: 38px;">
                </div>
            </div>
        </div>

        {{-- CARD 4: TÙY CHỈNH POPUP CHI TIẾT --}}
        <div class="bg-white p-3 border rounded-4 mb-3 shadow-sm text-product-category-admin">
            <div class="d-flex align-items-center mb-3">
                <div class="bg-warning bg-opacity-10 p-2 rounded-3 me-2">
                    <i class="fas fa-window-restore text-warning"></i>
                </div>
                <h6 class="fw-bold text-dark mb-0 small text-uppercase" style="letter-spacing: 0.5px;">Tùy chỉnh Popup Chi tiết</h6>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.8rem;">Tiêu đề Popup</label>
                    <input type="text" x-model="block.content.popup_title" class="form-control form-control-sm border-0 bg-light py-2 shadow-sm rounded-2 fw-bold" placeholder="Chi tiết ưu đãi">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.8rem;">Chữ hiển thị trên Nút COPY</label>
                    <input type="text" x-model="block.content.popup_button_text" class="form-control form-control-sm border-0 bg-light py-2 shadow-sm rounded-2 fw-bold" placeholder="SAO CHÉP MÃ NGAY">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.8rem;">Màu chủ đạo Popup</label>
                    <div class="d-flex align-items-center bg-light rounded-2 p-1 gap-1 shadow-sm border" style="height: 38px;">
                        <input type="color" x-model="block.content.popup_accent_color" class="form-control-color border-0 bg-transparent p-0 ms-1" style="width: 24px; height: 24px;">
                        <input type="text" x-model="block.content.popup_accent_color" class="form-control form-control-sm border-0 bg-transparent p-0 fs-7 fw-bold px-1 flex-grow-1">
                        <div class="d-flex gap-1 ms-auto pe-1 border-start ps-1">
                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="copyColor(block.content.popup_accent_color)" title="Copy"><i class="fas fa-copy" style="font-size: 0.6rem;"></i></button>
                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="pasteColor(block.content, 'popup_accent_color')" title="Dán"><i class="fas fa-paste" style="font-size: 0.6rem;"></i></button>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.8rem;">Màu chữ tiêu đề Popup</label>
                    <div class="d-flex align-items-center bg-light rounded-2 p-1 gap-1 shadow-sm border" style="height: 38px;">
                        <input type="color" x-model="block.content.popup_title_color" class="form-control-color border-0 bg-transparent p-0 ms-1" style="width: 24px; height: 24px;">
                        <input type="text" x-model="block.content.popup_title_color" class="form-control form-control-sm border-0 bg-transparent p-0 fs-7 fw-bold px-1 flex-grow-1">
                        <div class="d-flex gap-1 ms-auto pe-1 border-start ps-1">
                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="copyColor(block.content.popup_title_color)" title="Copy"><i class="fas fa-copy" style="font-size: 0.6rem;"></i></button>
                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="pasteColor(block.content, 'popup_title_color')" title="Dán"><i class="fas fa-paste" style="font-size: 0.6rem;"></i></button>
                        </div>
                    </div>
                </div>
        </div>
    </div>

{{-- CARD 5: TÙY CHỈNH POPUP DANH SÁCH --}}
<div class="bg-white p-3 border rounded-4 mb-3 shadow-sm text-product-category-admin">
    <div class="d-flex align-items-center mb-3">
        <div class="bg-info bg-opacity-10 p-2 rounded-3 me-2">
            <i class="fas fa-list-ul text-info"></i>
        </div>
        <h6 class="fw-bold text-dark mb-0 small text-uppercase" style="letter-spacing: 0.5px;">Tùy chỉnh Popup Danh sách</h6>
    </div>

    <div class="row g-3">
        <div class="col-md-4">
            <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.8rem;">Tiêu đề Popup Danh sách</label>
            <input type="text" x-model="block.content.list_popup_title" class="form-control form-control-sm border-0 bg-light py-2 shadow-sm rounded-2 fw-bold" placeholder="Tất cả mã giảm giá">
        </div>
        <div class="col-md-4">
            <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.8rem;">Gợi ý ô tìm kiếm</label>
            <input type="text" x-model="block.content.list_popup_search_placeholder" class="form-control form-control-sm border-0 bg-light py-2 shadow-sm rounded-2 fw-bold" placeholder="Tìm kiếm mã hoặc ưu đãi...">
        </div>
        <div class="col-md-6 border-top pt-3">
            <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.75rem;">Màu chủ đạo Popup Danh sách</label>
            <div class="d-flex align-items-center bg-light rounded-2 p-1 gap-1 shadow-sm border" style="height: 38px;">
                <input type="color" x-model="block.content.list_popup_accent_color" class="form-control-color border-0 bg-transparent p-0 ms-1" style="width: 24px; height: 24px;">
                <input type="text" x-model="block.content.list_popup_accent_color" class="form-control form-control-sm border-0 bg-transparent p-0 fs-7 fw-bold px-1 flex-grow-1">
                <div class="d-flex gap-1 ms-auto pe-1 border-start ps-1">
                    <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="copyColor(block.content.list_popup_accent_color)" title="Copy"><i class="fas fa-copy" style="font-size: 0.6rem;"></i></button>
                    <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="pasteColor(block.content, 'list_popup_accent_color')" title="Dán"><i class="fas fa-paste" style="font-size: 0.6rem;"></i></button>
                </div>
            </div>
        </div>
        <div class="col-md-6 border-top pt-3">
            <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.75rem;">Màu Icon sao chép & Chi tiết (Danh sách)</label>
            <div class="d-flex align-items-center bg-light rounded-2 p-1 gap-1 shadow-sm border" style="height: 38px;">
                <input type="color" x-model="block.content.list_popup_icon_color" class="form-control-color border-0 bg-transparent p-0 ms-1" style="width: 24px; height: 24px;">
                <input type="text" x-model="block.content.list_popup_icon_color" class="form-control form-control-sm border-0 bg-transparent p-0 fs-7 fw-bold px-1 flex-grow-1">
                <div class="d-flex gap-1 ms-auto pe-1 border-start ps-1">
                    <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="copyColor(block.content.list_popup_icon_color)" title="Copy"><i class="fas fa-copy" style="font-size: 0.6rem;"></i></button>
                    <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="pasteColor(block.content, 'list_popup_icon_color')" title="Dán"><i class="fas fa-paste" style="font-size: 0.6rem;"></i></button>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</template>

<style>
.text-product-category-admin input:not([type="checkbox"]):not([type="radio"]):focus {
    box-shadow: none !important;
    border-color: #3b82f6 !important;
    background-color: #fff !important;
}
</style>
