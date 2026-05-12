<template x-if="block.type === 'product_category_grid'">
    <div class="mt-2 text-product-category-admin"
        x-init="
            // Initialize banner_images array if not present
            if (!block.content.banner_images) {
                block.content.banner_images = [];
            }
            // Migrate legacy single banner_image to array
            if (block.content.banner_image && block.content.banner_images.length === 0) {
                block.content.banner_images.push({ url: block.content.banner_image });
                delete block.content.banner_image;
            }
            
            // Set default text and structure if missing
            if (!block.content.title) block.content.title = 'DANH MỤC SẢN PHẨM';
            if (!block.content.view_all_text) block.content.view_all_text = 'XEM TẤT CẢ';
            if (!block.content.items_limit) block.content.items_limit = 8;
            if (!block.content.items_per_row) block.content.items_per_row = 4;
            
            // Set default colors and typography if missing
            if (!block.content.accent_color) block.content.accent_color = '#e31824';
            if (!block.content.btn_bg_color) block.content.btn_bg_color = block.content.accent_color;
            if (!block.content.btn_text_color) block.content.btn_text_color = '#ffffff';
            if (!block.content.btn_font_size) block.content.btn_font_size = 13;
            if (!block.content.btn_border_radius) block.content.btn_border_radius = 50;
            
            if (!block.content.view_all_bg_color) block.content.view_all_bg_color = '#ffffff';
            if (!block.content.view_all_text_color) block.content.view_all_text_color = block.content.accent_color;
            if (!block.content.view_all_hover_color) block.content.view_all_hover_color = block.content.accent_color;
            if (!block.content.view_all_font_size) block.content.view_all_font_size = 11;
            if (!block.content.view_all_border_radius) block.content.view_all_border_radius = 50;

            if (!block.content.title_color) block.content.title_color = '#001d3d';
            if (!block.content.title_font_size) block.content.title_font_size = 20;

            if (!block.content.product_name_color) block.content.product_name_color = '#0f172a';
            if (!block.content.product_name_font_size) block.content.product_name_font_size = 15;
            
            if (!block.content.product_price_color) block.content.product_price_color = '#dc3545';
            if (!block.content.product_price_font_size) block.content.product_price_font_size = 17;

            // Frame & Display Defaults
            if (!block.content.container_max_width) block.content.container_max_width = '1200px';
            if (!block.content.box_shadow) block.content.box_shadow = 'shadow-sm';
            if (!block.content.frame_border_radius) block.content.frame_border_radius = '8px';
            if (!block.content.padding_top) block.content.padding_top = '30px';
            if (!block.content.padding_bottom) block.content.padding_bottom = '30px';
            if (!block.content.padding_left) block.content.padding_left = '0px';
            if (!block.content.padding_right) block.content.padding_right = '0px';

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

        {{-- CARD 1: CÔNG CỤ CHUNG --}}
        <div class="bg-white p-3 border rounded-4 mb-3 shadow-sm">
            <div class="d-flex align-items-center mb-3">
                <div class="bg-primary bg-opacity-10 p-2 rounded-3 me-2">
                    <i class="fas fa-cog text-primary"></i>
                </div>
                <h6 class="fw-bold text-dark mb-0 small text-uppercase" style="letter-spacing: 0.5px;">Cấu hình khối sản phẩm</h6>
            </div>

            <div class="row g-3">
                <div class="col-md-5">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.8rem;">Tiêu đề chính</label>
                    <input type="text" x-model="block.content.title" class="form-control form-control-sm border-0 bg-light py-2 shadow-sm rounded-2" placeholder="DANH MỤC SẢN PHẨM">
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

                <div class="col-md-3">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.8rem;">Chữ Link "Xem tất cả"</label>
                    <input type="text" x-model="block.content.view_all_text" class="form-control form-control-sm border-0 bg-light py-2 shadow-sm rounded-2" placeholder="XEM TẤT CẢ">
                </div>
                <div class="col-md-5">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.8rem;">Đường dẫn Link</label>
                    <input type="text" x-model="block.content.view_all_link" class="form-control form-control-sm border-0 bg-light py-2 shadow-sm rounded-2" placeholder="/danh-muc">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.8rem;">Màu trang trí (Accent)</label>
                    <div class="d-flex align-items-center bg-light rounded-2 p-1 gap-1 shadow-sm border" style="height: 38px;">
                        <input type="color" x-model="block.content.accent_color" class="form-control-color border-0 bg-transparent p-0 ms-1" style="width: 24px; height: 24px;">
                        <input type="text" x-model="block.content.accent_color" class="form-control form-control-sm border-0 bg-transparent p-0 fs-7 fw-bold px-1 flex-grow-1">
                        <div class="d-flex gap-1 ms-auto pe-1 border-start ps-1">
                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="copyColor(block.content.accent_color)"><i class="fas fa-copy" style="font-size: 0.6rem;"></i></button>
                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="pasteColor(block.content, 'accent_color')"><i class="fas fa-paste" style="font-size: 0.6rem;"></i></button>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 border-top pt-3">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.8rem;">Nguồn dữ liệu Sản phẩm</label>
                    <select x-model="block.content.category_id" class="form-select form-select-sm border-0 bg-light py-2 shadow-sm rounded-2">
                        <option value="">-- Tất cả sản phẩm --</option>
                        @foreach(\App\Models\ProductCategory::all() as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 border-top pt-3">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.8rem;">Số lượng (Limit)</label>
                    <input type="number" x-model="block.content.items_limit" class="form-control form-control-sm border-0 bg-light py-2 shadow-sm rounded-2 text-center fw-bold">
                </div>
                <div class="col-md-3 border-top pt-3">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.8rem;">Số cột (Grid)</label>
                    <select x-model="block.content.items_per_row" class="form-select form-select-sm border-0 bg-light py-2 shadow-sm rounded-2">
                        <option value="2">2 cột</option>
                        <option value="3">3 cột</option>
                        <option value="4">4 cột</option>
                        <option value="6">6 cột</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- CARD 2: THIẾT KẾ NÚT --}}
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <div class="bg-white p-3 border rounded-4 shadow-sm h-100">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center">
                            <div class="bg-success bg-opacity-10 p-2 rounded-3 me-2">
                                <i class="fas fa-shopping-cart text-success"></i>
                            </div>
                            <h6 class="fw-bold text-dark mb-0 small text-uppercase" style="letter-spacing: 0.5px;">Nút Đặt Hàng</h6>
                        </div>
                        <button type="button" class="btn btn-sm btn-light py-1 px-2 border rounded-pill" style="font-size: 0.55rem;" 
                            @click="block.content.btn_bg_color = block.content.accent_color">MÀU ACCENT</button>
                    </div>
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.8rem;">Màu nền</label>
                        <div class="d-flex align-items-center bg-light rounded-2 p-1 gap-1 shadow-sm border" style="height: 38px;">
                            <input type="color" x-model="block.content.btn_bg_color" class="form-control-color border-0 bg-transparent p-0 ms-1" style="width: 24px; height: 24px;">
                            <input type="text" x-model="block.content.btn_bg_color" class="form-control form-control-sm border-0 bg-transparent p-0 fs-7 px-1 flex-grow-1">
                            <div class="d-flex gap-1 ms-auto pe-1 border-start ps-1">
                                <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="copyColor(block.content.btn_bg_color)" title="Copy"><i class="fas fa-copy" style="font-size: 0.6rem;"></i></button>
                                <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="pasteColor(block.content, 'btn_bg_color')" title="Dán"><i class="fas fa-paste" style="font-size: 0.6rem;"></i></button>
                            </div>
                        </div>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.8rem;">Màu chữ</label>
                        <div class="d-flex align-items-center bg-light rounded-2 p-1 gap-1 shadow-sm border" style="height: 38px;">
                            <input type="color" x-model="block.content.btn_text_color" class="form-control-color border-0 bg-transparent p-0 ms-1" style="width: 24px; height: 24px;">
                            <input type="text" x-model="block.content.btn_text_color" class="form-control form-control-sm border-0 bg-transparent p-0 fs-7 px-1 flex-grow-1">
                            <div class="d-flex gap-1 ms-auto pe-1 border-start ps-1">
                                <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="copyColor(block.content.btn_text_color)" title="Copy"><i class="fas fa-copy" style="font-size: 0.6rem;"></i></button>
                                <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="pasteColor(block.content, 'btn_text_color')" title="Dán"><i class="fas fa-paste" style="font-size: 0.6rem;"></i></button>
                            </div>
                        </div>
                        </div>
                        <div class="col-6 mt-1">
                            <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.8rem;">Bo góc (Radius)</label>
                            <input type="number" x-model="block.content.btn_border_radius" class="form-control form-control-sm border-0 bg-light py-2 shadow-sm rounded-2 text-center">
                        </div>
                        <div class="col-6 mt-1">
                            <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.8rem;">Cỡ chữ</label>
                            <input type="number" x-model="block.content.btn_font_size" class="form-control form-control-sm border-0 bg-light py-2 shadow-sm rounded-2 text-center">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="bg-white p-3 border rounded-4 shadow-sm h-100">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center">
                            <div class="bg-info bg-opacity-10 p-2 rounded-3 me-2">
                                <i class="fas fa-link text-info"></i>
                            </div>
                            <h6 class="fw-bold text-dark mb-0 small text-uppercase" style="letter-spacing: 0.5px;">Nút Xem Tất Cả</h6>
                        </div>
                        <button type="button" class="btn btn-sm btn-light py-1 px-2 border rounded-pill" style="font-size: 0.55rem;" 
                            @click="block.content.view_all_bg_color = block.content.accent_color">MÀU ACCENT</button>
                    </div>
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.8rem;">Màu nền</label>
                        <div class="d-flex align-items-center bg-light rounded-2 p-1 gap-1 shadow-sm border" style="height: 38px;">
                            <input type="color" x-model="block.content.view_all_bg_color" class="form-control-color border-0 bg-transparent p-0 ms-1" style="width: 24px; height: 24px;">
                            <input type="text" x-model="block.content.view_all_bg_color" class="form-control form-control-sm border-0 bg-transparent p-0 fs-7 px-1 flex-grow-1">
                            <div class="d-flex gap-1 ms-auto pe-1 border-start ps-1">
                                <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="copyColor(block.content.view_all_bg_color)" title="Copy"><i class="fas fa-copy" style="font-size: 0.6rem;"></i></button>
                                <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="pasteColor(block.content, 'view_all_bg_color')" title="Dán"><i class="fas fa-paste" style="font-size: 0.6rem;"></i></button>
                            </div>
                        </div>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.8rem;">Màu chữ / viền</label>
                        <div class="d-flex align-items-center bg-light rounded-2 p-1 gap-1 shadow-sm border" style="height: 38px;">
                            <input type="color" x-model="block.content.view_all_text_color" class="form-control-color border-0 bg-transparent p-0 ms-1" style="width: 24px; height: 24px;">
                            <input type="text" x-model="block.content.view_all_text_color" class="form-control form-control-sm border-0 bg-transparent p-0 fs-7 px-1 flex-grow-1">
                            <div class="d-flex gap-1 ms-auto pe-1 border-start ps-1">
                                <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="copyColor(block.content.view_all_text_color)" title="Copy"><i class="fas fa-copy" style="font-size: 0.6rem;"></i></button>
                                <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="pasteColor(block.content, 'view_all_text_color')" title="Dán"><i class="fas fa-paste" style="font-size: 0.6rem;"></i></button>
                            </div>
                        </div>
                        </div>
                        <div class="col-6 mt-1">
                            <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.8rem;">Bo góc (Radius)</label>
                            <input type="number" x-model="block.content.view_all_border_radius" class="form-control form-control-sm border-0 bg-light py-2 shadow-sm rounded-2 text-center">
                        </div>
                        <div class="col-6 mt-1">
                            <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.8rem;">Cỡ chữ</label>
                            <input type="number" x-model="block.content.view_all_font_size" class="form-control form-control-sm border-0 bg-light py-2 shadow-sm rounded-2 text-center">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- CARD 3: TYPOGRAPHY SẢN PHẨM --}}
        <div class="bg-white p-3 border rounded-4 mb-3 shadow-sm">
            <div class="d-flex align-items-center mb-3">
                <div class="bg-secondary bg-opacity-10 p-2 rounded-3 me-2">
                    <i class="fas fa-font text-secondary"></i>
                </div>
                <h6 class="fw-bold text-dark mb-0 small text-uppercase" style="letter-spacing: 0.5px;">Typography Sản Phẩm</h6>
            </div>
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.8rem;">Màu Tên Sản Phẩm</label>
                    <div class="d-flex align-items-center bg-light rounded-2 p-1 gap-1 shadow-sm border" style="height: 38px;">
                        <input type="color" x-model="block.content.product_name_color" class="form-control-color border-0 bg-transparent p-0 ms-1" style="width: 24px; height: 24px;">
                        <input type="text" x-model="block.content.product_name_color" class="form-control form-control-sm border-0 bg-transparent p-0 fs-7 fw-bold px-1 flex-grow-1">
                        <div class="d-flex gap-1 ms-auto pe-1 border-start ps-1">
                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="copyColor(block.content.product_name_color)" title="Copy"><i class="fas fa-copy" style="font-size: 0.6rem;"></i></button>
                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="pasteColor(block.content, 'product_name_color')" title="Dán"><i class="fas fa-paste" style="font-size: 0.6rem;"></i></button>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.8rem;">Cỡ chữ tên SP</label>
                    <input type="number" x-model="block.content.product_name_font_size" class="form-control form-control-sm border-0 bg-light py-2 shadow-sm rounded-2 text-center fw-bold">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.8rem;">Màu Giá Sản Phẩm</label>
                    <div class="d-flex align-items-center bg-light rounded-2 p-1 gap-1 shadow-sm border" style="height: 38px;">
                        <input type="color" x-model="block.content.product_price_color" class="form-control-color border-0 bg-transparent p-0 ms-1" style="width: 24px; height: 24px;">
                        <input type="text" x-model="block.content.product_price_color" class="form-control form-control-sm border-0 bg-transparent p-0 fs-7 fw-bold px-1 flex-grow-1">
                        <div class="d-flex gap-1 ms-auto pe-1 border-start ps-1">
                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="copyColor(block.content.product_price_color)" title="Copy"><i class="fas fa-copy" style="font-size: 0.6rem;"></i></button>
                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="pasteColor(block.content, 'product_price_color')" title="Dán"><i class="fas fa-paste" style="font-size: 0.6rem;"></i></button>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.8rem;">Cỡ chữ giá SP</label>
                    <input type="number" x-model="block.content.product_price_font_size" class="form-control form-control-sm border-0 bg-light py-2 shadow-sm rounded-2 text-center fw-bold">
                </div>
            </div>
        </div>

        {{-- CARD 4: KHUNG & HIỂN THỊ --}}
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
                    <input type="text" x-model="block.content.container_max_width" class="form-control form-control-sm border-0 bg-light py-2 shadow-sm rounded-2" placeholder="1200px">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.8rem;">Đổ bóng Item</label>
                    <select x-model="block.content.box_shadow" class="form-select form-select-sm border-0 bg-light py-2 shadow-sm rounded-2">
                        <option value="none">Không có</option>
                        <option value="shadow-sm">Nhẹ</option>
                        <option value="shadow">Vừa</option>
                        <option value="shadow-lg">Đậm</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.8rem;">Bo góc Item</label>
                    <select x-model="block.content.frame_border_radius" class="form-select form-select-sm border-0 bg-light py-2 shadow-sm rounded-2">
                        <option value="0">0px</option>
                        <option value="8px">8px</option>
                        <option value="16px">16px</option>
                        <option value="24px">24px</option>
                    </select>
                </div>

                <template x-for="p in [
                    {key: 'padding_top', label: 'Padding Trên'},
                    {key: 'padding_bottom', label: 'Padding Dưới'},
                    {key: 'padding_left', label: 'Padding Trái'},
                    {key: 'padding_right', label: 'Padding Phải'}
                ]" :key="p.key">
                    <div class="col-md-3">
                        <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.8rem;" x-text="p.label"></label>
                        <input type="text" x-model="block.content[p.key]" class="form-control form-control-sm border-0 bg-light py-2 shadow-sm rounded-2 text-center">
                    </div>
                </template>
            </div>
        </div>

        {{-- CARD 5: BANNER --}}
        <div class="bg-white p-3 border rounded-4 mb-3 shadow-sm">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center">
                    <div class="bg-warning bg-opacity-10 p-2 rounded-3 me-2">
                        <i class="fas fa-image text-warning"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-0 small text-uppercase" style="letter-spacing: 0.5px;">Banners dọc cạnh sản phẩm</h6>
                </div>
                <button type="button" class="btn btn-sm btn-outline-warning rounded-pill px-3 fw-bold" style="font-size: 0.6rem;"
                    @click="if (!block.content.banner_images) block.content.banner_images = []; block.content.banner_images.push({ url: '' });">
                    <i class="fas fa-plus me-1"></i> THÊM BANNER
                </button>
            </div>

            <template x-for="(banner, bi) in block.content.banner_images" :key="bi">
                <div class="d-flex align-items-center gap-3 p-2 bg-light rounded-3 mb-2 border shadow-xs">
                    <div class="flex-shrink-0" style="width: 80px; height: 50px;">
                        <div class="w-100 h-100 rounded border bg-white d-flex align-items-center justify-content-center overflow-hidden cursor-pointer shadow-sm hover-opacity-75 transition-all"
                             @click="openMediaPicker(index, 'banner_images.' + bi + '.url')">
                            <template x-if="banner.url">
                                <img :src="banner.url" class="w-100 h-100" style="object-fit: cover;">
                            </template>
                            <template x-if="!banner.url">
                                <i class="fas fa-image text-muted opacity-25" style="font-size: 1.2rem;"></i>
                            </template>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <div class="input-group input-group-sm rounded-2 overflow-hidden border bg-white">
                            <span class="input-group-text bg-white border-0 fw-bold text-muted font-monospace" style="font-size: 0.7rem;">#<span x-text="bi+1"></span></span>
                            <input type="text" x-model="banner.url" class="form-control border-0 bg-transparent px-2" placeholder="URL banner cạnh sản phẩm...">
                            <button class="btn btn-light border-0 bg-white" type="button" @click="openMediaPicker(index, 'banner_images.' + bi + '.url')">
                                <i class="fas fa-folder-open text-muted"></i>
                            </button>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm text-danger p-0 px-2 hover-scale transition-all" @click="block.content.banner_images.splice(bi, 1)">
                        <i class="fas fa-times-circle fs-5"></i>
                    </button>
                </div>
            </template>
            
            <template x-if="!block.content.banner_images || block.content.banner_images.length === 0">
                <div class="text-center py-4 border rounded-3 border-dashed bg-light opacity-75">
                    <i class="fas fa-bullhorn text-muted mb-2 d-block fs-3"></i>
                    <span class="small text-muted text-uppercase fw-bold">Chưa có banner nào</span>
                </div>
            </template>
        </div>
    </div>
</template>

<style>
.border-dashed { border-style: dashed !important; }
.shadow-xs { box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
.hover-opacity-75:hover { opacity: 0.75; }
.hover-scale:hover { transform: scale(1.1); }
</style>


<style>
.border-dashed { border: 2px dashed #e2e8f0 !important; }
.hover-shadow:hover { box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1) !important; }
.hover-scale:hover { transform: scale(1.1); transition: transform 0.2s; }
.btn-white { background-color: #fff; color: #64748b; }
.btn-white:hover { background-color: #f8fafc; color: #1e293b; }
</style>


<style>
.border-dashed {
    border: 2px dashed #cbd5e1 !important;
}
.text-product-category-admin input:not([type="checkbox"]):not([type="radio"]):focus, 
.text-product-category-admin select:focus {
    box-shadow: none !important;
    border-color: #3b82f6 !important;
    background-color: #fff !important;
}
</style>
