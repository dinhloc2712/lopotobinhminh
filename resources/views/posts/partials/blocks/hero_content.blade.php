<div class="{{ !empty($content['full_width']) ? 'container-fluid px-0' : 'container' }}">
    @php
        $reverse = !empty($content['reverse_layout']) ? $content['reverse_layout'] : false;

        $imgClass = '';
        if (!empty($content['image_style'])) {
            if ($content['image_style'] === 'rhombus') {
                $imgClass = 'clip-path-rhombus';
            } elseif ($content['image_style'] !== 'natural') {
                $imgClass = $content['image_style'];
            }
        } else {
            $imgClass = 'rounded-4';
        }
    @endphp

    <style>
        .clip-path-rhombus {
            clip-path: polygon(50% 0%, 100% 50%, 50% 100%, 0% 50%);
        }

        .reveal-left,
        .reveal-right {
            opacity: 0;
            transition: all 0.8s ease-out;
        }

        .reveal-left {
            transform: translateX(-50px);
        }

        .reveal-right {
            transform: translateX(50px);
        }

        .reveal-left.active,
        .reveal-right.active {
            opacity: 1;
            transform: translateX(0);
        }

        .step-item {
            transition: all 0.5s ease-out;
            opacity: 0;
            transform: translateY(20px);
        }

        .reveal.active .step-item,
        .reveal-left.active .step-item,
        .reveal-right.active .step-item {
            opacity: 1;
            transform: translateY(0);
        }
    </style>

    <div class="row align-items-center {{ $reverse ? 'flex-row-reverse' : '' }}">
        {{-- Phần Hình Ảnh / Video --}}
        <div class="col-lg-6 p-3 reveal {{ $reverse ? 'reveal-right' : 'reveal-left' }}">
            @if (!empty($content['image']))
                @php
                    $mediaUrl = $content['image'];
                    $mediaType = $content['media_type'] ?? 'image';
                    $embedUrl = null;

                    if ($mediaType === 'video' || $mediaType !== 'image') {
                        // YouTube
                        if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $mediaUrl, $m)) {
                            $embedUrl = 'https://www.youtube.com/embed/' . $m[1] . '?rel=0&autoplay=0';
                        }
                        // Vimeo
                        elseif (preg_match('/vimeo\.com\/(\d+)/', $mediaUrl, $m)) {
                            $embedUrl = 'https://player.vimeo.com/video/' . $m[1];
                        }
                        // MP4 / direct video
                        elseif (preg_match('/\.(mp4|webm|ogg)(\?.*)?$/i', $mediaUrl)) {
                            $embedUrl = 'mp4:' . $mediaUrl;
                        }
                    }
                @endphp

                <div class="text-center position-relative w-100">
                    @if ($embedUrl && str_starts_with($embedUrl, 'mp4:'))
                        {{-- Direct video file --}}
                        <video controls class="img-fluid {{ $imgClass }}"
                            style="{{ ($content['image_style'] ?? '') !== 'natural' ? 'max-height:500px;width:100%;object-fit:cover;' : 'max-width:100%;height:auto;' }}">
                            <source src="{{ ltrim($embedUrl, 'mp4:') }}" type="video/mp4">
                        </video>
                    @elseif($embedUrl)
                        {{-- YouTube / Vimeo embed --}}
                        <div class="ratio ratio-16x9 {{ $imgClass }}"
                            style="{{ ($content['image_style'] ?? '') !== 'natural' ? 'max-height:500px;overflow:hidden;' : '' }}">
                            <iframe src="{{ $embedUrl }}"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen frameborder="0"></iframe>
                        </div>
                    @else
                        {{-- Ảnh thông thường --}}
                        <img src="{{ $mediaUrl }}" class="img-fluid {{ $imgClass }}"
                            alt="{{ $content['title'] ?? 'Hero Image' }}"
                            style="{{ ($content['image_style'] ?? '') !== 'natural' ? 'max-height:500px;object-fit:cover;width:100%;' : 'max-width:100%;height:auto;' }}">
                    @endif
                </div>
            @endif
        </div>

        {{-- Phần Nội Dung --}}
        <div class="col-lg-6 p-3 reveal {{ $reverse ? 'reveal-left' : 'reveal-right' }}">
            <div>
                @include('posts.partials.blocks.shared_title', [
                    'content' => array_merge(
                        [
                            'section_title' => $content['title'] ?? '',
                            'section_subtitle' => $content['body'] ?? '',
                            'section_title_align' => 'text-start',
                        ],
                        $content),
                    'no_container' => true,
                ])

                {{-- Step Items --}}
                @if (!empty($content['list_items']))
                    <div class="step-items-wrapper mt-4">
                        @foreach ($content['list_items'] as $item)
                            <div class="step-item d-flex align-items-center mb-4 p-3 position-relative rounded-pill"
                                style="border: 1px solid {{ $content['text_color'] ?? '#004a80' }}; margin-left: 35px; background: #fff; min-height: 100px; transition-delay: {{ $loop->index * 0.15 }}s;">

                                {{-- Number block - Protruding square --}}
                                <div class="step-num d-flex align-items-center justify-content-center fw-bold text-white shadow-sm"
                                    style="background-color: {{ $content['text_color'] ?? '#003358' }}; border-radius: 20px; position: absolute; left: -45px; width: 85px; height: 85px; font-size: 2.2rem; z-index: 2;">
                                    {{ $item['num'] }}
                                </div>

                                {{-- Text content --}}
                                <div class="flex-grow-1 ms-5">
                                    <h5 class="fw-bold mb-1"
                                        style="color: {{ $content['text_color'] ?? '#004a80' }}; font-size: 1.25rem;">
                                        {{ $item['title'] }}
                                    </h5>
                                    <p class="mb-0 text-muted lh-base" style="font-size: 0.95rem;">
                                        {{ $item['desc'] }}
                                    </p>
                                </div>

                                {{-- Icon block - Cyan circle --}}
                                @if (!empty($item['icon']))
                                    <div class="step-icon d-flex align-items-center justify-content-center rounded-circle ms-3 flex-shrink-0"
                                        style="width: 75px; height: 75px; border: 2px solid #0dcaf0; background: #fff;">
                                        <i class="{{ $item['icon'] }} text-info fs-2"></i>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif

                @if (!empty($content['btn_label']))
                    @php
                        $btnStyle = '';
                        if (!empty($content['btn_bg_color'])) {
                            $btnStyle .=
                                'background-color: ' .
                                $content['btn_bg_color'] .
                                '; border-color: ' .
                                $content['btn_bg_color'] .
                                '; ';
                        }
                        if (!empty($content['btn_text_color'])) {
                            $btnStyle .= 'color: ' . $content['btn_text_color'] . '; ';
                        }
                    @endphp
                    <a href="{{ $content['btn_link'] ?? '#' }}" class="btn px-4 py-2 rounded-pill fw-bold shadow-sm"
                        style="{{ $btnStyle }}">
                        @if (!empty($content['btn_icon']))
                            <i class="{{ $content['btn_icon'] }} me-2"></i>
                        @endif
                        {{ $content['btn_label'] }}
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
