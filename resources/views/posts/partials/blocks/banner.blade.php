@php
    $height = !empty($content['height']) ? $content['height'] : 'auto';
    $height = is_numeric($height) ? $height . 'px' : $height;
    $heightStr = strtolower(trim($height));
    $slider_items = !empty($content['items']) && is_array($content['items']) ? $content['items'] : [];

    if (!function_exists('isVideoUrl')) {
        function isVideoUrl($url)
        {
            $url = trim($url);
            if (empty($url)) {
                return false;
            }
            return strpos($url, 'youtube.com') !== false ||
                strpos($url, 'youtu.be') !== false ||
                strpos($url, 'vimeo.com') !== false ||
                preg_match('/\.mp4$/i', $url);
        }
    }

    if (!function_exists('parseVideoEmbed')) {
        function parseVideoEmbed($url)
        {
            $url = trim($url);
            if (strpos($url, 'youtube.com') !== false || strpos($url, 'youtu.be') !== false) {
                if (
                    preg_match(
                        '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?|shorts)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i',
                        $url,
                        $match,
                    )
                ) {
                    $youtube_id = $match[1] ?? null;
                    if ($youtube_id) {
                        return [
                            'type' => 'iframe',
                            'id' => $youtube_id,
                            'url' =>
                                'https://www.youtube.com/embed/' .
                                $youtube_id .
                                '?autoplay=1&mute=1&loop=1&controls=0&showinfo=0&rel=0&playlist=' .
                                $youtube_id,
                        ];
                    }
                }
            } elseif (strpos($url, 'vimeo.com') !== false) {
                if (preg_match('/vimeo\.com\/([0-9]+)/', $url, $match)) {
                    $vimeo_id = $match[1] ?? null;
                    if ($vimeo_id) {
                        return [
                            'type' => 'iframe',
                            'id' => $vimeo_id,
                            'url' =>
                                'https://player.vimeo.com/video/' .
                                $vimeo_id .
                                '?autoplay=1&loop=1&muted=1&background=1',
                        ];
                    }
                }
            }
            return ['type' => 'video', 'url' => $url];
        }
    }

    if (!function_exists('getMediaThumbnail')) {
        function getMediaThumbnail($url) {
            $url = trim($url);
            if (strpos($url, 'youtube.com') !== false || strpos($url, 'youtu.be') !== false) {
                if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?|shorts)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match)) {
                    return "https://img.youtube.com/vi/{$match[1]}/maxresdefault.jpg";
                }
            }
            return $url; // Fallback to original for MP4 or direct links
        }
    }

    $bannerId = 'banner-' . uniqid();

    $padding_top = $content['padding_top'] ?? '0px';
    $padding_bottom = $content['padding_bottom'] ?? '0px';
    $padding_left = $content['padding_left'] ?? '0px';
    $padding_right = $content['padding_right'] ?? '0px';

    // Auto-append px if numeric
    $padding_top = is_numeric($padding_top) ? $padding_top . 'px' : $padding_top;
    $padding_bottom = is_numeric($padding_bottom) ? $padding_bottom . 'px' : $padding_bottom;
    $padding_left = is_numeric($padding_left) ? $padding_left . 'px' : $padding_left;
    $padding_right = is_numeric($padding_right) ? $padding_right . 'px' : $padding_right;
@endphp

<style>
    .{{ $bannerId }} {
        @if ($heightStr !== 'auto')
            height: {{ $height }};
        @endif
    }
</style>

<div class="banner-block w-100 position-relative {{ $bannerId }}" 
     style="overflow: hidden; padding: {{ $padding_top }} {{ $padding_right }} {{ $padding_bottom }} {{ $padding_left }}; border-radius: 20px;">
    @if (count($slider_items) > 0)
        {{-- Banner Slider --}}
        @php $sliderId = 'banner-carousel-' . uniqid(); @endphp
        @php
            $layout = $content['layout'] ?? 'slider';
            $rightItems = !empty($content['right_items']) ? $content['right_items'] : [];
            $hasRightItems = $layout === 'split' && count(array_filter(array_column($rightItems, 'image'))) > 0;
            $sliderColClass = $hasRightItems ? 'col-lg-8' : 'col-12';
        @endphp

        <div class="container-fluid px-0 h-100">
        <div class="row m-0 align-items-stretch {{ $heightStr !== 'auto' ? 'h-100' : '' }}">
            <div class="{{ $sliderColClass }} {{ $heightStr !== 'auto' ? 'h-100' : '' }} p-0">
                {{-- Swiper Slider --}}
                <div class="swiper-container-{{ $bannerId }} swiper w-100 h-100 rounded-4 overflow-hidden shadow-sm" style="background-color: #034166;">
                    <div class="swiper-wrapper">
                        @foreach ($slider_items as $index => $item)
                            <div class="swiper-slide w-100 {{ $heightStr !== 'auto' ? 'h-100' : '' }} position-relative transition-none">
                                @php
                                    $mediaUrl = trim($item['image'] ?? '');
                                    $isVideo = isVideoUrl($mediaUrl);
                                @endphp

                                @if ($isVideo)
                                    @php $thumbUrl = getMediaThumbnail($mediaUrl); @endphp
                                    {{-- Hiển thị ảnh thay vì video tự chạy trong slider --}}
                                    <div class="position-absolute w-100 h-100 top-0 start-0 z-0"
                                        style="background-image: url('{{ $thumbUrl }}'); background-size: cover; background-position: center;">
                                    </div>

                                    <a href="{{ $mediaUrl }}" 
                                       data-fancybox="banner-video-gallery-{{ $bannerId }}"
                                       class="position-absolute w-100 h-100 top-0 start-0 z-3" 
                                       aria-label="Play Video">
                                        <div class="position-absolute top-50 start-50 translate-middle bg-white text-secondary rounded-circle shadow-lg d-flex align-items-center justify-content-center" 
                                             style="width: 80px; height: 80px; transition: all 0.3s;"
                                             onmouseover="this.style.transform='translate(-50%, -50%) scale(1.1)'" 
                                             onmouseout="this.style.transform='translate(-50%, -50%) scale(1)'">
                                            <i class="fas fa-play fa-2x ms-1" style="color: #6c757d;"></i>
                                        </div>
                                    </a>
                                @elseif (!empty($item['image']))
                                    @if ($heightStr === 'auto')
                                        <img loading="lazy" src="{{ $mediaUrl }}" class="d-block w-100"
                                            style="height: auto; object-fit: cover;" alt="Banner">
                                    @else
                                        <div class="position-absolute w-100 h-100 top-0 start-0 z-0"
                                            style="background-image: url('{{ $mediaUrl }}'); background-size: cover; background-position: center;">
                                        </div>
                                    @endif

                                    @if (!empty($item['link_url']))
                                        <a href="{{ $item['link_url'] }}" class="position-absolute w-100 h-100 top-0 start-0 z-3" aria-label="Banner Link"></a>
                                    @endif
                                @endif
                            </div>
                        @endforeach
                    </div>
                    
                    {{-- Navigation & Pagination --}}
                    @if (count($slider_items) > 1)
                        <div class="swiper-pagination swiper-pagination-{{ $bannerId }} mb-2"></div>
                        <div class="swiper-button-prev swiper-button-prev-{{ $bannerId }} text-white bg-dark bg-opacity-25 rounded-circle p-4 ms-2 shadow-none" style="width: 44px; height: 44px; --swiper-navigation-size: 18px;"></div>
                        <div class="swiper-button-next swiper-button-next-{{ $bannerId }} text-white bg-dark bg-opacity-25 rounded-circle p-4 me-2 shadow-none" style="width: 44px; height: 44px; --swiper-navigation-size: 18px;"></div>
                    @endif
                </div>
            </div>

            @if ($hasRightItems)
                <div class="col-12 col-lg-4 {{ $heightStr !== 'auto' ? 'h-100' : '' }} mt-lg-0 p-0" style="padding-left: 6px !important; margin-top: 6px;">
                    <style>
                        @media (min-width: 992px) {
                            .right-banner-col { margin-top: 0 !important; }
                        }
                    </style>
                    <div class="right-banner-col d-flex flex-column {{ $heightStr !== 'auto' ? 'h-100' : '' }}" style="gap: 6px;">
                        @foreach ($rightItems as $ritem)
                            @if (!empty($ritem['image']))
                                <div class="flex-grow-1 position-relative overflow-hidden rounded-4" style="flex: 1; min-height: 0;">
                                    @if (!empty($ritem['link']))
                                        <a href="{{ $ritem['link'] }}" class="d-block h-100">
                                    @endif
                                    <img src="{{ $ritem['image'] }}" class="w-100 h-100" 
                                         style="object-fit: cover; transition: transform 0.5s;" 
                                         onmouseover="this.style.transform='scale(1.05)'" 
                                         onmouseout="this.style.transform='scale(1)'">
                                    @if (!empty($ritem['link']))
                                        </a>
                                    @endif
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
        </div>
    @else
        {{-- Banner chưa có dữ liệu --}}
        <div class="d-flex w-100 h-100 align-items-center justify-content-center text-white bg-dark">
            <p class="mb-0 opacity-50"><i class="fas fa-image me-2 text-muted"></i> Banner chưa được điền dữ liệu Slide
            </p>
        </div>
    @endif
</div>

{{-- Fancybox 5 & Swiper 11 integration --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<style>
    .swiper-pagination-bullet { background: #fff; opacity: 0.5; }
    .swiper-pagination-bullet-active { background: #fff; opacity: 1; width: 25px; border-radius: 5px; }
    .swiper-button-prev::after, .swiper-button-next::after { font-size: 18px; font-weight: bold; }
    .swiper-button-prev, .swiper-button-next { 
        width: 44px; height: 44px; 
    }
</style>

<script>
    (function() {
        document.addEventListener('DOMContentLoaded', function() {
            const bannerId = '{{ $bannerId }}';
            
            // Initialize Swiper
            const swiper = new Swiper(`.swiper-container-${bannerId}`, {
                loop: true,
                grabCursor: true, 
                simulateTouch: true,
                autoplay: {
                    delay: 3000,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: `.swiper-pagination-${bannerId}`,
                    clickable: true,
                },
                navigation: {
                    nextEl: `.swiper-button-next-${bannerId}`,
                    prevEl: `.swiper-button-prev-${bannerId}`,
                },
                slidesPerView: 1,
                spaceBetween: 0,
            });

            // Fancybox integration
            Fancybox.bind(`[data-fancybox="banner-video-gallery-${bannerId}"]`, {
                Toolbar: {
                    display: {
                        left: ["infobar"],
                        middle: ["zoomIn", "zoomOut", "toggle1to1", "rotateCCW", "rotateCW", "flipX", "flipY"],
                        right: ["slideshow", "fullscreen", "download", "thumbs", "close"],
                    },
                },
                Html: {
                    video: { autoplay: true, controls: true },
                    youtube: { autoplay: 1, controls: 1, rel: 1, showinfo: 1 }
                },
                Thumbs: { autoStart: true },
            });
        });
    })();
</script>
