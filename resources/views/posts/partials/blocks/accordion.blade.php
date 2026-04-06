<div class="{{ !empty($content['full_width']) ? 'container-fluid px-0' : 'container' }}">
    <h2 class="text-center mb-4 fw-bold">{{ $content['title'] ?? 'Câu hỏi thường gặp' }}</h2>
    <div class="accordion accordion-flush shadow-sm rounded-4 overflow-hidden border" id="accordionBlocks-{{ uniqid() }}">
        @if(!empty($content['items']))
            @foreach($content['items'] as $index => $item)
                <div class="accordion-item {{ $loop->last ? 'border-0' : '' }}">
                    <h2 class="accordion-header" id="heading-{{ $index }}">
                        <button class="accordion-button collapsed fw-bold text-dark py-4 px-4" type="button" 
                                data-bs-toggle="collapse" data-bs-target="#collapse-{{ $index }}" 
                                aria-expanded="false" aria-controls="collapse-{{ $index }}">
                            {{ $item['title'] ?? 'Câu hỏi ' . ($index + 1) }}
                        </button>
                    </h2>
                    <div id="collapse-{{ $index }}" class="accordion-collapse collapse" 
                         aria-labelledby="heading-{{ $index }}" 
                         data-bs-parent="#accordionBlocks-{{ uniqid() }}">
                        <div class="accordion-body py-4 px-4 text-muted">
                            {!! nl2br(e($item['content'] ?? '')) !!}
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>
