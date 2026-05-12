<template x-if="block.type === 'grid'">
    <div class="mt-2">
        <div class="row g-3">
            {{-- Shared Block Styling --}}
    {{-- removed shared_styles --}}

            {{-- Grid Settings --}}
            <div class="col-12">
                <div class="bg-white p-3 rounded-4 border">
                    <h6 class="mb-3 fw-bold text-dark small text-uppercase">Cấu hình lưới (Grid Settings)</h6>
                    
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label small text-muted mb-2">Số cột trên Desktop (lg):</label>
                            <div class="d-flex flex-wrap gap-2">
                                <template x-for="col in [1, 2, 3, 4, 6]">
                                    <button type="button" @click="block.content.columns = col" class="btn btn-sm rounded-3 fw-bold py-1 px-3 border"
                                        :class="block.content.columns == col ? 'btn-primary border-primary' : 'btn-light text-muted'"
                                        x-text="col + ' Cột'"></button>
                                </template>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small text-muted mb-2">Số cột Tablet (md):</label>
                            <div class="d-flex flex-wrap gap-2">
                                <template x-for="col in [1, 2, 3, 4, 6]">
                                    <button type="button" @click="block.content.columns_tablet = col" class="btn btn-sm rounded-3 fw-bold py-1 px-3 border"
                                        :class="block.content.columns_tablet == col ? 'btn-primary border-primary' : 'btn-light text-muted'"
                                        x-text="col + ' Cột'"></button>
                                </template>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small text-muted mb-2">Số cột Mobile (xs/sm):</label>
                            <div class="d-flex flex-wrap gap-2">
                                <template x-for="col in [1, 2, 3]">
                                    <button type="button" @click="block.content.columns_mobile = col" class="btn btn-sm rounded-3 fw-bold py-1 px-3 border"
                                        :class="block.content.columns_mobile == col ? 'btn-primary border-primary' : 'btn-light text-muted'"
                                        x-text="col + ' Cột'"></button>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Grid Items List --}}
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <label class="form-label small fw-bold text-muted text-uppercase mb-0">Danh sách cột / ô (Grid Items)</label>
                    <button type="button"
                        @click="if(!block.content.items) { block.content.items = []; }; block.content.items.push({title: 'Tiêu đề mới', body: 'Nội dung cột', image: '', icon: '', link: '', bg_color: '#ffffff', bg_image: '', width: ''})"
                        class="btn btn-sm btn-outline-primary rounded-pill fw-bold" style="font-size: 0.7rem;">
                        <i class="fas fa-plus me-1"></i> Thêm ô (item)
                    </button>
                </div>

                <div class="d-flex flex-column gap-3">
                    <template x-if="block.content.items">
                        <template x-for="(item, i) in block.content.items">
                            <div class="bg-light p-3 rounded-4 border position-relative">
                                {{-- Nút thao tác (Nhân bản / Xoá) --}}
                                <div class="position-absolute top-0 end-0 p-2 d-flex gap-1" style="z-index: 10;">
                                    <button type="button" @click="block.content.items.splice(i + 1, 0, JSON.parse(JSON.stringify(item)))"
                                        class="btn btn-sm text-primary p-1 bg-white shadow-sm rounded-circle border d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;" title="Nhân bản ô này">
                                        <i class="fas fa-copy" style="font-size: 0.75rem;"></i>
                                    </button>
                                    <button type="button" @click="block.content.items.splice(i, 1)"
                                        class="btn btn-sm text-danger p-1 bg-white shadow-sm rounded-circle border d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;" title="Xóa ô này">
                                        <i class="fas fa-times" style="font-size: 0.75rem;"></i>
                                    </button>
                                </div>

                                {{-- Layout nhập liệu từng ô --}}
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <label class="small text-muted fw-bold mb-1">Tiêu đề (Title)</label>
                                        <input type="text" x-model="item.title" class="form-control form-control-sm border-0 bg-white shadow-sm" placeholder="Nhập tiêu đề...">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="small text-muted fw-bold mb-1">Đường dẫn (Link)</label>
                                        <input type="text" x-model="item.link" class="form-control form-control-sm border-0 bg-white shadow-sm" placeholder="https://...">
                                    </div>
                                    
                                    <div class="col-md-12">
                                        <label class="small text-muted fw-bold mb-1">Nội dung (Body / Text)</label>
                                        <textarea x-model="item.body" class="form-control form-control-sm border-0 bg-white shadow-sm" rows="2" placeholder="Nội dung mô tả..."></textarea>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="small text-muted fw-bold mb-1">Chiều rộng Custom (VD: 50%, 300px)</label>
                                        <input type="text" x-model="item.width" class="form-control form-control-sm border-0 bg-white shadow-sm" placeholder="Để trống = Tự động (Theo cột chung)">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="small text-muted fw-bold mb-1">Ảnh đại diện (Image)</label>
                                        <div class="input-group input-group-sm rounded-3 overflow-hidden shadow-sm">
                                            <button type="button" class="btn btn-white border-end text-primary p-1 px-2"
                                                @click="openMediaPicker(index, 'items.' + i + '.image')" title="Chọn ảnh">
                                                <i class="fas fa-image"></i>
                                            </button>
                                            <input type="text" x-model="item.image" class="form-control border-0 bg-white small" placeholder="URL ảnh đại diện..." style="font-size: 0.75rem;">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="small text-muted fw-bold mb-1">Ảnh nền ở lót (Bg Image)</label>
                                        <div class="input-group input-group-sm rounded-3 overflow-hidden shadow-sm">
                                            <button type="button" class="btn btn-white border-end text-primary p-1 px-2"
                                                @click="openMediaPicker(index, 'items.' + i + '.bg_image')" title="Chọn ảnh nền">
                                                <i class="fas fa-image"></i>
                                            </button>
                                            <input type="text" x-model="item.bg_image" class="form-control border-0 bg-white small" placeholder="URL ảnh nền..." style="font-size: 0.75rem;">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="small text-muted fw-bold mb-1">Biểu tượng (Icon Class)</label>
                                        <input type="text" x-model="item.icon" class="form-control form-control-sm border-0 bg-white shadow-sm" placeholder="Ví dụ: fas fa-star">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="small text-muted fw-bold mb-1">Màu nền ô (Bg Color)</label>
                                        <div class="d-flex align-items-center bg-white rounded-3 p-1 px-2 border shadow-sm">
                                            <input type="color" x-model="item.bg_color" class="form-control-color border-0 bg-transparent p-0" style="width: 24px; height: 24px;">
                                            <input type="text" x-model="item.bg_color" class="form-control form-control-sm border-0 bg-transparent p-0 fs-7 px-2 flex-grow-1" placeholder="#ffffff">
                                            <div class="d-flex gap-1">
                                                <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="copyColor(item.bg_color)" title="Copy màu">
                                                    <i class="fas fa-copy" style="font-size: 0.6rem;"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-light p-0 px-1 border-0" @click="pasteColor(item, 'bg_color')" title="Dán màu">
                                                    <i class="fas fa-paste" style="font-size: 0.6rem;"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </template>
                </div>
            </div>
        </div>
    </div>
</template>
