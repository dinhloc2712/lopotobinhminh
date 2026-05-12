@php
    $product = \App\Models\Product::find($content['product_id'] ?? null);
    $prodOptions = is_array($product?->images) ? $product->images : [];
    $prodVideoPart = $prodOptions['video_url'] ?? '';

    $videoUrl = $prodVideoPart;
    $showRating = $content['show_rating'] ?? true;
    $showSku = $content['show_sku'] ?? true;
    $promotionHtml = $content['body'] ?? '';

    // Standardized premium layout values
    $maxWidth     = '1200px';
    $borderRadius = '16px';
    $boxShadow    = 'none'; // Will use border instead if none
    $cardBg       = '#ffffff';
    $galleryActiveColor = $content['gallery_active_color'] ?? '#C92127';
    
    // Default Spacing
    $mt = '40px'; $mb = '40px';
    $pt = '40px'; $pb = '40px'; $pl = '40px'; $pr = '40px';

    // Typography & Price Colors from content
    $titleColor = $content['title_color'] ?? '#333333';
    $titleFontSize = $content['title_font_size'] ?? 26;
    $priceColor = $content['price_color'] ?? '#c62828';
    $priceFontSize = $content['price_font_size'] ?? 32;
    $oldPriceColor = $content['old_price_color'] ?? '#888888';
    $oldPriceFontSize = $content['old_price_font_size'] ?? 20;

    // Discount Badge
    $discountBg = $content['discount_bg_color'] ?? '#c62828';
    $discountColor = $content['discount_text_color'] ?? '#ffffff';
    $discountFontSize = $content['discount_font_size'] ?? 14;

    // Cart Button
    $cartBtnBg = $content['cart_btn_bg_color'] ?? '#ffffff';
    $cartBtnText = $content['cart_btn_text_color'] ?? '#C92127';
    $cartBtnSize = $content['cart_btn_font_size'] ?? 15;
    $cartBtnBgHover = $content['cart_btn_bg_hover'] ?? '#C92127';
    $cartBtnTextHover = $content['cart_btn_text_hover'] ?? '#ffffff';
    $cartBtnBorderWidth = ($content['cart_btn_border_width'] ?? 2) . 'px';
    $cartBtnBorderColor = $content['cart_btn_border_color'] ?? '#C92127';
    $cartBtnBorderColorHover = $content['cart_btn_border_color_hover'] ?? '#C92127';

    // Buy Button
    $buyBtnBg = $content['buy_btn_bg_color'] ?? '#C92127';
    $buyBtnText = $content['buy_btn_text_color'] ?? '#ffffff';
    $buyBtnSize = $content['buy_btn_font_size'] ?? 15;
    $buyBtnBgHover = $content['buy_btn_bg_hover'] ?? '#a31a1e';
    $buyBtnTextHover = $content['buy_btn_text_hover'] ?? '#ffffff';

    $wrapperStyle = "max-width: {$maxWidth}; margin-left: auto; margin-right: auto;";
    $cardStyle = "background-color: {$cardBg} !important; border-radius: {$borderRadius}; padding: {$pt} {$pr} {$pb} {$pl} !important;";
    $cardClasses = "product-detail-block " . ($boxShadow !== 'none' ? $boxShadow : 'border');
    
    // Sử dụng MARGIN thay vì PADDING cho lề ngoài
    $containerStyle = "margin-top: {$mt}; margin-bottom: {$mb}; margin-left: {$ml}; margin-right: {$mr};";
    
    // Create unique ID for scoped styles
    $bid = 'pd-' . uniqid();
@endphp

@if($product)
<style>
    /* Scoped styles for this block instance */
    #{{ $bid }} .product-title { color: {{ $titleColor }} !important; font-size: {{ $titleFontSize }}px !important; }
    #{{ $bid }} .price-main { color: {{ $priceColor }} !important; font-size: {{ $priceFontSize }}px !important; }
    #{{ $bid }} .price-old { color: {{ $oldPriceColor }} !important; font-size: {{ $oldPriceFontSize }}px !important; }
    #{{ $bid }} .discount-badge { background-color: {{ $discountBg }} !important; color: {{ $discountColor }} !important; font-size: {{ $discountFontSize }}px !important; }
    
    /* Cart Button Styles */
    #{{ $bid }} .btn-add-cart { 
        background-color: {{ $cartBtnBg }} !important; 
        color: {{ $cartBtnText }} !important; 
        font-size: {{ $cartBtnSize }}px !important;
        border: {{ $cartBtnBorderWidth }} solid {{ $cartBtnBorderColor }} !important;
    }
    #{{ $bid }} .btn-add-cart:hover { 
        background-color: {{ $cartBtnBgHover }} !important; 
        color: {{ $cartBtnTextHover }} !important;
        border-color: {{ $cartBtnBorderColorHover }} !important;
    }
    
    /* Buy Now Button Styles */
    #{{ $bid }} .btn-buy-now { 
        background-color: {{ $buyBtnBg }} !important; 
        color: {{ $buyBtnText }} !important; 
        font-size: {{ $buyBtnSize }}px !important;
        border: none !important;
    }
    #{{ $bid }} .btn-buy-now:hover { 
        background-color: {{ $buyBtnBgHover }} !important; 
        color: {{ $buyBtnTextHover }} !important;
        border-color: {{ $buyBtnBgHover }} !important;
    }

    @media (max-width: 768px) {
        #{{ $bid }} .product-detail-block {
            padding: 24px !important;
        }
        #{{ $bid }}.product-detail-container-outer {
            margin-left: 0 !important;
            margin-right: 0 !important;
        }
    }
</style>

<div class="product-detail-container-outer" id="{{ $bid }}" style="{{ $containerStyle }}">
    <div class="container px-0">
        <div class="{{ $cardClasses }}" style="{{ $wrapperStyle }} {{ $cardStyle }}">
            <div class="row g-5">
                {{-- Left: Image Gallery / Video --}}
                <div class="col-lg-6">
                    @php 
                        $sliderItems = [];
                        $prodVideoUrls = isset($prodOptions['video_urls']) ? $prodOptions['video_urls'] : [];
                        if (isset($prodOptions['video_url']) && !empty($prodOptions['video_url'])) {
                            array_unshift($prodVideoUrls, $prodOptions['video_url']);
                        }
                        $prodVideoUrls = array_unique($prodVideoUrls);
                        foreach($prodVideoUrls as $vUrl) {
                            if (empty($vUrl)) continue;
                            preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $vUrl, $matches);
                            if (!empty($matches[1])) {
                                $sliderItems[] = [
                                    'type' => 'video',
                                    'src' => "https://www.youtube.com/watch?v={$matches[1]}",
                                    'thumb' => 'https://img.youtube.com/vi/' . $matches[1] . '/hqdefault.jpg'
                                ];
                            }
                        }
                        $prodGallery = isset($prodOptions['gallery']) ? $prodOptions['gallery'] : (is_array($prodOptions) && !isset($prodOptions['video_url']) ? $prodOptions : []);
                        if (is_array($prodGallery) && count($prodGallery) > 0) {
                            foreach($prodGallery as $img) {
                                $sliderItems[] = [
                                    'type' => 'image',
                                    'src' => \Storage::url($img),
                                    'thumb' => \Storage::url($img)
                                ];
                            }
                        }
                        if (count($sliderItems) === 0) {
                            $noImagePath = 'https://placehold.co/800x600/f8f9fa/adb5bd?text=No+Image';
                            $sliderItems[] = ['type' => 'image', 'src' => $noImagePath, 'thumb' => $noImagePath];
                        }
                    @endphp

                    {{-- Swiper & Fancybox CSS --}}
                    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
                    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />
                    
                    <style>
                        #{{ $bid }} .main-swiper { border: 1px solid #eee; border-radius: 8px; background: #fff; }
                        #{{ $bid }} .main-swiper .swiper-slide { display: flex; align-items: center; justify-content: center; aspect-ratio: 4/3; overflow: hidden; cursor: pointer; }
                        #{{ $bid }} .main-swiper .swiper-slide img { width: 100%; height: 100%; object-fit: contain; }
                        #{{ $bid }} .thumb-swiper { margin-top: 15px; }
                        #{{ $bid }} .thumb-swiper .swiper-slide { width: 80px; height: 80px; opacity: 0.6; border: 2px solid transparent; border-radius: 8px; overflow: hidden; cursor: pointer; transition: all 0.3s; }
                        #{{ $bid }} .thumb-swiper .swiper-slide-thumb-active { opacity: 1; border-color: {{ $galleryActiveColor }}; }
                        #{{ $bid }} .thumb-swiper .swiper-slide img { width: 100%; height: 100%; object-fit: cover; }
                    </style>

                    <div style="--swiper-navigation-color: #fff; --swiper-pagination-color: #fff" class="swiper main-swiper shadow-sm">
                        <div class="swiper-wrapper">
                            @foreach($sliderItems as $item)
                                @if($item['type'] === 'video')
                                <div class="swiper-slide position-relative">
                                    <a data-fancybox="gallery-{{ $bid }}" href="{{ $item['src'] }}" class="w-100 h-100 d-flex justify-content-center align-items-center">
                                        <img src="{{ $item['thumb'] }}" />
                                        <i class="fas fa-play-circle position-absolute top-50 start-50 translate-middle text-white" style="font-size: 4rem; text-shadow: 0 0 10px rgba(0,0,0,0.5); pointer-events: none;"></i>
                                    </a>
                                </div>
                                @else
                                <div class="swiper-slide">
                                    <a data-fancybox="gallery-{{ $bid }}" href="{{ $item['src'] }}" class="w-100 h-100 d-flex justify-content-center align-items-center">
                                        <img src="{{ $item['src'] }}" />
                                    </a>
                                </div>
                                @endif
                            @endforeach
                        </div>
                        @if(count($sliderItems) > 1)
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                        @endif
                    </div>
                    
                    @if(count($sliderItems) >= 1)
                    <div class="swiper thumb-swiper">
                        <div class="swiper-wrapper">
                            @foreach($sliderItems as $item)
                                <div class="swiper-slide position-relative">
                                    <img src="{{ $item['thumb'] }}" {!! $item['type'] === 'video' ? 'style="opacity: 0.7;"' : '' !!} />
                                    @if($item['type'] === 'video')
                                    <i class="fas fa-play-circle position-absolute top-50 start-50 translate-middle text-white fs-4" style="text-shadow: 0 0 5px rgba(0,0,0,0.5);"></i>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Slider & Lightbox Scripts --}}
                    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
                    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
                    
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Fancybox.bind('[data-fancybox="gallery-{{ $bid }}"]', { Thumbs: { type: "classic" } });
                            @if(count($sliderItems) >= 1)
                            var ts_{{ str_replace('-', '_', $bid) }} = new Swiper('#{{ $bid }} .thumb-swiper', { spaceBetween: 10, slidesPerView: 'auto', freeMode: true, watchSlidesProgress: true });
                            var ms_{{ str_replace('-', '_', $bid) }} = new Swiper('#{{ $bid }} .main-swiper', { spaceBetween: 10, rewind: true, navigation: { nextEl: '#{{ $bid }} .swiper-button-next', prevEl: '#{{ $bid }} .swiper-button-prev' }, thumbs: { swiper: ts_{{ str_replace('-', '_', $bid) }} } });
                            @endif
                        });
                    </script>
                </div>

                {{-- Right: Product Info --}}
                <div class="col-lg-6">
                    <h1 class="fw-bold mb-3 product-title">{{ $product->name }}</h1>
                    
                    @if($showRating)
                    <div class="d-flex align-items-center gap-1 mb-4" style="color: #ffc107; font-size: 14px;">
                        <div class="d-flex gap-1">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                        </div>
                        <span class="ms-2 text-muted fw-normal">(4.5/5) 24 đánh giá</span>
                    </div>
                    @endif
                    
                    @if($showSku)
                    <div class="d-flex flex-wrap align-items-center justify-content-between mb-4" style="color: #333; font-size: 14px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; letter-spacing: -0.1px;">
                        <div><span class="fw-bold">Tồn kho:</span> {{ $product->stock > 0 ? $product->stock : 'Liên hệ' }}</div>
                        <div><span class="fw-bold">Mã sản phẩm:</span> {{ 'F' . str_pad($product->id, 3, '0', STR_PAD_LEFT) }}</div>
                    </div>
                    @endif

                    @if((int)$product->stock > 0)
                        <div class="d-flex align-items-center gap-3 mb-4">
                            @if($product->sale_price > 0 && $product->sale_price < $product->price)
                                <h2 class="fw-bold mb-0 price-main">{{ number_format((float)$product->sale_price, 0, ',', '.') }}đ</h2>
                                <del class="mb-0 price-old">{{ number_format((float)$product->price, 0, ',', '.') }}đ</del>
                                <span class="badge px-2 py-1 discount-badge" style="border-radius: 4px;">-{{ round((((float)$product->price - (float)$product->sale_price) / (float)$product->price) * 100) }}%</span>
                            @else
                                <h2 class="fw-bold mb-0 price-main">{{ number_format($product->price > 0 ? (float)$product->price : 0, 0, ',', '.') }}đ</h2>
                            @endif
                        </div>

                        <div class="d-flex align-items-center gap-3 mb-4 mt-2">
                            <span class="fw-bold" style="color: #333; font-size: 14px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; letter-spacing: -0.2px;">Số lượng:</span>
                            <div class="input-group" style="width: 140px; border: 1px solid #ccc; border-radius: 4px; overflow: hidden; background: #fff;">
                                <button class="btn px-3 fw-bold bg-white" type="button" onclick="let input = this.nextElementSibling; input.value = Math.max(1, parseInt(input.value || 1) - 1);" style="border: none; color: #333;">-</button>
                                <input type="number" name="quantity" class="form-control text-center py-2 fw-bold qty-input" value="1" min="1" max="{{ $product->stock > 0 ? $product->stock : 9999 }}" style="border: none; background: white; box-shadow: none; -moz-appearance: textfield;">
                                <button class="btn px-3 fw-bold bg-white" type="button" onclick="let input = this.previousElementSibling; input.value = Math.min(parseInt(input.getAttribute('max')), parseInt(input.value || 1) + 1);" style="border: none; color: #333;">+</button>
                            </div>
                        </div>

                        <div class="d-flex gap-3 mb-5 mt-4">
                            <button class="btn fw-bold d-flex align-items-center justify-content-center py-2 px-4 rounded-pill transition-all btn-add-cart" 
                                    style="flex: 1; text-transform: uppercase; letter-spacing: 0.5px;">
                                <i class="fas fa-cart-plus me-2"></i> Thêm vào giỏ
                            </button>
                            <button class="btn fw-bold d-flex align-items-center justify-content-center py-2 px-4 rounded-pill shadow-sm transition-all btn-buy-now" 
                                    style="flex: 1; text-transform: uppercase; letter-spacing: 0.5px;">
                                Mua ngay
                            </button>
                        </div>
                    @else
                        <h2 class="fw-bold mb-4 mt-2 price-main">Liên hệ</h2>
                        <h3 class="fw-bold mb-3" style="color: #333; font-size: 24px;">Đăng ký nhận thông tin khi có hàng</h3>
                        <form action="#" method="POST" class="mb-5 p-4 rounded-4" style="background: #f8f9fa; border: 1px solid #eee;">
                            @csrf
                            <div class="mb-3"><input type="text" name="name" class="form-control border-0 py-3 shadow-sm" placeholder="Họ và tên *" required style="border-radius: 12px; font-size: 15px;"></div>
                            <div class="mb-3"><input type="text" name="phone" class="form-control border-0 py-3 shadow-sm" placeholder="Điện thoại *" required style="border-radius: 12px; font-size: 15px;"></div>
                            <div class="mb-3"><input type="email" name="email" class="form-control border-0 py-3 shadow-sm" placeholder="Email *" required style="border-radius: 12px; font-size: 15px;"></div>
                            <button type="submit" class="btn w-100 fw-bold py-3 mt-2 text-white shadow-sm" style="background-color: #C92127; border: none; border-radius: 12px; font-size: 15px;">ĐĂNG KÝ NHẬN THÔNG TIN</button>
                        </form>
                    @endif

                    <div class="promotion-content rich-text-content">
                        {!! $promotionHtml !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap');
    #{{ $bid }} { font-family: 'Outfit', sans-serif !important; }
    #{{ $bid }} .promotion-content h1, #{{ $bid }} .promotion-content h2, #{{ $bid }} .promotion-content h3, #{{ $bid }} .promotion-content h4 { color: #C92127; font-weight: bold; text-transform: uppercase; margin-bottom: 15px; }
    #{{ $bid }} .promotion-content p { margin-bottom: 8px; color: #333; }
    #{{ $bid }} .promotion-content img { margin-top: 15px; border-radius: 8px; max-width: 100%; height: auto; }
    #{{ $bid }} .qty-input::-webkit-outer-spin-button, #{{ $bid }} .qty-input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
</style>
@endif
