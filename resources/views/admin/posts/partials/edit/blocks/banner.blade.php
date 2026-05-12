<template x-if="block.type === 'banner'">
    <div class="mt-2" x-init="
        if (!block.content) block.content = {};
        if (!block.content.height) block.content.height = '600px';
        if (!block.content.layout) block.content.layout = 'slider';
        if (!block.content.items) block.content.items = [
            {image: '', text: 'TIÊU ĐỀ BANNER 1', link_text: 'Mô tả ngắn banner 1', link_url: '#'}
        ];
        if (!block.content.right_items) {
            block.content.right_items = [
                {image: '', link: ''},
                {image: '', link: ''}
            ];
        }
        
        // Helper to get YouTube thumb
        block.getMediaThumb = (url) => {
            if (!url) return '';
            const yMatch = url.match(/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^&?\/\s]{11})/);
            return yMatch ? `https://img.youtube.com/vi/${yMatch[1]}/mqdefault.jpg` : url;
        };

        block.isVideo = (url) => {
            if (!url) return false;
            return url.includes('youtube.com') || url.includes('youtu.be') || url.match(/\.(mp4|webm|ogg)$/i);
        };

        // New Layout & Typography Defaults
        if (!block.content.title_color) block.content.title_color = '#ffffff';
        if (!block.content.title_font_size) block.content.title_font_size = 35;
        if (!block.content.desc_color) block.content.desc_color = '#ffffff';
        if (!block.content.desc_font_size) block.content.desc_font_size = 18;
        if (!block.content.padding_top) block.content.padding_top = '0px';
        if (!block.content.padding_bottom) block.content.padding_bottom = '0px';
        if (!block.content.padding_left) block.content.padding_left = '0px';
        if (!block.content.padding_right) block.content.padding_right = '0px';
        if (typeof block.content.show_overlay === 'undefined') block.content.show_overlay = true;
    ">
        
        {{-- Removed: Card 1 Typography & Colors (Done previously) --}}

        {{-- Removed: Card 2 Typography & Colors --}}

        {{-- Card 3: Frame & Spacing --}}
        <div class="bg-white p-3 border rounded-4 mb-3 shadow-sm">
            <div class="d-flex align-items-center mb-3">
                <div class="bg-info bg-opacity-10 p-2 rounded-3 me-2">
                    <i class="fas fa-expand-arrows-alt text-info"></i>
                </div>
                <h6 class="fw-bold text-dark mb-0 small text-uppercase" style="letter-spacing: 0.5px;">Tùy chỉnh Khung & Hiển thị</h6>
            </div>

            <div class="row g-3">
                {{-- Row 1: Height & Layout --}}
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.6rem;">Chiều cao (px)</label>
                    <input type="text" x-model="block.content.height" class="form-control form-control-sm border-0 bg-light py-2 shadow-sm" placeholder="VD: 600">
                </div>
                <div class="col-md-8">
                    <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.6rem;">Kiểu hiển thị</label>
                    <select x-model="block.content.layout" class="form-select form-select-sm border-0 bg-light py-2 shadow-sm">
                        <option value="slider">Mặc định (Toàn màn hình)</option>
                        <option value="split">Chia đôi (Slide trái + Ảnh phải)</option>
                    </select>
                </div>
                
                {{-- Row 2: Padding --}}
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.6rem;">Cách lề trên (px)</label>
                    <input type="text" x-model="block.content.padding_top" class="form-control form-control-sm border-0 bg-light py-2 shadow-sm" placeholder="VD: 0px">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.6rem;">Cách lề dưới (px)</label>
                    <input type="text" x-model="block.content.padding_bottom" class="form-control form-control-sm border-0 bg-light py-2 shadow-sm" placeholder="VD: 0px">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.6rem;">Cách lề trái (px)</label>
                    <input type="text" x-model="block.content.padding_left" class="form-control form-control-sm border-0 bg-light py-2 shadow-sm" placeholder="VD: 0px">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.6rem;">Cách lề phải (px)</label>
                    <input type="text" x-model="block.content.padding_right" class="form-control form-control-sm border-0 bg-light py-2 shadow-sm" placeholder="VD: 0px">
                </div>
            </div>
        </div>

        {{-- Card 4: Slide Content Template --}}
        <div class="bg-white p-3 border rounded-4 mb-3 shadow-sm">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center">
                    <div class="bg-warning bg-opacity-10 p-2 rounded-3 me-2">
                        <i class="fas fa-images text-warning"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-0 small text-uppercase" style="letter-spacing: 0.5px;">Danh sách Slide (Ảnh/Video)</h6>
                </div>
                <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 fw-bold" style="font-size: 0.6rem;"
                    @click="if(!block.content.items) block.content.items = []; block.content.items.push({image: '', text: 'Tiêu đề mới', link_text: '', link_url: '#'})">
                    <i class="fas fa-plus me-1"></i> THÊM SLIDE
                </button>
            </div>

            <div class="d-flex flex-column gap-3">
                <template x-for="(item, i) in (block.content.items || [])" :key="i">
                    <div class="card border-0 bg-light rounded-4 overflow-hidden shadow-sm">
                        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-2 px-3">
                            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill fw-bold" style="font-size: 0.65rem;" x-text="'SLIDE ' + (i + 1)"></span>
                            <button type="button" class="btn btn-sm btn-outline-danger border-0" @click="block.content.items.splice(i, 1)">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                        <div class="card-body p-3">
                            <div class="row g-3">
                                {{-- Preview & Media Choice --}}
                                <div class="col-md-4">
                                    <div class="bg-white p-2 rounded-3 border h-100 d-flex flex-column align-items-center justify-content-center" style="min-height: 120px;">
                                        <template x-if="item.image">
                                            <div class="w-100 text-center position-relative">
                                                <button type="button" class="btn btn-danger btn-sm rounded-circle position-absolute top-0 end-0 m-1 z-3 shadow-sm p-0 d-flex align-items-center justify-content-center" 
                                                        style="width: 20px; height: 20px; font-size: 0.6rem;" 
                                                        @click="item.image = ''">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                                <template x-if="!item.image.match(/\.(mp4|webm|ogg)$/i)">
                                                    <img :src="block.getMediaThumb(item.image)" class="rounded-3 w-100 shadow-sm border" style="height: 120px; object-fit: cover;">
                                                </template>
                                                <template x-if="item.image.match(/\.(mp4|webm|ogg)$/i)">
                                                    <div class="bg-dark rounded-3 d-flex align-items-center justify-content-center text-white" style="height: 120px;">
                                                        <i class="fas fa-video fa-2x"></i>
                                                    </div>
                                                </template>
                                            </div>
                                        </template>
                                        <template x-if="!item.image">
                                            <div class="text-muted small text-center opacity-50"><i class="fas fa-image fa-2x d-block mb-1"></i>Chưa có ảnh/video</div>
                                        </template>
                                        <button type="button" class="btn btn-sm btn-outline-primary mt-2 w-100 fw-bold" @click="openMediaPicker(index, 'items.' + i + '.image')" style="font-size: 0.65rem;">
                                            CHỌN MEDIA
                                        </button>
                                    </div>
                                </div>
                                {{-- Inputs --}}
                                <div class="col-md-8">
                                    <div class="mb-2">
                                        <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.55rem;">Link Ảnh/Video/Youtube</label>
                                        <input type="text" x-model="item.image" class="form-control form-control-sm border-0 bg-white py-2 shadow-sm fs-7" placeholder="URL media...">
                                    </div>
                                    <div class="mb-2" x-show="!block.isVideo(item.image)">
                                        <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.55rem;">Đường dẫn Link (URL)</label>
                                        <input type="text" x-model="item.link_url" class="form-control form-control-sm border-0 bg-white py-2 shadow-sm fs-7" placeholder="https://...">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        {{-- Split Layout Auxiliary Items (Conditional) --}}
        <template x-if="block.content.layout === 'split'">
            <div class="bg-white p-3 border rounded-4 mb-3 shadow-sm">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-danger bg-opacity-10 p-2 rounded-3 me-2">
                        <i class="fas fa-th-large text-danger"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-0 small text-uppercase" style="letter-spacing: 0.5px;">Cấu hình cột phụ (Bên phải)</h6>
                </div>
                <div class="row g-3">
                    <template x-for="(ritem, ri) in (block.content.right_items || [])" :key="ri">
                        <div class="col-md-6">
                            <div class="bg-light p-3 rounded-4 border shadow-sm">
                                <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill fw-bold mb-2 shadow-none" style="font-size: 0.6rem;" x-text="'ẢNH PHỤ ' + (ri + 1)"></span>
                                <div class="mb-2 position-relative" x-show="ritem.image">
                                    <button type="button" class="btn btn-danger btn-sm rounded-circle position-absolute top-0 end-0 m-1 z-3 shadow-sm p-0 d-flex align-items-center justify-content-center" 
                                            style="width: 20px; height: 20px; font-size: 0.6rem;" 
                                            @click="ritem.image = ''">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    <img :src="block.getMediaThumb(ritem.image)" class="rounded-3 w-100 shadow-sm border" style="height: 100px; object-fit: cover;">
                                </div>
                                <div class="input-group input-group-sm mb-2 shadow-sm rounded overflow-hidden">
                                    <button type="button" class="btn btn-white border shadow-none" @click="openMediaPicker(index, 'right_items.' + ri + '.image')">
                                        <i class="fas fa-image text-muted"></i>
                                    </button>
                                    <input type="text" x-model="ritem.image" class="form-control border-0 bg-white py-2 fs-7" placeholder="URL Ảnh...">
                                </div>
                                <input type="text" x-model="ritem.link" class="form-control form-control-sm border-0 bg-white py-2 shadow-sm fs-7" placeholder="Đường dẫn (URL)..." x-show="!block.isVideo(ritem.image)">
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </template>
    </div>
</template>
