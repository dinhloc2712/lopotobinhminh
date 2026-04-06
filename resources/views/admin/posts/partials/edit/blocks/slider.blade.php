<template x-if="block.type === 'slider'">
    <div class="mt-2 text-start">
        @include('admin.posts.partials.edit.blocks.shared_styles')
        
        <div class="row g-2 mb-3 align-items-end">
            <div class="col-md-2">
                <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.65rem;">Hiệu ứng</label>
                <select x-model="block.content.transition" x-init="if(!block.content.transition) block.content.transition = 'slide'" class="form-select form-select-sm border-0 bg-light py-2">
                    <option value="slide">Slide (Trượt ngang)</option>
                    <option value="fade">Fade (Mờ dần)</option>
                    <option value="center_focus">Center Focus (Tiêu điểm giữa)</option>
                    <option value="accordion">Accordion (Mở rộng khi trỏ chuột)</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.65rem;">Số Slide/Hàng</label>
                <select x-model="block.content.slides_per_view" x-init="if(!block.content.slides_per_view) block.content.slides_per_view = '1'" class="form-select form-select-sm border-0 bg-light py-2">
                    <option value="auto">Tự động (Theo kích thước ảnh)</option>
                    <option value="1">1 Slide</option>
                    <option value="2">2 Slide</option>
                    <option value="3">3 Slide</option>
                    <option value="4">4 Slide</option>
                    <option value="5">5 Slide</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.65rem;">Kích thước (Rộng)</label>
                <input type="text" x-model="block.content.image_size" x-init="if(typeof block.content.image_size === 'undefined') block.content.image_size = ''" class="form-control form-control-sm border-0 bg-light py-2" placeholder="VD: 300px, 80%">
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.65rem;">Hình dạng Ảnh</label>
                <select x-model="block.content.image_shape" x-init="if(!block.content.image_shape) block.content.image_shape = 'rectangle'" class="form-select form-select-sm border-0 bg-light py-2">
                    <option value="rectangle">Chữ nhật</option>
                    <option value="square">Vuông 1:1</option>
                    <option value="circle">Tròn (Circle)</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.65rem;">Vị trí Văn bản</label>
                <select x-model="block.content.text_position" x-init="if(!block.content.text_position) block.content.text_position = 'overlay'" class="form-select form-select-sm border-0 bg-light py-2">
                    <option value="overlay">Đè lên ảnh (Overlay)</option>
                    <option value="top">Phía trên ảnh</option>
                    <option value="bottom">Phía dưới ảnh</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.65rem;">Nút chuyển</label>
                <select x-model="block.content.show_nav" x-init="if(typeof block.content.show_nav === 'undefined') block.content.show_nav = 'yes'" class="form-select form-select-sm border-0 bg-light py-2">
                    <option value="yes">Hiện mũi tên</option>
                    <option value="no">Ẩn mũi tên</option>
                </select>
            </div>
            
            <div class="col-md-3 mt-3">
                <div class="d-flex gap-3">
                    <div>
                        <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.65rem;">Màu Tiêu đề</label>
                        <div class="d-flex align-items-center gap-2">
                            <input type="color" x-model="block.content.title_color" class="form-control form-control-sm form-control-color border-0 p-1 bg-light" style="width: 35px; height: 32px;" title="Màu Tiêu đề">
                            <button type="button" class="btn btn-sm btn-link text-muted p-0 text-decoration-none" @click="block.content.title_color = null" x-show="block.content.title_color" style="font-size: 0.7rem;"><i class="fas fa-times"></i></button>
                        </div>
                    </div>
                    <div>
                        <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.65rem;">Màu Mô tả</label>
                        <div class="d-flex align-items-center gap-2">
                            <input type="color" x-model="block.content.desc_color" class="form-control form-control-sm form-control-color border-0 p-1 bg-light" style="width: 35px; height: 32px;" title="Màu Mô tả">
                            <button type="button" class="btn btn-sm btn-link text-muted p-0 text-decoration-none" @click="block.content.desc_color = null" x-show="block.content.desc_color" style="font-size: 0.7rem;"><i class="fas fa-times"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 mt-3">
                <div class="d-flex gap-3">
                    <div>
                        <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.65rem;">Cỡ chữ Tiêu đề</label>
                        <input type="number" x-model="block.content.title_font_size" class="form-control form-control-sm border-0 bg-light py-2" placeholder="px" style="max-width: 80px;">
                    </div>
                    <div>
                        <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.65rem;">Cỡ chữ Mô tả</label>
                        <input type="number" x-model="block.content.desc_font_size" class="form-control form-control-sm border-0 bg-light py-2" placeholder="px" style="max-width: 80px;">
                    </div>
                </div>
            </div>

            <div class="col-md-2 mt-3" x-show="block.content.transition === 'center_focus'" x-cloak>
                <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.65rem;">Độ mờ Slide bên</label>
                <select x-model="block.content.inactive_opacity" x-init="if(typeof block.content.inactive_opacity === 'undefined') block.content.inactive_opacity = '0.4'" class="form-select form-select-sm border-0 bg-light py-2">
                    <option value="0.1">10%</option>
                    <option value="0.2">20%</option>
                    <option value="0.3">30%</option>
                    <option value="0.4">40% (Mặc định)</option>
                    <option value="0.5">50%</option>
                    <option value="0.6">60%</option>
                    <option value="0.7">70%</option>
                    <option value="0.8">80%</option>
                    <option value="0.9">90%</option>
                    <option value="1">100% (Rõ nét)</option>
                </select>
            </div>

            <div class="col-md-2 mt-3">
                <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.65rem;">T.Gian Trượt</label>
                <select x-model="block.content.autoplay_delay" x-init="if(typeof block.content.autoplay_delay === 'undefined') block.content.autoplay_delay = '4000'" class="form-select form-select-sm border-0 bg-light py-2">
                    <option value="2000">2 Giây</option>
                    <option value="3000">3 Giây</option>
                    <option value="4000">4 Giây (Mặc định)</option>
                    <option value="5000">5 Giây</option>
                    <option value="6000">6 Giây</option>
                    <option value="8000">8 Giây</option>
                    <option value="0">Tắt tự động</option>
                </select>
            </div>

            <div class="col-md-2 mt-3" x-show="block.content.slides_per_view !== '1' || block.content.transition === 'center_focus'" x-cloak>
                <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.65rem;">KC Giữa Slide (px)</label>
                <input type="number" x-model="block.content.space_between" x-init="if(typeof block.content.space_between === 'undefined') block.content.space_between = '20'" class="form-control form-control-sm border-0 bg-light py-2" placeholder="Ví dụ: 20">
            </div>

            <div class="col-md-2 mt-3 ms-auto d-flex justify-content-end text-end align-items-end">
                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill fw-bold w-100" style="font-size: 0.7rem; padding-top: 0.5rem; padding-bottom: 0.5rem;" @click="if(!block.content.items) block.content.items = []; block.content.items.push({image: '', text: '', desc: '', link_text: '', link_url: ''})">
                    <i class="fas fa-plus"></i> Thêm Slide Mới
                </button>
            </div>
        </div>

        <div class="d-flex flex-column gap-3 mb-3">
            <template x-for="(item, i) in block.content.items">
                <div class="bg-white p-3 rounded-4 border position-relative">
                    <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 rounded-circle" 
                            style="width: 24px; height: 24px; padding: 0; line-height: 24px; transform: translate(30%, -30%); box-shadow: 0 2px 4px rgba(0,0,0,0.1);" 
                            @click="block.content.items.splice(i, 1)"><i class="fas fa-times fs-6"></i></button>
                            
                    <span class="small text-muted fw-bold text-uppercase d-block mb-2" x-text="'SLIDE ' + (i+1)" style="font-size: 0.65rem;"></span>
                    
                    <div class="input-group input-group-sm mb-3 shadow-sm rounded-3 overflow-hidden">
                        <button type="button" class="btn btn-primary fw-bold border-0" style="min-width: 45px;" @click="openMediaPicker(index, 'items.' + i + '.image')" title="Chọn Ảnh/Video">
                            <i class="fas fa-photo-video"></i>
                        </button>
                        <input type="text" x-model="item.image" class="form-control border-0 bg-light py-2" placeholder="URL Ảnh hoặc Video (Youtube/Vimeo/MP4)...">
                    </div>
                    
                    <div class="row g-2">
                        <div class="col-12">
                            <label class="form-label small text-muted mb-1" style="font-size: 0.65rem;">Tiêu đề Slide</label>
                            <input type="text" x-model="item.text" class="form-control form-control-sm border-0 bg-light py-2" placeholder="Nhập Tiêu đề lớn...">
                        </div>
                        <div class="col-12">
                            <label class="form-label small text-muted mb-1" style="font-size: 0.65rem;">Mô tả chi tiết</label>
                            <textarea x-model="item.desc" class="form-control form-control-sm border-0 bg-light" rows="2" placeholder="Nhập Nội dung mô tả ngắn..."></textarea>
                        </div>
                        
                        <!-- Meta Info row -->
                        <div class="col-4">
                            <label class="form-label small text-muted mb-1" style="font-size: 0.65rem;">Icon phụ (VD: fa-user)</label>
                            <input type="text" x-model="item.meta_icon" class="form-control form-control-sm border-0 bg-light py-2" placeholder="VD: fa-user">
                        </div>
                        <div class="col-4">
                            <label class="form-label small text-muted mb-1" style="font-size: 0.65rem;">Chữ phụ (Bên trái)</label>
                            <input type="text" x-model="item.meta_text" class="form-control form-control-sm border-0 bg-light py-2" placeholder="VD: 1,000">
                        </div>
                        <div class="col-4">
                            <label class="form-label small text-muted mb-1" style="font-size: 0.65rem;">Điểm Đánh giá (Bên phải)</label>
                            <input type="text" x-model="item.rating" class="form-control form-control-sm border-0 bg-light py-2" placeholder="VD: 4.8">
                        </div>
                        
                        <div class="col-6">
                            <label class="form-label small text-muted mb-1" style="font-size: 0.65rem;">Chữ ở Nút bấm</label>
                            <input type="text" x-model="item.link_text" class="form-control form-control-sm border-0 bg-light py-2" placeholder="VD: Khám phá ngay">
                        </div>
                        <div class="col-6">
                            <label class="form-label small text-muted mb-1" style="font-size: 0.65rem;">Đường dẫn (URL)</label>
                            <input type="text" x-model="item.link_url" class="form-control form-control-sm border-0 bg-light py-2" placeholder="VD: https://...">
                        </div>
                    </div>
                </div>
            </template>
        </div>
        
        <!-- Script chuyển đổi dữ liệu cũ -->
        <div x-init="
            if (block.content.urls && typeof block.content.urls === 'string') {
                if (!block.content.items) block.content.items = [];
                let urls = block.content.urls.split('\n').map(u => u.trim()).filter(u => u !== '');
                urls.forEach(u => block.content.items.push({image: u, text: '', desc: '', link_text: '', link_url: ''}));
                delete block.content.urls;
            } else if (block.content.items && Array.isArray(block.content.items)) {
                // Đổi caption thành desc nếu có từ cũ
                block.content.items.forEach(it => {
                    if (it.url && !it.image) it.image = it.url;
                    if (it.caption && !it.text) it.text = it.caption;
                });
            }
        "></div>
    </div>
</template>
