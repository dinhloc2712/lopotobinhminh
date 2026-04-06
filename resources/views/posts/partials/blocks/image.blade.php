@php
    $displayType = $content['display_type'] ?? 'single';
    $images = $content['images'] ?? [];

    // Tương thích ngược: Nếu không có images nhưng có url, coi như có 1 ảnh
    if (empty($images) && !empty($content['url'])) {
        $images[] = [
            'url' => $content['url'],
            'alt' => $content['alt'] ?? '',
            'caption' => $content['caption'] ?? '',
        ];
    }

    $imageGap = isset($content['image_gap']) && $content['image_gap'] !== '' ? (int) $content['image_gap'] : 24; // Mặc định 24px (~ gap-4)
    $imageRadius = isset($content['image_radius']) && $content['image_radius'] !== '' ? (int) $content['image_radius'] : 16; // Mặc định 16px (~ rounded-4)
    
    $blockId = 'image-block-' . uniqid();
    $imageHeightInput = $content['image_height'] ?? ($content['image_ratio'] ?? '1/1');
    $imageRatioStyle = "";
    if (!empty($imageHeightInput) && $imageHeightInput !== 'auto') {
        if (strpos($imageHeightInput, '/') !== false || strpos($imageHeightInput, ':') !== false) {
            $ratio = str_replace(':', '/', $imageHeightInput);
            $imageRatioStyle = "aspect-ratio: {$ratio};";
        } elseif (is_numeric($imageHeightInput)) {
            $imageRatioStyle = "height: {$imageHeightInput}px;";
        } else {
            $imageRatioStyle = "height: {$imageHeightInput};";
        }
    }
@endphp

@if(!empty($imageRatioStyle))
    <style>
        @media (min-width: 992px) {
            .{{ $blockId }} .custom-pc-height {
                {!! $imageRatioStyle !!}
            }
        }
    </style>
@endif

<div class="{{ !empty($content['full_width']) ? 'container-fluid px-0' : 'container' }} {{ $blockId }}">
    @if ($displayType === 'single' || count($images) <= 1)
        {{-- Mặc định: Xếp dọc (hoặc 1 ảnh) -> Đã hỗ trợ cột bằng lưới row --}}
        <div class="row justify-content-center" style="--bs-gutter-y: {{ $imageGap }}px; --bs-gutter-x: {{ $imageGap }}px;">
            @foreach ($images as $index => $img)
                @php
                    $singleImgUrl = $img['url'] ?? '';
                    if (empty($singleImgUrl) && $index === 0) {
                        $singleImgUrl = $content['url'] ?? '';
                    }
                    if (empty($singleImgUrl)) {
                        continue;
                    }

                    $singleImgAlt = $img['alt'] ?? '';
                    if (empty($singleImgAlt) && $index === 0) {
                        $singleImgAlt = $content['alt'] ?? 'Image';
                    }

                    $singleImgCaption = $img['caption'] ?? '';
                    if (empty($singleImgCaption) && $index === 0) {
                        $singleImgCaption = $content['caption'] ?? '';
                    }
                @endphp
                @php
                    $colClasses = [];
                    if (!empty($img['col_lg'])) $colClasses[] = 'col-lg-' . $img['col_lg'];
                    if (!empty($img['col_md'])) $colClasses[] = 'col-md-' . $img['col_md'];
                    if (!empty($img['col_sm'])) $colClasses[] = 'col-sm-' . $img['col_sm'];
                    $colClassString = !empty($colClasses) ? implode(' ', $colClasses) : 'col-12';
                @endphp
                <div class="{{ $colClassString }} d-flex flex-column align-items-center">
                    <figure class="text-center overflow-hidden mb-0 shadow-sm border w-100"
                        style="max-width: {{ ($content['width'] ?? '100') . '%' }}; margin: 0 auto; border-radius: {{ $imageRadius }}px;">
                        <img src="{{ $singleImgUrl }}" alt="{{ $singleImgAlt }}" class="img-fluid"
                            style="object-fit: contain; width: 100%;">
                        @if (!empty($singleImgCaption))
                            <figcaption class="mt-2 text-muted small px-3 pb-2">{{ $singleImgCaption }}</figcaption>
                        @endif
                    </figure>
                </div>
            @endforeach
        </div>
    @elseif ($displayType === 'grid' || $displayType === 'masonry')
        @php
            $cols = explode('-', $content['grid_columns'] ?? '2-4');
            $colMobile = $cols[0] ?? 2;
            $colDesktop = $cols[1] ?? 4;
            $gridClass = "row row-cols-{$colMobile} row-cols-md-{$colDesktop}";
            $isMasonry = $displayType === 'masonry';
            $masonryId = 'masonry-' . uniqid();
        @endphp

        @if ($isMasonry)
            <style>
                .image-masonry-grid-{{ $masonryId }} {
                    column-count: {{ $colDesktop }};
                    column-gap: {{ $imageGap }}px;
                }

                @media (max-width: 768px) {
                    .image-masonry-grid-{{ $masonryId }} {
                        column-count: {{ $colMobile }};
                    }
                }

                .image-masonry-grid-{{ $masonryId }} .masonry-item {
                    break-inside: avoid;
                    margin-bottom: {{ $imageGap }}px;
                }
            </style>
            <div class="image-masonry-grid-{{ $masonryId }}">
                @foreach ($images as $img)
                    @if (!empty($img['url']))
                        @php
                            $colClasses = [];
                            if (!empty($img['col_lg'])) $colClasses[] = 'col-lg-' . $img['col_lg'];
                            if (!empty($img['col_md'])) $colClasses[] = 'col-md-' . $img['col_md'];
                            if (!empty($img['col_sm'])) $colClasses[] = 'col-sm-' . $img['col_sm'];
                            $colClassString = implode(' ', $colClasses);
                        @endphp
                        <div class="masonry-item overflow-hidden shadow-sm border bg-white {{ $colClassString }}" style="border-radius: {{ $imageRadius }}px;">
                            <img src="{{ $img['url'] }}" alt="{{ $img['alt'] ?? '' }}"
                                class="img-fluid w-100 object-fit-cover custom-pc-height" loading="lazy">
                        </div>
                    @endif
                @endforeach
            </div>
        @else
            <div class="{{ $gridClass }}"
                style="--bs-gutter-x: {{ $imageGap }}px; --bs-gutter-y: {{ $imageGap }}px;">
                @foreach ($images as $img)
                    @if (!empty($img['url']))
                        @php
                            $colClasses = ['col'];
                            if (!empty($img['col_lg'])) $colClasses[] = 'col-lg-' . $img['col_lg'];
                            if (!empty($img['col_md'])) $colClasses[] = 'col-md-' . $img['col_md'];
                            if (!empty($img['col_sm'])) $colClasses[] = 'col-sm-' . $img['col_sm'];
                            $colClassString = implode(' ', $colClasses);
                        @endphp
                        <div class="{{ $colClassString }}">
                            <div class="overflow-hidden shadow-sm border h-100 d-flex bg-white" style="border-radius: {{ $imageRadius }}px;">
                                <img src="{{ $img['url'] }}" alt="{{ $img['alt'] ?? '' }}"
                                    class="img-fluid w-100 object-fit-cover custom-pc-height" loading="lazy">
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif
    @elseif ($displayType === 'mosaic')
        @php
            $mosaicId = 'mosaic-' . uniqid();
        @endphp
        
        @once
        <style>
            [class^="image-mosaic-grid-"] {
                display: grid;
                grid-template-columns: repeat(12, 1fr) !important;
                grid-auto-rows: 250px;
            }
            @media (max-width: 992px) {
                [class^="image-mosaic-grid-"] {
                    grid-auto-rows: 200px;
                }
            }
            @media (max-width: 768px) {
                [class^="image-mosaic-grid-"] {
                    grid-auto-rows: 180px;
                }
                [class^="image-mosaic-grid-"] .mosaic-item {
                    grid-column: span 12 !important;
                }
            }
            .mosaic-item {
                position: relative;
                overflow: hidden;
            }
            .mosaic-item img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                transition: transform 0.4s ease;
            }
            .mosaic-item:hover img {
                transform: scale(1.05);
            }
            .mosaic-item:nth-child(5n + 1) { grid-column: span 3; }
            .mosaic-item:nth-child(5n + 2) { grid-column: span 6; }
            .mosaic-item:nth-child(5n + 3) { grid-column: span 3; }
            .mosaic-item:nth-child(5n + 4) { grid-column: span 6; }
            .mosaic-item:nth-child(5n + 5) { grid-column: span 6; }
            
            .mosaic-caption {
                position: absolute;
                bottom: 0;
                left: 0;
                right: 0;
                padding: 30px 20px 15px;
                background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, transparent 100%);
                color: white;
                font-weight: bold;
                font-size: 1.1rem;
                z-index: 2;
                pointer-events: none;
                text-transform: uppercase;
                text-shadow: 0 2px 4px rgba(0,0,0,0.5);
            }
        </style>
        @endonce
        
        <div class="image-mosaic-grid-{{ $mosaicId }}" style="gap: {{ $imageGap }}px;">
            @php $dynamicMosaicCSS = ""; @endphp
            @foreach ($images as $loopIndex => $img)
                @if (!empty($img['url']))
                    @php
                        $customClass = "";
                        // Advanced dynamic custom spans for 1-12 columns scale utilizing breakpoints!
                        if (!empty($img['col_lg']) || !empty($img['col_md']) || !empty($img['col_sm'])) {
                            $customClass = "mosaic-custom-{$mosaicId}-{$loopIndex}";
                            $smSpan = !empty($img['col_sm']) ? $img['col_sm'] : 12;
                            $mdSpan = !empty($img['col_md']) ? $img['col_md'] : $smSpan;
                            $lgSpan = !empty($img['col_lg']) ? $img['col_lg'] : $mdSpan;
                            
                            $dynamicMosaicCSS .= "
                                @media (max-width: 767.98px) { .{$customClass} { grid-column: span {$smSpan} !important; } }
                                @media (min-width: 768px) and (max-width: 991.98px) { .{$customClass} { grid-column: span {$mdSpan} !important; } }
                                @media (min-width: 992px) { .{$customClass} { grid-column: span {$lgSpan} !important; } }
                            ";
                        }
                    @endphp
                    <div class="mosaic-item shadow-sm border bg-light {{ $customClass }}" style="border-radius: {{ $imageRadius }}px;">
                        <img src="{{ $img['url'] }}" alt="{{ $img['alt'] ?? '' }}" loading="lazy">
                        @if (!empty($img['caption']))
                            <div class="mosaic-caption">
                                {{ $img['caption'] }}
                            </div>
                        @elseif (!empty($img['alt']))
                            <div class="mosaic-caption" style="opacity: 0.8; font-size: 0.9rem;">
                                {{ $img['alt'] }}
                            </div>
                        @endif
                    </div>
                @endif
            @endforeach
        </div>
        @if (!empty($dynamicMosaicCSS))
            <style>{!! $dynamicMosaicCSS !!}</style>
        @endif
    @endif
</div>
