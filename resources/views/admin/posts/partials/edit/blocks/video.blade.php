<template x-if="block.type === 'video'">
    <div class="mt-2">
{{-- removed shared_styles --}}
        
        <div class="mb-2">
            <span class="small fw-bold text-muted text-uppercase">Cài đặt Video</span>
        </div>

        <div class="input-group mb-2 shadow-sm rounded-3 overflow-hidden">
            <button type="button"
                class="btn btn-light border-end fw-bold text-danger"
                @click="openMediaPicker(index, 'url')" title="Chọn từ thư viện">
                <i class="fas fa-folder-open"></i> Thư viện
            </button>
            <input type="text" x-model="block.content.url"
                class="form-control border-0 bg-light"
                placeholder="URL Video (Nhúng Youtube/Vimeo/Mp4)...">
        </div>
        <p class="small text-muted mb-0"><i class="fas fa-info-circle"></i> Hệ
            thống sẽ tự động nhúng video từ liên kết bạn cung cấp.</p>
    </div>
</template>
