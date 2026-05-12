<div class="{{ !empty($content['full_width']) ? 'container-fluid px-0' : 'container' }}">
    <div class="row g-4">
        @if (!empty($content['items']))
            @foreach ($content['items'] as $item)
                @php
                    $columns = intval($content['columns'] ?? 2);
                    $columns = $columns > 0 ? $columns : 2;
                    $cols = 12 / $columns;

                    $columns_tablet = intval($content['columns_tablet'] ?? $columns);
                    $columns_tablet = $columns_tablet > 0 ? $columns_tablet : $columns;
                    $cols_tablet = 12 / $columns_tablet;

                    $columns_mobile = intval($content['columns_mobile'] ?? 1);
                    $columns_mobile = $columns_mobile > 0 ? $columns_mobile : 1;
                    $cols_mobile = 12 / $columns_mobile;

                    // Standardized premium layout values
                    $borderRadius = '16px';
                    $borderColor = '#e2e8f0';
                    $textAlign = 'center';
                    
                    $bgStyle = "border: 1px solid {$borderColor}; border-radius: {$borderRadius}; ";
                    
                    if (!empty($item['bg_color']) && $item['bg_color'] !== '#ffffff') {
                        $bgStyle .= 'background-color: ' . $item['bg_color'] . ' !important; ';
                    }
                    if (!empty($item['bg_image'])) {
                        $bgStyle .= "background-image: url('" . $item['bg_image'] . "'); background-size: cover; background-position: center; ";
                    }

                    $alignClass = 'mx-auto';
                    $iconSize = '48px';
                    $iconBoxSize = '80px';
                    $iconColor = '#004a80';

                    $titleFSValue = '1.25rem';
                    $bodyFSValue = '1rem';
                @endphp
                <style>
                    @media (max-width: 767.98px) {
                        .grid-item-title-{{ $loop->parent->index ?? 0 }}-{{ $loop->index }} {
                            font-size: calc({{ $titleFSValue }} / 1.5) !important;
                        }

                        .grid-item-body-{{ $loop->parent->index ?? 0 }}-{{ $loop->index }} {
                            font-size: calc({{ $bodyFSValue }} / 1.5) !important;
                        }
                    }
                </style>
                <div
                    class="col-lg-{{ $cols }} col-md-{{ $cols_tablet }} col-sm-{{ $cols_mobile }} col-{{ $cols_mobile }} reveal"
                    style="transition-delay: {{ $loop->index * 0.1 }}s;">
                    @if (!empty($item['link']))
                        <a href="{{ $item['link'] }}" class="text-decoration-none text-dark d-block h-100">
                    @endif

                    <div class="h-100 hover-shadow-sm transition-all position-relative p-0"
                        style="border-radius: {{ $borderRadius }};">
                        <div class="h-100 p-4 text-{{ $textAlign }} overflow-hidden" style="{{ $bgStyle }}">

                            {{-- Nếu có ảnh nền mà chữ cần dễ đọc thì nên tạo một lớp phủ (overlay), nhưng tạm thời tuỳ ý tuỳ biến theo màu nền card --}}
                            <div class="position-relative z-1">
                                @if (!empty($item['image']))
                                    <div class="mb-4 rounded-4 overflow-hidden position-relative ratio ratio-16x9">
                                        <img loading="lazy" src="{{ $item['image'] }}" class="img-fluid"
                                            style="object-fit: cover;">
                                    </div>
                                @elseif(!empty($item['icon']))
                                    <div class="mb-4 {{ empty($iconColor) ? 'text-primary bg-primary bg-opacity-10' : '' }} rounded-circle d-flex align-items-center justify-content-center {{ $alignClass }} shadow-sm"
                                        style="width: {{ $iconBoxSize }}; height: {{ $iconBoxSize }}; {{ !empty($iconColor) ? 'color: ' . $iconColor . ';' : '' }}">
                                        <i class="{{ $item['icon'] }} {{ empty($iconSize) ? 'fs-2' : '' }}"
                                            style="{{ $iconSize ? 'font-size: ' . $iconSize . ';' : '' }}"></i>
                                    </div>
                                @endif

                                <h4 class="grid-item-title-{{ $loop->parent->index ?? 0 }}-{{ $loop->index }} fw-bold mb-1"
                                    style="color: {{ $content['text_color'] ?? '#000000' }}; font-size: {{ $titleFSValue }};">
                                    {{ $item['title'] ?? 'Tiêu đề' }}</h4>
                                <p class="grid-item-body-{{ $loop->parent->index ?? 0 }}-{{ $loop->index }} mb-0 {{ empty($content['body_font_size']) ? 'fs-6' : '' }}"
                                    style="color: {{ $content['text_color'] ?? '#000000' }}; font-size: {{ $bodyFSValue }};">
                                    {!! nl2br(e($item['body'] ?? 'Nội dung...')) !!}</p>
                            </div>
                        </div>
                    </div>

                    @if (!empty($item['link']))
                        </a>
                    @endif
                </div>
            @endforeach
        @endif
    </div>
</div>
