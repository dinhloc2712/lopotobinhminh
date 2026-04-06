@php
    $sliderId = 'slider-' . uniqid();
    $slider_items = !empty($content['items']) && is_array($content['items']) ? $content['items'] : [];
    
    // Hỗ trợ migration dữ liệu cũ nếu chèn bằng URL List
    if (empty($slider_items) && !empty($content['urls'])) {
        $urls = array_filter(array_map('trim', explode("\n", $content['urls'])));
        foreach ($urls as $url) {
            $slider_items[] = ['image' => $url];
        }
    }
    
    $transition = $content['transition'] ?? 'slide';
    $textPosition = $content['text_position'] ?? 'overlay';
    $imageShape = $content['image_shape'] ?? 'rectangle';
    $imageSize = trim($content['image_size'] ?? '');
    $titleColor = $content['title_color'] ?? '';
    $descColor = $content['desc_color'] ?? '';
    $titleFontSize = !empty($content['title_font_size']) ? 'font-size: ' . $content['title_font_size'] . 'px;' : '';
    $descFontSize = !empty($content['desc_font_size']) ? 'font-size: ' . $content['desc_font_size'] . 'px;' : '';
    $showNav = $content['show_nav'] ?? 'yes';
    $slidesPerViewSetting = $content['slides_per_view'] ?? '1';
    $spaceBetween = isset($content['space_between']) && $content['space_between'] !== '' ? (int) $content['space_between'] : 20;
    $autoplayDelay = isset($content['autoplay_delay']) ? (int) $content['autoplay_delay'] : 4000;
    $isAutoWidth = $slidesPerViewSetting === 'auto';
    $slidesPerView = (int) $slidesPerViewSetting ?: 1;
    $isCenterFocus = $transition === 'center_focus';

    if (!function_exists('isVideoUrlSlider')) {
        function isVideoUrlSlider($url) {
            $url = trim($url);
            if (empty($url)) return false;
            return strpos($url, 'youtube.com') !== false || strpos($url, 'youtu.be') !== false || strpos($url, 'vimeo.com') !== false || preg_match('/\.mp4$/i', $url);
        }
    }
    
    if (!function_exists('parseVideoEmbedSlider')) {
        function parseVideoEmbedSlider($url) {
            $url = trim($url);
            if (strpos($url, 'youtube.com') !== false || strpos($url, 'youtu.be') !== false) {
                if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?|shorts)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match)) {
                    $youtube_id = $match[1] ?? null;
                    if ($youtube_id) {
                        return ['type' => 'iframe', 'url' => 'https://www.youtube.com/embed/' . $youtube_id . '?autoplay=1&mute=1&loop=1&controls=0&showinfo=0&rel=0&playlist=' . $youtube_id];
                    }
                }
            } elseif (strpos($url, 'vimeo.com') !== false) {
                if (preg_match('/vimeo\.com\/([0-9]+)/', $url, $match)) {
                    $vimeo_id = $match[1] ?? null;
                    if ($vimeo_id) {
                        return ['type' => 'iframe', 'url' => 'https://player.vimeo.com/video/' . $vimeo_id . '?autoplay=1&loop=1&muted=1&background=1'];
                    }
                }
            }
            return ['type' => 'video', 'url' => $url];
        }
    }
@endphp

@if($transition === 'accordion')
@once
    <style>
        .accordion-gallery {
            display: flex;
            width: 100%;
            gap: 15px;
        }
        .accordion-gallery .accordion-item {
            position: relative;
            flex: 1;
            transition: flex 0.6s cubic-bezier(0.25, 0.8, 0.25, 1);
            overflow: hidden;
            border-radius: 1rem;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            background-color: #000;
        }
        .accordion-gallery:hover .accordion-item {
            flex: 1;
        }
        .accordion-gallery .accordion-item:hover,
        .accordion-gallery:not(:hover) .accordion-item.active {
            flex: 4;
        }
        .accordion-gallery .accordion-content {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            padding: 2rem;
            background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.4) 60%, transparent 100%);
            color: white;
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.4s ease;
            transition-delay: 0.1s;
            pointer-events: none;
            z-index: 2;
        }
        .accordion-gallery .accordion-item:hover .accordion-content,
        .accordion-gallery:not(:hover) .accordion-item.active .accordion-content {
            opacity: 1;
            transform: translateY(0);
            pointer-events: auto;
        }
        .accordion-title-horizontal {
            position: absolute;
            bottom: 2rem;
            left: 1.5rem;
            right: 1.5rem;
            margin: 0;
            white-space: normal;
            word-wrap: break-word;
            font-weight: bold;
            font-size: 1.25rem;
            color: white;
            text-shadow: 0 2px 4px rgba(0,0,0,0.8);
            transition: opacity 0.3s;
            pointer-events: none;
            z-index: 1;
        }
        .accordion-gallery .accordion-item:hover .accordion-title-horizontal,
        .accordion-gallery:not(:hover) .accordion-item.active .accordion-title-horizontal {
            opacity: 0;
        }
        @media (max-width: 768px) {
            .accordion-gallery {
                flex-direction: column;
                height: 600px !important;
            }
            .accordion-gallery .accordion-item:hover,
            .accordion-gallery:not(:hover) .accordion-item.active {
                flex: 3;
            }
            .accordion-title-horizontal {
                bottom: 1rem;
                left: 1rem;
            }
        }
    </style>
@endonce
    
    <div class="{{ !empty($content['full_width']) ? 'container-fluid px-0' : 'container' }} mt-4">
        <div id="accordion-gallery-{{ $sliderId }}" class="accordion-gallery px-md-4 py-md-2" style="height: {{ !empty($imageSize) ? $imageSize : '400px' }};">
            @php
                $accordion_limit = $slidesPerView > 0 && !$isAutoWidth ? $slidesPerView : count($slider_items);
                $accordion_display_items = array_slice($slider_items, 0, $accordion_limit);
            @endphp
            @foreach($accordion_display_items as $item)
                @php
                    $mediaUrl = trim($item['image'] ?? $item['url'] ?? '');
                    $title = $item['text'] ?? $item['caption'] ?? '';
                    $desc = $item['desc'] ?? '';
                    $isVideo = isVideoUrlSlider($mediaUrl);
                @endphp
                <div class="accordion-item" onclick="void(0)">
                    @if($isVideo)
                        @php $videoData = parseVideoEmbedSlider($mediaUrl); @endphp
                        @if ($videoData['type'] === 'video')
                            <video autoplay loop muted playsinline class="position-absolute w-100 h-100 top-0 start-0" style="object-fit: cover; min-width: 100%; min-height: 100%;">
                                <source src="{{ $videoData['url'] }}" type="video/mp4">
                            </video>
                        @else
                            <iframe src="{{ $videoData['url'] }}" class="position-absolute w-100 h-100 top-0 start-0" style="border: 0; object-fit: cover; pointer-events: none; transform: scale(1.5);" allow="autoplay; fullscreen" allowfullscreen></iframe>
                        @endif
                    @else
                        <img src="{{ $mediaUrl }}" class="position-absolute w-100 h-100 top-0 start-0" style="object-fit: cover; min-width: 100%; min-height: 100%;" alt="">
                    @endif

                    @if(!empty($title))
                        <h4 class="accordion-title-horizontal" style="{!! !empty($titleColor) ? 'color: ' . $titleColor . ' !important;' : '' !!} {{ $titleFontSize }}">{{ $title }}</h4>
                    @endif

                    @if(!empty($title) || !empty($desc) || !empty($item['link_text']))
                    <div class="accordion-content">
                        @if(!empty($title))
                            <h3 class="fw-bold mb-2{{ empty($titleColor) ? ' text-white' : '' }}" style="{!! !empty($titleColor) ? 'color: ' . $titleColor . ' !important;' : '' !!} {{ $titleFontSize }}">{{ $title }}</h3>
                        @endif
                        @if(!empty($desc))
                            <p class="mb-2 opacity-90 d-none d-md-block{{ empty($descColor) ? ' text-white' : '' }}" style="{{ $descFontSize ?: 'font-size: 1.0rem;' }} {!! !empty($descColor) ? 'color: ' . $descColor . ' !important;' : '' !!}">{!! nl2br(e($desc)) !!}</p>
                        @endif

                        @if(!empty($item['meta_text']) || !empty($item['rating']))
                            <div class="d-flex align-items-center gap-3 mt-3 fw-bold" style="color: {{ $titleColor ?: '#ffffff' }}; font-size: 0.95rem;">
                                @if(!empty($item['meta_text']))
                                    <div class="d-flex align-items-center gap-2">
                                        @if(!empty($item['meta_icon']))
                                            <i class="fas {{ strpos($item['meta_icon'], 'fa-') === false ? 'fa-' . $item['meta_icon'] : $item['meta_icon'] }}"></i>
                                        @endif
                                        <span>{{ $item['meta_text'] }}</span>
                                    </div>
                                @endif
                                @if(!empty($item['meta_text']) && !empty($item['rating']))
                                    <div style="width: 1px; height: 16px; background-color: currentColor; opacity: 0.4;"></div>
                                @endif
                                @if(!empty($item['rating']))
                                    <div class="d-flex align-items-center gap-1">
                                        <span class="me-1">{{ $item['rating'] }}</span>
                                        <i class="fas fa-star text-warning"></i>
                                        <i class="fas fa-star text-warning"></i>
                                        <i class="fas fa-star text-warning"></i>
                                        <i class="fas fa-star text-warning"></i>
                                        <i class="fas fa-star text-warning"></i>
                                    </div>
                                @endif
                            </div>
                        @endif

                        @if(!empty($item['link_text']) && !empty($item['link_url']))
                            <a href="{{ $item['link_url'] }}" class="btn btn-primary rounded-pill px-4 py-2 mt-2 fw-bold shadow-sm">
                                {{ $item['link_text'] }}
                            </a>
                        @endif
                    </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const gallery = document.getElementById('accordion-gallery-{{ $sliderId }}');
            if (gallery) {
                const items = gallery.querySelectorAll('.accordion-item');
                if (items.length > 1) {
                    let currentIndex = 0;
                    items[0].classList.add('active'); // Mặc định mở tấm đầu tiên
                    @if($autoplayDelay > 0)
                    setInterval(() => {
                        // Trừ khi chuột đang nằm trong khu vực gallery (Hover ngắt JS)
                        if (!gallery.matches(':hover')) {
                            items[currentIndex].classList.remove('active');
                            currentIndex = (currentIndex + 1) % items.length;
                            items[currentIndex].classList.add('active');
                        }
                    }, {{ $autoplayDelay }});
                    @endif
                }
            }
        });
    </script>
@else
@once
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <style>
        .swiper-button-next::after, .swiper-button-prev::after {
            font-size: 1.2rem !important;
            font-weight: bold;
        }
        .swiper-pagination-bullet-active {
            background: #034166 !important;
        }
        .swiper-pagination-dynamic .swiper-pagination-bullet:not(.swiper-pagination-bullet-active-main) {
            opacity: 0 !important;
            transform: scale(0) !important;
            pointer-events: none;
        }
        .swiper-slide {
            height: auto;
        }
        .swiper-center-focus .swiper-slide .swiper-slide-inner {
            transition: all 0.6s cubic-bezier(0.25, 0.8, 0.25, 1);
            opacity: var(--inactive-opacity, 0.4);
            transform: scale(0.65);
        }
        .swiper-center-focus .swiper-slide-active .swiper-slide-inner {
            opacity: 1;
            transform: scale(1);
        }
        .swiper-center-focus .swiper-slide .slide-text-container {
            opacity: 0;
            visibility: hidden;
            transition: all 0.5s ease;
            transform: translateY(20px);
        }
        .swiper-center-focus .swiper-slide-active .slide-text-container {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
    </style>
@endonce

<div class="{{ !empty($content['full_width']) ? 'container-fluid px-0' : 'container' }} my-4">
    <div class="position-relative px-md-4 py-md-2">
        @php
            $display_items = $slider_items;
            // Chỉ nhân bản slide (Duplicate) cho chế độ Center Focus hoặc Auto Width để xử lý lỗi thiếu slide khi lặp
            if (($isCenterFocus || $isAutoWidth) && count($slider_items) > 0) {
                while (count($display_items) < 12) {
                    $display_items = array_merge($display_items, $slider_items);
                }
            }
        @endphp
        @if(count($display_items) > 0)
            <div class="swiper swiper-{{ $sliderId }} {{ $textPosition === 'overlay' && $imageShape === 'rectangle' ? 'rounded-5' : '' }} {{ $isCenterFocus ? 'swiper-center-focus' : '' }} pb-5" style="--inactive-opacity: {{ $content['inactive_opacity'] ?? '0.4' }};">
                <div class="swiper-wrapper align-items-stretch">
                    @foreach($display_items as $index => $item)
                        @php
                            $mediaUrl = trim($item['image'] ?? $item['url'] ?? '');
                            $isVideo = isVideoUrlSlider($mediaUrl);
                            $title = $item['text'] ?? $item['caption'] ?? '';
                            $desc = $item['desc'] ?? '';
                            $hasText = !empty($title) || !empty($desc) || !empty($item['link_text']);
                            
                            $slideClasses = 'swiper-slide position-relative';
                            if ($isAutoWidth) {
                                $slideClasses .= ' w-auto';
                            }
                            
                            $innerClasses = 'swiper-slide-inner w-100 h-100';
                            if ($textPosition !== 'overlay' && $hasText && !$isCenterFocus) {
                                $innerClasses .= ' d-flex flex-column overflow-hidden bg-transparent';
                            } else {
                                $innerClasses .= ' d-flex flex-column align-items-center justify-content-center bg-transparent';
                            }
                        @endphp
                        
                        <div class="{{ $slideClasses }}" style="{{ $isAutoWidth ? 'width: ' . (!empty($imageSize) ? $imageSize : '400px') . ';' : '' }}">
                            <div class="{{ $innerClasses }}">
                            @if($textPosition === 'top' && $hasText)
                                <div class="p-4 text-center d-flex flex-column justify-content-center align-items-center z-2 slide-text-container" style="flex: 0 0 auto;">
                                    @if(!empty($title))
                                        <h4 class="fw-bold mb-2{{ empty($titleColor) ? ' text-dark' : '' }}" style="{!! !empty($titleColor) ? 'color: ' . $titleColor . ' !important;' : '' !!} {{ $titleFontSize }}">{{ $title }}</h4>
                                    @endif
                                    @if(!empty($desc))
                                        <p class="mb-3{{ empty($descColor) ? ' text-secondary' : '' }}" style="{{ $descFontSize ?: 'font-size: 0.9rem;' }} {!! !empty($descColor) ? 'color: ' . $descColor . ' !important;' : '' !!}">{!! nl2br(e($desc)) !!}</p>
                                    @endif

                                    @if(!empty($item['meta_text']) || !empty($item['rating']))
                                        <div class="d-flex align-items-center justify-content-center gap-3 mt-2 fw-bold" style="color: {{ $titleColor ?: '#034166' }}; font-size: 0.95rem;">
                                            @if(!empty($item['meta_text']))
                                                <div class="d-flex align-items-center gap-2">
                                                    @if(!empty($item['meta_icon']))
                                                        <i class="fas {{ strpos($item['meta_icon'], 'fa-') === false ? 'fa-' . $item['meta_icon'] : $item['meta_icon'] }}"></i>
                                                    @endif
                                                    <span>{{ $item['meta_text'] }}</span>
                                                </div>
                                            @endif
                                            @if(!empty($item['meta_text']) && !empty($item['rating']))
                                                <div style="width: 1px; height: 16px; background-color: currentColor; opacity: 0.4;"></div>
                                            @endif
                                            @if(!empty($item['rating']))
                                                <div class="d-flex align-items-center gap-1">
                                                    <span class="me-1">{{ $item['rating'] }}</span>
                                                    <i class="fas fa-star text-warning"></i>
                                                    <i class="fas fa-star text-warning"></i>
                                                    <i class="fas fa-star text-warning"></i>
                                                    <i class="fas fa-star text-warning"></i>
                                                    <i class="fas fa-star text-warning"></i>
                                                </div>
                                            @endif
                                        </div>
                                    @endif

                                    @if(!empty($item['link_text']) && !empty($item['link_url']))
                                        <a href="{{ $item['link_url'] }}" class="btn btn-sm btn-primary rounded-pill px-4 py-2 mt-2 fw-bold shadow-sm">
                                            {{ $item['link_text'] }}
                                        </a>
                                    @endif
                                </div>
                            @endif

                            @php
                                $wrapperClass = 'position-relative w-100 bg-dark';
                                $wrapperStyle = '';
                                if ($imageShape === 'circle') {
                                    $wrapperClass .= ' rounded-circle overflow-hidden mx-auto my-3 shadow-sm';
                                    $wrapperStyle = 'aspect-ratio: 1/1; max-width: ' . (!empty($imageSize) ? $imageSize : '80%') . ';';
                                } elseif ($imageShape === 'square') {
                                    $wrapperClass .= ' rounded-4 overflow-hidden mx-auto my-3 shadow-sm';
                                    $wrapperStyle = 'aspect-ratio: 1/1; max-width: ' . (!empty($imageSize) ? $imageSize : '90%') . ';';
                                } else {
                                    if (!empty($imageSize)) {
                                        $wrapperClass .= ' mx-auto my-3 overflow-hidden rounded-4 shadow-sm border';
                                        $wrapperStyle = 'max-width: ' . $imageSize . '; aspect-ratio: 16/9;';
                                    } elseif ($textPosition === 'overlay' || !$hasText) {
                                        $wrapperClass .= ' h-100 ratio ratio-21x9';
                                    } else {
                                        $wrapperClass .= ' flex-grow-1 ratio ratio-16x9';
                                    }
                                }
                            @endphp
                            
                            <div class="{{ $wrapperClass }}" style="{{ $wrapperStyle }}">
                                @if($isVideo)
                                    @php $videoData = parseVideoEmbedSlider($mediaUrl); @endphp
                                    <div class="position-absolute w-100 h-100 top-0 start-0 z-0 text-center bg-black" style="pointer-events: none; overflow: hidden;">
                                        @if ($videoData['type'] === 'video')
                                            <video autoplay loop muted playsinline class="w-100 h-100" style="object-fit: cover;">
                                                <source src="{{ $videoData['url'] }}" type="video/mp4">
                                            </video>
                                        @else
                                            <iframe src="{{ $videoData['url'] }}" 
                                                class="w-100 h-100" style="border: 0; transform: scale(1.5);" 
                                                allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
                                        @endif
                                        @if($textPosition === 'overlay' && $hasText)
                                            <div class="position-absolute w-100 h-100 top-0 start-0" style="background-color: rgba(0,0,0,0.4);"></div>
                                        @endif
                                    </div>
                                @else
                                    <img src="{{ $mediaUrl }}" class="d-block w-100 position-absolute top-0 start-0 h-100" style="object-fit: cover;" alt="Slide">
                                    @if($textPosition === 'overlay' && $hasText)
                                        <div class="position-absolute w-100 h-100 top-0 start-0" style="background-color: rgba(0,0,0,0.4);"></div>
                                    @endif
                                @endif
                                
                                @if($textPosition === 'overlay' && $hasText)
                                    <div class="position-absolute d-flex flex-column justify-content-center align-items-center p-3 p-md-5 start-50 top-50 translate-middle w-100 h-100 z-2 text-center slide-text-container" style="pointer-events: auto;">
                                        @if(!empty($title))
                                            <h3 class="fw-bold mb-2{{ empty($titleColor) ? ' text-white' : '' }}" style="text-shadow: 0 2px 4px rgba(0,0,0,0.5); {!! !empty($titleColor) ? 'color: ' . $titleColor . ' !important;' : '' !!} {{ $titleFontSize }}">{{ $title }}</h3>
                                        @endif
                                        @if(!empty($desc))
                                            <p class="mb-3 opacity-90 d-none d-md-block{{ empty($descColor) ? ' text-white' : '' }}" style="text-shadow: 0 1px 3px rgba(0,0,0,0.5); {{ $descFontSize ?: 'font-size: 1.1rem;' }} {!! !empty($descColor) ? 'color: ' . $descColor . ' !important;' : '' !!}">{!! nl2br(e($desc)) !!}</p>
                                        @endif

                                        @if(!empty($item['meta_text']) || !empty($item['rating']))
                                            <div class="d-flex align-items-center justify-content-center gap-3 mt-2 fw-bold" style="color: {{ $titleColor ?: '#ffffff' }}; font-size: 0.95rem;">
                                                @if(!empty($item['meta_text']))
                                                    <div class="d-flex align-items-center gap-2">
                                                        @if(!empty($item['meta_icon']))
                                                            <i class="fas {{ strpos($item['meta_icon'], 'fa-') === false ? 'fa-' . $item['meta_icon'] : $item['meta_icon'] }}"></i>
                                                        @endif
                                                        <span>{{ $item['meta_text'] }}</span>
                                                    </div>
                                                @endif
                                                @if(!empty($item['meta_text']) && !empty($item['rating']))
                                                    <div style="width: 1px; height: 16px; background-color: currentColor; opacity: 0.4;"></div>
                                                @endif
                                                @if(!empty($item['rating']))
                                                    <div class="d-flex align-items-center gap-1">
                                                        <span class="me-1">{{ $item['rating'] }}</span>
                                                        <i class="fas fa-star text-warning"></i>
                                                        <i class="fas fa-star text-warning"></i>
                                                        <i class="fas fa-star text-warning"></i>
                                                        <i class="fas fa-star text-warning"></i>
                                                        <i class="fas fa-star text-warning"></i>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif

                                        @if(!empty($item['link_text']) && !empty($item['link_url']))
                                            <a href="{{ $item['link_url'] }}" class="btn btn-primary rounded-pill px-4 py-2 mt-3 fw-bold shadow-sm">
                                                {{ $item['link_text'] }}
                                            </a>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            @if($textPosition === 'bottom' && $hasText)
                                <div class="p-4 text-center d-flex flex-column justify-content-center align-items-center z-2 slide-text-container" style="flex: 0 0 auto;">
                                    @if(!empty($title))
                                        <h4 class="fw-bold mb-2{{ empty($titleColor) ? ' text-dark' : '' }}" style="{!! !empty($titleColor) ? 'color: ' . $titleColor . ' !important;' : '' !!} {{ $titleFontSize }}">{{ $title }}</h4>
                                    @endif
                                    @if(!empty($desc))
                                        <p class="mb-3{{ empty($descColor) ? ' text-secondary' : '' }}" style="{{ $descFontSize ?: 'font-size: 0.9rem;' }} {!! !empty($descColor) ? 'color: ' . $descColor . ' !important;' : '' !!}">{!! nl2br(e($desc)) !!}</p>
                                    @endif

                                    @if(!empty($item['meta_text']) || !empty($item['rating']))
                                        <div class="d-flex align-items-center justify-content-center gap-3 mt-2 fw-bold" style="color: {{ $titleColor ?: '#034166' }}; font-size: 0.95rem;">
                                            @if(!empty($item['meta_text']))
                                                <div class="d-flex align-items-center gap-2">
                                                    @if(!empty($item['meta_icon']))
                                                        <i class="fas {{ strpos($item['meta_icon'], 'fa-') === false ? 'fa-' . $item['meta_icon'] : $item['meta_icon'] }}"></i>
                                                    @endif
                                                    <span>{{ $item['meta_text'] }}</span>
                                                </div>
                                            @endif
                                            @if(!empty($item['meta_text']) && !empty($item['rating']))
                                                <div style="width: 1px; height: 16px; background-color: currentColor; opacity: 0.4;"></div>
                                            @endif
                                            @if(!empty($item['rating']))
                                                <div class="d-flex align-items-center gap-1">
                                                    <span class="me-1">{{ $item['rating'] }}</span>
                                                    <i class="fas fa-star text-warning"></i>
                                                    <i class="fas fa-star text-warning"></i>
                                                    <i class="fas fa-star text-warning"></i>
                                                    <i class="fas fa-star text-warning"></i>
                                                    <i class="fas fa-star text-warning"></i>
                                                </div>
                                            @endif
                                        </div>
                                    @endif

                                    @if(!empty($item['link_text']) && !empty($item['link_url']))
                                        <a href="{{ $item['link_url'] }}" class="btn btn-sm btn-primary rounded-pill px-4 py-2 mt-2 fw-bold shadow-sm">
                                            {{ $item['link_text'] }}
                                        </a>
                                    @endif
                                </div>
                            @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                
                @if(count($slider_items) > 1)
                    <div class="swiper-pagination swiper-pagination-{{ $sliderId }} mt-4 position-absolute bottom-0 w-100 text-center"></div>
                @endif
            </div>

            @if(count($slider_items) > 1 && $showNav !== 'no')
                <div class="swiper-button-prev swiper-button-prev-{{ $sliderId }} bg-white text-dark rounded-circle shadow start-0" style="width: 45px; height: 45px; margin-top: -30px;"></div>
                <div class="swiper-button-next swiper-button-next-{{ $sliderId }} bg-white text-dark rounded-circle shadow end-0" style="width: 45px; height: 45px; margin-top: -30px;"></div>
            @endif
        @else
            <div class="p-5 text-center bg-light text-muted border rounded-4 w-100">
                <i class="fas fa-images fs-2 mb-2"></i><br>
                Slider chưa có dữ liệu cấu hình.
            </div>
        @endif
    </div>
</div>

@if(count($slider_items) > 0)
@once
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
@endonce
<script>
    document.addEventListener("DOMContentLoaded", function () {
        setTimeout(function() {
            if (typeof Swiper !== 'undefined') {
                new Swiper('.swiper-{{ $sliderId }}', {
                    slidesPerView: {{ $isCenterFocus && !$isAutoWidth ? 1.5 : ($isAutoWidth ? "'auto'" : 1) }},
                    centeredSlides: {{ $isCenterFocus ? 'true' : 'false' }},
                    slideToClickedSlide: {{ $isCenterFocus ? 'true' : 'false' }},
                    spaceBetween: {{ $spaceBetween }},
                    breakpoints: {
                        768: {
                            slidesPerView: {{ $isCenterFocus && !$isAutoWidth ? 3 : ($isAutoWidth ? "'auto'" : ($slidesPerView > 1 ? min(2, $slidesPerView) : 1)) }}
                        },
                        992: {
                            slidesPerView: {{ $isCenterFocus && !$isAutoWidth ? max(3, $slidesPerView) : ($isAutoWidth ? "'auto'" : $slidesPerView) }}
                        }
                    },
                    effect: '{{ $slidesPerView == 1 && $transition == 'fade' && !$isCenterFocus && !$isAutoWidth ? 'fade' : 'slide' }}',
                    fadeEffect: {
                        crossFade: true
                    },
                    loop: {{ count($slider_items) > ($isCenterFocus || $isAutoWidth ? 1 : $slidesPerView) ? 'true' : 'false' }},
                    @if($showNav !== 'no')
                    navigation: {
                        nextEl: '.swiper-button-next-{{ $sliderId }}',
                        prevEl: '.swiper-button-prev-{{ $sliderId }}',
                    },
                    @endif
                    pagination: {
                        el: '.swiper-pagination-{{ $sliderId }}',
                        clickable: true,
                        dynamicBullets: true,
                        dynamicMainBullets: 5
                    },
                    @if($autoplayDelay > 0)
                    autoplay: {
                        delay: {{ $autoplayDelay }},
                        disableOnInteraction: false,
                    },
                    @else
                    autoplay: false,
                    @endif
                    grabCursor: true
                });
            }
        }, 100);
    });
</script>
@endif
@endif
