<template x-if="block.type === 'footer'">
    <div class="mt-2 text-footer-admin" x-init="
        if (!block.content) block.content = {};
        
        // Initialize defaults contextually
        if (!block.content.company_name) block.content.company_name = 'CÔNG TY TNHH THƯƠNG MẠI VÀ XUẤT NHẬP KHẨU BÌNH LINH';
        if (!block.content.copyright) block.content.copyright = '© 2024 lopotobinhminh. All rights reserved.';
        
        // Style Defaults
        if (!block.content.bg_color) block.content.bg_color = '#001d3d';
        if (!block.content.text_color) block.content.text_color = '#ffffff';
        
        // Structure Defaults
        if (!block.content.footer_columns) {
            block.content.footer_columns = [
                { title: 'LIÊN HỆ', body: 'Địa chỉ: 12 Mai Hắc Đế, Vinh Phú, Nghệ An\nĐiện thoại: 02383545886', links: [], width: 320, spacing: 30 },
                { title: 'SẢN PHẨM', body: '', links: [{label: 'Lốp ô tô', url: '#'}, {label: 'Dầu nhớt', url: '#'}], width: 200, spacing: 30 },
                { title: 'CHÍNH SÁCH', body: '', links: [{label: 'Chính sách bảo mật', url: '#'}], width: 250, spacing: 0 }
            ];
        }
        if (!block.content.socials) {
            block.content.socials = [
                { icon: 'fab fa-facebook', img: '', url: '#', bg_color: '#1877F2', icon_color: '#ffffff' },
                { icon: 'fab fa-tiktok', img: '', url: '#', bg_color: '#000000', icon_color: '#ffffff' },
                { icon: 'fab fa-youtube', img: '', url: '#', bg_color: '#FF0000', icon_color: '#ffffff' }
            ];
        }

        // New Layout & Typography Defaults
        if (!block.content.bg_color) block.content.bg_color = '#1b1b1b';
        if (!block.content.title_color) block.content.title_color = '#ffffff';
        if (!block.content.title_font_size) block.content.title_font_size = 14;
        if (!block.content.content_color) block.content.content_color = '#94a3b8';
        if (!block.content.content_font_size) block.content.content_font_size = 13;
        if (!block.content.link_color) block.content.link_color = '#cbd5e1';
        if (!block.content.link_font_size) block.content.link_font_size = 13;
        if (!block.content.padding_top) block.content.padding_top = '60px';
        if (!block.content.padding_bottom) block.content.padding_bottom = '60px';
        if (!block.content.padding_left) block.content.padding_left = '0px';
        if (!block.content.padding_right) block.content.padding_right = '0px';
    ">
        
        {{-- Card 1: General Info --}}
        <div class="bg-white p-3 border rounded-4 mb-3 shadow-sm">
            <div class="d-flex align-items-center mb-3">
                <div class="bg-primary bg-opacity-10 p-2 rounded-3 me-2">
                    <i class="fas fa-info-circle text-primary"></i>
                </div>
                <h6 class="fw-bold text-dark mb-0 small text-uppercase" style="letter-spacing: 0.5px;">Thông tin chung</h6>
            </div>

            <div class="row g-3">
                <div class="col-md-12">
                    <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.6rem;">Dòng bản quyền (Copyright)</label>
                    <input type="text" x-model="block.content.copyright" class="form-control form-control-sm border-0 bg-light py-2 shadow-sm" placeholder="© 2026. All rights reserved.">
                </div>
            </div>
        </div>

        {{-- Card 2: Typography & Colors --}}
        <div class="bg-white p-3 border rounded-4 mb-3 shadow-sm">
            <div class="d-flex align-items-center mb-3">
                <div class="bg-success bg-opacity-10 p-2 rounded-3 me-2">
                    <i class="fas fa-palette text-success"></i>
                </div>
                <h6 class="fw-bold text-dark mb-0 small text-uppercase" style="letter-spacing: 0.5px;">Màu sắc & Typography</h6>
            </div>

            <div class="row g-3">
                {{-- Background Color --}}
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.6rem;">Màu nền Footer</label>
                    <div class="d-flex align-items-center bg-light rounded-2 p-1 gap-1 shadow-sm" style="height: 38px;">
                        <input type="color" x-model="block.content.bg_color" class="form-control-color border-0 bg-transparent p-0 ms-1" style="width: 24px; height: 24px;">
                        <input type="text" x-model="block.content.bg_color" class="form-control form-control-sm border-0 bg-transparent p-0 fs-7 fw-bold px-1 flex-grow-1" style="width: 65px;" placeholder="#1b1b1b">
                        <div class="d-flex gap-1 me-1">
                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="copyColor(block.content.bg_color)" title="Copy màu"><i class="fas fa-copy" style="font-size: 0.6rem;"></i></button>
                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="pasteColor(block.content, 'bg_color')" title="Dán màu"><i class="fas fa-paste" style="font-size: 0.6rem;"></i></button>
                        </div>
                    </div>
                </div>

                {{-- Title Typography --}}
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.6rem;">Tiêu đề cột (Màu & Size)</label>
                    <div class="d-flex align-items-center bg-light rounded-2 p-1 gap-1 shadow-sm" style="height: 38px;">
                        <input type="color" x-model="block.content.title_color" class="form-control-color border-0 bg-transparent p-0 ms-1" style="width: 24px; height: 24px;">
                        <input type="text" x-model="block.content.title_color" class="form-control form-control-sm border-0 bg-transparent p-0 fs-7 fw-bold px-1 flex-grow-1" style="width: 65px;" placeholder="#fff">
                        <div class="d-flex gap-1 me-1">
                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="copyColor(block.content.title_color)" title="Copy màu"><i class="fas fa-copy" style="font-size: 0.6rem;"></i></button>
                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="pasteColor(block.content, 'title_color')" title="Dán màu"><i class="fas fa-paste" style="font-size: 0.6rem;"></i></button>
                        </div>
                        <div class="vr mx-1 opacity-25" style="height: 20px;"></div>
                        <input type="number" x-model="block.content.title_font_size" class="form-control form-control-sm border-0 bg-transparent p-0 fs-7 fw-bold px-1 text-center" style="width: 45px;" placeholder="14">
                    </div>
                </div>

                {{-- Content Typography --}}
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.6rem;">Nội dung (Màu & Size)</label>
                    <div class="d-flex align-items-center bg-light rounded-2 p-1 gap-1 shadow-sm" style="height: 38px;">
                        <input type="color" x-model="block.content.content_color" class="form-control-color border-0 bg-transparent p-0 ms-1" style="width: 24px; height: 24px;">
                        <input type="text" x-model="block.content.content_color" class="form-control form-control-sm border-0 bg-transparent p-0 fs-7 fw-bold px-1 flex-grow-1" style="width: 65px;" placeholder="#94a3b8">
                        <div class="d-flex gap-1 me-1">
                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="copyColor(block.content.content_color)" title="Copy màu"><i class="fas fa-copy" style="font-size: 0.6rem;"></i></button>
                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="pasteColor(block.content, 'content_color')" title="Dán màu"><i class="fas fa-paste" style="font-size: 0.6rem;"></i></button>
                        </div>
                        <div class="vr mx-1 opacity-25" style="height: 20px;"></div>
                        <input type="number" x-model="block.content.content_font_size" class="form-control form-control-sm border-0 bg-transparent p-0 fs-7 fw-bold px-1 text-center" style="width: 45px;" placeholder="13">
                    </div>
                </div>

                {{-- Link Typography --}}
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.6rem;">Đường Link (Màu & Size)</label>
                    <div class="d-flex align-items-center bg-light rounded-2 p-1 gap-1 shadow-sm" style="height: 38px;">
                        <input type="color" x-model="block.content.link_color" class="form-control-color border-0 bg-transparent p-0 ms-1" style="width: 24px; height: 24px;">
                        <input type="text" x-model="block.content.link_color" class="form-control form-control-sm border-0 bg-transparent p-0 fs-7 fw-bold px-1 flex-grow-1" style="width: 65px;" placeholder="#cbd5e1">
                        <div class="d-flex gap-1 me-1">
                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="copyColor(block.content.link_color)" title="Copy màu"><i class="fas fa-copy" style="font-size: 0.6rem;"></i></button>
                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="pasteColor(block.content, 'link_color')" title="Dán màu"><i class="fas fa-paste" style="font-size: 0.6rem;"></i></button>
                        </div>
                        <div class="vr mx-1 opacity-25" style="height: 20px;"></div>
                        <input type="number" x-model="block.content.link_font_size" class="form-control form-control-sm border-0 bg-transparent p-0 fs-7 fw-bold px-1 text-center" style="width: 45px;" placeholder="13">
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 2: Social Media --}}
        <div class="bg-white p-3 border rounded-4 mb-3 shadow-sm mt-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center">
                    <div class="bg-info bg-opacity-10 p-2 rounded-3 me-2">
                        <i class="fas fa-share-alt text-info"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-0 small text-uppercase" style="letter-spacing: 0.5px;">Kết nối mạng xã hội</h6>
                </div>
                <button type="button" class="btn btn-sm btn-outline-info rounded-pill px-3 fw-bold" style="font-size: 0.6rem;"
                    @click="block.content.socials = block.content.socials || []; block.content.socials.push({icon: 'fab fa-facebook', img: '', url: '#', bg_color: '#1877F2', icon_color: '#ffffff'});">
                    <i class="fas fa-plus me-1"></i> THÊM MẠNG XÃ HỘI
                </button>
            </div>

            <div class="row g-2">
                <template x-for="(soc, k) in (block.content.socials || [])" :key="k">
                    <div class="col-md-12">
                        <div class="bg-light p-2 rounded-3 border-0 d-flex gap-2 align-items-center shadow-sm">
                            {{-- Image/Icon Selector --}}
                            <div class="d-flex align-items-center bg-white rounded-2 px-1 py-1 shadow-xs" style="width: 210px; height: 32px;">
                                {{-- Icon Choice --}}
                                <div class="d-flex align-items-center flex-grow-1 px-1">
                                    <i :class="soc.icon" class="me-1 text-primary" style="font-size: 0.7rem;" x-show="!soc.img"></i>
                                    <input type="text" x-model="soc.icon" class="form-control form-control-sm border-0 p-0 fs-7 px-1 w-100" placeholder="fa-facebook..." style="font-size: 0.65rem !important;" x-show="!soc.img">
                                    <span class="badge bg-success bg-opacity-10 text-success p-1 px-2" x-show="soc.img" style="font-size: 0.55rem;"><i class="fas fa-check-circle me-1"></i> ĐÃ CHỌN ẢNH</span>
                                </div>
                                <div class="vr opacity-25 mx-1" style="height: 16px;"></div>
                                {{-- Image Choice --}}
                                <button type="button" class="btn btn-sm btn-light py-0 px-2 border-0 position-relative" @click="openMediaPicker(index, 'socials.' + k + '.img')" title="Chọn ảnh đại diện">
                                    <template x-if="soc.img">
                                        <div class="d-flex align-items-center">
                                            <img :src="soc.img" style="width: 18px; height: 18px; object-fit: contain; border-radius: 2px;">
                                            <div class="position-absolute top-0 end-0 bg-danger rounded-circle d-flex align-items-center justify-content-center" style="width: 10px; height: 10px; transform: translate(30%, -30%);" @click.stop="soc.img = ''">
                                                <i class="fas fa-times text-white" style="font-size: 0.4rem;"></i>
                                            </div>
                                        </div>
                                    </template>
                                    <template x-if="!soc.img">
                                        <i class="fas fa-images text-muted" style="font-size: 0.8rem;"></i>
                                    </template>
                                </button>
                                <input type="hidden" x-model="soc.img">
                            </div>
                            
                            {{-- URL --}}
                            <input type="text" x-model="soc.url" class="form-control form-control-sm border-0 bg-white py-1 flex-grow-1 fs-7" placeholder="https://...">

                            {{-- Color Settings - Hidden if Image is selected --}}
                            <template x-if="!soc.img">
                                <div class="d-flex gap-2">
                                    {{-- BG Color --}}
                                    <div class="d-flex align-items-center bg-white rounded-2 p-1 gap-1 shadow-xs" style="width: 155px; height: 32px;">
                                        <input type="color" x-model="soc.bg_color" class="form-control-color border-0 bg-transparent p-0 ms-1" style="width: 18px; height: 18px;" title="Màu nền">
                                        <input type="text" x-model="soc.bg_color" class="form-control form-control-sm border-0 bg-transparent p-0 fs-7 px-1" style="width: 60px;" placeholder="BG">
                                        <div class="d-flex gap-1 ms-1">
                                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0 shadow-none" @click="copyColor(soc.bg_color)" title="Copy màu"><i class="fas fa-copy" style="font-size: 0.6rem;"></i></button>
                                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0 shadow-none" @click="pasteColor(soc, 'bg_color')" title="Dán màu"><i class="fas fa-paste" style="font-size: 0.6rem;"></i></button>
                                        </div>
                                    </div>

                                    {{-- Icon Color --}}
                                    <div class="d-flex align-items-center bg-white rounded-2 p-1 gap-1 shadow-xs" style="width: 155px; height: 32px;">
                                        <input type="color" x-model="soc.icon_color" class="form-control-color border-0 bg-transparent p-0 ms-1" style="width: 18px; height: 18px;" title="Màu Icon">
                                        <input type="text" x-model="soc.icon_color" class="form-control form-control-sm border-0 bg-transparent p-0 fs-7 px-1" style="width: 60px;" placeholder="Icon">
                                        <div class="d-flex gap-1 ms-1">
                                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0 shadow-none" @click="copyColor(soc.icon_color)" title="Copy màu"><i class="fas fa-copy" style="font-size: 0.6rem;"></i></button>
                                            <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0 shadow-none" @click="pasteColor(soc, 'icon_color')" title="Dán màu"><i class="fas fa-paste" style="font-size: 0.6rem;"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <button type="button" class="btn btn-sm btn-light text-danger flex-shrink-0 border-0 ms-1" @click="block.content.socials.splice(k, 1)">
                                <i class="fas fa-trash-alt" style="font-size: 0.75rem;"></i>
                            </button>
                        </div>
                    </div>
                </template>
            </div>
            <div class="text-muted small mt-2 d-flex align-items-center opacity-75" style="font-size: 0.65rem;">
                <i class="fas fa-info-circle me-1 text-info"></i> 
                <span>Sử dụng class FontAwesome (VD: <code>fab fa-facebook</code>, <code>fab fa-tiktok</code>) và chỉnh màu icon riêng biệt.</span>
            </div>
        </div>

        {{-- Card 3: Footer Columns (Repeater) --}}
        <div class="bg-white p-3 border rounded-4 mb-3 shadow-sm mt-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center">
                    <div class="bg-warning bg-opacity-10 p-2 rounded-3 me-2">
                        <i class="fas fa-columns text-warning"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-0 small text-uppercase" style="letter-spacing: 0.5px;">Cấu hình các cột Footer</h6>
                </div>
                <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 fw-bold" style="font-size: 0.6rem;"
                    @click="if (!block.content.footer_columns) block.content.footer_columns = []; block.content.footer_columns.push({ title: 'Tiêu đề mới', body: '', links: [], width: '', spacing: 20 });">
                    <i class="fas fa-plus me-1"></i> THÊM CỘT MỚI
                </button>
            </div>

            <template x-if="!block.content.footer_columns || block.content.footer_columns.length === 0">
                <div class="text-muted small text-center py-4 border-dashed rounded-4 bg-light mb-2">
                    <i class="fas fa-columns fa-2x opacity-25 d-block mb-2"></i>
                    Chưa có cột nội dung nào. Bấm "Thêm cột" để bắt đầu trang trí chân trang.
                </div>
            </template>

            <div class="d-flex flex-column gap-3">
                <template x-for="(col, ci) in (block.content.footer_columns || [])" :key="ci">
                    <div class="card border-0 bg-light rounded-4 overflow-hidden shadow-sm">
                        {{-- Header: Index & Delete ONLY --}}
                        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-2 px-3">
                            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill fw-bold" style="font-size: 0.65rem;" x-text="'CỘT THỨ ' + (ci + 1)"></span>
                            <button type="button" class="btn btn-sm btn-outline-danger border-0" @click="block.content.footer_columns.splice(ci, 1)">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>

                        <div class="card-body p-3">
                            <div class="row g-3 mb-3 pb-3 border-bottom border-dashed">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.6rem;">Tiêu đề cột</label>
                                    <input type="text" x-model="col.title" class="form-control form-control-sm border-0 bg-white py-2 shadow-sm fs-7 fw-bold" style="letter-spacing: 0.5px;" placeholder="VD: LIÊN HỆ...">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.6rem;">Độ rộng (px)</label>
                                    <div class="input-group input-group-sm shadow-sm rounded-2 overflow-hidden">
                                        <span class="input-group-text border-0 bg-white"><i class="fas fa-arrows-alt-h text-muted" style="font-size: 0.7rem;"></i></span>
                                        <input type="number" x-model="block.content.footer_columns[ci].width" class="form-control border-0 bg-white py-2 fs-7 text-center" placeholder="VD: 250">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.6rem;">Khoảng cách (px)</label>
                                    <div class="input-group input-group-sm shadow-sm rounded-2 overflow-hidden">
                                        <span class="input-group-text border-0 bg-white"><i class="fas fa-arrows-alt-v fa-rotate-90 text-muted" style="font-size: 0.7rem;"></i></span>
                                        <input type="number" x-model="block.content.footer_columns[ci].spacing" class="form-control border-0 bg-white py-2 fs-7 text-center" placeholder="20">
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3">
                                {{-- WYSIWYG Mini Editor --}}
                                <div class="col-md-7">
                                    <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.6rem;">Nội dung văn bản (WYSIWYG)</label>
                                    <div class="footer-editor-container bg-white border rounded-3 overflow-hidden shadow-sm">
                                        <div class="footer-editor-toolbar d-flex gap-1 flex-wrap bg-light border-bottom px-2 py-1">
                                            <button type="button" class="btn btn-sm btn-white border shadow-xs py-0 px-2" @click="document.execCommand('bold')"><b>B</b></button>
                                            <button type="button" class="btn btn-sm btn-white border shadow-xs py-0 px-2" @click="document.execCommand('italic')"><i>I</i></button>
                                            <button type="button" class="btn btn-sm btn-white border shadow-xs py-0 px-2" @click="document.execCommand('underline')"><u>U</u></button>
                                            <div class="vr mx-1 opacity-25"></div>
                                            <button type="button" class="btn btn-sm btn-white border shadow-xs py-0 px-1" @click="document.execCommand('insertUnorderedList')"><i class="fas fa-list-ul" style="font-size:0.65rem;"></i></button>
                                            <button type="button" class="btn btn-sm btn-white border shadow-xs py-0 px-1" @click="document.execCommand('insertOrderedList')"><i class="fas fa-list-ol" style="font-size:0.65rem;"></i></button>
                                            <div class="vr mx-1 opacity-25"></div>
                                            <button type="button" class="btn btn-sm btn-white border shadow-xs py-0 px-1" title="Xóa định dạng" @click="document.execCommand('removeFormat')"><i class="fas fa-eraser" style="font-size:0.65rem;"></i></button>
                                        </div>
                                        <div class="footer-col-editor px-3 py-2 bg-white"
                                            style="min-height: 80px; max-height: 200px; overflow-y: auto; font-size: 0.8rem; line-height: 1.6; outline: none;"
                                            contenteditable="true"
                                            x-ref="'editor_' + ci"
                                            x-init="$el.innerHTML = col.body || ''"
                                            @input="col.body = $el.innerHTML"
                                            @paste.prevent="document.execCommand('insertText', false, $event.clipboardData.getData('text/plain'))">
                                        </div>
                                    </div>
                                    <div class="mt-1 text-muted" style="font-size: 0.6rem;">
                                        <i class="fas fa-info-circle opacity-50 me-1"></i> Nhấn <b>Shift + Enter</b> để xuống dòng sát nhau.
                                    </div>
                                </div>

                                {{-- Link List --}}
                                <div class="col-md-5">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <label class="form-label small fw-bold text-muted text-uppercase mb-0" style="font-size: 0.6rem;">Dánh sách liên kết</label>
                                        <button type="button" class="btn btn-sm btn-light py-0 px-2 border" style="font-size: 0.6rem;" @click="col.links = col.links || []; col.links.push({label: 'Link mới', url: '#'})">
                                            <i class="fas fa-plus me-1"></i> THÊM
                                        </button>
                                    </div>
                                    <div class="d-flex flex-column gap-1 overflow-auto" style="max-height: 180px;">
                                        <template x-if="!col.links || col.links.length === 0">
                                            <div class="p-2 text-center text-muted small bg-white rounded-3 border-dashed border-1 flex-grow-1" style="font-size: 0.65rem;">
                                                Chưa có liên kết nào.
                                            </div>
                                        </template>
                                        <template x-for="(link, li) in col.links" :key="li">
                                            <div class="d-flex gap-1 bg-white p-1 rounded-2 shadow-xs border">
                                                <input type="text" x-model="link.label" class="form-control form-control-sm border-0 p-1 fs-7" style="flex: 2;" placeholder="Nhãn...">
                                                <div class="vr opacity-25"></div>
                                                <input type="text" x-model="link.url" class="form-control form-control-sm border-0 p-1 fs-7" style="flex: 3;" placeholder="URL...">
                                                <button type="button" class="btn btn-sm text-danger border-0 p-1 ms-1" @click="col.links.splice(li, 1)">
                                                    <i class="fas fa-times-circle"></i>
                                                </button>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        {{-- Sync hidden inputs for socials --}}
        <template x-for="(soc, k) in (block.content.socials || [])" :key="'sync_'+k">
            <div>
                <input type="hidden" :name="'block['+index+'][content][socials]['+k+'][img]'" x-model="soc.img">
            </div>
        </template>

        {{-- Frame & Display Settings --}}
        <div class="bg-white p-3 rounded-4 border mb-2 shadow-sm mt-3">
        <div class="d-flex align-items-center mb-3">
            <div class="bg-primary bg-opacity-10 p-2 rounded-3 me-2">
                <i class="fas fa-expand-arrows-alt text-primary"></i>
            </div>
            <h6 class="fw-bold text-dark mb-0 small text-uppercase" style="letter-spacing: 0.5px;">Tùy chỉnh Khung & Hiển thị</h6>
        </div>

        <div class="row g-3">
            {{-- Padding Top --}}
            <div class="col-md-3">
                <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.6rem;">Khoảng cách trên (px)</label>
                <input type="text" x-model="block.content.padding_top" class="form-control form-control-sm border-0 bg-light py-2 shadow-sm" placeholder="VD: 60px">
            </div>
            {{-- Padding Bottom --}}
            <div class="col-md-3">
                <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.6rem;">Khoảng cách dưới (px)</label>
                <input type="text" x-model="block.content.padding_bottom" class="form-control form-control-sm border-0 bg-light py-2 shadow-sm" placeholder="VD: 60px">
            </div>
            {{-- Padding Left --}}
            <div class="col-md-3">
                <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.6rem;">Khoảng cách trái (px)</label>
                <input type="text" x-model="block.content.padding_left" class="form-control form-control-sm border-0 bg-light py-2 shadow-sm" placeholder="VD: 0px">
            </div>
            {{-- Padding Right --}}
            <div class="col-md-3">
                <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.6rem;">Khoảng cách phải (px)</label>
                <input type="text" x-model="block.content.padding_right" class="form-control form-control-sm border-0 bg-light py-2 shadow-sm" placeholder="VD: 0px">
            </div>
        </div>
    </div>
</template>

<style>
.text-footer-admin input:focus, 
.text-footer-admin select:focus {
    box-shadow: none !important;
    border-color: #3b82f6 !important;
    background-color: #fff !important;
}
.shadow-xs {
    box-shadow: 0 1px 2px rgba(0,0,0,0.05);
}
.border-dashed {
    border: 1.5px dashed #cbd5e1 !important;
}
.footer-col-editor:empty:before {
    content: attr(placeholder);
    color: #94a3b8;
    font-style: italic;
}
.footer-editor-toolbar .btn:hover {
    background-color: #f1f5f9;
}
</style>
