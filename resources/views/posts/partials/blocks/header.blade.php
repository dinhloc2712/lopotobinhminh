@php
    $navId = 'header-nav-' . ($block->id ?? uniqid());
    $hasButtons = !empty($content['buttons']);

    $headerTextColor = !empty($content['text_color']) ? $content['text_color'] : '#1e293b';
    $headerBgColor = !empty($content['bg_color']) ? $content['bg_color'] : '#ffffff';

    // Derive scrolled bg (keep hue but make translucent for glass effect)
    $headerBgScrolled = $headerBgColor;

    // E-commerce features
    $showSearch   = !empty($content['show_search']);
    $showAccount  = !empty($content['show_account']);
    $showCart     = !empty($content['show_cart']);
    $showWishlist = !empty($content['show_wishlist']);

    $headerHeight = !empty($content['header_height']) ? (int)$content['header_height'] : 64;

    // Individual Padding for Branding block
    $bPt = is_numeric($content['branding_padding_top'] ?? $content['branding_pt'] ?? null) ? ($content['branding_padding_top'] ?? $content['branding_pt']) . 'px' : ($content['branding_padding_top'] ?? $content['branding_pt'] ?? '16px');
    $bPb = is_numeric($content['branding_padding_bottom'] ?? $content['branding_pb'] ?? null) ? ($content['branding_padding_bottom'] ?? $content['branding_pb']) . 'px' : ($content['branding_padding_bottom'] ?? $content['branding_pb'] ?? '16px');
    $bPl = is_numeric($content['branding_padding_left'] ?? $content['branding_pl'] ?? null) ? ($content['branding_padding_left'] ?? $content['branding_pl']) . 'px' : ($content['branding_padding_left'] ?? $content['branding_pl'] ?? '0px');
    $bPr = is_numeric($content['branding_padding_right'] ?? $content['branding_pr'] ?? null) ? ($content['branding_padding_right'] ?? $content['branding_pr']) . 'px' : ($content['branding_padding_right'] ?? $content['branding_pr'] ?? '0px');
    $brandingStyle = "padding-top:{$bPt}; padding-bottom:{$bPb}; padding-left:{$bPl}; padding-right:{$bPr};";

    // Individual Padding for Menu block
    $mPt = is_numeric($content['menu_padding_top'] ?? $content['menu_pt'] ?? null) ? ($content['menu_padding_top'] ?? $content['menu_pt']) . 'px' : ($content['menu_padding_top'] ?? $content['menu_pt'] ?? '0px');
    $mPb = is_numeric($content['menu_padding_bottom'] ?? $content['menu_pb'] ?? null) ? ($content['menu_padding_bottom'] ?? $content['menu_pb']) . 'px' : ($content['menu_padding_bottom'] ?? $content['menu_pb'] ?? '0px');
    $mPl = is_numeric($content['menu_padding_left'] ?? $content['menu_pl'] ?? null) ? ($content['menu_padding_left'] ?? $content['menu_pl']) . 'px' : ($content['menu_padding_left'] ?? $content['menu_pl'] ?? '0px');
    $mPr = is_numeric($content['menu_padding_right'] ?? $content['menu_pr'] ?? null) ? ($content['menu_padding_right'] ?? $content['menu_pr']) . 'px' : ($content['menu_padding_right'] ?? $content['menu_pr'] ?? '0px');
    $mGap = (is_numeric($content['menu_gap'] ?? null) ? $content['menu_gap'] : 0) . 'px';
    $menuStyle = "padding-top:{$mPt}; padding-bottom:{$mPb}; padding-left:{$mPl}; padding-right:{$mPr};";

    // Individual Zone Backgrounds
    $brandingBg = !empty($content['branding_bg']) ? $content['branding_bg'] : $headerBgColor;
    $menuBg = !empty($content['menu_bg']) ? $content['menu_bg'] : $headerBgColor;

    $sBtnBg = !empty($content['search_bg']) ? $content['search_bg'] : '#f1f5f9';
    $sBtnColor = !empty($content['search_btn_color']) ? $content['search_btn_color'] : '#000000';

    // Menu Typography
    $l1C = !empty($content['menu_l1_color']) ? $content['menu_l1_color'] : $headerTextColor;
    $l1HC = !empty($content['menu_l1_hover_color']) ? $content['menu_l1_hover_color'] : '#0cebeb';
    $l1S = (is_numeric($content['menu_l1_size'] ?? null) ? $content['menu_l1_size'] : 14) . 'px';
    $l1W = $content['menu_l1_weight'] ?? '500';
    $l1pt = (is_numeric($content['menu_l1_padding_top'] ?? null) ? $content['menu_l1_padding_top'] : 0) . 'px';
    $l1pb = (is_numeric($content['menu_l1_padding_bottom'] ?? null) ? $content['menu_l1_padding_bottom'] : 0) . 'px';
    $l1pl = (is_numeric($content['menu_l1_padding_left'] ?? null) ? $content['menu_l1_padding_left'] : 0) . 'px';
    $l1pr = (is_numeric($content['menu_l1_padding_right'] ?? null) ? $content['menu_l1_padding_right'] : 0) . 'px';
    
    // Level 2
    $l2C = !empty($content['menu_l2_color']) ? $content['menu_l2_color'] : '#334155';
    $l2HC = !empty($content['menu_l2_hover_color']) ? $content['menu_l2_hover_color'] : '#0cebeb';
    $l2S = (is_numeric($content['menu_l2_size'] ?? null) ? $content['menu_l2_size'] : 14) . 'px';
    $l2W = $content['menu_l2_weight'] ?? '400';
    $l2Bg = !empty($content['menu_l2_bg']) ? $content['menu_l2_bg'] : '#ffffff';
    $l2pt = (is_numeric($content['menu_l2_padding_top'] ?? $content['menu_l2_pt'] ?? null) ? ($content['menu_l2_padding_top'] ?? $content['menu_l2_pt']) : 11) . 'px';
    $l2pb = (is_numeric($content['menu_l2_padding_bottom'] ?? $content['menu_l2_pb'] ?? null) ? ($content['menu_l2_padding_bottom'] ?? $content['menu_l2_pb']) : 11) . 'px';
    $l2pl = (is_numeric($content['menu_l2_padding_left'] ?? $content['menu_l2_pl'] ?? null) ? ($content['menu_l2_padding_left'] ?? $content['menu_l2_pl']) : 20) . 'px';
    $l2pr = (is_numeric($content['menu_l2_padding_right'] ?? $content['menu_l2_pr'] ?? null) ? ($content['menu_l2_padding_right'] ?? $content['menu_l2_pr']) : 20) . 'px';

    // Level 3
    $l3C = !empty($content['menu_l3_color']) ? $content['menu_l3_color'] : '#64748b';
    $l3HC = !empty($content['menu_l3_hover_color']) ? $content['menu_l3_hover_color'] : '#0cebeb';
    $l3S = (is_numeric($content['menu_l3_size'] ?? null) ? $content['menu_l3_size'] : 13) . 'px';
    $l3W = $content['menu_l3_weight'] ?? '400';
    $l3Bg = !empty($content['menu_l3_bg']) ? $content['menu_l3_bg'] : '#ffffff';
    $l3pt = (is_numeric($content['menu_l3_padding_top'] ?? $content['menu_l3_pt'] ?? null) ? ($content['menu_l3_padding_top'] ?? $content['menu_l3_pt']) : 11) . 'px';
    $l3pb = (is_numeric($content['menu_l3_padding_bottom'] ?? $content['menu_l3_pb'] ?? null) ? ($content['menu_l3_padding_bottom'] ?? $content['menu_l3_pb']) : 11) . 'px';
    $l3pl = (is_numeric($content['menu_l3_padding_left'] ?? $content['menu_l3_pl'] ?? null) ? ($content['menu_l3_padding_left'] ?? $content['menu_l3_pl']) : 20) . 'px';
    $l3pr = (is_numeric($content['menu_l3_padding_right'] ?? $content['menu_l3_pr'] ?? null) ? ($content['menu_l3_padding_right'] ?? $content['menu_l3_pr']) : 20) . 'px';

    $l2gap = (is_numeric($content['menu_l2_gap'] ?? null) ? $content['menu_l2_gap'] : 0) . 'px';
    $l3gap = (is_numeric($content['menu_l3_gap'] ?? null) ? $content['menu_l3_gap'] : 0) . 'px';

    $uid = 'hdr-' . ($block->id ?? 'x');
@endphp

<header class="header-main sticky-top {{ $uid }}" id="header-main-{{ $uid }}"
    style="z-index: 1020; --hdr-bg: {{ $headerBgColor }}; --hdr-text: {{ $headerTextColor }}; --s-btn-bg: {{ $sBtnBg }}; --s-btn-color: {{ $sBtnColor }}; --menu-gap: {{ $mGap }};
           --l1-c: {{ $l1C }}; --l1-h-c: {{ $l1HC }}; --l1-s: {{ $l1S }}; --l1-w: {{ $l1W }}; --l1-pt: {{ $l1pt }}; --l1-pb: {{ $l1pb }}; --l1-pl: {{ $l1pl }}; --l1-pr: {{ $l1pr }};
           --l2-c: {{ $l2C }}; --l2-h-c: {{ $l2HC }}; --l2-s: {{ $l2S }}; --l2-w: {{ $l2W }}; --l2-bg: {{ $l2Bg }}; --l2-pt: {{ $l2pt }}; --l2-pb: {{ $l2pb }}; --l2-pl: {{ $l2pl }}; --l2-pr: {{ $l2pr }}; --l2-gap: {{ $l2gap }};
           --l3-c: {{ $l3C }}; --l3-h-c: {{ $l3HC }}; --l3-s: {{ $l3S }}; --l3-w: {{ $l3W }}; --l3-bg: {{ $l3Bg }}; --l3-pt: {{ $l3pt }}; --l3-pb: {{ $l3pb }}; --l3-pl: {{ $l3pl }}; --l3-pr: {{ $l3pr }}; --l3-gap: {{ $l3gap }};
           @if(!empty($content['font_family'])) font-family: {{ $content['font_family'] }}; @endif">

    {{-- ════════════════════════════════════════
         MODERN HEADER BAR
         ════════════════════════════════════════ --}}
    <div class="header-content-wrapper transition-all">
        
        {{-- ROW 1: BRANDING + SEARCH + ACTIONS --}}
        <div class="header-branding-row" style="padding-top:{{ $bPt }} !important; padding-bottom:{{ $bPb }} !important; padding-left:{{ $bPl }} !important; padding-right:{{ $bPr }} !important; background-color: {{ $brandingBg }};">
            <div class="container-fluid" style="padding-left: 0 !important; padding-right: 0 !important; margin-left: 0 !important; margin-right: 0 !important; max-width: none !important; width: 100% !important;">
                <div class="d-flex align-items-center justify-content-between flex-nowrap gap-2">

                    {{-- ① LOGO SECTION --}}
                    @if (!empty($content['logo']))
                        <a href="{{ $content['logo_link'] ?? '/' }}"
                           class="flex-shrink-0 d-flex align-items-center text-decoration-none logo-link">
                            <img loading="lazy" src="{{ $content['logo'] }}" alt="Logo"
                                 class="main-logo"
                                 style="height: {{ $headerHeight }}px; width: auto; max-height: {{ $headerHeight }}px; max-width: 240px; object-fit: contain; display: block;">
                        </a>
                    @endif

                    {{-- ② FLOATING SEARCH BAR (Desktop) --}}
                    @if ($showSearch)
                        <div class="d-none d-lg-block mx-3" style="width: 100%; max-width: {{ $content['search_max_width'] ?? 600 }}px;">
                            <form action="{{ route('search') }}" method="GET" class="modern-search-form">
                                <div class="modern-search-wrap d-flex align-items-center rounded-pill overflow-hidden transition-all" 
                                     style="background-color: {{ $content['search_bg'] ?? '#f1f5f9' }}; border: 1px solid {{ $content['search_border_color'] ?? 'rgba(0,0,0,0.05)' }}; --s-ph-color: {{ $content['search_placeholder_color'] ?? '#64748b' }}; --s-ph-size: {{ $content['search_placeholder_size'] ?? '14' }}px;">
                                    <input type="text" name="q"
                                           class="modern-search-input flex-grow-1 border-0 bg-transparent ps-4 py-0"
                                           style="color: {{ $content['search_text_color'] ?? '#1e293b' }}; font-size: {{ $content['search_text_size'] ?? '14' }}px;"
                                           placeholder="{{ $content['search_placeholder'] ?? 'Hôm nay bạn cần tìm gì?' }}">
                                    <button type="submit" class="modern-search-btn border-0 d-flex align-items-center justify-content-center flex-shrink-0" style="background-color: {{ $sBtnBg }}; color: {{ $sBtnColor }};">
                                        <i class="fas fa-search px-3"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif

                    {{-- Spacer on mobile --}}
                    <div class="flex-grow-1 d-lg-none"></div>

                    {{-- ③ ACTION ICONS --}}
                    <div class="flex-shrink-0 d-flex align-items-center" style="gap: {{ $content['utility_icons_gap'] ?? 8 }}px;">

                        @if ($showAccount)
                            @php 
                                $aColor = $content['account_icon_color'] ?? $headerTextColor; 
                                $aHColor = $content['account_icon_hover_color'] ?? $aColor;
                                $aSize = $content['account_icon_size'] ?? 20; 
                            @endphp
                            <a href="{{ $content['account_link'] ?? '#' }}"
                               class="hdr-modern-btn d-flex align-items-center justify-content-center text-decoration-none rounded-circle transition-all"
                               style="--icon-color: {{ $aColor }}; --icon-hover-color: {{ $aHColor }};"
                               title="Tài khoản">
                                <i class="far fa-user" style="color: var(--icon-color); font-size: {{ $aSize }}px; transition: color 0.3s ease;"></i>
                            </a>
                        @endif

                        @if ($showCart)
                            @php 
                                $cColor = $content['cart_icon_color'] ?? $headerTextColor; 
                                $cHColor = $content['cart_icon_hover_color'] ?? $cColor;
                                $cSize = $content['cart_icon_size'] ?? 20; 

                                $cCount = (int)($content['cart_count'] ?? 0);
                                $cDisplayCount = $cCount > 9 ? '9+' : $cCount;
                                $cBColor = $content['cart_badge_color'] ?? '#ffffff';
                                $cBBg = $content['cart_badge_bg'] ?? '#dc3545';
                                $cBSize = $content['cart_badge_size'] ?? 9;
                            @endphp
                            <a href="{{ $content['cart_link'] ?? '#' }}"
                               class="hdr-modern-btn d-flex align-items-center justify-content-center text-decoration-none rounded-circle position-relative transition-all"
                               style="--icon-color: {{ $cColor }}; --icon-hover-color: {{ $cHColor }};"
                               title="Giỏ hàng">
                                <i class="fas fa-shopping-bag" style="color: var(--icon-color); font-size: {{ $cSize }}px; transition: color 0.3s ease;"></i>
                                <span class="hdr-modern-badge position-absolute rounded-pill d-flex align-items-center justify-content-center"
                                      style="background-color: {{ $cBBg }}; color: {{ $cBColor }}; font-size: {{ $cBSize }}px;">
                                    {{ $cDisplayCount }}
                                </span>
                            </a>
                        @endif

                        @if ($showWishlist)
                            @php 
                                $wColor = $content['wishlist_icon_color'] ?? $headerTextColor; 
                                $wHColor = $content['wishlist_icon_hover_color'] ?? $wColor;
                                $wSize = $content['wishlist_icon_size'] ?? 20; 

                                $wCount = (int)($content['wishlist_count'] ?? 0);
                                $wDisplayCount = $wCount > 9 ? '9+' : $wCount;
                                $wBColor = $content['wishlist_badge_color'] ?? '#ffffff';
                                $wBBg = $content['wishlist_badge_bg'] ?? '#dc3545';
                                $wBSize = $content['wishlist_badge_size'] ?? 9;
                            @endphp
                            <a href="{{ $content['wishlist_link'] ?? '#' }}"
                               class="hdr-modern-btn d-flex align-items-center justify-content-center text-decoration-none rounded-circle position-relative transition-all"
                               style="--icon-color: {{ $wColor }}; --icon-hover-color: {{ $wHColor }};"
                               title="Yêu thích">
                                <i class="far fa-heart" style="color: var(--icon-color); font-size: {{ $wSize }}px; transition: color 0.3s ease;"></i>
                                <span class="hdr-modern-badge position-absolute rounded-pill d-flex align-items-center justify-content-center"
                                      style="background-color: {{ $wBBg }}; color: {{ $wBColor }}; font-size: {{ $wBSize }}px;">
                                    {{ $wDisplayCount }}
                                </span>
                            </a>
                        @endif

                        {{-- Hamburger – Mobile only --}}
                        <button class="hdr-modern-btn d-flex d-lg-none align-items-center justify-content-center border-0 bg-transparent rounded-circle"
                                type="button" data-bs-toggle="offcanvas" data-bs-target="#{{ $navId }}">
                            <i class="fas fa-bars-staggered" style="color:{{ $headerTextColor }};font-size:20px;"></i>
                        </button>
                    </div>

                </div>
            </div>
        </div>

        {{-- ROW 2: MODERN NAVIGATION MENU (Desktop Only) --}}
        @if ($hasButtons)
            <div class="header-menu-row d-none d-lg-block" style="padding-left:{{ $mPl }} !important; padding-right:{{ $mPr }} !important; background-color: {{ $menuBg }};">
                <div class="container-fluid" style="padding-left: 0 !important; padding-right: 0 !important; margin-left: 0 !important; margin-right: 0 !important; max-width: none !important; width: 100% !important;">
                    <ul class="hdr-modern-nav d-flex align-items-stretch justify-content-{{ $content['menu_alignment'] ?? 'center' }} list-unstyled mb-0">
                        @foreach ($content['buttons'] as $btn)
                            @php
                                $hasC2  = !empty($btn['children']);
                                $link1  = $btn['link'] ?? '#';
                                $isA1   = ($link1 !== '#' && $link1 !== '' &&
                                           trim(parse_url($link1, PHP_URL_PATH) ?? '/', '/') === trim(request()->getPathInfo(), '/'));
                            @endphp
                            <li class="hdr-nav-item flex-shrink-0 position-relative d-flex align-items-stretch {{ $hasC2 ? 'has-mega-effect' : '' }}">
                                <a href="{{ $link1 }}"
                                   class="hdr-nav-link hdr-l1 d-flex align-items-center gap-2 text-decoration-none text-nowrap {{ $isA1 ? 'is-active' : '' }}"
                                   style="line-height: 1 !important; padding-top: calc({{ $l1pt }} + {{ $mPt }}) !important; padding-bottom: calc({{ $l1pb }} + {{ $mPb }}) !important;">
                                    <span class="nav-label" style="line-height: 1 !important; display: inline-block !important;">{{ $btn['label'] ?? '' }}</span>
                                    @if($hasC2)<i class="fas fa-chevron-down" style="font-size: 8px; color: inherit; line-height: 1 !important; margin: 0 !important;"></i>@endif
                                </a>

                                @if ($hasC2)
                                    <div class="hdr-modern-dropdown">
                                        <ul class="list-unstyled mb-0">
                                            @foreach ($btn['children'] as $sub)
                                                @php
                                                    $hasC3 = !empty($sub['children']);
                                                    $isA2  = !empty($sub['link']) && $sub['link'] !== '#' &&
                                                             trim(parse_url($sub['link'], PHP_URL_PATH) ?? '/', '/') === trim(request()->getPathInfo(), '/');
                                                @endphp
                                                <li class="position-relative {{ $hasC3 ? 'has-inner-dropdown' : '' }}">
                                                    <a href="{{ $sub['link'] ?? '#' }}"
                                                       class="hdr-dropdown-link hdr-l2 d-flex align-items-center justify-content-between {{ $isA2 ? 'is-active' : '' }}">
                                                        <span>{{ $sub['label'] ?? '' }}</span>
                                                        @if($hasC3)<i class="fas fa-chevron-right" style="font-size: 8px; color: inherit;"></i>@endif
                                                    </a>
                                                    @if ($hasC3)
                                                        <div class="hdr-modern-submenu">
                                                            <ul class="list-unstyled mb-0">
                                                                @foreach ($sub['children'] as $grand)
                                                                    <li>
                                                                        <a href="{{ $grand['link'] ?? '#' }}" class="hdr-dropdown-link hdr-l3 px-3">
                                                                            {{ $grand['label'] ?? '' }}
                                                                        </a>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

    </div>

    {{-- MOBILE SEARCH BAR AREA --}}
    @if ($showSearch)
        <div class="d-lg-none px-3 pb-3 pt-1">
            <form action="{{ route('search') }}" method="GET">
                <div class="modern-search-wrap d-flex align-items-center rounded-pill overflow-hidden">
                    <input type="text" name="q"
                           class="modern-search-input flex-grow-1 border-0 bg-transparent ps-4 py-0"
                           placeholder="{{ $content['search_placeholder'] ?? 'Tìm kiếm...' }}"
                           style="height: 42px; font-size: 14px;">
                    <button type="submit" class="modern-search-btn border-0 d-flex align-items-center justify-content-center flex-shrink-0" style="width:48px;height:42px;">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
    @endif

    {{-- MODERN MOBILE OFFCANVAS --}}
    @if ($hasButtons)
        <div class="offcanvas offcanvas-start border-0 modern-offcanvas" tabindex="-1" id="{{ $navId }}">
            <div class="offcanvas-header py-4 px-4">
                @if(!empty($content['logo']))
                    <img src="{{ $content['logo'] }}" alt="Logo" class="offcanvas-logo">
                @else
                    <span class="offcanvas-title-text">MENU</span>
                @endif
                <button type="button" class="btn-close-modern" data-bs-dismiss="offcanvas">
                    <i class="fas fa-xmark"></i>
                </button>
            </div>
            <div class="offcanvas-body p-0">
                <nav class="mobile-nav-stack py-3">
                    @foreach ($content['buttons'] as $bi => $btn)
                        @php
                            $hasC2m = !empty($btn['children']);
                            $id2    = $navId . '-sub-' . $bi;
                        @endphp
                        <div class="mob-nav-group mx-2">
                            @if($hasC2m)
                                <button class="mob-nav-btn w-100 d-flex align-items-center justify-content-between px-3 py-3"
                                        data-bs-toggle="collapse" data-bs-target="#{{ $id2 }}">
                                    <span>{{ $btn['label'] ?? '' }}</span>
                                    <i class="fas fa-plus mob-icon"></i>
                                </button>
                                <div class="collapse" id="{{ $id2 }}">
                                    <div class="mob-nav-sub-wrap ps-3 mb-2">
                                        @foreach ($btn['children'] as $si => $sub)
                                            @php
                                                $hasC3m = !empty($sub['children']);
                                                $id3    = $navId . '-grand-' . $bi . '-' . $si;
                                            @endphp
                                            <div class="mob-sub-group">
                                                @if($hasC3m)
                                                    <button class="mob-nav-btn sub w-100 d-flex align-items-center justify-content-between px-3 py-2"
                                                            data-bs-toggle="collapse" data-bs-target="#{{ $id3 }}">
                                                        <span>{{ $sub['label'] ?? '' }}</span>
                                                        <i class="fas fa-chevron-down mob-icon small"></i>
                                                    </button>
                                                    <div class="collapse" id="{{ $id3 }}">
                                                        <div class="mob-grand-wrap ps-3">
                                                            @foreach ($sub['children'] as $grand)
                                                                <a href="{{ $grand['link'] ?? '#' }}" class="mob-nav-link p-2">
                                                                    {{ $grand['label'] ?? '' }}
                                                                </a>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @else
                                                    <a href="{{ $sub['link'] ?? '#' }}" class="mob-nav-link p-3 fw-medium">
                                                        {{ $sub['label'] ?? '' }}
                                                    </a>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <a href="{{ $btn['link'] ?? '#' }}" class="mob-nav-btn d-block px-3 py-3 text-decoration-none">
                                    {{ $btn['label'] ?? '' }}
                                </a>
                            @endif
                        </div>
                    @endforeach
                </nav>
            </div>
        </div>
    @endif

</header>

{{-- ════════════════════════════════════════
     MODERN SCOPED STYLES
     ════════════════════════════════════════ --}}
<style>
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap');

:root {
    --hdr-ease: cubic-bezier(0.165, 0.84, 0.44, 1);
}

.{{ $uid }} {
    font-family: 'Outfit', sans-serif;
    color: var(--hdr-text);
    transition: all 0.4s var(--hdr-ease);
}

.transition-all { transition: all 0.4s var(--hdr-ease); }

/* Aero Glass Effect on Scroll */
.{{ $uid }}.scrolled {
    background: rgba(255, 255, 255, 0.8) !important;
    backdrop-filter: blur(20px) saturate(180%);
    -webkit-backdrop-filter: blur(20px) saturate(180%);
    box-shadow: 0 4px 30px rgba(0, 0, 0, 0.05);
}
/* Removed shrink effect on logo/branding per user request */

/* Floating Search Bar */
.modern-search-wrap {
    background: #ffffff;
    border: 1.5px solid rgba(0,0,0,0.06);
    height: 48px;
}
.modern-search-input {
    outline: none;
    font-size: 14px;
    font-weight: 500;
    color: #1e293b;
    letter-spacing: -0.2px;
}
.modern-search-btn {
    background: transparent;
    color: var(--s-btn-color);
    font-size: 16px;
    width: 50px;
    height: 100%;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
}
/* Static button - hover effects removed per user request */
.hdr-modern-btn:hover i {
    color: var(--icon-hover-color) !important;
}
.modern-search-input::placeholder {
    color: var(--s-ph-color) !important;
    font-size: var(--s-ph-size) !important;
    opacity: 1;
}


/* Modern Navigation Links */
.header-menu-row, .header-menu-row .container, .header-menu-row .container-fluid {
    height: auto !important;
    min-height: 0 !important;
    padding: 0 !important;
    margin: 0 !important;
    border: none !important;
}
.hdr-modern-nav, .hdr-nav-item {
    height: auto !important;
    min-height: 0 !important;
    padding: 0 !important;
    margin: 0 !important;
}
.hdr-modern-nav { gap: var(--menu-gap) !important; }
.hdr-nav-link {
    padding: var(--l1-pt) var(--l1-pl) var(--l1-pb) var(--l1-pr) !important;
    margin: 0 !important;
    min-height: 0 !important;
    height: auto !important;
    font-size: var(--l1-s) !important;
    font-weight: var(--l1-w);
    color: var(--l1-c);
    line-height: 1 !important;
    display: flex !important;
    align-items: center !important;
    text-decoration: none !important;
    transition: all 0.3s var(--hdr-ease);
    position: relative;
    border: none !important;
    outline: none !important;
}
.hdr-nav-link span, .hdr-nav-link i, .nav-label {
    line-height: 1 !important;
    margin: 0 !important;
    padding: 0 !important;
    display: inline-block !important;
    height: auto !important;
}
.hdr-nav-link:hover, .hdr-nav-link.is-active {
    opacity: 1;
    color: var(--l1-h-c) !important;
}

/* Action Icons */
.hdr-modern-btn {
    width: 40px;
    height: 40px;
    background: transparent;
    border: none;
}
.hdr-modern-btn:hover {
    transform: translateY(-3px) scale(1.1);
}
.hdr-modern-btn:hover i { 
    color: var(--icon-hover-color) !important; 
}

.hdr-modern-badge {
    top: 2px;
    right: 2px;
    min-width: 17px;
    height: 17px;
    font-size: 9px;
    padding: 0 4px;
    border: 1.5px solid #fff;
    font-weight: 700;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

/* Dropdowns & Mega Effects */
.hdr-modern-dropdown {
    position: absolute;
    top: 100%;
    left: 50%;
    transform: translateX(-50%) translateY(0);
    min-width: 240px;
    background: var(--l2-bg);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(0,0,0,0.06);
    opacity: 0;
    visibility: hidden;
    transition: all 0.4s var(--hdr-ease);
    z-index: 1000;
    border-radius: 0 !important;
    padding: var(--l2-pt) var(--l2-pr) var(--l2-pb) var(--l2-pl);
}
.hdr-modern-dropdown > ul, .hdr-modern-submenu > ul {
    display: flex;
    flex-direction: column;
}
.hdr-modern-dropdown > ul { gap: var(--l2-gap); }
.hdr-modern-submenu > ul { gap: var(--l3-gap); }
.hdr-nav-item:hover > .hdr-modern-dropdown {
    opacity: 1;
    visibility: visible;
    transform: translateX(-50%) translateY(0);
}

.hdr-dropdown-link {
    display: flex;
    padding: 0.5rem 1.25rem;
    text-decoration: none;
    transition: all 0.25s;
    margin: 0;
    border-radius: 0;
    line-height: 1.2;
}
.hdr-l2 { color: var(--l2-c); font-size: var(--l2-s); font-weight: var(--l2-w); }
.hdr-l3 { color: var(--l3-c); font-size: var(--l3-s); font-weight: var(--l3-w); }
.hdr-dropdown-link:hover { color: var(--l2-h-c) !important; transform: translateX(5px); }

.hdr-modern-submenu {
    position: absolute;
    top: calc(-1 * var(--l3-pt)) !important; 
    left: calc(100% + var(--l2-pr)) !important; /* Flush with Level 2 container right edge */
    min-width: 220px;
    background: var(--l3-bg);
    border: 1px solid rgba(0,0,0,0.06);
    opacity: 0;
    visibility: hidden;
    transform: translateX(0);
    transition: all 0.3s var(--hdr-ease);
    padding: var(--l3-pt) var(--l3-pr) var(--l3-pb) var(--l3-pl);
}
.has-inner-dropdown:hover > .hdr-modern-submenu {
    opacity: 1;
    visibility: visible;
    transform: translateX(0);
}

/* Mobile Modern UI */
.modern-offcanvas {
    background: rgba(255, 255, 255, 0.95) !important;
    backdrop-filter: blur(25px);
}
.offcanvas-logo { height: 40px; width: auto; object-fit: contain; }
.btn-close-modern {
    border: none;
    background: none;
    font-size: 24px;
    color: #64748b;
    transition: transform 0.3s;
}
.btn-close-modern:hover { transform: rotate(90deg); color: #0f172a; }

.mob-nav-btn {
    border: none;
    background: none;
    font-size: 15px;
    font-weight: 500;
    color: #1e293b;
    transition: all 0.2s;
    text-decoration: none;
}
.mob-nav-btn:active { background: #f1f5f9; }
.mob-icon { transition: transform 0.3s; color: #94a3b8; }
.mob-nav-btn[aria-expanded="true"] .mob-icon { transform: rotate(45deg); color: #0cebeb; }
.mob-nav-btn.sub[aria-expanded="true"] .mob-icon { transform: rotate(180deg); }

.mob-nav-link {
    display: block;
    text-decoration: none;
    color: #64748b;
    font-size: 14px;
    transition: color 0.2s;
}
.mob-nav-link:hover { color: #0cebeb; }

/* Utilities */
.shadow-2xl { box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); }
.opacity-30 { opacity: 0.3; }
</style>

<script>
(function() {
    const header = document.getElementById('header-main-{{ $uid }}');
    if (!header) return;

    // Glassmorphism Scroll Effect
    const handleScroll = () => {
        if (window.scrollY > 40) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    };

    window.addEventListener('scroll', handleScroll, { passive: true });
    
    // Initial check
    handleScroll();
})();
</script>