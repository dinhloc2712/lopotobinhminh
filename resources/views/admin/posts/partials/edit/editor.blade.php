{{-- Canvas: Editor Area --}}
<div class="col-lg-9">
    <div class="bg-white rounded-4 shadow-sm min-vh-100 p-4 border"
        style="background-image: radial-gradient(#e5e7eb 1px, transparent 1px); background-size: 20px 20px;">

        {{-- Empty State --}}
        <template x-if="blocks.length === 0">
            <div class="text-center py-5">
                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                    style="width: 80px; height: 80px;">
                    <i class="fas fa-layer-group fa-2x text-muted opacity-50"></i>
                </div>
                <h5 class="text-muted fw-bold">Chưa có nội dung</h5>
                <p class="text-muted small">Hãy chọn một thành phần từ bên trái để bắt đầu xây dựng.</p>
            </div>
        </template>

        {{-- Blocks List --}}
        <div id="blocks-container" class="d-flex flex-column gap-2">
            <template x-for="(block, index) in blocks" :key="block.uid">
                <div class="builder-block p-3 rounded-4 bg-white shadow-sm border d-flex justify-content-between align-items-center hover-shadow-sm transition-all"
                    :data-uid="block.uid">
                    <div class="d-flex align-items-center gap-3">
                        <div class="draggable-handle btn btn-sm btn-light text-muted p-2 rounded-3"
                            title="Kéo để di chuyển">
                            <i class="fas fa-grip-vertical"></i>
                        </div>
                        <div class="block-icon bg-light rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 45px; height: 45px;">
                            <i :class="getBlockIcon(block.type)" class="text-primary fs-5"></i>
                        </div>
                        <div>
                            <div class="fw-bold text-dark" x-text="getBlockName(block.type)"></div>
                            <div class="small text-muted" x-text="'Thứ tự: ' + (index + 1)"></div>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button @click="duplicateBlock(index)"
                            class="btn btn-sm btn-outline-success rounded-pill fw-bold">
                            <i class="fas fa-copy me-1"></i>
                        </button>

                        <button @click="editBlock(index)"
                            class="btn btn-sm btn-outline-primary rounded-pill fw-bold">
                            <i class="fas fa-edit me-1"></i>
                        </button>
                        
                        <button @click="removeBlock(index)"
                            class="btn btn-sm btn-outline-danger rounded-pill fw-bold">
                            <i class="fas fa-trash me-1"></i>
                        </button>
                    </div>
                </div>
            </template>
        </div>

        {{-- Quick Add Button at bottom --}}
        <div class="text-center mt-5">
            <button @click="addBlock('text')" class="btn btn-light rounded-circle shadow-sm hover-scale border"
                style="width: 50px; height: 50px;">
                <i class="fas fa-plus text-primary"></i>
            </button>
            <div class="small text-muted mt-2">Thêm khối mới</div>
        </div>
    </div>
</div>
