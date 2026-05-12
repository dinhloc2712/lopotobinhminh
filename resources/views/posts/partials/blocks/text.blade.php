@php
    $hasCard = $content['has_card'] ?? false;
    // Standardized premium layout values
    $maxWidth     = '900px'; 
    $borderRadius = '16px';
    $boxShadow    = 'shadow-sm';
    $cardBg       = '#ffffff';
    
    $wrapperStyle = "max-width: {$maxWidth}; margin-left: auto; margin-right: auto;";

    $cardStyle = '';
    $cardClasses = '';
    if ($hasCard) {
        $cardClasses = "bg-white p-4 p-md-5 " . $boxShadow;
        $cardStyle = "border-radius: {$borderRadius}; background-color: {$cardBg} !important;";
    }
@endphp

<div class="{{ !empty($content['full_width']) ? 'container-fluid px-0' : 'container' }} pt-4">
    <div class="text-block-wrapper">
        @php
            $body = $content['body'] ?? '';
            $showToc = $content['show_toc'] ?? false;
            $tocPosition = $content['toc_position'] ?? 'top'; // top, left, right
            
            // Tự động nhận diện layout bài báo nếu có sidebar hoặc thumbnail đặc biệt
            $isArticleLayout = !empty($content['show_new_posts']) || 
                               !empty($content['show_new_products']) || 
                               !empty($content['post_thumbnail']) ||
                               ($content['is_article_layout'] ?? false);
            
            $sidebarItems = $content['sidebar_items'] ?? [];

            $postTitle = $post->title ?? '';
            // Ưu tiên chọn ảnh được cấu hình trong block, fallback về ảnh chính của post
            $postThumbnail = !empty($content['post_thumbnail']) ? $content['post_thumbnail'] : ($post->thumbnail ?? '');
            $tocHtml = '';

            if ($showToc && !empty($body)) {
                $options = [
                    'list_type' => 'ul',
                    'toc_class' => 'toc-list list-unstyled mb-0',
                    'toc_item_class' => 'mb-2',
                    'toc_link_class' => 'text-decoration-none text-dark toc-link hover-primary small d-block',
                    'min_level' => 1,
                    'max_level' => 6,
                    'enable_toggle' => true,
                    'display_style' => 'none',
                ];

                $generator = new \App\Services\TOCGenerator($body, $options);
                $tocListHtml = $generator->generateTOC();

                if (!empty($tocListHtml)) {
                    $tocHtml .= '<div class="custom-toc-container p-4 rounded-4 bg-light border ' . ($tocPosition === 'top' ? 'mb-4' : '') . '">';
                    $tocHtml .= $tocListHtml;
                    $tocHtml .= '</div>';
                    $body = $generator->getProcessedHtml();
                }
            }

            $headerHtml = '';
            if ($isArticleLayout) {
                /* Thumbnail removed from block rendering as per user request */
                if ($postTitle) {
                    $textColor = $content['text_color'] ?? '#004a80';
                    $headerHtml .= '<h1 class="fw-bold mb-4 fs-3" style="color: ' . $textColor . '; line-height: 1.3;">' . htmlspecialchars($postTitle) . '</h1>';
                }
            }

            // Prepare content specific style
            $contentStyle = $cardStyle;
            if (!$isArticleLayout && $maxWidth) {
                $wrapperStyle = "max-width: {$maxWidth}; margin-left: auto; margin-right: auto;";
            }
        @endphp

        @if ($isArticleLayout)
            @php
                $sidebarSections = [];
                
                // 1. Bài viết mới
                if (!empty($content['show_new_posts'])) {
                    $sLimit = !empty($content['new_posts_limit']) ? (int)$content['new_posts_limit'] : 5;
                    $sCat = \App\Models\PostCategory::where('slug', 'tin-tuc')->first();
                    
                    $sPosts = \App\Models\Post::where('status', 'published')
                        ->where('id', '!=', $post->id) // Loại trừ bài viết hiện tại
                        ->when($sCat, function($q) use ($sCat) {
                            return $q->where('category_id', $sCat->id);
                        })
                        ->orderBy('created_at', 'desc') // Sắp xếp theo ngày tạo mới nhất
                        ->take($sLimit)
                        ->get();
                        
                    if ($sPosts->count() > 0) {
                        $sidebarSections[] = [
                            'type' => 'post',
                            'title' => (!empty($content['new_posts_title']) ? $content['new_posts_title'] : 'Bài viết mới nhất'), 
                            'items' => $sPosts
                        ];
                    }
                }

                // 2. Sản phẩm mới - Lấy từ model Product thực tế
                if (!empty($content['show_new_products'])) {
                    $sLimit = !empty($content['new_products_limit']) ? (int)$content['new_products_limit'] : 5;
                    // Lấy sản phẩm mới nhất theo ngày tạo
                    $sProducts = \App\Models\Product::where('status', 'active')
                        ->orderBy('created_at', 'asc')
                        ->take($sLimit)
                        ->get();
                    
                    if ($sProducts->count() > 0) {
                        // Mapping products to their respective "post" pages by searching for product_detail blocks
                        $productIds = $sProducts->pluck('id')->toArray();
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

                        $sidebarSections[] = [
                            'type' => 'product',
                            'title' => (!empty($content['new_products_title']) ? $content['new_products_title'] : 'Sản phẩm mới nhất'), 
                            'items' => $sProducts,
                            'productPostMap' => $productPostMap
                        ];
                    }
                }

                $hasSidebar = count($sidebarSections) > 0;
                $mainMaxWidth = $maxWidth ?? '1000px';
                $containerMaxWidth = $hasSidebar ? "calc({$mainMaxWidth} + 350px + 20px)" : $mainMaxWidth;
            @endphp

            <div class="d-flex justify-content-center">
                <div class="row gx-0 {{ !empty($content['full_width']) ? 'w-100' : '' }}" 
                     style="width: 100%; max-width: {{ $containerMaxWidth }}; {{ $hasSidebar ? 'gap: 20px;' : '' }}">
                    {{-- Main Content Column --}}
                    <div class="content-column flex-grow-1" style="min-width: 0; max-width: {{ $mainMaxWidth }};">
                        <div class="rich-text-content position-relative {{ $cardClasses }}" style="{{ $contentStyle }}">
                            {!! $headerHtml !!}

                            <div class="mb-4 pb-3 border-bottom d-flex align-items-center flex-wrap gap-3 small text-muted">
                                <div><i class="far fa-calendar-alt me-1"></i> {{ $post->created_at->format('d F, Y') }}</div>
                                <div><i class="far fa-user me-1"></i> {{ $post->admin->name ?? 'Administrator' }}</div>
                            </div>

                            @if (!empty($tocHtml))
                                @if ($tocPosition === 'top')
                                    {!! $tocHtml !!}
                                    <div class="content-body">
                                        {!! $body !!}
                                    </div>
                                @else
                                    <div class="row {{ $tocPosition === 'left' ? 'flex-row' : 'flex-row-reverse' }}" style="--bs-gutter-x: 20px;">
                                        <div class="col-md-4 mb-4 mb-md-0 d-none d-md-block">
                                            <div class="sticky-toc" style="top: 100px; position: sticky;">
                                                {!! $tocHtml !!}
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="d-block d-md-none mb-4">
                                                {!! $tocHtml !!}
                                            </div>
                                            <div class="content-body">
                                                {!! $body !!}
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @else
                                <div class="content-body">
                                    {!! $body !!}
                                </div>
                            @endif
                        </div>
                    </div>

                    @if($hasSidebar)
                        {{-- Sidebar Column --}}
                        <div class="sidebar-column mt-0 mt-lg-0" style="width: 100%; max-width: 100%; flex: 0 0 auto;">
                            <style>
                                @media (min-width: 992px) {
                                    .sidebar-column {
                                        width: 350px !important;
                                        flex: 0 0 350px !important;
                                    }
                                    .content-column {
                                        flex: 0 0 {{ $mainMaxWidth }} !important;
                                    }
                                }
                                .sidebar-title-underlined {
                                    position: relative;
                                    border-bottom: 1px solid #eee;
                                    padding-bottom: 10px;
                                }
                                .sidebar-title-underlined::after {
                                    content: '';
                                    position: absolute;
                                    bottom: -1px;
                                    left: 0;
                                    width: 100px;
                                    height: 3px;
                                    background: #00cfef;
                                }
                                .product-price-new {
                                    color: #ef4444;
                                    font-weight: bold;
                                    font-size: 0.9rem;
                                }
                                .product-price-old {
                                    color: #9ca3af;
                                    text-decoration: line-through;
                                    font-size: 0.8rem;
                                }
                            </style>
                            <div class="sidebar-content">
                                <div class="d-flex flex-column" style="gap: 20px;"> {{-- Fixed 20px gap between sidebar cards --}}
                                @foreach($sidebarSections as $section)
                                        <div class="sidebar-block bg-white p-4 shadow-sm rounded-4 border">
                                            <h5 class="fw-bold text-uppercase mb-4 sidebar-title-underlined"
                                                style="color: #1a202c; font-size: 1.1rem; letter-spacing: 0.5px;">
                                                {{ $section['title'] }}
                                            </h5>

                                            <div class="related-items d-flex flex-column gap-3">
                                                @foreach ($section['items'] as $item)
                                                    @php
                                                        $itemTitle = ($section['type'] === 'post') ? $item->title : $item->name;
                                                        
                                                        // Resolve URL
                                                        if ($section['type'] === 'post') {
                                                            $itemUrl = route('posts.show', $item->slug ?: $item->id);
                                                        } else {
                                                            $pId = $item->id;
                                                            $pSlug = $section['productPostMap'][$pId] ?? null;
                                                            $itemUrl = $pSlug ? route('posts.show', $pSlug) : '#';
                                                        }
                                                        
                                                        // Resolve thumbnail path robustly
                                                        $thumb = $item->thumbnail;
                                                        $imgUrl = '';
                                                        if ($thumb) {
                                                            $imgUrl = (str_starts_with($thumb, 'http://') || str_starts_with($thumb, 'https://')) 
                                                                ? $thumb 
                                                                : asset('storage/' . $thumb);
                                                        }
                                                    @endphp
                                                    <a href="{{ $itemUrl }}"
                                                        class="text-decoration-none group d-flex gap-3 align-items-start article-sidebar-item"
                                                        style="transition: all 0.3s ease;">
                                                        @if ($imgUrl)
                                                            <div class="flex-shrink-0 rounded-3 overflow-hidden border" style="width: 100px; height: 75px; background: #f8fafc;">
                                                                <img src="{{ $imgUrl }}" alt="{{ $itemTitle }}"
                                                                    class="w-100 h-100"
                                                                    style="object-fit: cover; transition: transform 0.5s ease;">
                                                            </div>
                                                        @else
                                                            <div class="flex-shrink-0 rounded-3 bg-light d-flex align-items-center justify-content-center border"
                                                                style="width: 100px; height: 75px;">
                                                                <i class="far fa-image text-muted"></i>
                                                            </div>
                                                        @endif
                                                        <div class="flex-grow-1 overflow-hidden">
                                                            <h6 class="fw-bold mb-1 lh-base text-dark sidebar-post-title"
                                                                style="font-size: 0.85rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; transition: color 0.2s; text-transform: uppercase;">
                                                                {{ $itemTitle }}
                                                            </h6>
                                                            
                                                            @if($section['type'] === 'product')
                                                                <div class="d-flex flex-wrap align-items-center gap-2 mt-1">
                                                                    @if($item->stock > 0)
                                                                        <span class="product-price-new">{{ number_format($item->sale_price ?: $item->price, 0, ',', '.') }}đ</span>
                                                                        @if($item->sale_price > 0 && $item->sale_price < $item->price)
                                                                            <span class="product-price-old">{{ number_format($item->price, 0, ',', '.') }}đ</span>
                                                                        @endif
                                                                    @else
                                                                        <span class="product-price-new fw-bold">Liên hệ</span>
                                                                    @endif
                                                                </div>
                                                            @else
                                                                {{-- Post specific info if needed, e.g. date --}}
                                                                {{-- <div class="text-secondary" style="font-size: 0.75rem;"><i class="far fa-calendar-alt me-1"></i> {{ $item->created_at->format('d/m/Y') }}</div> --}}
                                                            @endif
                                                        </div>
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @else
            {{-- Standard Non-Sidebar Layout --}}
            <div class="rich-text-content position-relative {{ $cardClasses }}" style="{{ $contentStyle }}">
                @if (!empty($tocHtml))
                    @if ($tocPosition === 'top')
                        {!! $tocHtml !!}
                        <div class="content-body">
                            {!! $body !!}
                        </div>
                    @else
                        <div class="row gx-lg-5 {{ $tocPosition === 'left' ? 'flex-row' : 'flex-row-reverse' }}">
                            <div class="col-lg-3 mb-4 mb-lg-0 d-none d-lg-block">
                                <div class="sticky-toc" style="top: 100px; position: sticky;">
                                    {!! $tocHtml !!}
                                </div>
                            </div>
                            <div class="col-lg-9">
                                <div class="d-block d-lg-none mb-4">
                                    {!! $tocHtml !!}
                                </div>
                                <div class="content-body">
                                    {!! $body !!}
                                </div>
                            </div>
                        </div>
                    @endif
                @else
                    <div class="content-body">
                        {!! $body !!}
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>

<style>
    .rich-text-content h1,
    .rich-text-content h2,
    .rich-text-content h3 {
        margin-top: 2rem;
        margin-bottom: 1.25rem;
        color: #1a202c;
    }

    .rich-text-content h1:first-child,
    .rich-text-content h2:first-child {
        margin-top: 0;
    }

    .rich-text-content img {
        max-width: 100%;
        height: auto;
        border-radius: 12px;
        margin: 1.5rem 0;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }

    .rich-text-content p {
        margin-bottom: 1.25rem;
    }


    /* TOC Styles */
    .custom-toc-container {
        width: 100%;
    }

    .sticky-toc {
        max-height: calc(100vh - 120px);
        overflow-y: auto;
    }

    .sticky-toc::-webkit-scrollbar {
        width: 4px;
    }

    .sticky-toc::-webkit-scrollbar-thumb {
        background-color: #cbd5e1;
        border-radius: 4px;
    }

    .toc-link {
        transition: color 0.2s;
        display: block;
        line-height: 1.4;
    }

    .toc-link:hover,
    .hover-primary:hover {
        color: #0d6efd !important;
    }

    .custom-toc-container ul ul {
        padding-left: 1.5rem;
        list-style: none;
        margin-top: 0.5rem;
        margin-bottom: 0.5rem;
    }

    .custom-toc-container ul ul li {
        margin-bottom: 0.5rem;
    }

    /* Style for marker lines using CSS pseudo-elements (Optional) */
    .custom-toc-container>ul>li {
        position: relative;
    }

    .toc-header:hover h6 {
        color: #0d6efd !important;
    }

    .toc-header.active .toc-toggle-icon {
        transform: rotate(180deg);
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Toggle TOC function defined globally to be used by onclick
        window.toggleTOC = function(header) {
            const $header = $(header);
            const $body = $header.next('.toc-body');

            if ($header.hasClass('active')) {
                // Đóng: trượt lên + mờ dần
                $header.removeClass('active');
                $body.animate({
                    height: 'hide',
                    opacity: 0
                }, 350);
            } else {
                // Mở: trượt xuống + rõ dần
                $header.addClass('active');
                $body.animate({
                    height: 'show',
                    opacity: 1
                }, 350);
            }
        };

        // Smooth scroll for TOC links
        document.querySelectorAll('.toc-link').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                const targetEl = document.querySelector(targetId);

                if (targetEl) {
                    // Offset cho fixed header nếu có 
                    const headerOffset = 80;
                    const elementPosition = targetEl.getBoundingClientRect().top;
                    const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

                    window.scrollTo({
                        top: offsetPosition,
                        behavior: "smooth"
                    });
                }
            });
        });
    });
</script>
