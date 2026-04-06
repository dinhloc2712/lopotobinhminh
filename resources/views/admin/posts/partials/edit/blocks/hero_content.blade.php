<template x-if="block.type === 'hero_content'">
    <div class="mt-2" x-init="
        if (!block.content.section_title && block.content.title) block.content.section_title = block.content.title;
        if (!block.content.section_subtitle && block.content.body) block.content.section_subtitle = block.content.body;
    ">
        <div class="row g-3">
            {{-- Shared Block Styling --}}
            @include('admin.posts.partials.edit.blocks.shared_styles')

            {{-- Nội dung Hero --}}
            <div class="col-12">
                <div class="bg-white p-3 rounded-4 border">
                    <h6 class="mb-3 fw-bold text-dark small text-uppercase">Nội dung Hero</h6>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="small text-muted fw-bold mb-1">Giao diện Đảo chiều</label>
                            <div class="form-check form-switch mt-1">
                                <input class="form-check-input" type="checkbox" x-model="block.content.reverse_layout" :id="'hero-reverse-' + index">
                                <label class="form-check-label small text-muted" :for="'hero-reverse-' + index">Ảnh bên phải, Chữ bên trái</label>
                            </div>
                        </div>

                        
                        {{-- Ảnh / Video đại diện --}}
                        <div class="col-md-6">
                            <label class="small text-muted fw-bold mb-1">Loại media</label>
                            <div class="d-flex gap-2">
                                <label class="d-flex align-items-center gap-1 small">
                                    <input type="radio" x-model="block.content.media_type" value="image" @change="block.content.image = ''"> Ảnh
                                </label>
                                <label class="d-flex align-items-center gap-1 small">
                                    <input type="radio" x-model="block.content.media_type" value="video" @change="block.content.image = ''"> Video
                                </label>
                            </div>

                            {{-- Ảnh --}}
                            <template x-if="!block.content.media_type || block.content.media_type === 'image'">
                                <div class="input-group input-group-sm rounded-3 overflow-hidden shadow-sm mt-1">
                                    <button type="button" class="btn btn-light border-end text-primary p-1 px-2"
                                        @click="openMediaPicker(index, 'image')" title="Chọn ảnh">
                                        <i class="fas fa-image"></i>
                                    </button>
                                    <input type="text" x-model="block.content.image" class="form-control border-0 bg-white small" placeholder="URL ảnh...">
                                </div>
                            </template>

                            {{-- Video --}}
                            <template x-if="block.content.media_type === 'video'">
                                <div class="mt-1">
                                    <input type="text" x-model="block.content.image"
                                        class="form-control form-control-sm border-0 bg-light shadow-sm"
                                        placeholder="Youtube / Vimeo / URL .mp4...">
                                    <div class="form-text small text-muted mt-1">
                                        <i class="fas fa-info-circle"></i>
                                        Hỗ trợ: youtube.com, youtu.be, vimeo.com, .mp4
                                    </div>
                                </div>
                            </template>
                        </div>

                        <div class="col-md-6">
                            <label class="small text-muted fw-bold mb-1">Kiểu hiển thị (Hình dáng ảnh)</label>
                            <select x-model="block.content.image_style" class="form-select form-select-sm bg-light border-0 shadow-sm">
                                <option value="rounded-4">Vuông bo góc (Mặc định)</option>
                                <option value="rounded-circle">Tròn xoe (Circle)</option>
                                <option value="rhombus">Hình thoi (Rhombus)</option>
                                <option value="natural">Tự nhiên (Không ép khung)</option>
                                <option value="shadow-lg rounded-3">Cổ điển có viền bóng</option>
                            </select>
                        </div>

                        {{-- Nút Button --}}
                        <div class="col-12 mt-4" 
                            x-init="if(!block.content.list_items) block.content.list_items = []">
                            <h6 class="fw-bold small text-primary mb-2 border-bottom pb-2">Cấu hình Nút (Button)</h6>
                        </div>

                        <div class="col-md-3">
                            <label class="small text-muted fw-bold mb-1">Nhãn Nút (Button Label)</label>
                            <input type="text" x-model="block.content.btn_label" class="form-control form-control-sm border-0 bg-light shadow-sm" placeholder="VD: Tìm hiểu thêm">
                        </div>
                        <div class="col-md-3">
                            <label class="small text-muted fw-bold mb-1">Đường dẫn (Button Link)</label>
                            <input type="text" x-model="block.content.btn_link" class="form-control form-control-sm border-0 bg-light shadow-sm" placeholder="https://...">
                        </div>
                        <div class="col-md-2">
                            <label class="small text-muted fw-bold mb-1">Icon (fa-class)</label>
                            <input type="text" x-model="block.content.btn_icon" class="form-control form-control-sm border-0 bg-light shadow-sm" placeholder="VD: fas fa-arrow-right">
                        </div>

                        <div class="col-md-2">
                            <label class="small text-muted fw-bold mb-1">Màu nền Nút</label>
                            <div class="d-flex gap-2">
                                <input type="color" x-model="block.content.btn_bg_color" class="form-control-color border-0 p-0 shadow-sm rounded bg-transparent" style="width: 32px; height: 32px;">
                                <input type="text" x-model="block.content.btn_bg_color" class="form-control form-control-sm border-0 bg-light shadow-sm" placeholder="#004a80">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label class="small text-muted fw-bold mb-1">Màu chữ Nút</label>
                            <div class="d-flex gap-2">
                                <input type="color" x-model="block.content.btn_text_color" class="form-control-color border-0 p-0 shadow-sm rounded bg-transparent" style="width: 32px; height: 32px;">
                                <input type="text" x-model="block.content.btn_text_color" class="form-control form-control-sm border-0 bg-light shadow-sm" placeholder="#ffffff">
                            </div>
                        </div>

                        {{-- Danh sách tính năng / Bước (New) --}}
                        <div class="col-12 mt-4">
                            <div class="d-flex justify-content-between align-items-center mb-2 border-bottom pb-2">
                                <h6 class="fw-bold small text-primary mb-0">Danh sách Bước / Tính năng (Hiển thị như hình minh họa)</h6>
                                <button type="button" class="btn btn-sm btn-success rounded-pill px-3" 
                                    @click="block.content.list_items.push({num: '0' + (block.content.list_items.length + 1), title: '', desc: '', icon: 'fas fa-check'})">
                                    <i class="fas fa-plus me-1"></i>Thêm bước
                                </button>
                            </div>
                            
                            <div class="d-flex flex-column gap-2 mt-2">
                                <template x-for="(item, idx) in block.content.list_items" :key="idx">
                                    <div class="bg-light p-3 rounded-3 border position-relative">
                                        <button type="button" class="btn btn-sm text-danger position-absolute top-0 end-0 m-1" @click="block.content.list_items.splice(idx, 1)">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        <div class="row g-2">
                                            <div class="col-md-1">
                                                <label class="small text-muted d-block">Số</label>
                                                <input type="text" x-model="item.num" class="form-control form-control-sm border-0 shadow-none bg-white" placeholder="01">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="small text-muted d-block">Tiêu đề bước</label>
                                                <input type="text" x-model="item.title" class="form-control form-control-sm border-0 shadow-none bg-white font-weight-bold" placeholder="Tên bước...">
                                            </div>
                                            <div class="col-md-5">
                                                <label class="small text-muted d-block">Mô tả ngắn</label>
                                                <input type="text" x-model="item.desc" class="form-control form-control-sm border-0 shadow-none bg-white" placeholder="Mô tả...">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="small text-muted d-block">Icon (fa-*)</label>
                                                <input type="text" x-model="item.icon" class="form-control form-control-sm border-0 shadow-none bg-white" placeholder="fas fa-home">
                                            </div>
                                        </div>
                                    </div>
                                </template>
                                <div x-show="block.content.list_items.length === 0" class="text-center py-3 text-muted border rounded-3 border-dashed small">
                                    Chưa có danh sách bước hiển thị.
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
