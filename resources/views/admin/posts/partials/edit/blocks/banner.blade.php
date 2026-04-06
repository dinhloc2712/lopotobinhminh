<template x-if="block.type === 'banner'">
    <div class="mt-2">
        @include('admin.posts.partials.edit.blocks.shared_styles')
        
        <div class="row mb-3 align-items-center">
            <div class="col-md-3">
                <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.65rem;">Chiều cao (VD: 400px, 100vh)</label>
                <input type="text" x-model="block.content.height" class="form-control form-control-sm bg-light border-0 py-2" placeholder="VD: 400px">
            </div>
            <div class="col-md-5">
                <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.65rem;">Kiểu hiển thị</label>
                <select x-model="block.content.layout" class="form-select form-select-sm bg-light border-0 py-2">
                    <option value="slider">Mặc định (Toàn màn hình)</option>
                    <option value="split">Chia đôi (Slide trái + Ảnh phải)</option>
                </select>
            </div>
            <div class="col-md-4 mt-3 mt-md-0">
                <div class="form-check form-switch d-flex align-items-center gap-2">
                    <input class="form-check-input mt-0" type="checkbox" 
                           x-init="if(typeof block.content.show_overlay === 'undefined') block.content.show_overlay = true" 
                           x-model="block.content.show_overlay" style="width: 35px; height: 18px; cursor: pointer;">
                    <label class="form-check-label small fw-bold text-muted text-uppercase mb-0" style="font-size: 0.65rem; padding-top:2px; cursor: pointer;">Bật Lớp phủ làm tối nền</label>
                </div>
            </div>
        </div>

        {{-- Cấu hình cột bên phải khi chọn chia đôi --}}
        <template x-if="block.content.layout === 'split'">
            <div class="bg-light p-3 rounded-4 border mb-3">
                <label class="form-label small fw-bold text-muted text-uppercase mb-2" style="font-size: 0.65rem;">Cột bên phải (Tối đa 2 Ảnh)</label>
                <div class="row g-3">
                    <template x-for="(ritem, ri) in block.content.right_items">
                        <div class="col-md-6">
                            <div class="bg-white p-2 rounded-3 border">
                                <label class="small text-muted fw-bold mb-1" x-text="'Ảnh phụ ' + (ri+1)"></label>
                                <div class="mb-2" x-show="ritem.image">
                                    <img :src="ritem.image" class="rounded-3 w-100 shadow-sm border" style="height: 80px; object-fit: cover;">
                                </div>
                                <div class="input-group input-group-sm mb-2 shadow-none rounded">
                                    <button type="button" class="btn btn-light border-end fw-bold text-primary" @click="openMediaPicker(index, 'right_items.' + ri + '.image')" title="Chọn Hình Ảnh">
                                        <i class="fas fa-image"></i>
                                    </button>
                                    <input type="text" x-model="ritem.image" class="form-control border bg-light" placeholder="URL Ảnh...">
                                </div>
                                <input type="text" x-model="ritem.link" class="form-control form-control-sm border bg-light" placeholder="Link (URL)...">
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </template>
        
        <div>
            <div class="d-flex justify-content-between align-items-center mb-2">
                <label class="form-label small fw-bold text-muted text-uppercase mb-0" style="font-size: 0.65rem;">Danh sách Slide (Hình Ảnh / Video)</label>
                <button type="button"
                    class="btn btn-sm btn-outline-primary rounded-pill fw-bold"
                    style="font-size: 0.7rem;"
                    @click="if(!block.content.items) block.content.items = []; block.content.items.push({image: '', text: '', link_text: '', link_url: ''})">
                    <i class="fas fa-plus"></i> Thêm slide
                </button>
            </div>
            <div class="d-flex flex-column gap-2 mb-3">
                <template x-for="(item, i) in block.content.items">
                    <div class="bg-white p-2 rounded-3 border">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="small text-muted fw-bold" x-text="'Slide ' + (i+1)"></span>
                            <button type="button" class="btn btn-sm text-danger p-0" @click="block.content.items.splice(i, 1)"><i class="fas fa-times"></i></button>
                        </div>
                        <div class="mb-2" x-show="item.image && !item.image.match(/\.(mp4|webm|ogg)$/i)">
                            <img :src="item.image" class="rounded-3 w-100 shadow-sm border" style="height: 120px; object-fit: cover;">
                        </div>
                        <div class="mb-2 bg-dark rounded-3 d-flex align-items-center justify-content-center" x-show="item.image && item.image.match(/\.(mp4|webm|ogg)$/i)" style="height: 120px;">
                            <i class="fas fa-video text-white fs-3"></i>
                        </div>
                        <div class="input-group input-group-sm mb-2 shadow-none rounded">
                            <button type="button" class="btn btn-light border-end fw-bold text-primary" @click="openMediaPicker(index, 'items.' + i + '.image')" title="Chọn Hình Ảnh hoặc Video MP4">
                                <i class="fas fa-photo-video"></i>
                            </button>
                            <input type="text" x-model="item.image" class="form-control border bg-light" placeholder="URL Ảnh hoặc Video (Youtube/Vimeo/MP4)...">
                        </div>
                        <textarea x-model="item.text" class="form-control form-control-sm border bg-light mt-2" rows="2" placeholder="Nhập Tiêu đề lớn (Title)..."></textarea>
                        <div class="row g-2 mt-1">
                            <div class="col-6">
                                <input type="text" x-model="item.link_text" class="form-control form-control-sm border bg-light" placeholder="Text thường (Mô tả, nội dung, HTML)...">
                            </div>
                            <div class="col-6">
                                <input type="text" x-model="item.link_url" class="form-control form-control-sm border bg-light" placeholder="Đường dẫn (URL)...">
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</template>
