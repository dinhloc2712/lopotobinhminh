<template x-if="block.type === 'product_category_grid'">
    <div class="mt-2">
        @include('admin.posts.partials.edit.blocks.shared_styles')

        <div class="row g-3">
            {{-- Header Settings --}}
            <div class="col-md-6">
                <label class="form-label small fw-bold text-muted mb-1">Tiêu đề khối</label>
                <input type="text" x-model="block.content.title" class="form-control border-0 bg-light" placeholder="Ví dụ: LỐP YOKOHAMA">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold text-muted mb-1">Chữ link (View all)</label>
                <input type="text" x-model="block.content.view_all_text" class="form-control border-0 bg-light" placeholder="Xem tất cả">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold text-muted mb-1">Đường dẫn link</label>
                <input type="text" x-model="block.content.view_all_link" class="form-control border-0 bg-light" placeholder="#">
            </div>

            <hr class="my-3 opacity-10">

            {{-- Sidebar Banner --}}
            <div class="col-md-12">
                <label class="form-label small fw-bold text-muted mb-1">Banner dọc (Bên trái)</label>
                <div class="input-group">
                    <input type="text" x-model="block.content.banner_image" class="form-control border-0 bg-light" placeholder="Đường dẫn ảnh banner">
                    <button class="btn btn-primary" type="button" @click="openMediaPicker(index, 'banner_image')">
                        <i class="fas fa-image"></i>
                    </button>
                </div>
                <div class="mt-2" x-show="block.content.banner_image">
                    <img :src="block.content.banner_image" class="img-thumbnail" style="max-height: 150px;">
                </div>
            </div>

            <hr class="my-3 opacity-10">

            {{-- Product Selection --}}
            <div class="col-md-6">
                <label class="form-label small fw-bold text-muted mb-1">Danh mục sản phẩm</label>
                <select x-model="block.content.category_id" class="form-select border-0 bg-light">
                    <option value="">-- Tất cả sản phẩm --</option>
                    @foreach(\App\Models\ProductCategory::all() as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold text-muted mb-1">Số lượng hiển thị</label>
                <input type="number" x-model="block.content.items_limit" class="form-control border-0 bg-light">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold text-muted mb-1">Số cột sản phẩm</label>
                <select x-model="block.content.items_per_row" class="form-select border-0 bg-light">
                    <option value="2">2 cột</option>
                    <option value="3">3 cột</option>
                    <option value="4">4 cột</option>
                </select>
            </div>
        </div>

        <div class="alert alert-info border-0 bg-info bg-opacity-10 mt-3 small">
            <i class="fas fa-info-circle me-1"></i> Khối này hiển thị một banner dọc bên trái và lưới sản phẩm bên phải, giống như trang danh mục lốp xe.
        </div>
    </div>
</template>
