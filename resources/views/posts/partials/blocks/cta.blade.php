<div class="{{ !empty($content['full_width']) ? 'container-fluid px-0' : 'container' }} text-center">
    <div class="p-5 bg-primary bg-gradient text-white rounded-5 shadow-lg">
        @if(!empty($content['label']))
            <h2 class="mb-4 fw-bold">{{ $content['title'] ?? 'Bắt đầu ngay hôm nay' }}</h2>
            <a href="{{ $content['link'] ?? '#' }}" class="btn btn-light btn-lg rounded-pill px-5 fw-bold shadow-sm">
                {{ $content['label'] }}
            </a>
        @endif
    </div>
</div>
