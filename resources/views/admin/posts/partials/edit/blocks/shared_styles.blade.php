<div class="bg-white p-3 rounded-4 border-dashed mb-3">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h6 class="mb-0 fw-bold text-dark small text-uppercase">
            <i class="fas fa-palette me-2 text-primary"></i>Cài đặt phong cách (Style)
        </h6>
        <button type="button" class="btn btn-sm btn-light rounded-pill border py-0 px-2"
            @click="block.showStyles = !block.showStyles" style="font-size: 0.65rem;">
            <i class="fas" :class="block.showStyles ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
            <span x-text="block.showStyles ? 'Thu gọn' : 'Mở rộng'"></span>
        </button>
    </div>

    <div x-show="block.showStyles" x-transition class="row g-3">
        {{-- Màu nền & Opacity --}}
        <div class="col-md-4">
            <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.65rem;">Màu
                nền</label>
            <div class="d-flex gap-2 align-items-center">
                <input type="color" x-model="block.content.bg_color"
                    class="form-control-color border-0 bg-transparent p-0" style="width: 32px; height: 32px;">
                <div class="flex-grow-1">
                    <input type="text" x-model="block.content.bg_color"
                        class="form-control form-control-sm bg-light border-0 mb-1 py-0 px-2" placeholder="#ffffff"
                        style="font-size: 0.7rem;">
                    <div class="d-flex align-items-center gap-2">
                        <input type="range" x-model="block.content.bg_opacity" min="0" max="1"
                            step="0.1" class="form-range flex-grow-1" style="height: 10px;">
                    </div>
                </div>
            </div>
        </div>

        {{-- Màu chữ --}}
        <div class="col-md-4">
            <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.65rem;">Màu
                chữ</label>
            <div class="d-flex gap-2 align-items-center">
                <input type="color" x-model="block.content.text_color"
                    class="form-control-color border-0 bg-transparent p-0" style="width: 32px; height: 32px;">
                <input type="text" x-model="block.content.text_color"
                    class="form-control form-control-sm bg-light border-0 py-0 px-2" placeholder="#000000"
                    style="font-size: 0.7rem;">
            </div>
        </div>

        {{-- Cỡ chữ --}}
        <div class="col-md-4">
            <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.65rem;">Cỡ chữ
                (px)</label>
            <input type="number" x-model="block.content.font_size"
                class="form-control form-control-sm bg-light border-0 py-0 px-2" placeholder="16"
                style="font-size: 0.7rem;">
        </div>

        {{-- Phông chữ --}}
        <div class="col-md-4">
            <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.65rem;">Phông
                chữ</label>
            <select x-model="block.content.font_family"
                class="form-select form-select-sm bg-light border-0 shadow-none py-0 px-2" style="font-size: 0.7rem;">
                <option value="">Mặc định</option>
                <option value="'Montserrat', sans-serif">Montserrat</option>
                <option value="'Inter', sans-serif">Inter</option>
                <option value="'Roboto', sans-serif">Roboto</option>
                <option value="'Outfit', sans-serif">Outfit</option>
                <option value="'Playfair Display', serif">Playfair Display</option>
                <option value="system-ui">Hệ thống</option>
            </select>
        </div>

        {{-- Khoảng cách Trên --}}
        <div class="col-md-4">
            <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.65rem;">KC Trên
                (pt - px)</label>
            <input type="number" x-model="block.content.padding_top"
                class="form-control form-control-sm bg-light border-0 py-0 px-2" placeholder="0"
                style="font-size: 0.7rem;">
        </div>

        {{-- Khoảng cách Dưới --}}
        <div class="col-md-4">
            <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.65rem;">KC Dưới
                (pb - px)</label>
            <input type="number" x-model="block.content.padding_y"
                class="form-control form-control-sm bg-light border-0 py-0 px-2" placeholder="10"
                style="font-size: 0.7rem;">
        </div>

        {{-- Khoảng cách Trái --}}
        <div class="col-md-4">
            <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.65rem;">KC Trái
                (pl - px)</label>
            <input type="number" x-model="block.content.padding_left"
                class="form-control form-control-sm bg-light border-0 py-0 px-2" placeholder="0"
                style="font-size: 0.7rem;">
        </div>

        {{-- Khoảng cách Phải --}}
        <div class="col-md-4">
            <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.65rem;">KC Phải
                (pr - px)</label>
            <input type="number" x-model="block.content.padding_right"
                class="form-control form-control-sm bg-light border-0 py-0 px-2" placeholder="0"
                style="font-size: 0.7rem;">
        </div>

        {{-- Ảnh nền --}}
        <div class="col-md-4">
            <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.65rem;">Ảnh
                nền</label>
            <div class="input-group input-group-sm shadow-none rounded overflow-hidden border">
                <button type="button" class="btn btn-sm btn-white border-end text-primary p-1 py-0"
                    @click="openMediaPicker(index, 'bg_image')">
                    <i class="fas fa-image" style="font-size: 0.7rem;"></i>
                </button>
                <input type="text" x-model="block.content.bg_image" class="form-control border-0 bg-white"
                    placeholder="URL..." style="font-size: 0.7rem;">
            </div>
        </div>

        {{-- Khung hiển thị --}}
        <div class="col-md-4">
            <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.65rem;">Tùy
                chọn khung</label>
            <div class="form-check form-switch mt-1">
                <input class="form-check-input" type="checkbox" x-model="block.content.full_width"
                    :id="'block-full-width-' + index">
                <label class="form-check-label small text-muted" :for="'block-full-width-' + index"
                    style="font-size: 0.7rem;">Full-width (Tràn viền)</label>
            </div>
        </div>
    </div>
</div>

{{-- Tiêu đề chung cho Block (Dùng chung cho mọi block nếu cần) --}}
<div class="bg-light p-3 rounded-4 border mb-3" x-init="if (!block.content.title_parts) {
    block.content.title_parts = block.content.section_title ? [{ text: block.content.section_title, color: '#004a80' }] : [];
}
if (!block.content.section_subtitle_color) {
    block.content.section_subtitle_color = '#6b7280';
}
if (!block.content.section_title_align) {
    block.content.section_title_align = 'text-center';
}">
    {{-- Tiêu đề section (theo từng segment có màu) --}}
    <div class="mb-2">
        <div class="d-flex justify-content-between align-items-center mb-1">
            <div class="d-flex align-items-center gap-3">
                <label class="form-label small fw-bold text-muted text-uppercase mb-0" style="font-size:0.65rem;">Tiêu
                    đề
                    Block (Pha màu)</label>
                {{-- Alignment setting --}}
                <div class="bg-white rounded-pill px-2 border d-flex align-items-center gap-1 shadow-sm"
                    style="height:26px;">
                    <button type="button" class="btn btn-sm p-0 px-1 border-0"
                        :class="block.content.section_title_align === 'text-start' ? 'text-primary' : 'text-muted'"
                        @click="block.content.section_title_align = 'text-start'" title="Căn trái">
                        <i class="fas fa-align-left" style="font-size: 0.7rem;"></i>
                    </button>
                    <button type="button" class="btn btn-sm p-0 px-1 border-0"
                        :class="block.content.section_title_align === 'text-center' ? 'text-primary' : 'text-muted'"
                        @click="block.content.section_title_align = 'text-center'" title="Căn giữa">
                        <i class="fas fa-align-center" style="font-size: 0.7rem;"></i>
                    </button>
                    <button type="button" class="btn btn-sm p-0 px-1 border-0"
                        :class="block.content.section_title_align === 'text-end' ? 'text-primary' : 'text-muted'"
                        @click="block.content.section_title_align = 'text-end'" title="Căn phải">
                        <i class="fas fa-align-right" style="font-size: 0.7rem;"></i>
                    </button>
                </div>
            </div>
            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill" style="font-size:0.65rem;"
                @click="block.content.title_parts.push({text: '', color: '#004a80'})">
                <i class="fas fa-plus"></i> Thêm đoạn chữ
            </button>
        </div>

        <div>
            {{-- Hiện từng segment --}}
            <div class="d-flex flex-wrap gap-2 align-items-start mb-1" x-show="block.content.title_parts.length > 0">
                <template x-for="(part, pi) in block.content.title_parts" :key="pi">
                    <div class="d-flex align-items-center gap-1 bg-white border rounded-3 p-1 shadow-sm">
                        <input type="color" x-model="part.color"
                            style="width:28px;height:28px;border:none;padding:2px;cursor:pointer;" title="Chọn màu">
                        <input type="text" x-model="part.text"
                            class="form-control form-control-sm border-0 p-0 fw-bold"
                            style="width:120px;min-width:60px;" placeholder="Nhập text...">
                        <button type="button" class="btn btn-sm text-danger p-0 ms-1"
                            @click="block.content.title_parts.splice(pi, 1)">
                            <i class="fas fa-times" style="font-size:0.7rem;"></i>
                        </button>
                    </div>
                </template>
            </div>

            <div x-show="block.content.title_parts.length === 0" class="text-muted small fst-italic mb-2 opacity-75">
                Chưa có tiêu đề. Nhấn "Thêm đoạn chữ" bên trên để tạo tiêu đề.
            </div>

            {{-- Preview --}}
            <div class="small bg-white border rounded-3 px-2 py-1 text-muted mt-2" style="font-size:0.8rem;"
                x-show="block.content.title_parts.length > 0">
                <span class="me-1 opacity-50">Preview:</span>
                <template x-for="part in block.content.title_parts">
                    <span :style="'color:' + part.color + ';font-weight:bold;'" x-text="part.text"></span>
                </template>
            </div>
        </div>
    </div>

    {{-- Mô tả --}}
    <div class="mb-0 mt-3">
        <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size:0.65rem;">Mô tả & Màu
            chữ</label>
        <div class="d-flex gap-2 align-items-center">
            <input type="color" x-model="block.content.section_subtitle_color"
                class="bg-white border rounded-3 shadow-sm"
                style="width:34px;height:34px;border:none;padding:2px;cursor:pointer;" title="Chọn màu chữ mô tả">
            <input type="text" x-model="block.content.section_subtitle"
                class="form-control form-control-sm border-0 bg-white shadow-sm"
                placeholder="Mô tả tiêu đề (VD: Hơn 500+ khách hàng hài lòng)">
        </div>
    </div>
</div>
