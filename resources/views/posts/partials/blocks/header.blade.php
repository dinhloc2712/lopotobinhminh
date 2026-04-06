@php
    $navId = 'header-nav-' . ($block->id ?? uniqid());
    $hasButtons = !empty($content['buttons']);
    $hasLogo = !empty($content['logo']) || !empty($content['logo_text']);

    $firstBtnTextColor = !empty($content['buttons'][0]['text_color']) ? $content['buttons'][0]['text_color'] : null;
    $headerTextColor = $firstBtnTextColor ?: (!empty($content['text_color']) ? $content['text_color'] : '#000000');
    $headerBgColor = !empty($content['bg_color']) ? $content['bg_color'] : '#ffffff';

    // E-commerce features
    $showSearch = !empty($content['show_search']);
    $showAccount = !empty($content['show_account']);
    $showCart = !empty($content['show_cart']);
    $showWishlist = !empty($content['show_wishlist']);
    $hasActions = $showAccount || $showCart || $showWishlist;

    $headerHeight = !empty($content['header_height']) ? $content['header_height'] : 80;
@endphp

<header class="shadow-sm sticky-top"
    style="min-height: {{ $headerHeight }}px; z-index: 1020; background-color: {{ $headerBgColor }}; border-bottom: 1px solid rgba(0,0,0,0.05); @if ($content['font_family'] ?? null) font-family: {{ $content['font_family'] }}; @endif">
    <nav class="navbar navbar-expand-lg navbar-light w-100 py-3" style="min-height: {{ $headerHeight }}px;">
        <div class="{{ !empty($content['full_width']) ? 'container-fluid px-3' : 'container' }}">
            <div class="row g-0 align-items-center w-100 flex-nowrap">

                {{-- Left: Logo --}}
                @if ($hasLogo)
                    <div class="col-auto flex-shrink-0">

                        <a class="navbar-brand py-0 me-3" href="{{ $content['logo_link'] ?? '/' }}">
                            @if (!empty($content['logo']))
                                <img loading="lazy" src="{{ $content['logo'] }}" alt="Logo"
                                    style="max-height: 50px; width: auto; object-fit: contain;">
                            @else
                                <span class="fw-bold fs-4"
                                    style="color: {{ $headerTextColor }} !important;">{{ $content['logo_text'] }}</span>
                            @endif
                        </a>

                    </div>
                @endif

                {{-- Center: Search Bar (Desktop) --}}
                @if ($showSearch)
                    <div class="col d-none d-lg-flex justify-content-center px-4">
                        <form action="{{ route('search') }}" method="GET" class="w-100" style="max-width: 600px;">
                            <div class="input-group rounded-pill overflow-hidden border shadow-sm"
                                style="transition: all 0.3s; background: #fdfdfd;">
                                <input type="text" name="q" class="form-control border-0 ps-4 py-2"
                                    placeholder="{{ $content['search_placeholder'] ?? 'Tìm kiếm mọi thứ ở đây...' }}"
                                    style="background: transparent; box-shadow: none; font-size: 15px;">
                                <button class="btn px-4 d-flex align-items-center justify-content-center" type="submit"
                                    style="background: #00f2ff; color: #000; border: none;">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                @endif

                {{-- Right: Actions & Menu --}}
                <div class="col-auto flex-shrink-0">
                    <div class="d-flex align-items-center gap-2 gap-md-3">

                        {{-- E-commerce Icons --}}
                        @if ($showAccount)
                            <a href="{{ $content['account_link'] ?? '#' }}" class="text-decoration-none d-flex p-2"
                                style="color: {{ $headerTextColor }}; transition: transform 0.2s;">
                                <i class="far fa-user fs-4"></i>
                            </a>
                        @endif

                        @if ($showCart)
                            <a href="{{ $content['cart_link'] ?? '#' }}"
                                class="text-decoration-none d-flex p-2 position-relative"
                                style="color: {{ $headerTextColor }}; transition: transform 0.2s;">
                                <i class="fas fa-shopping-bag fs-4"></i>
                                <span
                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                    style="font-size: 10px; padding: 3px 6px; transform: translate(-30%, 10%) !important;">
                                    {{ $content['cart_count'] ?? 1 }}
                                </span>
                            </a>
                        @endif

                        @if ($showWishlist)
                            <a href="{{ $content['wishlist_link'] ?? '#' }}"
                                class="text-decoration-none d-flex p-2 position-relative"
                                style="color: {{ $headerTextColor }}; transition: transform 0.2s;">
                                <i class="far fa-heart fs-4"></i>
                                <span
                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                    style="font-size: 10px; padding: 3px 6px; transform: translate(-30%, 10%) !important;">
                                    {{ $content['wishlist_count'] ?? 0 }}
                                </span>
                            </a>
                        @endif

                        {{-- Hamburger --}}
                        @if ($hasButtons)
                            <button class="btn border-0 p-2 d-lg-none" type="button" data-bs-toggle="offcanvas"
                                data-bs-target="#{{ $navId }}">
                                <i class="fas fa-bars fs-4" style="color: {{ $headerTextColor }};"></i>
                            </button>

                            {{-- Desktop Nav --}}
                            <div class="d-none d-lg-flex">
                                <ul class="navbar-nav flex-row align-items-center gap-2">
                                    @foreach ($content['buttons'] as $btn)
                                        @php
                                            $linkUrl = $btn['link'] ?? '#';
                                            $isActive = false;
                                            if ($linkUrl !== '#' && $linkUrl !== '') {
                                                $path = parse_url($linkUrl, PHP_URL_PATH) ?? '/';
                                                if (trim($path, '/') === trim(request()->getPathInfo(), '/')) {
                                                    $isActive = true;
                                                }
                                            }
                                        @endphp
                                        <li class="nav-item">
                                            <a href="{{ $linkUrl }}"
                                                class="nav-link px-3 py-1 fw-bold text-nowrap"
                                                style="background-color: {{ $btn['bg_color'] ?? 'transparent' }};
                                                      color: {{ $btn['text_color'] ?? ($content['text_color'] ?? '#000000') }};
                                                      border-radius: {{ ($btn['border_radius'] ?? 0) . 'px' }};
                                                      font-size: 14px; {{ $isActive ? 'border-bottom: 2px solid currentColor;' : '' }}">
                                                {{ $btn['label'] ?? '' }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Mobile Search Bar --}}
            @if ($showSearch)
                <div class="d-lg-none w-100 mt-3 px-2">
                    <form action="{{ route('search') }}" method="GET">
                        <div class="input-group rounded-pill overflow-hidden border bg-light shadow-sm">
                            <input type="text" name="q" class="form-control border-0 ps-3 py-1 small"
                                placeholder="{{ $content['search_placeholder'] ?? 'Tìm kiếm...' }}"
                                style="background: transparent; box-shadow: none;">
                            <button class="btn px-3 btn-light border-0" type="submit" style="background: #00f2ff;">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            {{-- Mobile Sidebar Menu --}}
            @if ($hasButtons)
                <div class="offcanvas offcanvas-start d-lg-none" tabindex="-1" id="{{ $navId }}"
                    style="background-color: {{ $headerBgColor }}; color: {{ $headerTextColor }};">
                    <div class="offcanvas-header border-bottom">
                        <div class="offcanvas-title fw-bold fs-5">Menu</div>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
                    </div>
                    <div class="offcanvas-body">
                        <ul class="navbar-nav gap-2">
                            @foreach ($content['buttons'] as $btn)
                                <li class="nav-item">
                                    <a href="{{ $btn['link'] ?? '#' }}" class="nav-link p-3 rounded-3"
                                        style="background: {{ $btn['bg_color'] ?? '#f8f9fa' }}; color: {{ $btn['text_color'] ?? '#000' }}; fw-bold">
                                        @if (!empty($btn['icon']))
                                            <i class="{{ $btn['icon'] }} me-2"></i>
                                        @endif
                                        {{ $btn['label'] ?? '' }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
        </div>
    </nav>
</header>
