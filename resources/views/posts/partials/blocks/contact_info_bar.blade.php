@php
    $c = $content;
    $items = $c['items'] ?? [];
    $btn_bg = $c['btn_bg_color'] ?? '#00ffff';
    $btn_color = $c['btn_text_color'] ?? '#000000';
    $btn_radius = isset($c['btn_border_radius']) ? $c['btn_border_radius'] : 0;
@endphp

<div class="contact-info-bar-block industrial-theme border-top border-bottom">
    <div class="container-fluid px-3 px-xl-5">
        <div class="row align-items-center g-0">
            
            {{-- Dynamic Info Items --}}
            <div class="col-lg-10">
                <div class="row g-0 align-items-stretch">
                    @foreach($items as $index => $item)
                        @php
                            $w = intval($item['width'] ?? 0);
                            $style = $w > 0 ? "width: {$w}px; flex: 0 0 auto; max-width: 100%;" : "flex: 1 1 0%; min-width: 200px;";
                        @endphp
                        <div class="contact-item-industrial px-2 px-xl-4 position-relative" style="{{ $style }}">
                            {{-- Technical Vertical Divider --}}
                            @if(!$loop->last)
                                <div class="d-none d-lg-block tech-divider"></div>
                            @endif

                            <div class="d-flex flex-column align-items-center text-center py-2 h-100 item-content-hover">
                                {{-- Badge Style Icon atop - Smaller size --}}
                                @if(!empty($item['icon_image']))
                                    <div class="icon-badge-container mb-2 mt-1">
                                        <div class="icon-circle">
                                            <img src="{{ $item['icon_image'] }}" alt="icon" class="industrial-icon">
                                        </div>
                                    </div>
                                @endif

                                {{-- Sophisticated Text Content --}}
                                <div class="info-content-modern w-100">
                                    @if(!empty($item['content']))
                                        <div class="rich-content-industrial">
                                            {!! $item['content'] !!}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Technical Horizontal Divider (Mobile) --}}
                            @if(!$loop->last)
                                <div class="d-lg-none border-bottom tech-divider-h"></div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Action Button --}}
            <div class="col-lg-2 text-center text-lg-end px-3 py-3 py-lg-0">
                <div class="ps-lg-4 border-lg-start h-100 d-flex align-items-center justify-content-center justify-content-lg-end">
                    <a href="{{ $c['btn_link'] ?? '#' }}" 
                       class="btn btn-industrial-cta px-4 py-2 fw-bold transition-all w-100 w-lg-auto"
                       style="background-color: {{ $btn_bg }} !important; 
                              color: {{ $btn_color }} !important; 
                              border-radius: {{ $btn_radius }}px !important;">
                        {{ $c['btn_text'] ?? 'Cộng tác viên' }}
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
/* Industrial Theme Base */
.contact-info-bar-block.industrial-theme {
    background: #fdfdfd; 
    border-color: #eef2f7 !important;
}

/* Badge Icon Styles - Smaller */
.icon-badge-container {
    padding:4px;
    background: #ffffff;
    border: 1px solid #edf2f7;
    border-radius: 50%;
    box-shadow: 0 2px 5px rgba(0,0,0,0.02);
}

.icon-circle {
    width: 45px;
    height: 45px;
    background: #f8fafc;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #e2e8f0;
}

.industrial-icon {
    max-width: 25px;
    max-height: 25px;
    object-fit: contain;
    transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

/* Interaction Effects */
.item-content-hover:hover .industrial-icon {
    transform: scale(1.15) rotate(5deg);
}

/* Typography Professionalism */
.rich-content-industrial {
    font-family: 'Inter', 'Montserrat', sans-serif;
    color: #475569;
    font-size: 0.75rem;
    line-height: 1.5;
}

.rich-content-industrial p {
    margin-bottom: 0px;
}

.rich-content-industrial b, 
.rich-content-industrial strong {
    color: #0f172a;
    font-weight: 800;
    display: block;
    margin-bottom: 2px;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.02em;
}

/* Technical Dividers */
.tech-divider {
    position: absolute;
    right: 0;
    top: 50%;
    transform: translateY(-50%);
    height: 25px;
    width: 1px;
    background: #cbd5e1;
}

.tech-divider::before, 
.tech-divider::after {
    content: '';
    position: absolute;
    left: -1px;
    width: 3px;
    height: 3px;
    background: #cbd5e1;
    border-radius: 50%;
}
.tech-divider::before { top: -3px; }
.tech-divider::after { bottom: -3px; }

.tech-divider-h {
    border-color: #f1f5f9 !important;
}

/* Industrial Button Style - Smaller */
.btn-industrial-cta {
    border: none;
    font-size: 0.8rem;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    position: relative;
    overflow: hidden;
    padding-top: 10px !important;
    padding-bottom: 10px !important;
}

.btn-industrial-cta::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(120deg, transparent, rgba(255, 255, 255, 0.5), transparent);
    transition: all 0.6s;
}

.btn-industrial-cta:hover::before {
    left: 100%;
}

.btn-industrial-cta:hover {
    filter: brightness(1.05);
}

/* Responsive Alignment */
@media (min-width: 992px) {
    .border-lg-start {
        border-left: 1px solid #edf2f7 !important;
    }
}

@media (max-width: 991px) {
    .contact-item-industrial {
        text-align: center;
        width: 100% !important;
        flex: 1 1 100% !important;
    }
}
</style>
