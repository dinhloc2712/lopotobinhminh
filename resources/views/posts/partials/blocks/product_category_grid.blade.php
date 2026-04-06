@php
    $items_limit = !empty($content['items_limit']) ? (int)$content['items_limit'] : 8;
    $category_id = !empty($content['category_id']) ? $content['category_id'] : null;
    $columns = !empty($content['items_per_row']) ? (int)$content['items_per_row'] : 4;
    
    $query = \App\Models\Product::where('status', 'published');
    if ($category_id) {
        $query->where('category_id', $category_id);
    }
    $products = $query->latest()->limit($items_limit)->get();
    
    // Grid class for the products (Right side)
    $colClass = 'col-lg-' . (12 / $columns);
    if ($columns == 4) $colClass = 'col-lg-3 col-md-4 col-12';
    elseif ($columns == 3) $colClass = 'col-lg-4 col-md-6 col-12';
    elseif ($columns == 2) $colClass = 'col-lg-6 col-md-6 col-12';
@endphp

@include('posts.partials.blocks.common_styles')

<div class="product-category-grid py-4" style="{{ $commonBlockStyle }}">
    <div class="{{ !empty($content['full_width']) ? 'container-fluid px-4' : 'container' }}">
        {{-- Section Header --}}
        <div class="d-flex justify-content-between align-items-end mb-4 border-bottom pb-2">
            <h3 class="fw-bold mb-0 position-relative" style="color: #1a202c; font-size: 1.5rem; letter-spacing: -0.5px;">
                {{ $content['title'] ?? 'DANH MỤC SẢN PHẨM' }}
                <div class="position-absolute bottom-0 start-0 bg-info" style="height: 4px; width: 60px; margin-bottom: -10px;"></div>
            </h3>
            <a href="{{ $content['view_all_link'] ?? '#' }}" class="text-dark text-decoration-none small fw-semibold hover-info">
                {{ $content['view_all_text'] ?? 'Xem tất cả' }} <i class="fas fa-chevron-right ms-1" style="font-size: 0.7rem;"></i>
            </a>
        </div>

        <div class="row g-4">
            {{-- Left Banner --}}
            <div class="col-lg-3 col-md-4 d-none d-md-block">
                <div class="banner-sidebar h-100 rounded-4 overflow-hidden shadow-sm">
                    @if(!empty($content['banner_image']))
                        <img src="{{ $content['banner_image'] }}" alt="Featured Banner" class="w-100 h-100 shadow" style="object-fit: cover;">
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center h-100 text-muted border">
                            <i class="fas fa-image fa-3x opacity-25"></i>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Right Product Grid --}}
            <div class="col-lg-9 col-md-8 col-12">
                <div class="row g-3 g-md-4">
                    @forelse($products as $product)
                        <div class="{{ $colClass }}">
                            <div class="product-card-yokohama h-100 bg-white rounded-3 shadow-sm border-0 d-flex flex-column" 
                                 style="transition: all 0.3s; border: 1px solid #f0f0f0 !important;">
                                
                                {{-- Thumbnail --}}
                                <div class="position-relative p-3">
                                    <div class="aspect-ratio-square overflow-hidden rounded-3">
                                        <img src="{{ $product->thumbnail ?? 'https://placehold.co/400x400?text=Product' }}" 
                                             class="w-100 h-100 transition-transform" style="object-fit: contain;">
                                    </div>
                                    @if($product->is_on_sale)
                                        <img src="https://static.vecteezy.com/system/resources/previews/016/528/775/non_2x/best-seller-with-crown-logo-best-seller-design-template-best-seller-label-badge-symbol-badge-vector.jpg" 
                                             class="position-absolute top-0 end-0 m-2" style="width: 40px; z-index: 2;">
                                    @endif
                                </div>

                                {{-- Details --}}
                                <div class="px-3 pb-3 pt-0 text-center flex-grow-1 d-flex flex-column">
                                    <h6 class="fw-bold mb-3 text-dark line-clamp-2" style="font-size: 0.9rem; min-height: 2.7rem;">
                                        {{ $product->name }}
                                    </h6>

                                    <div class="mb-3">
                                        @if($product->is_on_sale)
                                            <div class="price-container">
                                                <span class="fw-bold text-danger d-block fs-5" style="letter-spacing: -0.5px;">{{ $product->formatted_sale_price }}</span>
                                                <span class="text-muted text-decoration-line-through small">{{ $product->formatted_price }}</span>
                                            </div>
                                        @else
                                            <span class="fw-bold text-danger fs-5">{{ $product->formatted_price ?? 'Liên hệ' }}</span>
                                        @endif
                                    </div>

                                    <div class="mt-auto">
                                        <a href="#" class="btn btn-cyan text-white rounded-pill w-100 fw-bold py-2 mb-2 shadow-sm" style="background-color: #00e5ff; border: none; font-size: 0.85rem;">
                                            Đặt hàng
                                        </a>
                                        <button class="btn btn-link link-secondary text-decoration-none small d-flex align-items-center justify-content-center gap-1 w-100">
                                            <i class="far fa-heart"></i> Yêu thích
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5">
                            <p class="text-muted small">Chưa có sản phẩm nào trong danh mục này.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.product-category-grid .aspect-ratio-square {
    aspect-ratio: 1/1;
}
.product-card-yokohama:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.08) !important;
}
.product-card-yokohama img.transition-transform {
    transition: transform 0.5s ease;
}
.product-card-yokohama:hover img.transition-transform {
    transform: scale(1.08);
}
.btn-cyan:hover {
    background-color: #00d4ec !important;
    transform: scale(1.02);
}
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;  
    overflow: hidden;
}
.hover-info:hover {
    color: #00e5ff !important;
}
</style>
