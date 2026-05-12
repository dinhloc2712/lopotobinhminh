@php
    $product = \App\Models\Product::find($content['product_id'] ?? null);
    $title = $content['title'] ?? 'Thông tin chi tiết';
    $showTitle = $content['show_title'] ?? true;
    $titleColor = $content['title_color'] ?? '#1a202c';
    $titleFontSize = ($content['title_font_size'] ?? 20) . 'px';
    
    // Card Style consistency with Product Detail
    $maxWidthRaw = $content['container_max_width'] ?? '1024px';
    $maxWidth = is_numeric($maxWidthRaw) ? $maxWidthRaw . 'px' : $maxWidthRaw;
    $boxShadow = $content['box_shadow'] ?? 'none';
    $borderRadiusRaw = $content['border_radius'] ?? '16px';
    $borderRadius = is_numeric($borderRadiusRaw) ? $borderRadiusRaw . 'px' : $borderRadiusRaw;
    $cardBg = '#ffffff';

    // Outer Spacing (Lề ngoài khối - dùng MARGIN)
    $mt = ($content['margin_top'] ?? 40) . 'px';
    $mb = ($content['margin_bottom'] ?? 40) . 'px';
    $ml = ($content['margin_left'] ?? 0) . 'px';
    $mr = ($content['margin_right'] ?? 0) . 'px';

    // Inner Padding (Lề trong khung - dùng PADDING)
    $pt = ($content['padding_top'] ?? 48) . 'px';
    $pb = ($content['padding_bottom'] ?? 48) . 'px';
    $pl = ($content['padding_left'] ?? 48) . 'px';
    $pr = ($content['padding_right'] ?? 48) . 'px';
    
    $enableReadMore = $content['enable_read_more'] ?? false;
    $maxHeight = $content['max_height'] ?? 400;

    // Advanced Button Styles
    $btnText = $content['btn_text_color'] ?? '#4e73df';
    $btnTextHover = $content['btn_text_color_hover'] ?? '#ffffff';
    $btnBg = $content['btn_bg_color'] ?? 'transparent';
    $btnBgHover = $content['btn_bg_color_hover'] ?? '#4e73df';
    $btnBorder = $content['btn_border_color'] ?? '#4e73df';
    $btnBorderHover = $content['btn_border_color_hover'] ?? '#4e73df';
    $btnFontSize = ($content['btn_font_size'] ?? 14) . 'px';
    $btnBorderWidth = ($content['btn_border_width'] ?? 1) . 'px';
    
    // Create unique ID for scoped styles
    $bid = 'pdd-' . (isset($block['id']) ? $block['id'] : uniqid());
    
    $wrapperStyle = "max-width: {$maxWidth}; margin-left: auto; margin-right: auto;";
    $cardStyle = "background-color: {$cardBg} !important; border-radius: {$borderRadius}; padding: {$pt} {$pr} {$pb} {$pl} !important;";
    $cardClasses = "product-description-card " . ($boxShadow !== 'none' ? $boxShadow : 'border');
    
    // Sử dụng MARGIN thay vì PADDING cho lề ngoài
    $containerStyle = "margin-top: {$mt}; margin-bottom: {$mb}; margin-left: {$ml}; margin-right: {$mr};";
@endphp

@if($product && !empty(trim(strip_tags($product->description))))
<div id="{{ $bid }}" class="product-description-container" style="{{ $containerStyle }}">
    <div class="container px-0">
        
        <div class="{{ $cardClasses }}" style="{{ $wrapperStyle }} {{ $cardStyle }}">
            @if($showTitle)
            <div class="description-header mb-3">
                <h2 class="fw-bold mb-0 text-uppercase" 
                    style="color: {{ $titleColor }} !important; font-size: {{ $titleFontSize }} !important; letter-spacing: 0.5px;">
                    {{ $title }}
                </h2>
            </div>
            @endif

            <div class="description-content-wrapper position-relative">
                <div class="content-body rich-text-content {{ $enableReadMore ? 'read-more-enabled' : '' }}" 
                     style="{{ $enableReadMore ? 'max-height: ' . $maxHeight . 'px;' : '' }}"
                     id="desc-content-{{ $bid }}">
                    {!! $product->description !!}
                </div>
                
                @if($enableReadMore)
                <div class="read-more-overlay" id="overlay-{{ $bid }}"></div>
                @endif
            </div>

            @if($enableReadMore)
            <div class="read-more-action text-center mt-4">
                <button type="button" class="btn btn-read-more-custom rounded-pill px-4 fw-bold" onclick="toggleReadMorePD('{{ $bid }}')">
                    <span id="btn-text-{{ $bid }}">Xem thêm nội dung</span>
                    <i class="fas fa-chevron-down ms-2" id="btn-icon-{{ $bid }}"></i>
                </button>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap');
    
    #{{ $bid }} { font-family: 'Outfit', sans-serif !important; }

    /* Custom Button Styles */
    #{{ $bid }} .btn-read-more-custom {
        color: {{ $btnText }} !important;
        background-color: {{ $btnBg }} !important;
        border: {{ $btnBorderWidth }} solid {{ $btnBorder }} !important;
        font-size: {{ $btnFontSize }} !important;
        transition: all 0.3s ease;
    }

    #{{ $bid }} .btn-read-more-custom:hover {
        color: {{ $btnTextHover }} !important;
        background-color: {{ $btnBgHover }} !important;
        border-color: {{ $btnBorderHover }} !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    /* Content Area Styles */
    #{{ $bid }} .content-body {
        transition: max-height 0.4s ease-in-out;
        overflow: hidden;
    }

    #{{ $bid }} .content-body.read-more-enabled { position: relative; }
    #{{ $bid }} .content-body.expanded { max-height: 10000px !important; }

    #{{ $bid }} .read-more-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 120px;
        background: linear-gradient(transparent, {{ $cardBg }}cc, {{ $cardBg }});
        pointer-events: none;
        transition: opacity 0.3s;
    }

    #{{ $bid }} .content-body.expanded + .read-more-overlay { opacity: 0; visibility: hidden; }

    /* Rich Text Consistency with Product Detail */
    #{{ $bid }} .rich-text-content img {
        max-width: 100%;
        height: auto;
        border-radius: 12px;
        margin: 1.5rem auto;
        display: block;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }

    #{{ $bid }} .rich-text-content h1, 
    #{{ $bid }} .rich-text-content h2, 
    #{{ $bid }} .rich-text-content h3 {
        color: #1a202c;
        font-weight: 700;
        margin-top: 2rem;
        margin-bottom: 1.25rem;
        letter-spacing: -0.02em;
    }

    #{{ $bid }} .rich-text-content h2 { font-size: 1.6rem; }
    #{{ $bid }} .rich-text-content p {
        line-height: 1.8;
        color: #4a5568;
        font-size: 1.05rem;
        margin-bottom: 1.25rem;
    }

    #{{ $bid }} .rich-text-content ul, 
    #{{ $bid }} .rich-text-content ol { margin-bottom: 1.5rem; padding-left: 1.5rem; }
    #{{ $bid }} .rich-text-content li { margin-bottom: 0.5rem; color: #4a5568; }

    #{{ $bid }} .rich-text-content table {
        width: 100%;
        border-collapse: collapse;
        margin: 1.5rem 0;
        border-radius: 8px;
        overflow: hidden;
    }

    #{{ $bid }} .rich-text-content table td, 
    #{{ $bid }} .rich-text-content table th {
        border: 1px solid #edf2f7;
        padding: 12px 15px;
    }

    #{{ $bid }} .rich-text-content table th { background: #f7fafc; font-weight: 600; }

    /* Responsive Spacing Adjustment */
    @media (max-width: 768px) {
        #{{ $bid }} .product-description-card {
            padding: 24px !important;
        }
        #{{ $bid }}.product-description-container {
            margin-left: 0 !important;
            margin-right: 0 !important;
        }
    }
</style>

@once
<script>
    function toggleReadMorePD(bid) {
        const content = document.getElementById('desc-content-' + bid);
        const btnText = document.getElementById('btn-text-' + bid);
        const btnIcon = document.getElementById('btn-icon-' + bid);
        const overlay = document.getElementById('overlay-' + bid);
        
        if (content.classList.contains('expanded')) {
            content.classList.remove('expanded');
            btnText.innerText = 'Xem thêm nội dung';
            btnIcon.classList.remove('fa-chevron-up');
            btnIcon.classList.add('fa-chevron-down');
            if (overlay) overlay.style.display = 'block';
            
            // Scroll back
            document.getElementById(bid).scrollIntoView({ behavior: 'smooth', block: 'start' });
        } else {
            content.classList.add('expanded');
            btnText.innerText = 'Thu gọn nội dung';
            btnIcon.classList.remove('fa-chevron-down');
            btnIcon.classList.add('fa-chevron-up');
            if (overlay) overlay.style.display = 'none';
        }
    }
</script>
@endonce

@endif
