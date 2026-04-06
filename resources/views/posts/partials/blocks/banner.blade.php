@php
    $height = !empty($content['height']) ? $content['height'] : 'auto';
    $heightStr = strtolower(trim($height));
    $showOverlay = isset($content['show_overlay'])
        ? filter_var($content['show_overlay'], FILTER_VALIDATE_BOOLEAN)
        : true;
    $overlayCss = 'linear-gradient(#03416699, #03416699)';

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

    $bannerId = 'banner-' . uniqid();
@endphp

<style>
    .{{ $bannerId }} {
        @if ($heightStr !== 'auto')
            height: {{ $height }};
        @endif
    }
</style>

<div class="banner-block w-100 position-relative {{ $bannerId }}" style="overflow: hidden;">
    @if (count($slider_items) > 0)
        {{-- Banner Slider --}}
        @php $sliderId = 'banner-carousel-' . uniqid(); @endphp
        @php
            $layout = $content['layout'] ?? 'slider';
            $rightItems = !empty($content['right_items']) ? $content['right_items'] : [];
            $hasRightItems = $layout === 'split' && count(array_filter(array_column($rightItems, 'image'))) > 0;
            $sliderColClass = $hasRightItems ? 'col-lg-8' : 'col-12';
        @endphp

        <div class="row g-2 {{ $heightStr !== 'auto' ? 'h-100' : '' }}">
            <div class="{{ $sliderColClass }} {{ $heightStr !== 'auto' ? 'h-100' : '' }}">
                <div id="{{ $sliderId }}" class="carousel slide w-100 {{ $heightStr !== 'auto' ? 'h-100' : '' }}"
                    data-bs-ride="carousel">
                    @if (count($slider_items) > 1)
                        <div class="carousel-indicators mb-3 z-3">
                            @foreach ($slider_items as $index => $item)
                                <button type="button" data-bs-target="#{{ $sliderId }}"
                                    data-bs-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}"
                                    aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                                    aria-label="Slide {{ $index + 1 }}"></button>
                            @endforeach
                        </div>
                    @endif

                    <div
                        class="carousel-inner w-100 {{ $heightStr !== 'auto' ? 'h-100' : '' }} rounded-4 overflow-hidden">
                        @foreach ($slider_items as $index => $item)
                            <div class="carousel-item w-100 {{ $heightStr !== 'auto' ? 'h-100' : '' }} {{ $index === 0 ? 'active' : '' }}"
                                style="background-color: #034166;">

                                @php
                                    $mediaUrl = trim($item['image'] ?? '');
                                    $isVideo = isVideoUrl($mediaUrl);
                                @endphp

                                @if ($isVideo)
                                    @php $videoData = parseVideoEmbed($mediaUrl); @endphp

                                    @if ($heightStr === 'auto')
                                        <div class="w-100 position-relative text-center"
                                            style="pointer-events: none; overflow: hidden; aspect-ratio: 16/9;">
                                            @if ($videoData['type'] === 'video')
                                                <video autoplay loop muted playsinline class="w-100 h-100"
                                                    style="object-fit: cover;">
                                                    <source src="{{ $videoData['url'] }}" type="video/mp4">
                                                </video>
                                            @else
                                                <iframe src="{{ $videoData['url'] }}" class="w-100 h-100"
                                                    style="border: 0; transform: scale(1.5);"
                                                    allow="autoplay; fullscreen; picture-in-picture"
                                                    allowfullscreen></iframe>
                                            @endif
                                        </div>
                                    @else
                                        <div class="position-absolute w-100 h-100 top-0 start-0 z-0 text-center"
                                            style="pointer-events: none; overflow: hidden;">
                                            @if ($videoData['type'] === 'video')
                                                <video autoplay loop muted playsinline class="w-100 h-100"
                                                    style="object-fit: cover;">
                                                    <source src="{{ $videoData['url'] }}" type="video/mp4">
                                                </video>
                                            @else
                                                <iframe src="{{ $videoData['url'] }}" class="w-100 h-100"
                                                    style="border: 0; transform: scale(1.5);"
                                                    allow="autoplay; fullscreen; picture-in-picture"
                                                    allowfullscreen></iframe>
                                            @endif
                                        </div>
                                    @endif

                                    <!-- Lớp phủ cho video -->
                                    @if ($showOverlay)
                                        <div class="position-absolute w-100 h-100 top-0 start-0 z-1"
                                            style="background-image: {{ $overlayCss }};"></div>
                                    @endif
                                @else
                                    @if ($heightStr === 'auto')
                                        <!-- Hình ảnh tĩnh (chiều cao tự động) -->
                                        <img loading="lazy" src="{{ $mediaUrl }}" class="d-block w-100"
                                            style="height: auto; object-fit: cover;" alt="Banner">

                                        @if ($showOverlay)
                                            <!-- Lớp phủ cho ảnh auto -->
                                            <div class="position-absolute w-100 h-100 top-0 start-0 z-1"
                                                style="background-image: {{ $overlayCss }};"></div>
                                        @endif
                                    @else
                                        <!-- Hình ảnh tĩnh làm background (chiều cao cố định) -->
                                        <div class="position-absolute w-100 h-100 top-0 start-0 z-0"
                                            style="background-image: {{ $showOverlay ? $overlayCss . ', ' : '' }}url('{{ $mediaUrl }}'); background-size: cover; background-position: center;">
                                        </div>
                                    @endif
                                @endif

                                @if (!empty($item['text']) || !empty($item['link_text']))
                                    <div class="carousel-caption d-flex h-100 flex-column justify-content-center align-items-center top-0 start-50 translate-middle-x z-2 w-100 px-3"
                                        style="pointer-events: none;">
                                        <div class="text-white text-center w-100 px-md-4"
                                            style="pointer-events: auto; text-shadow: 0 4px 12px rgba(0,0,0,0.6), 0 1px 3px rgba(0,0,0,0.8);">
                                            @if (!empty($item['text']))
                                                <div class="text-white fw-bold mb-3"
                                                    style="font-size: clamp(18px, 4vw, 35px); line-height: 1.2;">
                                                    {!! $item['text'] !!}
                                                </div>
                                            @endif

                                            @if (!empty($item['link_text']) && !empty($item['link_url']))
                                                <a href="{{ $item['link_url'] }}"
                                                    class="text-white text-decoration-none d-block"
                                                    style="font-size: clamp(12px, 2vw, 18px); opacity: 0.95;">
                                                    {!! $item['link_text'] !!}
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    @if (count($slider_items) > 1)
                        <button class="carousel-control-prev z-3" type="button" data-bs-target="#{{ $sliderId }}"
                            data-bs-slide="prev">
                            <span class="carousel-control-prev-icon p-3 rounded-circle bg-dark bg-opacity-25"
                                aria-hidden="true"></span>
                            <span class="visually-hidden">Trước</span>
                        </button>
                        <button class="carousel-control-next z-3" type="button" data-bs-target="#{{ $sliderId }}"
                            data-bs-slide="next">
                            <span class="carousel-control-next-icon p-3 rounded-circle bg-dark bg-opacity-25"
                                aria-hidden="true"></span>
                            <span class="visually-hidden">Sau</span>
                        </button>
                    @endif
                </div>
            </div>

            @if ($hasRightItems)
                <div class="col-12 col-lg-4 {{ $heightStr !== 'auto' ? 'h-lg-100' : '' }} mt-2 mt-lg-0">
                    <div class="d-flex flex-column gap-2 h-100">
                        @foreach ($rightItems as $ritem)
                            @if (!empty($ritem['image']))
                                <div class="flex-grow-1 position-relative overflow-hidden rounded-4" style="min-height: 200px;">
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
    @else
        {{-- Banner chưa có dữ liệu --}}
        <div class="d-flex w-100 h-100 align-items-center justify-content-center text-white bg-dark">
            <p class="mb-0 opacity-50"><i class="fas fa-image me-2 text-muted"></i> Banner chưa được điền dữ liệu Slide
            </p>
        </div>
    @endif
</div>
