@php
    $items_limit = !empty($content['items_limit']) ? (int)$content['items_limit'] : 8;
    $category_id = !empty($content['category_id']) ? $content['category_id'] : null;
    $columns = !empty($content['items_per_row']) ? (int)$content['items_per_row'] : 4;

    // Button Styles
    $btn_bg = !empty($content['btn_bg_color']) ? $content['btn_bg_color'] : '#00e5ff';
    $btn_color = !empty($content['btn_text_color']) ? $content['btn_text_color'] : '#ffffff';
    $btn_radius = isset($content['btn_border_radius']) ? (int)$content['btn_border_radius'] : 50;
    
    // View All Button Styles
    $va_bg = !empty($content['view_all_bg_color']) ? $content['view_all_bg_color'] : '#ffffff';
    $va_color = !empty($content['view_all_text_color']) ? $content['view_all_text_color'] : '#64748b';
    $va_radius = isset($content['view_all_border_radius']) ? (int)$content['view_all_border_radius'] : 50;

    $accent_color = !empty($content['accent_color']) ? $content['accent_color'] : '#e31824';

    // Typography Settings
    $title_color = !empty($content['title_color']) ? $content['title_color'] : '#001d3d';
    $title_size = !empty($content['title_font_size']) ? (int)$content['title_font_size'] : 20;

    $product_name_color = !empty($content['product_name_color']) ? $content['product_name_color'] : '#0f172a';
    $product_name_size = !empty($content['product_name_font_size']) ? (int)$content['product_name_font_size'] : 15;

    $product_price_color = !empty($content['product_price_color']) ? $content['product_price_color'] : '#dc3545';
    $product_price_size = !empty($content['product_price_font_size']) ? (int)$content['product_price_font_size'] : 17;

    $view_all_size = !empty($content['view_all_font_size']) ? (int)$content['view_all_font_size'] : 11;
    $btn_font_size = !empty($content['btn_font_size']) ? (int)$content['btn_font_size'] : 13;

    // Frame & Display settings
    $frame_max_width = !empty($content['container_max_width']) ? $content['container_max_width'] : '1200px';
    if (is_numeric($frame_max_width)) $frame_max_width .= 'px';
    $frame_shadow = !empty($content['box_shadow']) && $content['box_shadow'] !== 'none' ? $content['box_shadow'] : '';
    $frame_radius = !empty($content['frame_border_radius']) ? $content['frame_border_radius'] : '0';
    $frame_bg = !empty($content['frame_bg_color']) ? $content['frame_bg_color'] : 'transparent';
    $padding_top = !empty($content['padding_top']) ? $content['padding_top'] : '30px';
    $padding_bottom = !empty($content['padding_bottom']) ? $content['padding_bottom'] : '30px';
    $padding_left = !empty($content['padding_left']) ? $content['padding_left'] : '0px';
    $padding_right = !empty($content['padding_right']) ? $content['padding_right'] : '0px';

    $query = \App\Models\Product::whereIn('status', ['published', 'active']);
    if ($category_id) {
        $query->where('category_id', $category_id);
    }
    $products = $query->orderBy('price', 'desc')->limit($items_limit)->get();

    // Mapping products to their respective "post" pages by searching for product_detail blocks
    $productIds = $products->pluck('id')->toArray();
    $productPostMap = \App\Models\PostBlock::where('type', 'product_detail')
        ->whereIn('content->product_id', $productIds)
        ->join('posts', 'post_blocks.post_id', '=', 'posts.id')
        ->select('post_blocks.content', 'posts.slug')
        ->get()
        ->reduce(function($carry, $item) {
            $pid = $item->content['product_id'] ?? null;
            if ($pid) { $carry[$pid] = $item->slug; }
            return $carry;
        }, []);

    // Banners: support new multi-banner array, fallback to legacy single banner_image
    $banners = [];
    if (!empty($content['banner_images']) && is_array($content['banner_images'])) {
        foreach ($content['banner_images'] as $b) {
            $banners[] = $b['url'] ?? '';
        }
    } elseif (!empty($content['banner_image'])) {
        $banners[] = $content['banner_image'];
    }

    // Chunk products into rows of $columns each
    $chunks = $products->chunk($columns);
@endphp

<div class="product-category-grid" 
     style="--theme-color: {{ $btn_bg }}; --accent-color: {{ $accent_color }}; background-color: {{ $frame_bg }}; padding: {{ $padding_top }} {{ $padding_right }} {{ $padding_bottom }} {{ $padding_left }};">
    <div class="{{ !empty($content['full_width']) ? 'container-fluid px-4' : 'container' }}" 
         style="max-width: {{ $frame_max_width }} !important; margin: 0 auto;">

        {{-- Modern Industrial Section Header --}}
        <div class="section-header-industrial d-flex justify-content-between align-items-center mb-4 pb-2">
            <div class="d-flex align-items-center gap-2">
                <div class="accent-bar" style="background-color: var(--accent-color); width: 4px; height: 24px; border-radius: 0;"></div>
                <h3 class="fw-bold mb-0 text-uppercase" style="color: {{ $title_color }}; font-size: {{ $title_size }}px; letter-spacing: 0.02em;">
                    {{ $content['title'] ?? 'DANH MỤC SẢN PHẨM' }}
                </h3>
            </div>
            <a href="{{ $content['view_all_link'] ?? '#' }}" 
               class="view-all-industrial text-decoration-none d-flex align-items-center p-0 border-0"
               style="background: none; color: {{ $va_color }};">
                <span class="fw-bold text-uppercase" style="font-size: {{ $view_all_size }}px; letter-spacing: 0.05em;">{{ $content['view_all_text'] ?? 'XEM TẤT CẢ' }}</span>
            </a>
        </div>

        @if($products->isEmpty())
            <div class="text-center py-5">
                <p class="text-muted small">Chưa có sản phẩm nào trong danh mục này.</p>
            </div>
        @else
            {{-- Render one row per product chunk, banner at start of each row --}}
            @foreach($chunks as $rowIndex => $chunk)
                @php
                    $bannerUrl = $banners[$rowIndex] ?? null;
                    // Total cols in this row = banner slot (1) + products count
                    $totalCols = count($banners) > 0 ? $columns + 1 : $columns;
                @endphp

                <div class="row g-3 g-md-3 mb-3 {{ count($banners) > 0 ? 'row-cols-2 row-cols-md-' . ($columns + 1) . ' row-cols-lg-' . ($columns + 1) : 'row-cols-2 row-cols-md-' . $columns . ' row-cols-lg-' . $columns }}">

                    {{-- Banner at start of each row (desktop only) --}}
                    @if(count($banners) > 0)
                        <div class="col d-none d-md-block">
                            <div class="banner-sidebar h-100 overflow-hidden border {{ $frame_shadow }}" style="min-height: 280px; border-radius: {{ $frame_radius }};">
                                @if($bannerUrl)
                                    <img src="{{ $bannerUrl }}" alt="Banner hàng {{ $rowIndex + 1 }}" class="w-100 h-100" style="object-fit: cover;">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center h-100 text-muted border-0">
                                        <i class="fas fa-image fa-2x opacity-25"></i>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- Products in this row --}}
                    @foreach($chunk as $product)
                        @php
                            $postSlug = $productPostMap[$product->id] ?? null;
                            $productUrl = $postSlug ? route('posts.show', $postSlug) : '#';
                        @endphp
                        <div class="col">
                            <div class="product-card-industrial h-100 bg-white overflow-hidden d-flex flex-column {{ $frame_shadow }}"
                                 style="transition: all 0.3s; border: 1px solid #f1f5f9; border-radius: {{ $frame_radius }};">

                                {{-- Thumbnail --}}
                                <a href="{{ $productUrl }}" class="text-decoration-none">
                                    <div class="position-relative p-2">
                                        <div class="aspect-ratio-square overflow-hidden rounded-2 bg-transparent">
                                            <img src="{{ $product->thumbnail ? asset('storage/' . $product->thumbnail) : 'https://placehold.co/400x400?text=Product' }}"
                                                 class="w-100 h-100 transition-transform" style="object-fit: contain;">
                                        </div>
                                    </div>
                                </a>

                                {{-- Details --}}
                                <div class="px-3 pb-3 pt-1 text-center flex-grow-1 d-flex flex-column">
                                    <a href="{{ $productUrl }}" class="text-decoration-none" style="color: {{ $product_name_color }};">
                                        <h6 class="fw-bold mb-2 line-clamp-2" style="font-size: {{ $product_name_size }}px; min-height: 2.85rem; color: inherit; line-height: 1.5;">
                                            {{ $product->name }}
                                        </h6>
                                    </a>

                                    <div class="mb-3">
                                        @if($product->stock <= 0 || $product->price <= 0)
                                            <span class="fw-bold d-block" style="font-size: {{ $product_price_size }}px; color: {{ $product_price_color }}; letter-spacing: -0.5px;">Liên hệ</span>
                                        @elseif($product->is_on_sale)
                                            <div class="price-container">
                                                <span class="fw-bold d-block" style="font-size: {{ $product_price_size }}px; color: {{ $product_price_color }}; letter-spacing: -0.5px;">{{ $product->formatted_sale_price }}</span>
                                                <span class="text-muted text-decoration-line-through" style="font-size: 0.75rem;">{{ $product->formatted_price }}</span>
                                            </div>
                                        @else
                                            <span class="fw-bold" style="font-size: {{ $product_price_size }}px; color: {{ $product_price_color }}; letter-spacing: -0.5px;">{{ $product->formatted_price }}</span>
                                        @endif
                                    </div>

                                    <div class="mt-auto">
                                        <a href="{{ $productUrl }}" class="btn w-100 fw-bold py-2 mb-2 shadow-sm order-button-industrial" 
                                           style="background-color: {{ $btn_bg }} !important; 
                                                  color: {{ $btn_color }} !important; 
                                                  border-radius: {{ $btn_radius }}px !important;
                                                  border: none; 
                                                  font-size: {{ $btn_font_size }}px;
                                                  text-transform: uppercase;
                                                  letter-spacing: 0.08em;
                                                  position: relative;
                                                  overflow: hidden;">
                                            Đặt hàng
                                        </a>
                                        <button class="btn btn-link link-secondary text-decoration-none d-flex align-items-center justify-content-center gap-1 w-100 opacity-75 btn-favorite-industrial"
                                                style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">
                                            <i class="far fa-heart"></i> Yêu thích
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        @endif
    </div>
</div>

<style>
.product-category-grid .aspect-ratio-square {
    aspect-ratio: 1/1;
}
.product-card-industrial:hover {
    transform: translateY(-5px);
    box-shadow: none !important;
}
.product-card-industrial img.transition-transform {
    transition: transform 0.6s cubic-bezier(0.165, 0.84, 0.44, 1);
}
.product-card-industrial:hover img.transition-transform {
    transform: scale(1.1);
}
.order-button-industrial:hover {
    filter: brightness(1.1);
    transform: translateY(-1px);
}
.order-button-industrial::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(120deg, transparent, rgba(255, 255, 255, 0.4), transparent);
    transition: all 0.6s;
}
.order-button-industrial:hover::before {
    left: 100%;
}
.btn-favorite-industrial {
    transition: all 0.3s ease;
}
.btn-favorite-industrial:hover {
    color: var(--theme-color) !important;
    opacity: 1 !important;
}
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;  
    overflow: hidden;
}
.hover-info:hover {
    color: var(--theme-color) !important;
}
.section-header-industrial {
    border-bottom: none !important;
}
.accent-bar {
    /* Styles inline in HTML for more flexibility */
}
.view-all-industrial {
    /* No transition per user request */
}
.view-all-industrial:hover {
    color: var(--accent-color) !important;
    background-color: transparent !important;
    background: none !important;
    box-shadow: none !important;
}
.banner-sidebar:hover img {
    transform: scale(1.03);
    transition: transform 0.6s ease;
}
</style>
