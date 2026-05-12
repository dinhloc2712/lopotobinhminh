<template x-if="block.type === 'pricing'">
    <div class="mt-2">
{{-- removed shared_styles --}}
        <div class="d-flex justify-content-between align-items-center mb-2">
            <p class="small text-muted mb-0">Thiết lập bảng giá</p>
            <button
                @click="block.content.plans.push({name: '', price: '', features: '', button_label: 'Mua ngay'})"
                class="btn btn-sm btn-outline-success rounded-pill fw-bold"
                style="font-size: 0.7rem;">+ Thêm gói</button>
        </div>
        <div class="row g-2">
            <template x-for="(plan, i) in block.content.plans">
                <div class="col-md-4">
                    <div class="bg-light p-3 rounded-4 border position-relative">
                        <button @click="block.content.plans.splice(i, 1)"
                            class="btn btn-sm text-danger position-absolute top-0 end-0 p-2"><i
                                class="fas fa-times"></i></button>
                        <input type="text" x-model="plan.name"
                            class="form-control form-control-sm fw-bold mb-2 border-0 bg-white"
                            placeholder="Tên gói (Ví dụ: Gold)">
                        <input type="text" x-model="plan.price"
                            class="form-control form-control-sm mb-2 border-0 bg-white"
                            placeholder="Giá (Ví dụ: 999.000đ)">
                        <textarea x-model="plan.features" class="form-control form-control-sm mb-2 border-0 bg-white" rows="3"
                            placeholder="Tính năng (Mỗi dòng 1 cái)"></textarea>
                        <input type="text" x-model="plan.button_label"
                            class="form-control form-control-sm border-0 bg-white"
                            placeholder="Nhãn nút">
                    </div>
                </div>
            </template>
        </div>
    </div>
</template>
