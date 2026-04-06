<template x-if="block.type === 'contact_form'">
    
<div class="bg-white p-3 rounded-4 border mb-3">
    @include('admin.posts.partials.edit.blocks.shared_styles')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h6 class="mb-0 fw-bold text-dark small text-uppercase">
            <i class="fas fa-envelope-open-text me-2 text-primary"></i>Cấu hình Mẫu liên hệ
        </h6>
    </div>

    <div class="row g-3">
        {{-- Tiêu đề --}}
        <div class="col-md-12">
            <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.65rem;">Tiêu đề chính</label>
            <input type="text" x-model="block.content.title" class="form-control form-control-sm bg-light border-0 py-2" placeholder="VD: Để lại thông tin tư vấn">
        </div>

        {{-- Mô tả phụ --}}
        <div class="col-md-12">
            <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.65rem;">Mô tả phụ</label>
            <textarea x-model="block.content.description" class="form-control form-control-sm bg-light border-0 py-2" rows="2" placeholder="VD: Chúng tôi sẽ liên hệ lại với bạn trong vòng 24h..."></textarea>
        </div>

        {{-- Nút Submit --}}
        <div class="col-md-6">
            <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.65rem;">Nhãn nút gửi</label>
            <input type="text" x-model="block.content.submit_label" class="form-control form-control-sm bg-light border-0 py-2" placeholder="VD: Gửi ngay">
        </div>

        {{-- Ảnh bên phải --}}
        <div class="col-md-6">
            <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.65rem;">Ảnh minh họa bên phải</label>
            <div class="input-group input-group-sm shadow-none rounded overflow-hidden border">
                <button type="button" class="btn btn-sm btn-white border-end text-primary p-1 py-0" @click="openMediaPicker(index, 'image')">
                    <i class="fas fa-image" style="font-size: 0.7rem;"></i>
                </button>
                <input type="text" x-model="block.content.image" class="form-control border-0 bg-white" placeholder="URL ảnh..." style="font-size: 0.7rem;">
            </div>
        </div>
    </div>
</div>


</template>
