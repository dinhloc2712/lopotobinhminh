@php
    $bg_color = $content['bg_color'] ?? '#1b1b1b';
    $title_color = $content['title_color'] ?? '#ffffff';
    $title_font_size = $content['title_font_size'] ?? 14;
    $content_color = $content['content_color'] ?? '#94a3b8';
    $content_font_size = $content['content_font_size'] ?? 13;
    $link_color = $content['link_color'] ?? '#cbd5e1';
    $link_font_size = $content['link_font_size'] ?? 13;
    
    $padding_top = $content['padding_top'] ?? '60px';
    $padding_bottom = $content['padding_bottom'] ?? '60px';
    $padding_left = $content['padding_left'] ?? '0px';
    $padding_right = $content['padding_right'] ?? '0px';

    // Auto-append px if numeric
    $padding_top = is_numeric($padding_top) ? $padding_top . 'px' : $padding_top;
    $padding_bottom = is_numeric($padding_bottom) ? $padding_bottom . 'px' : $padding_bottom;
    $padding_left = is_numeric($padding_left) ? $padding_left . 'px' : $padding_left;
    $padding_right = is_numeric($padding_right) ? $padding_right . 'px' : $padding_right;

    $footerColumns = $content['footer_columns'] ?? [];
@endphp

<footer class="footer-industrial" style="background-color: {{ $bg_color }}; color: {{ $content_color }}; padding: {{ $padding_top }} {{ $padding_right }} {{ $padding_bottom }} {{ $padding_left }}; font-family: 'Inter', sans-serif;">
    <div class="{{ !empty($content['full_width']) ? 'container-fluid px-4' : 'container' }}">
        <div class="row gx-0 gy-4">
            {{-- Column Loop --}}
            @if(!empty($footerColumns) && is_array($footerColumns))
                @foreach($footerColumns as $index => $col)
                    @php
                        $colWidth = $col['width'] ?? '';
                        $colSpacing = $col['spacing'] ?? '';
                        
                        $customStyle = '';
                        $columnClass = 'col'; 
                        
                        if (!empty($colWidth) && is_numeric($colWidth)) {
                            $customStyle .= "flex: 0 0 auto; width: {$colWidth}px;";
                            $columnClass = ''; 
                        }

                        if (is_numeric($colSpacing)) {
                            $customStyle .= " padding-right: {$colSpacing}px;";
                        } else {
                            if ($index < count($footerColumns) - 1) {
                                $customStyle .= " padding-right: 30px;";
                            }
                        }
                    @endphp
                    <div class="{{ $columnClass }} col-md-6" style="{{ $customStyle }}">
                        {{-- Column Title --}}
                        @if(!empty($col['title']))
                            <h6 class="fw-bold text-uppercase mb-3" style="letter-spacing: 1px; font-size: {{ $title_font_size }}px; color: {{ $title_color }};">
                                {{ $col['title'] }}
                            </h6>
                        @endif

                        {{-- Body Text --}}
                        @if(!empty($col['body']))
                            <div class="footer-body mb-3" style="font-size: {{ $content_font_size }}px; line-height: 1.6; color: {{ $content_color }};">
                                {!! $col['body'] !!}
                            </div>
                        @endif

                        {{-- Links List --}}
                        @if(!empty($col['links']) && is_array($col['links']))
                            <ul class="list-unstyled mb-0">
                                @foreach($col['links'] as $link)
                                    <li class="mb-2">
                                        <a href="{{ $link['url'] ?? '#' }}" class="text-decoration-none transition-all footer-link" 
                                           style="color: {{ $link_color }}; font-size: {{ $link_font_size }}px; opacity: 0.85;">
                                            {{ $link['label'] ?? 'Link' }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @endforeach
            @endif

            {{-- Social Media Column --}}
            <div class="col-md-3 ms-auto text-md-end footer-meta-col">
                {{-- Social Icons --}}
                @if(!empty($content['socials']) && is_array($content['socials']))
                    <h6 class="fw-bold text-uppercase mb-3" style="letter-spacing: 1px; font-size: {{ $title_font_size }}px; color: {{ $title_color }};">
                        Kết nối với chúng tôi
                    </h6>
                    <div class="d-flex flex-wrap gap-2 justify-content-md-end">
                        @foreach($content['socials'] as $social)
                            @php
                                $hasImg = !empty($social['img']);
                                $bgStyle = $hasImg ? 'transparent' : ($social['bg_color'] ?? 'rgba(255,255,255,0.2)');
                                $iconColor = $social['icon_color'] ?? '#ffffff';
                            @endphp
                            <a href="{{ $social['url'] ?? '#' }}" target="_blank" rel="noopener"
                                class="footer-social-btn d-flex align-items-center justify-content-center rounded-circle text-decoration-none {{ $hasImg ? '' : 'shadow-sm' }}"
                                style="width: 40px; height: 40px; background-color: {{ $bgStyle }}; color: {{ $iconColor }}; transition: all 0.3s; overflow: hidden; position: relative; border: {{ $hasImg ? '1px solid rgba(255,255,255,0.1)' : 'none' }};">
                                @if($hasImg)
                                    <img src="{{ $social['img'] }}" 
                                         style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                                @else
                                    <i class="{{ $social['icon'] ?? 'fab fa-facebook' }}" style="font-size: 1.1rem; position: relative; z-index: 1;"></i>
                                @endif
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- Copyright Row --}}
        <hr class="mt-5 mb-4 border-white border-opacity-10">
        <div class="text-center mb-0" style="font-size: {{ $content_font_size }}px; color: {{ $content_color }}; opacity: 0.8; line-height: 1;">
            {{ $content['copyright'] ?? '© 2026 lopotobinhminh. All rights reserved.' }}
        </div>
    </div>
</footer>

<style>
    .footer-link:hover {
        opacity: 1 !important;
        padding-left: 5px;
        color: {{ $title_color }} !important;
    }
    .footer-social-btn:hover {
        transform: translateY(-3px);
        filter: brightness(1.1);
    }
    .transition-all {
        transition: all 0.3s ease;
    }
</style>
