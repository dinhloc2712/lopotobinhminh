<template x-if="block.type === 'divider'">
    <div class="mt-2">
{{-- removed shared_styles --}}
        <hr
            :style="'border-color: ' + (block.content.color || '#e2e8f0') +
            '; border-width: ' + (block.content.thickness || 1) +
            'px; border-style: ' + (block.content.style || 'solid')">
        <div class="d-flex gap-3 mt-3 align-items-center flex-wrap">
            <span class="small text-muted">Kiểu:</span>
            <select x-model="block.content.style"
                class="form-select form-select-sm border-0 bg-light w-auto">
                <option value="solid">Nét liền</option>
                <option value="dashed">Nét đứt</option>
                <option value="dotted">Chấm chấm</option>
            </select>
            <span class="small text-muted ms-2">Độ dày:</span>
            <input type="number" x-model="block.content.thickness"
                class="form-control form-control-sm border-0 bg-light"
                style="width: 60px;">
        </div>
    </div>
</template>
