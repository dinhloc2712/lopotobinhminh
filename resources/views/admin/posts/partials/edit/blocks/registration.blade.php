<template x-if="block.type === 'registration'">
    <div class="bg-white p-3 rounded-4 border mb-3">
{{-- removed shared_styles --}}

        {{-- Content Settings Section --}}
        <div class="d-flex align-items-center justify-content-between mb-3 pt-3 border-top">
            <h6 class="mb-0 fw-bold text-dark small text-uppercase">
                <i class="fas fa-file-signature me-2 text-primary"></i>Nội dung Đăng ký
            </h6>
        </div>

        <div class="row g-3 px-2 mb-4">
            {{-- Tiêu đề --}}
            <div class="col-md-12">
                <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.65rem;">Tiêu đề chính</label>
                <input type="text" x-model="block.content.title"
                    class="form-control form-control-sm bg-light border-0 py-2"
                    placeholder="VD: ĐĂNG KÝ TÀI KHOẢN">
            </div>

            {{-- Mô tả phụ --}}
            <div class="col-md-12">
                <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.65rem;">Mô tả phụ</label>
                <textarea x-model="block.content.subtitle"
                    class="form-control form-control-sm bg-light border-0 py-2" rows="2"
                    placeholder="VD: Tham gia để nhận ưu đãi đặc quyền..."></textarea>
            </div>

            {{-- Nhãn nút --}}
            <div class="col-md-6">
                <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.65rem;">Nhãn nút gửi</label>
                <input type="text" x-model="block.content.button_label"
                    class="form-control form-control-sm bg-light border-0 py-2"
                    placeholder="VD: Tạo tài khoản ngay">
            </div>

            {{-- Ảnh minh họa --}}
            <div class="col-md-6">
                <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.65rem;">Ảnh minh họa</label>
                <div class="input-group input-group-sm shadow-none rounded overflow-hidden border">
                    <button type="button" class="btn btn-sm btn-white border-end text-primary p-1 py-0"
                        @click="openMediaPicker(index, 'image')">
                        <i class="fas fa-image" style="font-size: 0.7rem;"></i>
                    </button>
                    <input type="text" x-model="block.content.image" class="form-control border-0 bg-white"
                        placeholder="URL ảnh..." style="font-size: 0.7rem;">
                </div>
            </div>

            {{-- Redirect To --}}
            <div class="col-md-12">
                <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.65rem;">Đường dẫn chuyển hướng sau đăng ký</label>
                <input type="text" x-model="block.content.redirect_to"
                    class="form-control form-control-sm bg-light border-0 py-2"
                    placeholder="VD: /admin/profile hoặc /trang-chu">
                <div class="form-text mt-1" style="font-size: 0.6rem; opacity: 0.7;">Để trống để mặc định về trang cá nhân.</div>
            </div>
        </div>

        {{-- Appearance & Layout Settings Section --}}
        <div class="d-flex align-items-center justify-content-between mb-3 pt-3 border-top">
            <h6 class="mb-0 fw-bold text-dark small text-uppercase">
                <i class="fas fa-layer-group me-2 text-primary"></i>Bố cục & Màu sắc nâng cao
            </h6>
        </div>

        <div class="row g-3 px-2">
            {{-- Accent Color (Primary) --}}
            <div class="col-md-6">
                <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.65rem;">Màu nhấn (Accent)</label>
                <div class="input-group input-group-sm">
                    <input type="color" x-model="block.content.accent_color"
                        class="form-control form-control-color border-0 p-0" style="width:36px; height:31px;">
                    <input type="text" x-model="block.content.accent_color"
                        class="form-control bg-light border-0" placeholder="#004a80" style="font-size: 0.75rem;">
                </div>
            </div>

            {{-- Button Text Color --}}
            <div class="col-md-6">
                <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.65rem;">Màu chữ nút</label>
                <div class="input-group input-group-sm">
                    <input type="color" x-model="block.content.btn_text_color"
                        class="form-control form-control-color border-0 p-0" style="width:36px; height:31px;">
                    <input type="text" x-model="block.content.btn_text_color"
                        class="form-control bg-light border-0" placeholder="#ffffff" style="font-size: 0.75rem;">
                </div>
            </div>

            {{-- Đảo ngược bố cục --}}
            <div class="col-md-12">
                <div class="form-check form-switch mt-1">
                    <input class="form-check-input" type="checkbox" x-model="block.content.reverse_layout"
                        id="reg_reverse_layout">
                    <label class="form-check-label small fw-semibold text-muted" for="reg_reverse_layout">
                        Đảo ngược bố cục (Ảnh bên phải, Form bên trái)
                    </label>
                </div>
            </div>
        </div>
    </div>
</template>
