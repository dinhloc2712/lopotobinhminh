@php
    $is_full_width = filter_var($content['full_width'] ?? false, FILTER_VALIDATE_BOOLEAN);
    $container_class = $is_full_width ? 'container-fluid px-0' : 'container';
@endphp
<div class="{{ $container_class }}">
    @php
        $url = $content['url'] ?? '';
        $embed_url = $url;
        $is_mp4 = false;

        if (!empty($url)) {
            if (strpos($url, 'youtube.com') !== false || strpos($url, 'youtu.be') !== false) {
                // Phân tích và trích xuất ID Youtube (Hỗ trợ Shorts, youtu.be, URL embed)
                if (
                    preg_match(
                        '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?|shorts)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i',
                        $url,
                        $match,
                    )
                ) {
                    $youtube_id = $match[1] ?? null;
                    if ($youtube_id) {
                        $embed_url = 'https://www.youtube.com/embed/' . $youtube_id;
                    }
                }
            } elseif (strpos($url, 'vimeo.com') !== false) {
                preg_match('/vimeo\.com\/([0-9]+)/', $url, $match);
                $vimeo_id = $match[1] ?? null;
                if ($vimeo_id) {
                    $embed_url = 'https://player.vimeo.com/video/' . $vimeo_id;
                }
            } else {
                // Nếu không phải Youtube/Vimeo, giả định đây là link file (MP4 được chọn từ CMedia)
                $is_mp4 = true;
            }
        }
    @endphp

    @if (!empty($url))
        @php
            $ratio_class = $is_full_width
                ? 'ratio ratio-16x9 shadow-lg overflow-hidden bg-dark border-0'
                : 'ratio ratio-16x9 shadow-lg rounded-4 overflow-hidden border bg-dark';
        @endphp
        <div class="{{ $ratio_class }}">
            @if ($is_mp4)
                <video controls class="w-100 h-100" style="object-fit: contain;">
                    <source src="{{ $embed_url }}" type="video/mp4">
                    Trình duyệt của bạn không hỗ trợ thẻ video.
                </video>
            @else
                <iframe src="{{ $embed_url }}" title="Video player" frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                    allowfullscreen loading="lazy"></iframe>
            @endif
        </div>

        @if (!empty($content['caption']))
            <div class="text-center mt-3 text-muted small">{{ $content['caption'] }}</div>
        @endif
    @endif
</div>
