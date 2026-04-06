@if (!empty($content['section_title']) || !empty($content['section_subtitle']) || !empty($content['title_parts']))
    <div class="{{ ($no_container ?? false) ? '' : (!empty($content['full_width']) ? 'container-fluid px-0' : 'container') }}">
        <div class="{{ $content['section_title_align'] ?? 'text-center' }}"
             style="{{ !empty($content['section_padding_left']) ? 'padding-left: ' . $content['section_padding_left'] . 'px;' : '' }}
                    {{ !empty($content['section_padding_right']) ? 'padding-right: ' . $content['section_padding_right'] . 'px;' : '' }}">
            @if (!empty($content['section_title']) || !empty($content['title_parts']))
                @php
                    $titleHtml = '';
                    if (!empty($content['title_parts']) && is_array($content['title_parts'])) {
                        foreach ($content['title_parts'] as $part) {
                            $text = htmlspecialchars($part['text'] ?? '', ENT_QUOTES);
                            $color = htmlspecialchars($part['color'] ?? '#000000', ENT_QUOTES);
                            $titleHtml .= '<span style="color:' . $color . '">' . $text . '&nbsp;</span>';
                        }
                    } else {
                        $titleHtml = htmlspecialchars($content['section_title'] ?? '', ENT_QUOTES);
                    }
                @endphp
                <h3 class="fw-bold mb-3 section-title" style="line-height: 1.4;">{!! $titleHtml !!}
                </h3>
            @endif
            @if (!empty($content['section_subtitle']))
                <p class="section-subtitle mb-4"
                    style="max-width: 800px; {{ ($content['section_title_align'] ?? 'text-center') === 'text-center' ? 'margin: 0 auto;' : '' }} font-size: 1.1rem; line-height: 1.6; color: {{ $content['section_subtitle_color'] ?? '#6b7280' }};">
                    {!! $content['section_subtitle'] !!}</p>
            @endif
        </div>
    </div>
@endif
