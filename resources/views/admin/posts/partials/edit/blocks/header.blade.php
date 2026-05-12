<template x-if="block.type === 'header'">
    <div class="mt-2 text-header-admin"
        x-init="
            if (!block.content) block.content = {};
            if (!block.content.buttons) block.content.buttons = [];
            
            // Initialization
            if (!block.content.branding_bg) block.content.branding_bg = '#ffffff';
            if (!block.content.header_height) block.content.header_height = 64;
            
            if (typeof block.content.branding_padding_top === 'undefined') block.content.branding_padding_top = 16;
            if (typeof block.content.branding_padding_bottom === 'undefined') block.content.branding_padding_bottom = 16;
            if (typeof block.content.branding_padding_left === 'undefined') block.content.branding_padding_left = 0;
            if (typeof block.content.branding_padding_right === 'undefined') block.content.branding_padding_right = 0;

            if (typeof block.content.menu_padding_top === 'undefined') block.content.menu_padding_top = 5;
            if (typeof block.content.menu_padding_bottom === 'undefined') block.content.menu_padding_bottom = 5;
            if (typeof block.content.menu_padding_left === 'undefined') block.content.menu_padding_left = 0;
            if (typeof block.content.menu_padding_right === 'undefined') block.content.menu_padding_right = 0;
            if (!block.content.menu_bg) block.content.menu_bg = '#ffffff';
            if (!block.content.menu_alignment) block.content.menu_alignment = 'center';

            if (typeof block.content.show_search === 'undefined') block.content.show_search = true;
            if (!block.content.search_placeholder) block.content.search_placeholder = 'Tìm kiếm sản phẩm...';

            @foreach(['1','2','3'] as $lv)
                if (!block.content.menu_l{{ $lv }}_color) block.content.menu_l{{ $lv }}_color = '#333333';
                if (!block.content.menu_l{{ $lv }}_hover_color) block.content.menu_l{{ $lv }}_hover_color = '#0cebeb';
                if (!block.content.menu_l{{ $lv }}_size) block.content.menu_l{{ $lv }}_size = {{ $lv == 1 ? 14 : ($lv == 2 ? 14 : 13) }};
                if (!block.content.menu_l{{ $lv }}_weight) block.content.menu_l{{ $lv }}_weight = '500';
                if (typeof block.content.menu_l{{ $lv }}_bg === 'undefined') block.content.menu_l{{ $lv }}_bg = '#ffffff';
            @endforeach

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

        {{-- CARD 1: BRANDING --}}
        <div class="bg-white p-3 border rounded-4 mb-3 shadow-sm">
            <div class="d-flex align-items-center mb-3">
                <div class="bg-primary bg-opacity-10 p-2 rounded-3 me-2">
                    <i class="fas fa-certificate text-primary"></i>
                </div>
                <h6 class="fw-bold text-dark mb-0 small text-uppercase" style="letter-spacing: 0.5px;">Cấu hình nhận diện</h6>
            </div>

            <div class="row g-3">
                {{-- Preview Area --}}
                <div class="col-md-2">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.8rem;">Preview</label>
                    <div class="bg-light rounded-3 border d-flex align-items-center justify-content-center overflow-hidden position-relative shadow-sm" style="height: 100px; background-image: radial-gradient(#cbd5e1 1px, transparent 1px); background-size: 10px 10px;">
                        <template x-if="block.content.logo">
                            <div class="w-100 h-100 d-flex align-items-center justify-content-center p-2">
                                <img :src="block.content.logo" class="img-fluid" style="max-height: 80px; object-fit: contain;">
                                <button type="button" @click="block.content.logo = ''" class="btn btn-danger btn-sm p-0 position-absolute top-0 end-0 m-1 shadow-sm" style="width: 20px; height: 20px; font-size: 0.6rem; border-radius: 5px;">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </template>
                        <template x-if="!block.content.logo">
                            <div class="text-center">
                                <i class="fas fa-image text-muted opacity-25" style="font-size: 1.5rem;"></i>
                                <span class="d-block text-muted opacity-50" style="font-size: 0.6rem;">No logo</span>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="col-md-5">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.8rem;">Ảnh Logo</label>
                    <div class="input-group input-group-sm rounded-2 overflow-hidden border">
                        <input type="text" x-model="block.content.logo" class="form-control border-0 bg-light py-2" placeholder="Tải lên hoặc dán URL...">
                        <button class="btn btn-light border-0" type="button" @click="openMediaPicker(index, 'logo')" title="Chọn ảnh từ hệ thống">
                            <i class="fas fa-folder-open text-muted"></i>
                        </button>
                    </div>
                </div>

                <div class="col-md-5">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.8rem;">Liên kết Logo</label>
                    <input type="text" x-model="block.content.logo_link" class="form-control form-control-sm border-0 bg-light py-2 shadow-sm rounded-2" placeholder="/">
                </div>

                <div class="col-md-6 border-top pt-3">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.8rem;">Màu nền Header</label>
                    <div class="d-flex align-items-center bg-light rounded-2 p-1 gap-1 shadow-sm border" style="height: 38px;">
                        <input type="color" x-model="block.content.branding_bg" class="form-control-color border-0 bg-transparent p-0 ms-1" style="width: 24px; height: 24px;">
                        <input type="text" x-model="block.content.branding_bg" class="form-control form-control-sm border-0 bg-transparent p-0 fs-7 fw-bold px-1" placeholder="#ffffff">
                        <div class="d-flex gap-1 ms-auto pe-1">
                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="copyColor(block.content.branding_bg)" title="Copy màu"><i class="fas fa-copy" style="font-size: 0.6rem;"></i></button>
                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="pasteColor(block.content, 'branding_bg')" title="Paste màu"><i class="fas fa-paste" style="font-size: 0.6rem;"></i></button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 border-top pt-3">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.8rem;">Chiều cao tổng quát (px)</label>
                    <input type="number" x-model="block.content.header_height" class="form-control form-control-sm border-0 bg-light py-2 shadow-sm rounded-2">
                </div>


                <template x-for="p in [
                    {key: 'branding_padding_top', label: 'Padding Top'},
                    {key: 'branding_padding_bottom', label: 'Padding Bottom'},
                    {key: 'branding_padding_left', label: 'Padding Left'},
                    {key: 'branding_padding_right', label: 'Padding Right'}
                ]" :key="p.key">
                    <div class="col-md-3 col-6 border-top pt-2">
                        <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.8rem;" x-text="p.label"></label>
                        <input type="number" x-model="block.content[p.key]" class="form-control form-control-sm border-0 bg-light py-2 shadow-sm text-center">
                    </div>
                </template>
            </div>
        </div>

        {{-- CARD 2: MENU ZONE --}}
        <div class="bg-white p-3 border rounded-4 mb-3 shadow-sm">
            <div class="d-flex align-items-center mb-3">
                <div class="bg-warning bg-opacity-10 p-2 rounded-3 me-2">
                    <i class="fas fa-bars text-warning"></i>
                </div>
                <h6 class="fw-bold text-dark mb-0 small text-uppercase" style="letter-spacing: 0.5px;">Thiết lập Vùng Menu Cấp 1</h6>
            </div>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.8rem;">Căn lề danh mục</label>
                    <select x-model="block.content.menu_alignment" class="form-select form-select-sm border-0 bg-light py-2 shadow-sm">
                        <option value="start">Căn Trái</option>
                        <option value="center">Căn Giữa</option>
                        <option value="end">Căn Phải</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.8rem;">Màu nền dải Menu</label>
                    <div class="d-flex align-items-center bg-light rounded-2 p-1 gap-1 shadow-sm border" style="height: 38px;">
                        <input type="color" x-model="block.content.menu_bg" class="form-control-color border-0 bg-transparent p-0 ms-1" style="width: 24px; height: 24px;">
                        <input type="text" x-model="block.content.menu_bg" class="form-control form-control-sm border-0 bg-transparent p-0 fs-7 fw-bold px-1" placeholder="#ffffff">
                        <div class="d-flex gap-1 ms-auto pe-1">
                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="copyColor(block.content.menu_bg)" title="Copy màu"><i class="fas fa-copy" style="font-size: 0.6rem;"></i></button>
                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="pasteColor(block.content, 'menu_bg')" title="Paste màu"><i class="fas fa-paste" style="font-size: 0.6rem;"></i></button>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.8rem;">Khoảng cách (Gap)</label>
                    <input type="number" x-model="block.content.menu_gap" class="form-control form-control-sm border-0 bg-light py-2 shadow-sm text-center fw-bold">
                </div>
                <template x-for="p in [
                    {key: 'menu_padding_top', label: 'Padding Top'},
                    {key: 'menu_padding_bottom', label: 'Padding Bottom'},
                    {key: 'menu_padding_left', label: 'Padding Left'},
                    {key: 'menu_padding_right', label: 'Padding Right'}
                ]" :key="p.key">
                    <div class="col-md-3 col-6">
                        <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.8rem;" x-text="p.label"></label>
                        <input type="number" x-model="block.content[p.key]" class="form-control form-control-sm border-0 bg-light py-2 shadow-sm text-center">
                    </div>
                </template>



                <div class="col-md-6 mt-1">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.8rem;">Màu chính</label>
                    <div class="d-flex align-items-center bg-light rounded-2 p-1 gap-1 shadow-sm border" style="height: 38px;">
                        <input type="color" x-model="block.content.menu_l1_color" class="form-control-color border-0 bg-transparent p-0 ms-1" style="width: 24px; height: 24px;">
                        <input type="text" x-model="block.content.menu_l1_color" class="form-control form-control-sm border-0 bg-transparent p-0 fs-7 fw-bold px-1 flex-grow-1">
                        <div class="d-flex gap-1 ms-auto pe-1 border-start ps-1">
                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="copyColor(block.content.menu_l1_color)" title="Copy"><i class="fas fa-copy" style="font-size: 0.6rem;"></i></button>
                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="pasteColor(block.content, 'menu_l1_color')" title="Paste"><i class="fas fa-paste" style="font-size: 0.6rem;"></i></button>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mt-1">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.8rem;">Màu Hover</label>
                    <div class="d-flex align-items-center bg-light rounded-2 p-1 gap-1 shadow-sm border" style="height: 38px;">
                        <input type="color" x-model="block.content.menu_l1_hover_color" class="form-control-color border-0 bg-transparent p-0 ms-1" style="width: 24px; height: 24px;">
                        <input type="text" x-model="block.content.menu_l1_hover_color" class="form-control form-control-sm border-0 bg-transparent p-0 fs-7 fw-bold px-1 flex-grow-1">
                        <div class="d-flex gap-1 ms-auto pe-1 border-start ps-1">
                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="copyColor(block.content.menu_l1_hover_color)" title="Copy"><i class="fas fa-copy" style="font-size: 0.6rem;"></i></button>
                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="pasteColor(block.content, 'menu_l1_hover_color')" title="Paste"><i class="fas fa-paste" style="font-size: 0.6rem;"></i></button>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.8rem;">Cỡ chữ (px)</label>
                    <input type="number" x-model="block.content.menu_l1_size" class="form-control form-control-sm border-0 bg-light py-2 shadow-sm rounded-2 text-center fw-bold">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.8rem;">Độ dày (Weight)</label>
                    <select x-model="block.content.menu_l1_weight" class="form-select form-select-sm border-0 bg-light py-2 shadow-sm rounded-2 fw-bold">
                        <option value="300">300 - Light</option>
                        <option value="400">400 - Normal</option>
                        <option value="500">500 - Medium</option>
                        <option value="600">600 - Semi-Bold</option>
                        <option value="700">700 - Bold</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- CARD 3: E-COMMERCE --}}
        <div class="bg-white p-3 border rounded-4 mb-3 shadow-sm">
            <div class="d-flex align-items-center mb-3">
                <div class="bg-info bg-opacity-10 p-2 rounded-3 me-2">
                    <i class="fas fa-shopping-cart text-info"></i>
                </div>
                <h6 class="fw-bold text-dark mb-0 text-uppercase" style="letter-spacing: 1px;">Tiện ích & Tìm kiếm</h6>
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="bg-light p-3 rounded-4 border shadow-sm h-100">
                        <label class="form-label fw-bold text-muted text-uppercase mb-2" style="font-size: 0.85rem; letter-spacing: 0.5px;">Thanh Tìm kiếm</label>
                        <div class="col-12 mb-3">
                            <label class="form-label fw-bold text-muted mb-1" style="font-size: 0.75rem;">Hiển thị tìm kiếm</label>
                            <div class="d-flex align-items-center justify-content-between bg-white rounded-3 px-3 py-2 border shadow-sm">
                                <span class="small fw-bold text-muted" x-text="block.content.show_search ? 'ĐANG BẬT' : 'ĐANG TẮT'"></span>
                                <div class="form-check form-switch m-0">
                                    <input class="form-check-input" type="checkbox" role="switch" x-model="block.content.show_search">
                                </div>
                            </div>
                        </div>
                        <div class="col-12" x-show="block.content.show_search" x-collapse>
                            <label class="form-label fw-bold text-muted mb-1" style="font-size: 0.75rem;">Độ rộng tối đa (px)</label>
                            <input type="number" x-model="block.content.search_max_width" class="form-control form-control-sm border-0 bg-white py-1 text-center shadow-sm mb-3" placeholder="600">

                            <label class="form-label fw-bold text-muted mb-1" style="font-size: 0.75rem;">Gợi ý tìm kiếm (Placeholder)</label>
                            <input type="text" x-model="block.content.search_placeholder" class="form-control form-control-sm border-0 bg-white py-2 shadow-sm px-3 mb-3" placeholder="Nhập placeholder...">
                            
                            <div class="row g-2">
                                <div class="col-6">
                                    <label class="form-label fw-bold text-muted mb-1" style="font-size: 0.75rem;">Nền Thanh</label>
                                    <div class="d-flex align-items-center bg-white rounded-2 p-1 border shadow-sm">
                                        <input type="color" x-model="block.content.search_bg" class="form-control-color border-0 bg-transparent p-0 ms-1" style="width: 20px; height: 20px;">
                                        <input type="text" x-model="block.content.search_bg" class="form-control form-control-sm border-0 bg-transparent p-0 fs-7 px-1 flex-grow-1" placeholder="#...">
                                        <div class="d-flex gap-1 border-start ps-1">
                                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="copyColor(block.content.search_bg)" title="Copy"><i class="fas fa-copy" style="font-size: 0.55rem;"></i></button>
                                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="pasteColor(block.content, 'search_bg')" title="Paste"><i class="fas fa-paste" style="font-size: 0.55rem;"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-bold text-muted mb-1" style="font-size: 0.75rem;">Màu Viền</label>
                                    <div class="d-flex align-items-center bg-white rounded-2 p-1 border shadow-sm">
                                        <input type="color" x-model="block.content.search_border_color" class="form-control-color border-0 bg-transparent p-0 ms-1" style="width: 20px; height: 20px;">
                                        <input type="text" x-model="block.content.search_border_color" class="form-control form-control-sm border-0 bg-transparent p-0 fs-7 px-1 flex-grow-1" placeholder="#...">
                                        <div class="d-flex gap-1 border-start ps-1">
                                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="copyColor(block.content.search_border_color)" title="Copy"><i class="fas fa-copy" style="font-size: 0.55rem;"></i></button>
                                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="pasteColor(block.content, 'search_border_color')" title="Paste"><i class="fas fa-paste" style="font-size: 0.55rem;"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-bold text-muted mb-1" style="font-size: 0.75rem;">Màu Chữ Nhập</label>
                                    <div class="d-flex align-items-center bg-white rounded-2 p-1 border shadow-sm">
                                        <input type="color" x-model="block.content.search_text_color" class="form-control-color border-0 bg-transparent p-0 ms-1" style="width: 20px; height: 20px;">
                                        <input type="text" x-model="block.content.search_text_color" class="form-control form-control-sm border-0 bg-transparent p-0 fs-7 px-1 flex-grow-1" placeholder="#...">
                                        <div class="d-flex gap-1 border-start ps-1">
                                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="copyColor(block.content.search_text_color)" title="Copy"><i class="fas fa-copy" style="font-size: 0.55rem;"></i></button>
                                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="pasteColor(block.content, 'search_text_color')" title="Paste"><i class="fas fa-paste" style="font-size: 0.55rem;"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-bold text-muted mb-1" style="font-size: 0.75rem;">Màu Chữ Gợi Ý</label>
                                    <div class="d-flex align-items-center bg-white rounded-2 p-1 border shadow-sm">
                                        <input type="color" x-model="block.content.search_placeholder_color" class="form-control-color border-0 bg-transparent p-0 ms-1" style="width: 20px; height: 20px;">
                                        <input type="text" x-model="block.content.search_placeholder_color" class="form-control form-control-sm border-0 bg-transparent p-0 fs-7 px-1 flex-grow-1" placeholder="#...">
                                        <div class="d-flex gap-1 border-start ps-1">
                                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="copyColor(block.content.search_placeholder_color)" title="Copy"><i class="fas fa-copy" style="font-size: 0.55rem;"></i></button>
                                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="pasteColor(block.content, 'search_placeholder_color')" title="Paste"><i class="fas fa-paste" style="font-size: 0.55rem;"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-bold text-muted mb-1" style="font-size: 0.75rem;">Cỡ Chữ Nhập</label>
                                    <input type="number" x-model="block.content.search_text_size" class="form-control form-control-sm border-0 bg-white py-1 text-center shadow-sm" placeholder="14">
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-bold text-muted mb-1" style="font-size: 0.75rem;">Cỡ Chữ Gợi Ý</label>
                                    <input type="number" x-model="block.content.search_placeholder_size" class="form-control form-control-sm border-0 bg-white py-1 text-center shadow-sm" placeholder="14">
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold text-muted mb-1" style="font-size: 0.75rem;">Màu Icon Nút</label>
                                    <div class="d-flex align-items-center bg-white rounded-2 p-1 border shadow-sm">
                                        <input type="color" x-model="block.content.search_btn_color" class="form-control-color border-0 bg-transparent p-0 ms-1" style="width: 20px; height: 20px;">
                                        <input type="text" x-model="block.content.search_btn_color" class="form-control form-control-sm border-0 bg-transparent p-0 fs-7 px-1 flex-grow-1" placeholder="#...">
                                        <div class="d-flex gap-1 border-start ps-1">
                                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="copyColor(block.content.search_btn_color)" title="Copy"><i class="fas fa-copy" style="font-size: 0.55rem;"></i></button>
                                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="pasteColor(block.content, 'search_btn_color')" title="Paste"><i class="fas fa-paste" style="font-size: 0.55rem;"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="bg-light p-3 rounded-4 border shadow-sm h-100">
                        <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom border-secondary border-opacity-10">
                            <label class="form-label fw-bold text-muted text-uppercase mb-0" style="font-size: 0.85rem; letter-spacing: 0.5px;">Tiện ích</label>
                            <div class="d-flex align-items-center bg-white px-2 py-1 rounded-2 border shadow-sm" style="width: 120px;">
                                <label class="small fw-bold text-muted me-2 mb-0">GAP</label>
                                <input type="number" x-model="block.content.utility_icons_gap" class="form-control form-control-sm border-0 bg-transparent p-0 text-center fw-bold" placeholder="8">
                            </div>
                        </div>
                        <div class="row g-3">
                            <template x-for="item in [
                                {key: 'account', label: 'Tài khoản'},
                                {key: 'cart', label: 'Giỏ hàng'},
                                {key: 'wishlist', label: 'Yêu thích'}
                            ]" :key="item.key">
                                <div class="col-12">
                                    <div class="bg-white p-2 rounded-3 border shadow-sm">
                                        <div class="d-flex align-items-center justify-content-between mb-2 pb-2 border-bottom border-light">
                                            <label class="form-label fw-bold text-muted text-uppercase mb-0" style="font-size: 0.85rem; letter-spacing: 0.5px;" x-text="'HIỂN THỊ ' + item.label"></label>
                                            <div class="form-check form-switch m-0">
                                                <input class="form-check-input" type="checkbox" role="switch" x-model="block.content['show_' + item.key]">
                                            </div>
                                        </div>
                                        <div x-show="block.content['show_' + item.key]" x-collapse>
                                            <div class="row g-2 mb-2">
                                                <div class="col-12">
                                                    <label class="form-label fw-bold text-muted mb-1" style="font-size: 0.75rem;">Màu Icon</label>
                                                    <div class="d-flex align-items-center bg-light rounded-2 p-1 border shadow-sm" style="height: 30px;">
                                                        <input type="color" x-model="block.content[item.key + '_icon_color']" class="form-control-color border-0 bg-transparent p-0 ms-1" style="width: 18px; height: 18px;">
                                                        <input type="text" x-model="block.content[item.key + '_icon_color']" class="form-control form-control-sm border-0 bg-transparent p-0 fs-7 px-1 flex-grow-1" placeholder="#...">
                                                        <div class="d-flex gap-1 border-start ps-1">
                                                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="copyColor(block.content[item.key + '_icon_color'])" title="Copy"><i class="fas fa-copy" style="font-size: 0.5rem;"></i></button>
                                                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="pasteColor(block.content, item.key + '_icon_color')" title="Paste"><i class="fas fa-paste" style="font-size: 0.5rem;"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 mt-1">
                                                    <label class="form-label fw-bold text-muted mb-1" style="font-size: 0.75rem;">Màu Hover</label>
                                                    <div class="d-flex align-items-center bg-light rounded-2 p-1 border shadow-sm" style="height: 30px;">
                                                        <input type="color" x-model="block.content[item.key + '_icon_hover_color']" class="form-control-color border-0 bg-transparent p-0 ms-1" style="width: 18px; height: 18px;">
                                                        <input type="text" x-model="block.content[item.key + '_icon_hover_color']" class="form-control form-control-sm border-0 bg-transparent p-0 fs-7 px-1 flex-grow-1" placeholder="#...">
                                                        <div class="d-flex gap-1 border-start ps-1">
                                                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="copyColor(block.content[item.key + '_icon_hover_color'])" title="Copy"><i class="fas fa-copy" style="font-size: 0.5rem;"></i></button>
                                                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="pasteColor(block.content, item.key + '_icon_hover_color')" title="Paste"><i class="fas fa-paste" style="font-size: 0.5rem;"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 mt-1">
                                                    <label class="form-label fw-bold text-muted mb-1" style="font-size: 0.75rem;">Cỡ Icon (px)</label>
                                                    <input type="number" x-model="block.content[item.key + '_icon_size']" class="form-control form-control-sm border-0 bg-light py-1 text-center shadow-sm" style="height: 30px;" placeholder="20">
                                                </div>
                                            </div>
                                            <div class="mt-2">
                                                <label class="form-label fw-bold text-muted mb-1" style="font-size: 0.75rem;" x-text="'ĐƯỜNG DẪN ' + item.label"></label>
                                                <input type="text" x-model="block.content[item.key + '_link']" class="form-control form-control-sm border-0 bg-light py-1 shadow-sm px-2" placeholder="/...">
                                            </div>

                                            {{-- Badge Settings for Cart/Wishlist --}}
                                            <template x-if="item.key === 'cart' || item.key === 'wishlist'">
                                                <div class="mt-3 pt-2 border-top">
                                                    <label class="form-label fw-bold text-dark text-uppercase mb-2" style="font-size: 0.75rem;">Cài đặt Badge (Thông báo số)</label>
                                                    <div class="row g-2">
                                                        <div class="col-6">
                                                            <label class="form-label fw-bold text-muted mb-0" style="font-size: 0.7rem;">Màu nền Badge</label>
                                                            <div class="d-flex align-items-center bg-light rounded-2 p-1 border shadow-sm">
                                                                <input type="color" x-model="block.content[item.key + '_badge_bg']" class="form-control-color border-0 bg-transparent p-0 ms-1" style="width: 18px; height: 18px;">
                                                                <input type="text" x-model="block.content[item.key + '_badge_bg']" class="form-control form-control-sm border-0 bg-transparent p-0 fs-7 px-1 flex-grow-1" placeholder="#...">
                                                                <div class="d-flex gap-1 border-start ps-1">
                                                                    <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="copyColor(block.content[item.key + '_badge_bg'])" title="Copy"><i class="fas fa-copy" style="font-size: 0.45rem;"></i></button>
                                                                    <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="pasteColor(block.content, item.key + '_badge_bg')" title="Paste"><i class="fas fa-paste" style="font-size: 0.45rem;"></i></button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <label class="form-label fw-bold text-muted mb-0" style="font-size: 0.7rem;">Màu chữ Badge</label>
                                                            <div class="d-flex align-items-center bg-light rounded-2 p-1 border shadow-sm">
                                                                <input type="color" x-model="block.content[item.key + '_badge_color']" class="form-control-color border-0 bg-transparent p-0 ms-1" style="width: 18px; height: 18px;">
                                                                <input type="text" x-model="block.content[item.key + '_badge_color']" class="form-control form-control-sm border-0 bg-transparent p-0 fs-7 px-1 flex-grow-1" placeholder="#...">
                                                                <div class="d-flex gap-1 border-start ps-1">
                                                                    <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="copyColor(block.content[item.key + '_badge_color'])" title="Copy"><i class="fas fa-copy" style="font-size: 0.45rem;"></i></button>
                                                                    <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="pasteColor(block.content, item.key + '_badge_color')" title="Paste"><i class="fas fa-paste" style="font-size: 0.45rem;"></i></button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 mt-1">
                                                            <label class="form-label fw-bold text-muted mb-0" style="font-size: 0.7rem;">Cỡ chữ Badge (px)</label>
                                                            <input type="number" x-model="block.content[item.key + '_badge_size']" class="form-control form-control-sm border-0 bg-light py-1 shadow-sm" placeholder="9">
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- CARD 4: TYPOGRAPHY PER LEVEL (L2 & L3) --}}
        @foreach(['2' => 'Cấp 2', '3' => 'Cấp 3'] as $lv => $name)
            <div class="bg-white p-3 border rounded-4 mb-3 shadow-sm">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-danger bg-opacity-10 p-2 rounded-3 me-2">
                        <i class="fas @if($lv==1) fa-font @elseif($lv==2) fa-sitemap @else fa-leaf @endif text-danger"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-0 small text-uppercase" style="letter-spacing: 0.5px;">Thiết lập vùng Menu {{ $name }}</h6>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.8rem;">Màu chính</label>
                        <div class="d-flex align-items-center bg-light rounded-2 p-1 gap-1 shadow-sm border" style="height: 38px;">
                            <input type="color" x-model="block.content.menu_l{{ $lv }}_color" class="form-control-color border-0 bg-transparent p-0 ms-1" style="width: 24px; height: 24px;">
                            <input type="text" x-model="block.content.menu_l{{ $lv }}_color" class="form-control form-control-sm border-0 bg-transparent p-0 fs-7 fw-bold px-1 flex-grow-1">
                            <div class="d-flex gap-1 ms-auto pe-1 border-start ps-1">
                                <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="copyColor(block.content.menu_l{{ $lv }}_color)" title="Copy"><i class="fas fa-copy" style="font-size: 0.6rem;"></i></button>
                                <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="pasteColor(block.content, 'menu_l{{ $lv }}_color')" title="Paste"><i class="fas fa-paste" style="font-size: 0.6rem;"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.8rem;">Màu Hover</label>
                        <div class="d-flex align-items-center bg-light rounded-2 p-1 gap-1 shadow-sm border" style="height: 38px;">
                            <input type="color" x-model="block.content.menu_l{{ $lv }}_hover_color" class="form-control-color border-0 bg-transparent p-0 ms-1" style="width: 24px; height: 24px;">
                            <input type="text" x-model="block.content.menu_l{{ $lv }}_hover_color" class="form-control form-control-sm border-0 bg-transparent p-0 fs-7 fw-bold px-1 flex-grow-1">
                            <div class="d-flex gap-1 ms-auto pe-1 border-start ps-1">
                                <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="copyColor(block.content.menu_l{{ $lv }}_hover_color)" title="Copy"><i class="fas fa-copy" style="font-size: 0.6rem;"></i></button>
                                <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="pasteColor(block.content, 'menu_l{{ $lv }}_hover_color')" title="Paste"><i class="fas fa-paste" style="font-size: 0.6rem;"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.8rem;">Cỡ chữ (px)</label>
                        <input type="number" x-model="block.content.menu_l{{ $lv }}_size" class="form-control form-control-sm border-0 bg-light py-2 shadow-sm rounded-2 text-center fw-bold">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.8rem;">Độ dày (Weight)</label>
                        <select x-model="block.content.menu_l{{ $lv }}_weight" class="form-select form-select-sm border-0 bg-light py-2 shadow-sm rounded-2 fw-bold">
                            <option value="300">300 - Light</option>
                            <option value="400">400 - Normal</option>
                            <option value="500">500 - Medium</option>
                            <option value="600">600 - Semi-Bold</option>
                            <option value="700">700 - Bold</option>
                        </select>
                    </div>
                    @foreach(['top','bottom','left','right'] as $p)
                        <div class="col-md-3 col-6">
                            <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.75rem;">Pad {{ ucfirst($p) }}</label>
                            <input type="number" x-model="block.content['menu_l'+{{ $lv }}+'_padding_'+'{{ $p }}']" class="form-control form-control-sm border-0 bg-light py-1 text-center shadow-sm">
                        </div>
                    @endforeach
                    @if($lv > 1)
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.8rem;">Màu nền Menu</label>
                            <div class="d-flex align-items-center bg-light rounded-2 p-1 gap-1 shadow-sm border" style="height: 38px;">
                                <input type="color" x-model="block.content.menu_l{{ $lv }}_bg" class="form-control-color border-0 bg-transparent p-0 ms-1" style="width: 24px; height: 24px;">
                                <input type="text" x-model="block.content.menu_l{{ $lv }}_bg" class="form-control form-control-sm border-0 bg-transparent p-0 fs-7 fw-bold px-1 flex-grow-1">
                                <div class="d-flex gap-1 ms-auto pe-1 border-start ps-1">
                                    <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="copyColor(block.content.menu_l{{ $lv }}_bg)" title="Copy"><i class="fas fa-copy" style="font-size: 0.6rem;"></i></button>
                                    <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="pasteColor(block.content, 'menu_l{{ $lv }}_bg')" title="Paste"><i class="fas fa-paste" style="font-size: 0.6rem;"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.8rem;">Khoảng cách (Gap)</label>
                            <input type="number" x-model="block.content.menu_l{{ $lv }}_gap" class="form-control form-control-sm border-0 bg-light py-2 shadow-sm rounded-2 text-center">
                        </div>
                    @endif
                </div>
            </div>
        @endforeach

        {{-- SECTION 5: MENU BUILDER --}}
        <div class="bg-white p-3 border rounded-4 shadow-sm mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                <div class="d-flex align-items-center">
                    <div class="bg-success bg-opacity-10 p-2 rounded-3 me-2">
                        <i class="fas fa-list-ul text-success"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-0 small text-uppercase" style="letter-spacing: 0.5px;">Cây thư mục Menu</h6>
                </div>
                <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 fw-bold shadow-sm" style="font-size: 0.65rem;"
                    @click="block.content.buttons.push({label: 'Mục lục mới', link: '#', children: []})">
                    <i class="fas fa-plus me-1"></i> THÊM MỤC CHÍNH
                </button>
            </div>

            <div class="d-flex flex-column gap-3" 
                x-init="Sortable.create($el, {
                    handle: '.drag-handle-l1', animation: 200, ghostClass: 'bg-light-blue',
                    onEnd: (evt) => {
                        if (evt.oldIndex === evt.newIndex) return;
                        const list = [...block.content.buttons];
                        const item = list.splice(evt.oldIndex, 1)[0];
                        list.splice(evt.newIndex, 0, item);
                        block.content.buttons = []; $nextTick(() => { block.content.buttons = list; });
                    }
                })">
                <template x-for="(btn, i) in block.content.buttons" :key="i">
                    <!-- LEVEL 1 CARD -->
                    <div class="rounded-4 border shadow-xs bg-white overflow-hidden">
                        <div class="p-2 d-flex align-items-center gap-2 bg-light bg-opacity-50 border-bottom">
                            <div class="drag-handle-l1 cursor-grab text-primary opacity-50 px-2 py-1 rounded hover-bg-light">
                                <i class="fas fa-grip-lines"></i>
                            </div>
                            <span class="badge bg-primary bg-opacity-10 text-primary small fw-bold px-2 py-1 border border-primary border-opacity-10">L1</span>
                            <div class="flex-grow-1 row g-2 align-items-center">
                                <div :class="(btn.children && btn.children.length > 0) ? 'col-12' : 'col-6'">
                                    <input type="text" x-model="btn.label" class="form-control form-control-sm border-0 bg-transparent fw-bold fs-6 text-dark py-1" placeholder="Tên menu cấp 1...">
                                </div>
                                <div class="col-6" x-show="!btn.children || btn.children.length === 0">
                                    <input type="text" x-model="btn.link" class="form-control form-control-sm border-0 bg-white shadow-xs px-2 py-1 text-muted italic" placeholder="Link /...">
                                </div>
                            </div>
                            <div class="d-flex gap-1 border-start ps-2">
                                <button type="button" @click="if(!btn.children) btn.children = []; btn.children.push({label: 'Mục con mới', link: '#', children: []})"
                                        class="btn btn-sm btn-primary btn-opacity-10 py-1 px-2 border-0 shadow-none rounded-3" title="Thêm cấp 2">
                                    <i class="fas fa-plus"></i></button>
                                <button @click="block.content.buttons.splice(i, 1)" class="btn btn-sm text-danger px-2 border-0 opacity-75 hover-opacity-100">
                                    <i class="fas fa-trash-alt"></i></button>
                            </div>
                        </div>

                        <!-- LEVEL 2 CONTAINER -->
                        <div x-show="btn.children && btn.children.length > 0" x-collapse class="p-3 bg-white border-start border-4 border-primary border-opacity-10 ms-3 my-2">
                            <div class="d-flex flex-column gap-2" 
                                x-init="Sortable.create($el, {
                                    handle: '.drag-handle-l2', animation: 200, ghostClass: 'bg-light-blue',
                                    onEnd: (evt) => {
                                        if (evt.oldIndex === evt.newIndex) return;
                                        const list = [...btn.children];
                                        const item = list.splice(evt.oldIndex, 1)[0];
                                        list.splice(evt.newIndex, 0, item);
                                        btn.children = []; $nextTick(() => { btn.children = list; });
                                    }
                                })">
                                <template x-for="(sub, si) in btn.children" :key="si">
                                    <div class="rounded-3 border border-light-subtle shadow-xs bg-light bg-opacity-25 mb-1">
                                        <div class="d-flex align-items-center gap-2 p-2">
                                            <div class="drag-handle-l2 cursor-grab text-info opacity-50 px-2 py-1"><i class="fas fa-bars small"></i></div>
                                            <span class="badge bg-info bg-opacity-10 text-info small fw-bold" style="font-size: 0.6rem;">L2</span>
                                            <div class="flex-grow-1 row g-2">
                                                <div :class="(sub.children && sub.children.length > 0) ? 'col-12' : 'col-6'">
                                                    <input type="text" x-model="sub.label" class="form-control form-control-sm border-0 bg-transparent fw-bold text-dark p-0" placeholder="Tên menu cấp 2...">
                                                </div>
                                                <div class="col-6" x-show="!sub.children || sub.children.length === 0">
                                                    <input type="text" x-model="sub.link" class="form-control form-control-sm border-0 bg-white shadow-xs px-2 py-0 text-muted italic" placeholder="Link /...">
                                                </div>
                                            </div>
                                            <div class="d-flex gap-1">
                                                <button type="button" @click="if(!sub.children) sub.children = []; sub.children.push({label: 'Cấp 3 mới', link: '#'})"
                                                    class="btn btn-sm btn-info btn-opacity-10 py-1 px-2 border-0 shadow-none rounded-3" title="Thêm cấp 3">
                                                    <i class="fas fa-plus fs-7"></i></button>
                                                <button @click="btn.children.splice(si, 1)" class="btn btn-sm text-danger p-0 px-2 opacity-50 hover-opacity-100"><i class="fas fa-times"></i></button>
                                            </div>
                                        </div>

                                        <!-- LEVEL 3 CONTAINER (SORTABLE) -->
                                        <div x-show="sub.children && sub.children.length > 0" x-collapse class="ps-4 pb-2 pe-2 border-start border-3 border-info border-opacity-10 ms-3 mt-1">
                                            <div class="d-flex flex-column gap-1"
                                                x-init="Sortable.create($el, {
                                                    handle: '.drag-handle-l3', animation: 200, ghostClass: 'bg-light-blue',
                                                    onEnd: (evt) => {
                                                        if (evt.oldIndex === evt.newIndex) return;
                                                        const list = [...sub.children];
                                                        const item = list.splice(evt.oldIndex, 1)[0];
                                                        list.splice(evt.newIndex, 0, item);
                                                        sub.children = []; $nextTick(() => { sub.children = list; });
                                                    }
                                                })">
                                                <template x-for="(grand, gi) in sub.children" :key="gi">
                                                    <div class="d-flex align-items-center gap-2 p-1 px-2 bg-white rounded-2 border shadow-xs border-light">
                                                        <div class="drag-handle-l3 cursor-grab text-teal opacity-30 px-1"><i class="fas fa-ellipsis-v small"></i></div>
                                                        <span class="badge bg-teal bg-opacity-10 text-teal small fw-bold" style="font-size: 0.55rem;">L3</span>
                                                        <input type="text" x-model="grand.label" class="form-control form-control-sm border-0 bg-transparent fw-medium text-dark p-0 fs-7" placeholder="Tên cấp 3...">
                                                        <div class="vr mx-1 opacity-10" style="height: 12px;"></div>
                                                        <input type="text" x-model="grand.link" class="form-control form-control-sm border-0 bg-transparent text-muted italic p-0 fs-7" placeholder="Link /...">
                                                        <button @click="sub.children.splice(gi, 1)" class="btn btn-sm text-danger p-0 px-1 ms-auto opacity-30 hover-opacity-100"><i class="fas fa-times"></i></button>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
            
            <template x-if="block.content.buttons.length === 0">
                <div class="text-center py-5 border-dashed rounded-4 bg-light mt-2">
                    <i class="fas fa-project-diagram fa-2x opacity-10 d-block mb-3"></i>
                    <p class="text-muted small mb-0">Chưa có cây thư mục Menu nào.<br>Nhấn nút phí trên để bắt đầu xây dựng.</p>
                </div>
            </template>
        </div>
    </div>
</template>

<style>
[x-cloak] { display: none !important; }

/* 100% MIRROR OF REFERENCE CSS (Product Category Grid) */
.text-header-admin input:not([type="checkbox"]):not([type="radio"]):not([type="color"]):focus, 
.text-header-admin select:focus {
    box-shadow: none !important;
    border-color: #3b82f6 !important;
    background-color: #fff !important;
}

.shadow-xs { box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); }
.cursor-grab { cursor: grab !important; }
.cursor-grab:active { cursor: grabbing !important; }
.bg-light-blue { background-color: rgba(59, 130, 246, 0.05) !important; border: 1px dashed #3b82f6 !important; }

.hover-bg-light:hover { background-color: rgba(0,0,0,0.05); }
.btn-opacity-10 { background-color: rgba(var(--bs-primary-rgb), 0.1); color: var(--bs-primary); }
.btn-info.btn-opacity-10 { background-color: rgba(13, 202, 240, 0.1); color: #0dcaf0; }
.text-teal { color: #0d9488 !important; }
.bg-teal { background-color: #0d9488 !important; }
.border-teal { border-color: #0d9488 !important; }
.bg-opacity-10.bg-teal { background-color: rgba(13, 148, 136, 0.1) !important; }

.fs-7 { font-size: 0.8rem; }
.border-dashed { border: 2px dashed #cbd5e1 !important; }
</style>
