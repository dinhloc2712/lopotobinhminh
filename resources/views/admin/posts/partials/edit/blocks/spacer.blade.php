<template x-if="block.type === 'spacer'">
    <div class="mt-2">
{{-- removed shared_styles --}}
        <div class="d-flex align-items-center gap-3">
        <span class="small text-muted">Chiều cao (px):</span>
        <input type="range" x-model="block.content.height" min="10"
            max="200" step="10" class="form-range flex-grow-1">
        <span class="badge bg-light text-dark border px-2 py-1"
            x-text="block.content.height + 'px'"></span>
    </div>
    </div>
</template>
