@php
    $items_limit = !empty($content['items_limit']) ? (int)$content['items_limit'] : 8;
    $category_id = !empty($content['category_id']) ? $content['category_id'] : null;
    $columns = !empty($content['items_per_row']) ? (int)$content['items_per_row'] : 4;
    
    $query = \App\Models\Product::whereIn('status', ['published', 'active']);
    
    if ($category_id) {
        $query->where('category_id', $category_id);
    }
    
    $products = $query->latest()->limit($items_limit)->get();
    
    // Grid classes based on columns
    $colClass = 'col-lg-' . (12 / $columns);
    if ($columns == 4) $colClass = 'col-lg-3 col-md-4 col-6';
    elseif ($columns == 3) $colClass = 'col-lg-4 col-md-6';
    elseif ($columns == 2) $colClass = 'col-lg-6 col-md-6';
@endphp

<div class="product-block py-4" style="@include('posts.partials.blocks.common_styles')">
    <div class="{{ !empty($content['full_width']) ? 'container-fluid px-4' : 'container' }}">
        <div class="row g-3 g-md-4">
            @forelse($products as $product)
                <div class="{{ $colClass }}">
                    <div class="product-card h-100 border-0 shadow-sm rounded-4 overflow-hidden bg-white d-flex flex-column" 
                         style="transition: transform 0.3s, box-shadow 0.3s;">
                        
                        {{-- Thumbnail --}}
                        <div class="position-relative overflow-hidden" style="aspect-ratio: 1/1;">
                            <img src="{{ $product->thumbnail ? asset('storage/' . $product->thumbnail) : 'https://placehold.co/400x400?text=Product' }}" 
                                 class="w-100 h-100" style="object-fit: cover; transition: transform 0.5s;"
                                 onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                            
                            @if($product->is_on_sale)
                                <div class="position-absolute top-0 start-0 m-3 badge rounded-pill bg-danger shadow-sm">
                                    Giảm {{ $product->discount_percent }}%
                                </div>
                            @endif
                        </div>

                        {{-- Details --}}
                        <div class="p-3 flex-grow-1 d-flex flex-column">
                            <h6 class="fw-bold mb-2 text-dark line-clamp-2" style="font-size: 0.95rem;">
                                {{ $product->name }}
                            </h6>

                            @if(!empty($content['show_price']))
                                <div class="mb-2">
                                    @if($product->is_on_sale)
                                        <div class="d-flex align-items-center gap-2 flex-wrap">
                                            <span class="fw-bold text-danger fs-5">{{ $product->formatted_sale_price }}</span>
                                            <span class="text-muted text-decoration-line-through small">{{ $product->formatted_price }}</span>
                                        </div>
                                    @else
                                        <span class="fw-bold text-dark fs-5">{{ $product->formatted_price }}</span>
                                    @endif
                                </div>
                            @endif

                            <div class="mt-auto">
                                @if(!empty($content['show_stock']) || !empty($content['show_sold']))
                                    <div class="d-flex justify-content-between align-items-center mb-3 small opacity-75">
                                        @if(!empty($content['show_stock']))
                                            <span>Kho: {{ $product->stock }}</span>
                                        @endif
                                        @if(!empty($content['show_sold']))
                                            <span>Đã bán: {{ $product->sold }}</span>
                                        @endif
                                    </div>
                                @endif

                                <a href="#" class="btn btn-outline-primary rounded-pill w-100 fw-bold py-2" style="font-size: 0.85rem;">
                                    {{ $content['btn_text'] ?? 'Xem chi tiết' }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <div class="opacity-50 fs-5 mb-2"><i class="fas fa-shopping-basket fa-2x mb-3"></i></div>
                    <p class="text-muted">Không tìm thấy sản phẩm nào trong danh mục này.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;  
    overflow: hidden;
}
.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
}
</style>
