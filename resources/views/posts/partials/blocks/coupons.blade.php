@php
    $title = $content['title'] ?? 'ƯU ĐÃI LIÊN QUAN';
    $couponIds = $content['coupon_ids'] ?? [];
    
    // Query base for valid coupons
    $validQuery = \App\Models\Coupon::where('status', 'active')
        ->where(function($q) {
            $q->whereNull('start_date')->orWhere('start_date', '<=', now());
        })
        ->where(function($q) {
            $q->whereNull('expiry_date')->orWhere('expiry_date', '>=', now());
        })
        ->where(function($q) {
            $q->whereRaw('quantity = 0 OR used < quantity');
        });

    $coupons = (clone $validQuery)->when(!empty($couponIds), function($q) use ($couponIds) {
            $q->whereIn('id', $couponIds);
        })
        ->orderBy('priority', 'desc')
        ->take(3)
        ->get();

    $allCoupons = (clone $validQuery)->orderBy('priority', 'desc')->get();

    // Typography & Colors
    $titleColor = $content['title_color'] ?? '#001d3d';
    $titleFontSize = is_numeric($content['title_font_size'] ?? 20) ? ($content['title_font_size'] ?? 20) . 'px' : ($content['title_font_size'] ?? 20);
    $accentColor = $content['accent_color'] ?? '#e31824';
    $linkIconColor = $content['link_icon_color'] ?? '#3b82f6';
    
    // Item Typography
    $codeColor = $content['code_color'] ?? '#1a202c';
    $codeFontSize = is_numeric($content['code_font_size'] ?? 14) ? ($content['code_font_size'] ?? 14) . 'px' : ($content['code_font_size'] ?? 14);
    $descColor = $content['desc_color'] ?? '#718096';
    $descFontSize = is_numeric($content['desc_font_size'] ?? 12) ? ($content['desc_font_size'] ?? 12) . 'px' : ($content['desc_font_size'] ?? 12);

    // Card Look
    $maxWidthRaw = $content['container_max_width'] ?? '1200px';
    $maxWidth = is_numeric($maxWidthRaw) ? $maxWidthRaw . 'px' : $maxWidthRaw;
    $boxShadow = $content['box_shadow'] ?? 'none';
    $itemBoxShadow = $content['item_box_shadow'] ?? 'shadow-sm';
    
    $borderRadiusRaw = $content['border_radius'] ?? '16px';
    $borderRadius = is_numeric($borderRadiusRaw) ? $borderRadiusRaw . 'px' : $borderRadiusRaw;
    
    $itemBorderRadius = $content['item_border_radius'] ?? '12px';
    $itemBorderRadius = is_numeric($itemBorderRadius) ? $itemBorderRadius . 'px' : $itemBorderRadius;
    
    $cardBg = $content['card_bg_color'] ?? '#ffffff';

    // Spacing
    $mt = ($content['margin_top'] ?? 40) . 'px';
    $mb = ($content['margin_bottom'] ?? 40) . 'px';
    $ml = ($content['margin_left'] ?? 0) . 'px';
    $mr = ($content['margin_right'] ?? 0) . 'px';
    
    $pt = ($content['padding_top'] ?? 24) . 'px';
    $pb = ($content['padding_bottom'] ?? 24) . 'px';
    $pl = ($content['padding_left'] ?? 24) . 'px';
    $pr = ($content['padding_right'] ?? 24) . 'px';

    // Popup Settings
    $popupTitle = $content['popup_title'] ?? 'Chi tiết ưu đãi';
    $popupButtonText = $content['popup_button_text'] ?? 'SAO CHÉP MÃ NGAY';
    $popupAccentColor = $content['popup_accent_color'] ?? '#e31824';
    $popupTitleColor = $content['popup_title_color'] ?? '#001d3d';

    $listPopupTitle = $content['list_popup_title'] ?? 'Tất cả mã giảm giá';
    $listPopupSearchPlaceholder = $content['list_popup_search_placeholder'] ?? 'Tìm kiếm mã hoặc ưu đãi...';
    $listPopupAccentColor = $content['list_popup_accent_color'] ?? '#3b82f6';
    $listPopupIconColor = $content['list_popup_icon_color'] ?? '#3b82f6';

    $bid = 'coupons-' . uniqid();
    
    $wrapperStyle = "max-width: {$maxWidth}; margin-left: auto; margin-right: auto;";
    $cardStyle = "background-color: {$cardBg} !important; border-radius: {$borderRadius}; padding: {$pt} {$pr} {$pb} {$pl} !important;";
    $cardClasses = "coupon-list-card " . ($boxShadow !== 'none' ? $boxShadow : '');

    if (!function_exists('renderCouponCardIndustrial')) {
        function renderCouponCardIndustrial($coupon, $bid, $itemBoxShadow, $itemBorderRadius, $accentColor, $codeColor, $codeFontSize, $descColor, $descFontSize, $linkIconColor, $popupTitle, $popupButtonText, $popupAccentColor, $popupTitleColor) {
            $code = $coupon->code;
            $name = $coupon->name;
            $desc = $coupon->description;
            
            $fullDesc = $desc ?: '';
            $conditions = [];
            if ($coupon->min_order_value > 0) $conditions[] = "- Đơn hàng tối thiểu: " . number_format($coupon->min_order_value) . "đ";
            if ($coupon->max_discount_amount > 0) $conditions[] = "- Giảm tối đa: " . number_format($coupon->max_discount_amount) . "đ";
            if ($coupon->expiry_date) $conditions[] = "- Hạn dùng: " . $coupon->expiry_date->format('d/m/Y');
            
            if (!empty($conditions)) {
                if (!empty($fullDesc)) $fullDesc .= "\n";
                $fullDesc .= implode("\n", $conditions);
            }
            
            // Prepare JS arguments using addslashes
            $jsCode = addslashes($code);
            $jsName = addslashes($name);
            $jsDesc = str_replace(["\r", "\n"], [' ', '\n'], addslashes($fullDesc));
            $jsPopupTitle = addslashes($popupTitle);
            $jsPopupBtn = addslashes($popupButtonText);

            // Use global modal call with custom popup settings
            $onclick = "showCouponDetail('{$jsCode}', '{$jsName}', `{$jsDesc}`, '{$popupAccentColor}', '{$jsPopupTitle}', '{$jsPopupBtn}', '{$popupTitleColor}')";
            $onclickHtml = htmlspecialchars($onclick, ENT_QUOTES, 'UTF-8');

            return '
            <div class="coupon-ticket '.($itemBoxShadow !== 'none' ? $itemBoxShadow : '').' h-100" style="border-radius: '.$itemBorderRadius.' !important;">
                <div class="coupon-left d-flex align-items-center justify-content-center" style="background: '.$accentColor.';">
                    <div class="coupon-icon-wrapper"><i class="fas fa-percent"></i></div>
                    <div class="cutout top"></div>
                    <div class="cutout bottom"></div>
                </div>
                <div class="coupon-right p-3 d-flex flex-column justify-content-between h-100">
                    <div class="w-100">
                        <div class="d-flex align-items-start justify-content-between mb-2 gap-1">
                            <div class="d-flex align-items-center gap-1 flex-wrap min-w-0">
                                <span class="coupon-code-text fw-bold text-truncate" style="color: '.$codeColor.'; font-size: '.$codeFontSize.';">MÃ '.$code.'</span>
                                <button class="btn-copy-mini flex-shrink-0" onclick="copyCoupon(\''.addslashes($code).'\', \''.$accentColor.'\')" title="Sao chép mã" style="color: '.$linkIconColor.';">
                                    <i class="far fa-copy"></i>
                                </button>
                            </div>
                            <button class="btn-detail-link flex-shrink-0" onclick="'.$onclickHtml.'" style="color: '.$linkIconColor.';">Chi tiết</button>
                        </div>
                        <p class="coupon-description mb-0 line-clamp-2" style="color: '.$descColor.' !important; font-size: '.$descFontSize.' !important;">
                            '.($desc ?: 'Ưu đãi hấp dẫn dành riêng cho bạn!').'
                        </p>
                    </div>
                </div>
            </div>';
        }
    }
@endphp

<div id="{{ $bid }}" class="coupon-list-block" style="margin-top: {{ $mt }}; margin-bottom: {{ $mb }}; margin-left: {{ $ml }}; margin-right: {{ $mr }};">
    <div class="container px-0">
        {{-- THE WHITE CONTAINER (CARD) --}}
        <div class="{{ $cardClasses }}" style="{{ $wrapperStyle }} {{ $cardStyle }}">
            
            {{-- HEADER --}}
            <div class="d-flex align-items-center mb-4">
                <div class="p-2 rounded-3 me-2" style="background-color: {{ $accentColor }}1a;">
                    <i class="fas fa-gift fs-5" style="color: {{ $accentColor }};"></i>
                </div>
                <h4 class="fw-bold mb-0 text-uppercase" style="color: {{ $titleColor }}; letter-spacing: 0.5px; font-size: {{ $titleFontSize }};">{{ $title }}</h4>
            </div>

            {{-- CONTENT: FLEXBOX LAYOUT (NO SWIPER) --}}
            <div class="coupon-flex-container d-flex align-items-stretch gap-3 flex-wrap">
                @if($coupons->count() > 0)
                    {{-- Coupons occupy remaining space --}}
                    <div class="coupons-inner-flex d-flex gap-3 flex-grow-1 flex-wrap">
                        @foreach($coupons as $coupon)
                            <div class="coupon-item-flex">
                                {!! renderCouponCardIndustrial($coupon, $bid, $itemBoxShadow, $itemBorderRadius, $accentColor, $codeColor, $codeFontSize, $descColor, $descFontSize, $linkIconColor, $popupTitle, $popupButtonText, $popupAccentColor, $popupTitleColor) !!}
                            </div>
                        @endforeach
                    </div>

                    @if($allCoupons->count() > 3)
                        <div class="btn-more-flex-wrapper d-flex align-items-center justify-content-center">
                            <button type="button" class="btn-view-more-coupons-circle border-0 bg-transparent shadow-none" onclick="showAllCouponsModal()" title="Xem thêm mã giảm giá">
                                <div class="icon-circle shadow-sm mb-2" style="background-color: {{ $accentColor }};">
                                    <i class="fas fa-chevron-right text-white"></i>
                                </div>
                                <span class="fw-bold" style="color: {{ $accentColor }};">Xem thêm</span>
                            </button>
                        </div>
                    @endif
                @else
                    <div class="text-center py-5 bg-light rounded-4 border-dashed border-2 w-100">
                        <i class="fas fa-ticket-alt text-muted fs-1 opacity-25 mb-3"></i>
                        <p class="text-muted mb-0">Chưa có mã giảm giá nào được chọn.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>


<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap');
    #{{ $bid }} { font-family: 'Outfit', sans-serif !important; }

    .coupons-inner-flex {
        flex: 1;
        min-width: 0;
    }

    .coupon-item-flex {
        flex: 1;
        min-width: 280px;
        max-width: 450px;
    }

    .btn-more-flex-wrapper {
        min-width: 100px;
        flex-shrink: 0;
    }

    .btn-view-more-coupons-circle {
        display: flex; flex-direction: column; align-items: center; text-decoration: none; transition: all 0.3s ease; padding: 10px;
    }
    .btn-view-more-coupons-circle .icon-circle {
        width: 44px; height: 44px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1rem; transition: transform 0.3s ease;
    }
    .btn-view-more-coupons-circle:hover .icon-circle { transform: scale(1.1); }
    .btn-view-more-coupons-circle span { font-size: 0.8rem; margin-top: 5px; }

    /* TICKET STYLES */
    .coupon-ticket { display: flex; background: #fff; position: relative; border: 1px solid #f0f0f0; transition: all 0.3s ease; overflow: hidden; }
    .coupon-ticket:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(0,0,0,0.05) !important; }

    .coupon-left { width: 70px; position: relative; flex-shrink: 0; color: #fff; }
    .coupon-left::after { content: ""; position: absolute; right: -1px; top: 10%; bottom: 10%; width: 1px; border-right: 2px dashed rgba(0,0,0,0.05); z-index: 2; }
    .coupon-icon-wrapper { width: 34px; height: 34px; background: rgba(255,255,255,0.25); border: 2px solid #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1rem; }
    .coupon-left .cutout { position: absolute; width: 14px; height: 14px; background: #fff; border-radius: 50%; right: -7px; z-index: 3; }
    .coupon-left .cutout.top { top: -7px; border: 1px solid #f0f0f0; }
    .coupon-left .cutout.bottom { bottom: -7px; border: 1px solid #f0f0f0; }

    .coupon-right { background: #fff; flex-grow: 1; min-width: 0; }
    .coupon-code-text { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 140px; }
    .btn-copy-mini { background: none; border: none; color: #3b82f6; cursor: pointer; padding: 0; font-size: 0.85rem; }
    .btn-detail-link { background: none; border: none; color: #3b82f6; font-size: 0.75rem; font-weight: 600; padding: 0; cursor: pointer; }
    .coupon-description { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.4; }

    @media (max-width: 991px) {
        .coupon-item-flex { min-width: calc(50% - 15px); }
    }

    @media (max-width: 768px) {
        .coupon-list-card { padding: 20px !important; }
        .coupon-item-flex { flex: 0 0 100%; max-width: 100%; min-width: 0; }
        .btn-more-flex-wrapper { width: 100%; margin-top: 10px; }
        .btn-view-more-coupons-circle { flex-direction: row; gap: 10px; }
        .btn-view-more-coupons-circle span { margin-top: 0; }
    }

    #globalModalCopyBtn {
        transition: all 0.3s ease;
    }
    #globalModalCopyBtn:hover {
        filter: brightness(0.9);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    #globalModalCopyBtn:active {
        transform: translateY(0);
        filter: brightness(0.8);
    }

    /* CUSTOM TOAST NOTIFICATION - STYLE 1: TECH SIDE-SLIDER */
    #couponToast {
        position: fixed; top: 40px; right: -400px; 
        background: rgba(17, 24, 39, 0.95); backdrop-filter: blur(12px);
        color: #fff; padding: 16px 24px; border-radius: 12px;
        z-index: 100000; display: flex; align-items: center; gap: 12px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.3), 0 0 10px rgba(16, 185, 129, 0.2);
        opacity: 0; visibility: hidden; transition: all 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        border: 1px solid rgba(255,255,255,0.1);
        border-left: 5px solid #10b981;
        overflow: hidden;
        min-width: 300px;
    }
    #couponToast.show {
        opacity: 1; visibility: visible;
        right: 25px;
    }
    #couponToast .toast-icon { width: 32px; height: 32px; background: rgba(16, 185, 129, 0.15); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1rem; color: #10b981; }
    #couponToastProgress {
        position: absolute; bottom: 0; left: 0; height: 3px; background: #10b981; width: 0;
    }

    /* ALL COUPONS MODAL STYLE */
    #globalAllCouponsModal .modal-content {
        border: none;
        border-radius: 24px;
        background: #f8fafc;
        overflow: hidden;
    }
    #globalAllCouponsModal .modal-header {
        background: #fff;
        border-bottom: 1px solid #e2e8f0;
        padding: 20px 24px;
    }
    #globalAllCouponsModal .modal-body {
        padding: 24px;
        background: #f8fafc;
    }
    .all-coupons-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 20px;
    }
    @media (max-width: 768px) {
        .all-coupons-grid { grid-template-columns: 1fr; }
    }

    /* SEARCH BAR STYLE */
    .search-wrapper { position: relative; max-width: 300px; width: 100%; }
    .search-input { 
        width: 100%; padding: 10px 15px 10px 40px; border-radius: 12px; 
        border: 1px solid #e2e8f0; background: #f8fafc; font-size: 0.9rem;
        transition: all 0.3s ease;
    }
    .search-input:focus { 
        background: #fff; border-color: {{ $listPopupAccentColor }}; outline: none; 
        box-shadow: 0 0 0 4px {{ $listPopupAccentColor }}1a; 
    }
    .search-icon { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.9rem; pointer-events: none; }
    .search-clear { 
        position: absolute; right: 10px; top: 50%; transform: translateY(-50%); 
        border: none; background: none; color: #94a3b8; padding: 5px; 
        cursor: pointer; display: none; border-radius: 50%;
    }
    .search-clear:hover { background: #f1f5f9; color: #64748b; }
    .search-input:not(:placeholder-shown) ~ .search-clear { display: block; }

    #noCouponsFound { padding: 40px; text-align: center; display: none; }
    #noCouponsFound i { font-size: 3rem; color: #e2e8f0; margin-bottom: 15px; }
</style>

@once
{{-- Modal: Danh sách tất cả mã giảm giá --}}
<div class="modal fade" id="globalAllCouponsModal" tabindex="-1" aria-hidden="true" style="z-index: 9990 !important;">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content shadow-lg">
            <div class="modal-header align-items-center flex-wrap gap-3" style="border-bottom: 2px solid {{ $listPopupAccentColor }}1a;">
                <h5 class="modal-title fw-bold mb-0 me-auto" style="color: #0F172A;">
                    <i class="fas fa-ticket-alt me-2" style="color: {{ $listPopupAccentColor }};"></i>{{ $listPopupTitle }}
                </h5>
                <div class="search-wrapper">
                    <i class="fas fa-search search-icon" style="color: {{ $listPopupAccentColor }};"></i>
                    <input type="text" id="couponSearchInput" class="search-input shadow-none" placeholder="{{ $listPopupSearchPlaceholder }}" oninput="filterCoupons()">
                    <button type="button" class="search-clear" onclick="clearCouponSearch()"><i class="fas fa-times-circle"></i></button>
                </div>
                <button type="button" class="btn-close ms-2 ms-md-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body custom-scrollbar">
                <div id="noCouponsFound">
                    <i class="fas fa-search"></i>
                    <h6 class="fw-bold text-dark">Không tìm thấy mã nào</h6>
                    <p class="text-muted small">Vui lòng thử từ khóa khác hoặc kiểm tra lại tên mã nhé!</p>
                </div>
                <div class="all-coupons-grid" id="allCouponsGrid">
                    @foreach($allCoupons as $coupon)
                        <div class="coupon-item-in-modal" data-search-terms="{{ strtolower($coupon->code . ' ' . $coupon->name . ' ' . $coupon->description) }}">
                            {!! renderCouponCardIndustrial($coupon, 'all-modal', 'shadow-sm', '16px', $listPopupAccentColor, $codeColor, '1.1rem', $descColor, '0.9rem', $listPopupIconColor, $popupTitle, $popupButtonText, $popupAccentColor, $popupTitleColor) !!}
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Global Coupon Modal placed outside transformed containers --}}
<div class="modal fade" id="globalCouponModal" tabindex="-1" aria-hidden="true" style="z-index: 10000 !important;">
    <div class="modal-dialog modal-dialog-centered" style="z-index: 10000 !important;">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
            <div class="modal-header border-0 bg-light p-4">
                <h5 class="modal-title fw-bold" id="globalModalTitle">Chi tiết ưu đãi</h5>
                <button type="button" class="btn-close shadow-none" onclick="hideGlobalCouponModal()" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="d-flex align-items-center mb-4">
                    <div id="globalModalIconBox" class="p-3 rounded-4 me-3">
                        <i class="fas fa-gift fs-3" id="globalModalIcon"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-1" id="globalModalCode">CODE</h4>
                        <p class="text-muted mb-0 fw-medium" id="globalModalName">Tên mã giảm giá</p>
                    </div>
                </div>
                <div class="bg-light p-3 rounded-3 mb-4 border">
                    <h6 class="fw-bold text-dark mb-2">Điều kiện áp dụng:</h6>
                    <div class="text-muted small" id="globalModalDesc" style="white-space: pre-wrap; line-height: 1.6;"></div>
                </div>
                <button type="button" id="globalModalCopyBtn" class="btn w-100 py-3 fw-bold rounded-pill text-white" onclick="copyCouponFromGlobalModal()">SAO CHÉP MÃ NGAY</button>
            </div>
        </div>
    </div>
</div>

{{-- Global Custom Toast Notification --}}
<div id="couponToast">
    <div class="toast-icon"><i class="fas fa-check-circle"></i></div>
    <div class="toast-content py-1">
        <div class="fw-bold mb-0" id="couponToastMsg">Sao chép thành công!</div>
        <div class="small opacity-75" id="couponToastSubMsg">Mã đã được lưu vào bộ nhớ tạm.</div>
    </div>
    <div id="couponToastProgress"></div>
</div>

<script>
    // RELOCATION: Move modals AND toast to body
    (function() {
        const moveElements = () => {
            const modalDetail = document.getElementById('globalCouponModal');
            const modalList = document.getElementById('globalAllCouponsModal');
            const toast = document.getElementById('couponToast');
            if (modalDetail && modalDetail.parentElement !== document.body) document.body.appendChild(modalDetail);
            if (modalList && modalList.parentElement !== document.body) document.body.appendChild(modalList);
            if (toast && toast.parentElement !== document.body) document.body.appendChild(toast);
        };
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', moveElements);
        } else {
            moveElements();
        }
    })();

    function showAllCouponsModal() {
        const modalEl = document.getElementById('globalAllCouponsModal');
        if (modalEl) {
            const modal = new bootstrap.Modal(modalEl);
            modal.show();
        }
    }

    function hideAllCouponsModal() {
        const modalEl = document.getElementById('globalAllCouponsModal');
        if (modalEl) {
            const modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) modal.hide();
        }
    }

    function removeVietnameseTones(str) {
        str = str.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g,"a"); 
        str = str.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g,"e"); 
        str = str.replace(/ì|í|ị|ỉ|ĩ/g,"i"); 
        str = str.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g,"o"); 
        str = str.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g,"u"); 
        str = str.replace(/ỳ|ý|ỵ|ỷ|ỹ/g,"y"); 
        str = str.replace(/đ/g,"d");
        str = str.replace(/À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ/g,"A");
        str = str.replace(/È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ/g,"E");
        str = str.replace(/Ì|Í|Ị|Ỉ|Ĩ/g,"I");
        str = str.replace(/Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ/g,"O");
        str = str.replace(/Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ/g,"U");
        str = str.replace(/Ỳ|Ý|Ỵ|Ỷ|Ỹ/g,"Y");
        str = str.replace(/Đ/g,"D");
        str = str.replace(/\u0300|\u0301|\u0303|\u0309|\u0323/g, "");
        str = str.replace(/\u02C6|\u0306|\u031B/g, "");
        return str.toLowerCase().trim();
    }

    function filterCoupons() {
        const input = document.getElementById('couponSearchInput');
        const grid = document.getElementById('allCouponsGrid');
        const emptyState = document.getElementById('noCouponsFound');
        if (!input || !grid) return;

        const filter = removeVietnameseTones(input.value);
        const items = grid.getElementsByClassName('coupon-item-in-modal');
        let foundCount = 0;

        for (let i = 0; i < items.length; i++) {
            const terms = removeVietnameseTones(items[i].getAttribute('data-search-terms') || '');
            if (terms.includes(filter)) {
                items[i].style.display = "";
                foundCount++;
            } else {
                items[i].style.display = "none";
            }
        }

        if (emptyState) {
            emptyState.style.display = (foundCount === 0) ? "block" : "none";
        }
    }

    function clearCouponSearch() {
        const input = document.getElementById('couponSearchInput');
        if (input) {
            input.value = '';
            filterCoupons();
            input.focus();
        }
    }

    function showCouponDetail(code, name, desc, accentColor, popupTitle, popupButtonText, popupTitleColor) {
        const modalEl = document.getElementById('globalCouponModal');
        if (!modalEl) return;
        
        // Populate content
        document.getElementById('globalModalTitle').innerText = popupTitle || 'Chi tiết ưu đãi';
        document.getElementById('globalModalTitle').style.color = popupTitleColor || '#001d3d';
        document.getElementById('globalModalCode').innerText = code;
        document.getElementById('globalModalName').innerText = name;
        document.getElementById('globalModalDesc').innerText = desc || 'Không có mô tả chi tiết.';
        document.getElementById('globalModalCopyBtn').innerText = popupButtonText || 'SAO CHÉP MÃ NGAY';

        // Apply dynamic theme colors
        const iconBox = document.getElementById('globalModalIconBox');
        const icon = document.getElementById('globalModalIcon');
        const codeText = document.getElementById('globalModalCode');
        const copyBtn = document.getElementById('globalModalCopyBtn');

        if (iconBox) iconBox.style.backgroundColor = accentColor + '1a'; // 10% opacity
        if (icon) icon.style.color = accentColor;
        if (codeText) codeText.style.color = accentColor;
        if (copyBtn) {
            copyBtn.style.backgroundColor = accentColor;
            copyBtn.style.borderColor = accentColor;
        }

        // Show modal using BS5 or fallback
        if (typeof bootstrap !== 'undefined') {
            const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.show();
        } else if (window.jQuery && window.jQuery.fn.modal) {
            window.jQuery(modalEl).modal('show');
        } else {
            alert('Mã: ' + code + '\n' + name + '\n\n' + desc);
        }
    }

    function hideGlobalCouponModal() {
        const modalEl = document.getElementById('globalCouponModal');
        if (typeof bootstrap !== 'undefined') {
            const modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) modal.hide();
        } else if (window.jQuery && window.jQuery.fn.modal) {
            window.jQuery(modalEl).modal('hide');
        }
    }

    function copyCoupon(code, color) {
        navigator.clipboard.writeText(code).then(() => {
            showCouponToast('Mã: ' + code, color || '#10b981');
        });
    }

    let toastTimer = null;
    function showCouponToast(msg, color) {
        const toast = document.getElementById('couponToast');
        const msgEl = document.getElementById('couponToastMsg');
        const progress = document.getElementById('couponToastProgress');
        const icon = document.querySelector('#couponToast .toast-icon');
        
        if (!toast || !msgEl || !progress) return;

        // Reset state
        msgEl.innerText = msg;
        
        // Success Green for the toast status
        const successGreen = '#10b981';
        toast.style.borderLeftColor = successGreen;
        progress.style.backgroundColor = successGreen;
        if(icon) icon.style.color = successGreen;
        
        toast.classList.remove('show');
        progress.style.transition = 'none';
        progress.style.width = '100%';
        
        // Trigger reflow
        void toast.offsetWidth;

        // Show
        toast.classList.add('show');
        
        // Start progress
        setTimeout(() => {
            progress.style.transition = 'width 3s linear';
            progress.style.width = '0';
        }, 50);

        if (toastTimer) clearTimeout(toastTimer);
        toastTimer = setTimeout(() => {
            toast.classList.remove('show');
        }, 3000);
    }

    function copyCouponFromGlobalModal() {
        const code = document.getElementById('globalModalCode').innerText;
        // Get the current accent color from the modal icon or button
        const color = document.getElementById('globalModalIcon').style.color;
        copyCoupon(code, color);
        hideGlobalCouponModal();
    }
</script>
@endonce
