<template x-if="block.type === 'cta'">
    <div class="mt-2">
{{-- removed shared_styles --}}
        <div class="row g-2">
        <div class="col-md-6">
            <input type="text" x-model="block.content.label"
                class="form-control border-0 bg-light"
                placeholder="Nhãn nút (Ví dụ: Đăng ký ngay)">
        </div>
        <div class="col-md-6">
            <input type="text" x-model="block.content.link"
                class="form-control border-0 bg-light"
                placeholder="Link liên kết (URL)">
        </div>
    </div>
    </div>
</template>
