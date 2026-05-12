<template x-if="block.type === 'contact_info_bar'">
    <div class="mt-2 text-contact-info-admin">
        {{-- Repeater Items --}}
        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <label class="form-label small fw-bold text-muted text-uppercase mb-0" style="font-size: 0.65rem;">Danh sách mục thông tin</label>
                <button type="button" @click="if(!block.content.items) block.content.items = []; const newUid = 'item_' + Date.now(); block.content.items.push({uid: newUid, icon_image: '', content: '', width: 0})" 
                        class="btn btn-sm btn-outline-primary rounded-pill py-0 px-3 fw-bold"
                        style="font-size: 0.65rem; height: 24px;">
                    <i class="fas fa-plus me-1"></i> Thêm mục
                </button>
            </div>

            <div class="d-flex flex-column gap-3">
                <template x-for="(item, i) in block.content.items" :key="item.uid">
                    <div class="bg-white p-3 border rounded-3 position-relative shadow-sm">
                        {{-- Delete Button --}}
                        <button type="button" @click="block.content.items.splice(i, 1)" 
                                class="btn btn-sm btn-danger position-absolute top-0 end-0 rounded-circle d-flex align-items-center justify-content-center shadow-sm"
                                style="width: 22px; height: 22px; padding: 0; transform: translate(35%, -35%); z-index: 10;">
                            <i class="fas fa-times" style="font-size: 0.7rem;"></i>
                        </button>
                        
                        <div class="row g-3">
                            {{-- Icon Selection --}}
                            <div class="col-md-3">
                                <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.65rem;">Icon ảnh</label>
                                <div class="icon-preview-box bg-light rounded-3 d-flex align-items-center justify-content-center border-dashed mb-2 position-relative overflow-hidden" 
                                     style="height: 100px; cursor: pointer;"
                                     @click="openMediaPicker('modal', 'items.' + i + '.icon_image')">
                                    <template x-if="item.icon_image">
                                        <img :src="item.icon_image" class="img-fluid" style="max-height: 80px; object-fit: contain;">
                                    </template>
                                    <template x-if="!item.icon_image">
                                        <div class="text-center text-muted">
                                            <i class="fas fa-image fs-3 mb-1 op-50"></i>
                                            <div style="font-size: 0.55rem;" class="text-uppercase fw-bold">Chọn ảnh</div>
                                        </div>
                                    </template>
                                </div>
                                <button type="button" @click="openMediaPicker('modal', 'items.' + i + '.icon_image')" class="btn btn-light border btn-sm w-100 fw-bold py-1" style="font-size: 0.65rem;">
                                    Thay đổi
                                </button>
                            </div>

                            {{-- Content Editor & Size Settings --}}
                            <div class="col-md-9">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label class="form-label small fw-bold text-muted text-uppercase mb-0" style="font-size: 0.65rem;">Nội dung</label>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="small text-muted text-uppercase fw-bold" style="font-size: 0.6rem;">Rộng (px):</span>
                                        <input type="number" x-model="item.width" class="form-control form-control-sm border-0 bg-light py-0" 
                                               style="font-size: 0.65rem; width: 60px; height: 22px;" placeholder="0">
                                    </div>
                                </div>
                                <div class="editor-wrapper bg-light rounded-3 border overflow-hidden" 
                                     x-init="setTimeout(() => initItemEditor(item.uid, 'items.' + i + '.content', item), 200)">
                                    <textarea :id="'editor-item-' + item.uid" x-model="item.content"></textarea>
                                </div>
                                <div class="mt-1 d-flex justify-content-between align-items-center">
                                    <span class="small text-muted fst-italic" style="font-size: 0.55rem;">
                                        <i class="fas fa-info-circle me-1"></i>0 = Auto
                                    </span>
                                    <button type="button" @click="initItemEditor(item.uid, 'items.' + i + '.content', item)" class="btn btn-link btn-sm p-0 text-decoration-none" style="font-size: 0.6rem;">
                                        <i class="fas fa-sync-alt me-1"></i>Tải lại editor
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        {{-- Button Settings --}}
        <div class="bg-white p-3 border rounded-3 shadow-sm mt-4">
            <div class="mb-3 border-bottom pb-2">
                <h6 class="mb-0 fw-bold text-dark small text-uppercase">
                    <i class="fas fa-mouse-pointer me-2 text-primary"></i>Nút hành động (CTA)
                </h6>
            </div>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.65rem;">Văn bản nút</label>
                    <input type="text" x-model="block.content.btn_text" class="form-control form-control-sm bg-light border-0 py-2" placeholder="Ví dụ: Cộng tác viên">
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.65rem;">Đường dẫn (URL)</label>
                    <input type="text" x-model="block.content.btn_link" class="form-control form-control-sm bg-light border-0 py-2" placeholder="Ví dụ: # hoặc https://...">
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.65rem;">Phong cách nút</label>
                    <div class="d-flex flex-column gap-2">
                        <div class="d-flex gap-2 align-items-center bg-light p-1 rounded-2">
                            <input type="color" x-model="block.content.btn_bg_color" class="form-control form-control-color border-0 bg-transparent" style="width: 32px; height: 28px;">
                            <span class="small text-muted" style="font-size: 0.6rem;">Nền</span>
                            <input type="text" x-model="block.content.btn_bg_color" class="form-control form-control-sm border-0 bg-transparent flex-grow-1" placeholder="#00ffff" style="font-size: 0.65rem;">
                            <div class="d-flex gap-1">
                                <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="copyColor(block.content.btn_bg_color)">
                                    <i class="fas fa-copy" style="font-size: 0.6rem;"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="pasteColor(block.content, 'btn_bg_color')">
                                    <i class="fas fa-paste" style="font-size: 0.6rem;"></i>
                                </button>
                            </div>
                        </div>
                        <div class="d-flex gap-2 align-items-center bg-light p-1 rounded-2">
                            <input type="color" x-model="block.content.btn_text_color" class="form-control form-control-color border-0 bg-transparent" style="width: 32px; height: 28px;">
                            <span class="small text-muted" style="font-size: 0.6rem;">Chữ</span>
                            <input type="text" x-model="block.content.btn_text_color" class="form-control form-control-sm border-0 bg-transparent flex-grow-1" placeholder="#000000" style="font-size: 0.65rem;">
                            <div class="d-flex gap-1">
                                <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="copyColor(block.content.btn_text_color)">
                                    <i class="fas fa-copy" style="font-size: 0.6rem;"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="pasteColor(block.content, 'btn_text_color')">
                                    <i class="fas fa-paste" style="font-size: 0.6rem;"></i>
                                </button>
                            </div>
                        </div>
                        <div class="d-flex gap-2 align-items-center bg-light p-1 rounded-2">
                            <div class="bg-white border rounded px-1 d-flex align-items-center justify-content-center" style="height: 28px; width: 32px;">
                                <i class="fas fa-shapes fs-6 text-muted"></i>
                            </div>
                            <span class="small text-muted" style="font-size: 0.6rem;">Bo góc</span>
                            <input type="number" x-model="block.content.btn_border_radius" class="form-control form-control-sm border-0 bg-transparent flex-grow-1" placeholder="0" style="font-size: 0.65rem;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
.border-dashed {
    border: 1px dashed #cbd5e1 !important;
}
.icon-preview-box:hover {
    border-color: #3b82f6 !important;
    background-color: #f1f5f9 !important;
}
.editor-wrapper .tox-tinymce {
    border: none !important;
    min-height: 120px !important;
}
.op-50 {
    opacity: 0.5;
}
</style>
