{{-- Header Actions --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1 text-gray-800 fw-bold mt-2">Thiết kế: <span class="text-primary">{{ $post->title }}</span>
        </h1>
    </div>
    <div class="d-flex gap-2">
        <button @click="save()" class="btn btn-success shadow-sm fw-bold rounded-pill px-4" :disabled="saving">
            <template x-if="!saving">
                <span><i class="fas fa-save me-1"></i> Lưu thiết kế</span>
            </template>
            <template x-if="saving">
                <span><i class="fas fa-spinner fa-spin me-1"></i> Đang lưu...</span>
            </template>
        </button>
        <button class="btn btn-primary shadow-sm fw-bold rounded-pill px-4" data-bs-toggle="modal"
            data-bs-target="#settingsModal">
            <i class="fas fa-cog me-1"></i> Cài đặt SEO
        </button>
    </div>
</div>
