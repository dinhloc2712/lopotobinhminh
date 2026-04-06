@php
    $reviews   = !empty($content['reviews']) && is_array($content['reviews']) ? $content['reviews'] : [];
    $total     = count($reviews);
    $carouselId = 'testi-' . ($block->id ?? uniqid());
@endphp

<div class="{{ !empty($content['full_width']) ? 'container-fluid px-0' : 'container' }} pt-2">
    {{-- Section heading has been moved to shared_title --}}

    @if($total > 0)
    <div class="row align-items-stretch g-4">

        {{-- LEFT: big image + thumbnails on right arc --}}
        <div class="col-lg-5">
            {{-- Image area with thumbnails positioned around right arc --}}
            <div class="position-relative d-flex justify-content-center align-items-center" style="height:300px;">

                {{-- Background circle --}}
                <div class="position-absolute" style="width:260px;height:260px;border-radius:50%;background:linear-gradient(135deg,#e8f4fd,#c8e6f5);top:50%;left:40%;transform:translate(-50%,-50%);z-index:0;"></div>

                {{-- Main image --}}
                <div id="{{ $carouselId }}-img" style="position:absolute;z-index:1;top:50%;left:40%;transform:translate(-50%,-50%);">
                    @foreach($reviews as $i => $review)
                        <div class="testi-img-slide" style="display:{{ $i === 0 ? 'block' : 'none' }};">
                            @if(!empty($review['avatar']))
                                <img src="{{ $review['avatar'] }}"
                                     alt="{{ $review['author'] ?? '' }}"
                                     class="rounded-circle shadow-lg"
                                     style="width:230px;height:230px;object-fit:cover;">
                            @else
                                <div class="rounded-circle shadow-lg bg-light d-flex align-items-center justify-content-center"
                                     style="width:230px;height:230px;">
                                    <i class="fas fa-user fa-5x text-muted opacity-25"></i>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                {{-- Thumbnails: render tất cả, JS sẽ chọn 3 (prev/current/next) và đặt vị trí động --}}
                <div id="{{ $carouselId }}-thumbs" style="position:absolute;width:100%;height:100%;top:0;left:0;z-index:2;pointer-events:none;">
                    @foreach($reviews as $i => $review)
                        <div onclick="testiGoTo('{{ $carouselId }}', {{ $i }})"
                             id="{{ $carouselId }}-thumb-{{ $i }}"
                             class="testi-thumb"
                             data-index="{{ $i }}"
                             style="position:absolute;
                                    display:none;
                                    transform: translate(-50%, -50%);
                                    cursor:pointer;
                                    pointer-events:auto;
                                    transition:all .3s;">
                            @if(!empty($review['avatar']))
                                <img src="{{ $review['avatar'] }}"
                                     alt="{{ $review['author'] ?? '' }}"
                                     class="rounded-circle shadow"
                                     style="width:56px;height:56px;object-fit:cover;
                                            border:3px solid white;
                                            opacity:0.6;
                                            transition:all .3s;">
                            @else
                                <div class="rounded-circle bg-white shadow d-flex align-items-center justify-content-center"
                                     style="width:56px;height:56px;
                                            border:3px solid white;
                                            opacity:0.6;
                                            transition:all .3s;">
                                    <i class="fas fa-user text-muted"></i>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

            </div>
        </div>

        {{-- RIGHT: content carousel --}}
        <div class="col-lg-7 ps-lg-4 d-flex flex-column justify-content-center">
            <div id="{{ $carouselId }}-content">
                @foreach($reviews as $i => $review)
                    <div class="testi-content-slide" style="display:{{ $i === 0 ? 'block' : 'none' }};">
                        <h4 class="fw-bold mb-1">{{ $review['author'] ?? 'Khách hàng' }}</h4>

                        @if(!empty($review['subtitle']))
                            <p class="fw-semibold mb-3" style="color:green;font-size:.9rem;">{{ $review['subtitle'] }}</p>
                        @endif

                        <div class="position-relative mb-2" style="min-height:20px;">
                            <span style="font-size:6rem;line-height:1;color:#e8e8e8;font-family:Georgia,serif;position:absolute;top:-20px;right:0;">"</span>
                        </div>

                        <p class="text-muted lh-lg" style="font-size:1rem;font-style:italic;position:relative;z-index:1;">
                            {{ $review['quote'] ?? '' }}
                        </p>

                        <div class="d-flex gap-1 mt-3 mb-4">
                            @php $stars = intval($review['stars'] ?? 5); @endphp
                            @for($s = 1; $s <= 5; $s++)
                                <i class="fas fa-star {{ $s <= $stars ? 'text-warning' : 'text-muted opacity-25' }}"></i>
                            @endfor
                        </div>
                    </div>
                @endforeach
            </div>

            @if($total > 1)
            <div class="d-flex gap-2 mt-2">
                <button onclick="testiPrev('{{ $carouselId }}', {{ $total }})"
                        class="btn btn-light rounded-circle shadow-sm"
                        style="width:42px;height:42px;padding:0;">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button onclick="testiNext('{{ $carouselId }}', {{ $total }})"
                        class="btn btn-light rounded-circle shadow-sm"
                        style="width:42px;height:42px;padding:0;">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
            @endif
        </div>

    </div>
    @endif
</div>

<script>
(function() {
    var _testiCurrent = window._testiCurrent || {};
    window._testiCurrent = _testiCurrent;

    window.testiGoTo = function(id, index) {
        _testiCurrent[id] = index;

        // Update images
        var imgs = document.querySelectorAll('#' + id + '-img .testi-img-slide');
        imgs.forEach(function(el, i) { el.style.display = i === index ? 'block' : 'none'; });

        // Update content
        var contents = document.querySelectorAll('#' + id + '-content .testi-content-slide');
        contents.forEach(function(el, i) { el.style.display = i === index ? 'block' : 'none'; });

        // Update thumbnails: hiển thị 3 cái (prev, current, next), đặt vị trí arc động
        var total = document.querySelectorAll('#' + id + '-img .testi-img-slide').length;
        var arcRadius = 155;
        // 3 vị trí trên cung phải: trên, giữa, dưới
        var arcAngles = [-45, 0, 45];
        // Xác định 3 index cần hiển thị
        var slots;
        if (total === 1) {
            slots = [index];
        } else if (total === 2) {
            slots = [index, (index + 1) % total];
        } else {
            slots = [
                (index - 1 + total) % total,
                index,
                (index + 1) % total
            ];
        }
        // Ẩn tất cả thumb trước
        var allThumbs = document.querySelectorAll('#' + id + '-thumbs .testi-thumb');
        allThumbs.forEach(function(t) { t.style.display = 'none'; });
        // Hiển thị và đặt vị trí cho từng slot
        slots.forEach(function(reviewIdx, slotPos) {
            var thumb = document.getElementById(id + '-thumb-' + reviewIdx);
            if (!thumb) return;
            var angle = arcAngles[slotPos] || 0;
            var rad   = angle * Math.PI / 180;
            var tx    = arcRadius * Math.cos(rad);
            var ty    = arcRadius * -Math.sin(rad);
            thumb.style.display = 'block';
            thumb.style.left    = 'calc(40% + ' + Math.round(tx) + 'px)';
            thumb.style.top     = 'calc(50% + ' + Math.round(ty) + 'px)';
            var img = thumb.querySelector('img, div.rounded-circle');
            if (img) {
                if (reviewIdx === index) {
                    img.style.opacity = '1';
                    img.style.border  = '3px solid #0d6efd';
                    img.style.width   = '64px';
                    img.style.height  = '64px';
                } else {
                    img.style.opacity = '0.6';
                    img.style.border  = '3px solid white';
                    img.style.width   = '56px';
                    img.style.height  = '56px';
                }
            }
        });
    };

    window.testiNext = function(id, total) {
        var cur = (_testiCurrent[id] || 0) + 1;
        testiGoTo(id, cur >= total ? 0 : cur);
    };

    window.testiPrev = function(id, total) {
        var cur = (_testiCurrent[id] || 0) - 1;
        testiGoTo(id, cur < 0 ? total - 1 : cur);
    };

    // Init first thumb active style
    document.addEventListener('DOMContentLoaded', function() {
        var id = '{{ $carouselId }}';
        testiGoTo(id, 0);
    });
})();
</script>
