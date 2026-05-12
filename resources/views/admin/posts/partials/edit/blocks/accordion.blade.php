<template x-if="block.type === 'accordion'">
    <div class="mt-2">
{{-- removed shared_styles --}}
        <div class="d-flex justify-content-between align-items-center mb-2">
            <p class="small text-muted mb-0">Danh sách các mục FAQ/Accordion</p>
            <button @click="block.content.items.push({title: '', content: ''})"
                class="btn btn-sm btn-outline-secondary rounded-pill fw-bold"
                style="font-size: 0.7rem;">+ Thêm mục</button>
        </div>
        <div class="d-flex flex-column gap-2">
            <template x-for="(item, i) in block.content.items">
                <div class="bg-light p-2 rounded-3 border">
                    <div class="d-flex gap-2 mb-2">
                        <input type="text" x-model="item.title"
                            class="form-control form-control-sm border-0"
                            placeholder="Tiêu đề mục...">
                        <button @click="block.content.items.splice(i, 1)"
                            class="btn btn-sm text-danger"><i
                                class="fas fa-times"></i></button>
                    </div>
                    <textarea x-model="item.content" class="form-control form-control-sm border-0" rows="2"
                        placeholder="Nội dung mục..."></textarea>
                </div>
            </template>
        </div>
    </div>
</template>
