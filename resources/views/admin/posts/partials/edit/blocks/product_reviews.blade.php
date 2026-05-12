<template x-if="block.type === 'product_reviews'">
    <div class="mt-2 text-product-reviews-admin"
        x-init="
            if (!block.content.product_id)          block.content.product_id = '';
            if (!block.content.accent_color)         block.content.accent_color = '#C92127';
            if (!block.content.title)                block.content.title = 'ĐÁNH GIÁ SẢN PHẨM';
            if (!block.content.title_font_size)      block.content.title_font_size = 16;
            if (!block.content.container_max_width)  block.content.container_max_width = '1200px';
            if (!block.content.box_shadow)           block.content.box_shadow = 'none';
            if (!block.content.border_radius)        block.content.border_radius = '12px';
            if (block.content.margin_top    === undefined) block.content.margin_top    = 40;
            if (block.content.margin_bottom === undefined) block.content.margin_bottom = 40;
            if (block.content.margin_left   === undefined) block.content.margin_left   = 0;
            if (block.content.margin_right  === undefined) block.content.margin_right  = 0;
            if (block.content.padding_top    === undefined) block.content.padding_top    = 28;
            if (block.content.padding_bottom === undefined) block.content.padding_bottom = 28;
            if (block.content.padding_left   === undefined) block.content.padding_left   = 28;
            if (block.content.padding_right  === undefined) block.content.padding_right  = 28;

            window.copyColor = window.copyColor || ((color) => {
                navigator.clipboard.writeText(color).then(() => {
                    if(window.toastr) toastr.success('Đã copy: ' + color);
                });
            });
            window.pasteColor = window.pasteColor || ((obj, key) => {
                navigator.clipboard.readText().then(text => {
                    if (text.startsWith('#') || text === 'transparent') {
                        obj[key] = text;
                        if(window.toastr) toastr.success('Đã dán màu!');
                    } else {
                        if(window.toastr) toastr.error('Mã màu không hợp lệ!');
                    }
                });
            });
        ">

        {{-- CARD 1: CẤU HÌNH SẢN PHẨM --}}
        <div class="bg-white p-3 border rounded-4 mb-3 shadow-sm">
            <div class="d-flex align-items-center mb-3">
                <div class="bg-primary bg-opacity-10 p-2 rounded-3 me-2">
                    <i class="fas fa-star text-primary"></i>
                </div>
                <h6 class="fw-bold text-dark mb-0 small text-uppercase" style="letter-spacing: 0.5px;">Cấu hình Sản phẩm</h6>
            </div>

            <div class="row g-3">
                <div class="col-md-12">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.75rem;">Chọn Sản phẩm hiển thị đánh giá</label>
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

        {{-- CARD 2: TIÊU ĐỀ & MÀU --}}
        <div class="bg-white p-3 border rounded-4 mb-3 shadow-sm">
            <div class="d-flex align-items-center mb-3">
                <div class="bg-success bg-opacity-10 p-2 rounded-3 me-2">
                    <i class="fas fa-font text-success"></i>
                </div>
                <h6 class="fw-bold text-dark mb-0 small text-uppercase" style="letter-spacing: 0.5px;">Tiêu đề & Màu chủ đạo</h6>
            </div>

            <div class="row g-3">
                {{-- Title text --}}
                <div class="col-md-7">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.75rem;">Nội dung tiêu đề</label>
                    <input type="text" x-model="block.content.title"
                        class="form-control form-control-sm border-0 bg-light py-2 shadow-sm rounded-2 fw-bold"
                        placeholder="VD: ĐÁNH GIÁ SẢN PHẨM">
                </div>

                {{-- Accent color --}}
                <div class="col-md-5">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 0.75rem;">Màu chủ đạo (Accent)</label>
                    <div class="d-flex align-items-center bg-light rounded-2 p-1 gap-1 shadow-sm border" style="height: 38px;">
                        <input type="color" x-model="block.content.accent_color"
                            class="form-control-color border-0 bg-transparent p-0 ms-1" style="width: 24px; height: 24px;">
                        <input type="text" x-model="block.content.accent_color"
                            class="form-control form-control-sm border-0 bg-transparent p-0 fw-bold px-1 flex-grow-1" style="font-size: 0.7rem;">
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>
