<template x-if="block.type === 'image'">
    <div class="mt-2" @on-image-block-added.window="
        if (window.activeBlockUidForImage === block.uid) {
            if(!block.content.images) block.content.images = [];
            block.content.images.push({url: $event.detail.url, alt: '', caption: '', col_lg: '', col_md: '', col_sm: ''});
            window.activeBlockUidForImage = null;
        }
    ">
{{-- removed shared_styles --}}

        <div class="row g-2 mb-3">
            <div class="col-md">
                <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.65rem;">Kiểu hiển thị</label>
                <select x-model="block.content.display_type" x-init="if (!block.content.display_type) block.content.display_type = 'single'"
                    class="form-select form-select-sm border-0 bg-light py-2">
                    <option value="single">Xếp dọc (Mặc định)</option>
                    <option value="grid">Album (Lưới/Grid)</option>
                    <option value="masonry">Album (Xếp gạch/Masonry)</option>
                    <option value="mosaic">Album (Ghép/Mosaic Bento)</option>
                </select>
            </div>
            <div class="col-md"
                x-show="block.content.display_type === 'grid' || block.content.display_type === 'masonry'">
                <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.65rem;">Số cột</label>
                <select x-model="block.content.grid_columns" x-init="if (!block.content.grid_columns) block.content.grid_columns = '2-4'"
                    class="form-select form-select-sm border-0 bg-light py-2">
                    <option value="1-2">1 cột - 2 cột</option>
                    <option value="2-2">2 cột - 2 cột</option>
                    <option value="2-3">2 cột - 3 cột</option>
                    <option value="2-4">2 cột - 4 cột</option>
                    <option value="3-6">3 cột - 6 cột</option>
                </select>
            </div>
            <div class="col-md"
                x-show="block.content.display_type === 'grid' || block.content.display_type === 'masonry'">
                <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.65rem;">Cao ảnh / Tỷ lệ</label>
                <input type="text" x-model="block.content.image_height" x-init="if (!block.content.image_height && block.content.image_ratio) { block.content.image_height = block.content.image_ratio; }"
                    class="form-control form-control-sm border-0 bg-light py-2" placeholder="VD: 300px hoặc 1/1">
            </div>
            <div class="col-md">
                <label class="form-label small fw-bold text-muted text-uppercase mb-1"
                    style="font-size: 0.65rem;">Khoảng cách</label>
                <input type="number" x-model="block.content.image_gap"
                    class="form-control form-control-sm border-0 bg-light py-2" placeholder="px">
            </div>
            <div class="col-md">
                <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.65rem;">Bo góc</label>
                <input type="number" x-model="block.content.image_radius"
                    class="form-control form-control-sm border-0 bg-light py-2" placeholder="px">
            </div>
        </div>

        <!-- Chế độ ảnh (Chung) -->
        <div>
            <div class="mb-2">
                <label class="form-label small fw-bold text-muted text-uppercase mb-0" style="font-size: 0.65rem;">Danh
                    sách hình ảnh</label>
            </div>

            <div class="d-flex flex-column gap-3" x-init="if (!block.content.images || block.content.images.length === 0) {
                block.content.images = [];
                if (block.content.url) {
                    block.content.images.push({
                        url: block.content.url,
                        alt: block.content.alt || '',
                        caption: block.content.caption || ''
                    });
                } else {
                    block.content.images.push({ url: '', alt: '', caption: '' });
                }
            }">
                <template x-for="(img, i) in block.content.images">
                    <div class="bg-white p-3 border rounded-3 position-relative shadow-sm">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-light text-secondary border">Ảnh <span x-text="i + 1"></span></span>
                            <button type="button" class="btn btn-sm btn-danger rounded-circle p-0"
                                style="width: 24px; height: 24px; line-height: 24px;"
                                @click="block.content.images.splice(i, 1)" title="Xóa ảnh này">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div class="d-flex gap-3">
                            <div class="flex-shrink-0" style="width: 100px; height: 100px;">
                                <div class="w-100 h-100 rounded-3 bg-light d-flex align-items-center justify-content-center overflow-hidden position-relative"
                                    style="cursor: pointer; border: 2px dashed #dee2e6;"
                                    @click="openMediaPicker(index, 'images.' + i + '.url')" title="Nhấn để chọn ảnh">
                                    <template x-if="img.url">
                                        <img :src="img.url" class="w-100 h-100" style="object-fit: cover;">
                                    </template>
                                    <template x-if="!img.url">
                                        <div class="text-center text-muted">
                                            <i class="fas fa-plus fa-lg"></i>
                                        </div>
                                    </template>
                                </div>
                            </div>
                            <div class="flex-grow-1 d-flex flex-column justify-content-center gap-2">
                                <input type="text" x-model="img.alt"
                                    class="form-control form-control-sm border-0 bg-light"
                                    placeholder="Mô tả ảnh (Alt text)...">
                                <input type="text" x-model="img.caption"
                                    class="form-control form-control-sm border-0 bg-light"
                                    placeholder="Chú thích ảnh...">
                                <div class="row g-2" x-show="block.content.display_type !== 'masonry'">
                                    <div class="col-4">
                                        <select @change="img.col_lg = $event.target.value" class="form-select form-select-sm border-0 bg-light">
                                            <option value="">Col LG (PC)</option>
                                            <template x-for="n in 12">
                                                <option :value="n" :selected="img.col_lg == n" x-text="n"></option>
                                            </template>
                                        </select>
                                    </div>
                                    <div class="col-4">
                                        <select @change="img.col_md = $event.target.value" class="form-select form-select-sm border-0 bg-light">
                                            <option value="">Col MD (Tablet)</option>
                                            <template x-for="n in 12">
                                                <option :value="n" :selected="img.col_md == n" x-text="n"></option>
                                            </template>
                                        </select>
                                    </div>
                                    <div class="col-4">
                                        <select @change="img.col_sm = $event.target.value" class="form-select form-select-sm border-0 bg-light">
                                            <option value="">Col SM (Mobile)</option>
                                            <template x-for="n in 12">
                                                <option :value="n" :selected="img.col_sm == n" x-text="n"></option>
                                            </template>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
            <div class="mt-3 text-center">
                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill py-1 px-4"
                    @click="window.activeBlockUidForImage = block.uid; window.dispatchEvent(new CustomEvent('open-media-picker', { detail: { eventName: 'on-image-block-added' } }));">
                    <i class="fas fa-plus me-1"></i> Thêm ảnh
                </button>
            </div>
        </div>
    </div>
</template>
