<template x-if="block.type === 'product'">
    <div class="mt-2">
{{-- removed shared_styles --}}

        <div class="row g-3">
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
                <label class="form-label small fw-bold text-muted mb-1">Số lượng tối đa</label>
                <input type="number" x-model="block.content.items_limit" class="form-control border-0 bg-light" placeholder="Ví dụ: 8">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold text-muted mb-1">Số cột (Máy tính)</label>
                <select x-model="block.content.items_per_row" class="form-select border-0 bg-light">
                    <option value="2">2 cột</option>
                    <option value="3">3 cột</option>
                    <option value="4">4 cột</option>
                    <option value="6">6 cột</option>
                </select>
            </div>

            <div class="col-md-4">
                <div class="form-check form-switch mt-2">
                    <input class="form-check-input" type="checkbox" x-model="block.content.show_price" :id="'show_price_' + index">
                    <label class="form-check-label small fw-bold text-muted" :for="'show_price_' + index">Hiện giá tiền</label>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-check form-switch mt-2">
                    <input class="form-check-input" type="checkbox" x-model="block.content.show_stock" :id="'show_stock_' + index">
                    <label class="form-check-label small fw-bold text-muted" :for="'show_stock_' + index">Hiện số lượng còn</label>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-check form-switch mt-2">
                    <input class="form-check-input" type="checkbox" x-model="block.content.show_sold" :id="'show_sold_' + index">
                    <label class="form-check-label small fw-bold text-muted" :for="'show_sold_' + index">Hiện số lượng đã bán</label>
                </div>
            </div>

            <div class="col-md-12 mt-2">
                <label class="form-label small fw-bold text-muted mb-1">Chữ trên nút (Button text)</label>
                <input type="text" x-model="block.content.btn_text" class="form-control border-0 bg-light" placeholder="Xem chi tiết">
            </div>
        </div>

        <div class="alert alert-info border-0 bg-info bg-opacity-10 mt-3 small">
            <i class="fas fa-info-circle me-1"></i> Khối này sẽ tự động hiển thị danh sách sản phẩm từ cơ sở dữ liệu dựa trên danh mục bạn chọn.
        </div>
    </div>
</template>
