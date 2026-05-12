@php
    $product = null;
    if (!empty($content['product_id'])) {
        $product = \App\Models\Product::find($content['product_id']);
    }

    $accentColor    = $content['accent_color'] ?? '#C92127';
    $title          = $content['title'] ?? 'ĐÁNH GIÁ SẢN PHẨM';
    $titleFontSize  = '16px';

    $avgRating    = $product ? $product->averageRating : 0;
    $reviewCount  = $product ? $product->reviewCount : 0;
    $distribution = $product ? $product->getReviewDistribution() : [1=>0, 2=>0, 3=>0, 4=>0, 5=>0];

    // Standardized premium layout values
    $maxWidth     = '1200px';
    $borderRadius = '12px';
    $shadowClass  = 'border'; // Fallback to border if no shadow needed
    
    // Default Spacing
    $mt = '40px'; $mb = '40px';
    $pt = '28px'; $pb = '28px'; $pl = '28px'; $pr = '28px';

    $containerStyle = "margin-top:{$mt}; margin-bottom:{$mb};";
    $wrapperStyle   = "max-width:{$maxWidth}; margin-left:auto; margin-right:auto;";
    $cardStyle      = "background-color:#ffffff !important; border-radius:{$borderRadius}; padding:{$pt} {$pr} {$pb} {$pl} !important;";

    $bid     = 'pr-' . uniqid();
    $modalId = 'reviewModal-' . $bid;
@endphp

@if(!$product)
{{-- Placeholder when no product selected --}}
<div style="{{ $containerStyle }}">
    <div style="{{ $wrapperStyle }}">
        <div class="bg-light border border-dashed rounded-3 p-4 text-center text-muted">
            <i class="fas fa-star fa-2x mb-2 d-block opacity-25"></i>
            <div class="fw-bold">Block Đánh giá Sản phẩm</div>
            <div class="small">Vui lòng chọn sản phẩm trong phần cài đặt block.</div>
        </div>
    </div>
</div>
@else

{{-- ====================== BLOCK ====================== --}}
<div id="{{ $bid }}" style="{{ $containerStyle }}">
    <div style="{{ $wrapperStyle }}">

        {{-- White Outer Card --}}
        <div class="bg-white {{ $shadowClass }}" style="border-radius:{{ $borderRadius }}; padding:{{ $pt }} {{ $pr }} {{ $pb }} {{ $pl }};">

                {{-- Section Title --}}
                <div class="pr-section-header mb-4">
                    <h5 class="pr-section-title mb-0" style="font-size:{{ $titleFontSize }} !important;">{{ $title }} {{ $product->name }}</h5>
                </div>

                {{-- Summary Card (inner) --}}
                <div class="pr-card" style="border-radius:10px; padding:20px 24px;">
                    <div class="row g-0 align-items-stretch">

                        {{-- Score --}}
                        <div class="col-12 col-md-4 pr-score-col text-center d-flex flex-column align-items-center justify-content-center py-3 py-md-0">
                            <div class="pr-score-big" style="color:{{ $accentColor }}">
                                {{ number_format($avgRating, 1) }}
                            </div>
                            <div class="pr-stars mb-2">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= floor($avgRating))
                                        <i class="fas fa-star pr-star-on"></i>
                                    @elseif($i - $avgRating < 1)
                                        <i class="fas fa-star-half-alt pr-star-on"></i>
                                    @else
                                        <i class="far fa-star pr-star-off"></i>
                                    @endif
                                @endfor
                            </div>
                            <div class="pr-count-text">{{ $reviewCount }} đánh giá</div>
                        </div>

                        {{-- Divider (desktop) --}}
                        <div class="col-auto d-none d-md-flex align-items-center">
                            <div style="width:1px; height:80%; background:#EDF2F7;"></div>
                        </div>
                        <div class="col-12 d-md-none">
                            <hr class="my-2" style="border-color:#EDF2F7;">
                        </div>

                        {{-- Bars --}}
                        <div class="col-12 col-md d-flex flex-column justify-content-center gap-2 ps-md-4 py-2 py-md-0">
                            @for($i = 5; $i >= 1; $i--)
                            @php
                                $cnt  = $distribution[$i] ?? 0;
                                $pct  = $reviewCount > 0 ? round(($cnt / $reviewCount) * 100) : 0;
                            @endphp
                            <div class="d-flex align-items-center gap-2">
                                <span class="pr-bar-label">{{ $i }}</span>
                                <i class="fas fa-star pr-bar-star"></i>
                                <div class="pr-bar-track flex-grow-1">
                                    <div class="pr-bar-fill" style="width:{{ $pct }}%; background:{{ $accentColor }};"></div>
                                </div>
                                <span class="pr-bar-count">{{ $cnt }}</span>
                            </div>
                            @endfor
                        </div>
                    </div>
                </div>

                {{-- CTA --}}
                <div class="text-center mt-4">
                    <p class="pr-cta-text mb-3">Bạn đã dùng sản phẩm này? Hãy để lại nhận xét!</p>
                    <button class="pr-btn-cta"
                        style="background:{{ $accentColor }};"
                        data-bs-toggle="modal" data-bs-target="#{{ $modalId }}">
                        <i class="fas fa-pen-alt me-2"></i>ĐÁNH GIÁ NGAY
                    </button>
                </div>

        </div>{{-- /white outer card --}}

    </div>{{-- /wrapperStyle --}}
</div>{{-- /bid --}}

{{-- ====================== POPUP MODAL ====================== --}}
<div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-labelledby="{{ $modalId }}-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:520px;">
        <div class="modal-content pr-modal-content">

            {{-- Header --}}
            <div class="pr-modal-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="pr-modal-icon" style="background:{{ $accentColor }}22;">
                        <i class="fas fa-star" style="color:{{ $accentColor }};"></i>
                    </div>
                    <div>
                        <h6 class="pr-modal-title mb-0" id="{{ $modalId }}-label">Viết đánh giá</h6>
                        <div class="pr-modal-subtitle">{{ $product->name }}</div>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>

            {{-- Form --}}
            <form id="reviewForm-{{ $bid }}"
                  class="review-submit-form"
                  data-product-id="{{ $product->id }}"
                  enctype="multipart/form-data"
                  novalidate>
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="rating" id="ratingInput-{{ $bid }}" value="0">

                <div class="pr-modal-body">

                    {{-- Stars --}}
                    <div class="pr-field-group">
                        <label class="pr-field-label">Chọn số sao <span class="text-danger">*</span></label>
                        <div class="pr-star-picker" id="starPicker-{{ $bid }}">
                            @for($i = 1; $i <= 5; $i++)
                                <button type="button" class="pr-star-btn" data-val="{{ $i }}">
                                    <i class="fas fa-star"></i>
                                </button>
                            @endfor
                        </div>
                        <div class="pr-rating-label" id="ratingLabel-{{ $bid }}">Nhấn vào sao để chọn điểm</div>
                    </div>

                    {{-- Comment --}}
                    <div class="pr-field-group">
                        <label class="pr-field-label">Nhận xét của bạn</label>
                        <textarea name="comment" class="pr-textarea"
                            placeholder="Chia sẻ trải nghiệm thực tế về sản phẩm..." rows="4"></textarea>
                    </div>

                    {{-- Images --}}
                    <div class="pr-field-group mb-0">
                        <label class="pr-field-label">Thêm ảnh <span class="text-muted fw-normal">(tuỳ chọn, tối đa 5 ảnh)</span></label>
                        <label class="pr-upload-zone" for="imgInput-{{ $bid }}">
                            <i class="fas fa-cloud-upload-alt pr-upload-icon"></i>
                            <span class="pr-upload-text">Nhấn để chọn hoặc kéo thả ảnh vào đây</span>
                            <span class="pr-upload-hint">JPG, PNG, WEBP · Mỗi ảnh tối đa 5MB</span>
                            <input type="file" id="imgInput-{{ $bid }}" name="images[]" multiple accept="image/*" class="d-none">
                        </label>
                        <div class="pr-img-preview" id="imgPreview-{{ $bid }}"></div>
                    </div>

                </div>

                {{-- Footer --}}
                <div class="pr-modal-footer">
                    <button type="button" class="pr-btn-cancel" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="pr-btn-submit" style="background:{{ $accentColor }};">
                        <span class="submit-text"><i class="fas fa-paper-plane me-2"></i>Gửi đánh giá</span>
                        <span class="submit-loading d-none">
                            <span class="spinner-border spinner-border-sm me-2"></span>Đang gửi...
                        </span>
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

{{-- ====================== STYLES ====================== --}}
<style>
/* ── Override section wrapper (legacy inline styles from DB) ── */
section.block-product_reviews {
    padding: 0 !important;
    margin: 0 !important;
    opacity: 1 !important;
    transform: none !important;
}

/* ── Base ───────────────────────────────── */
#{{ $bid }} { font-family: 'Inter', sans-serif !important; color: #2D3748; }

/* ── Section Header ─────────────────────── */
#{{ $bid }} .pr-section-header   { display:flex; align-items:center; gap:10px; }
#{{ $bid }} .pr-title-bar        { width:4px; height:22px; border-radius:4px; flex-shrink:0; }
#{{ $bid }} .pr-section-title    { font-size:1rem; font-weight:700; color:#2D3748; letter-spacing:.3px; font-family:'Montserrat',sans-serif; }

/* ── Card ───────────────────────────────── */
#{{ $bid }} .pr-card             { background:#fff; border:1px solid #e2e8f0; }

/* ── Score Column ───────────────────────── */
#{{ $bid }} .pr-score-big        { font-size:3.8rem; font-weight:800; line-height:1; font-family:'Montserrat',sans-serif; }
#{{ $bid }} .pr-stars            { font-size:1.2rem; }
#{{ $bid }} .pr-star-on          { color:#ffc107; }
#{{ $bid }} .pr-star-off         { color:#e2e8f0; }
#{{ $bid }} .pr-count-text       { font-size:.82rem; color:#718096; font-weight:500; }

/* ── Bar ────────────────────────────────── */
#{{ $bid }} .pr-bar-label        { font-size:.82rem; font-weight:700; color:#4A5568; min-width:12px; text-align:right; }
#{{ $bid }} .pr-bar-star         { font-size:.7rem; color:#ffc107; }
#{{ $bid }} .pr-bar-track        { height:7px; background:#EDF2F7; border-radius:99px; overflow:hidden; }
#{{ $bid }} .pr-bar-fill         { height:100%; border-radius:99px; transition:width .4s ease; }
#{{ $bid }} .pr-bar-count        { font-size:.78rem; color:#A0AEC0; min-width:16px; }

/* ── CTA ────────────────────────────────── */
#{{ $bid }} .pr-cta-text         { font-size:.88rem; color:#718096; font-weight:500; }
#{{ $bid }} .pr-btn-cta          {
    display:inline-flex; align-items:center; gap:6px;
    color:#fff; font-family:'Inter',sans-serif; font-weight:700; font-size:.88rem;
    letter-spacing:.4px; border:none; border-radius:8px;
    padding:11px 32px; cursor:pointer;
    transition:filter .2s, transform .2s, box-shadow .2s;
    box-shadow:0 2px 10px rgba(0,0,0,.12);
}
#{{ $bid }} .pr-btn-cta:hover    { filter:brightness(.9); transform:translateY(-1px); box-shadow:0 4px 16px rgba(0,0,0,.18); }
#{{ $bid }} .pr-btn-cta:active   { transform:translateY(0); }

/* ── Modal Shell ────────────────────────── */
#{{ $modalId }} .pr-modal-content   {
    border:0; border-radius:16px; overflow:hidden;
    font-family:'Inter',sans-serif; box-shadow:0 20px 60px rgba(0,0,0,.18);
    color:#2D3748;
}
#{{ $modalId }} .pr-modal-header    {
    display:flex; align-items:center; justify-content:space-between;
    padding:20px 24px 16px; border-bottom:1px solid #EDF2F7; background:#F7FAFC;
}
#{{ $modalId }} .pr-modal-icon      {
    width:42px; height:42px; border-radius:10px;
    display:flex; align-items:center; justify-content:center; font-size:1.1rem;
}
#{{ $modalId }} .pr-modal-title     { font-weight:700; font-size:1rem; color:#2D3748; font-family:'Montserrat',sans-serif; }
#{{ $modalId }} .pr-modal-subtitle  { font-size:.78rem; color:#A0AEC0; margin-top:1px; }
#{{ $modalId }} .pr-modal-body      { padding:20px 24px; background:#fff; }
#{{ $modalId }} .pr-modal-footer    {
    display:flex; align-items:center; justify-content:flex-end; gap:10px;
    padding:14px 24px; border-top:1px solid #EDF2F7; background:#F7FAFC;
}

/* ── Form Fields ────────────────────────── */
#{{ $modalId }} .pr-field-group  { margin-bottom:18px; }
#{{ $modalId }} .pr-field-label  { font-size:.75rem; font-weight:600; color:#718096; text-transform:uppercase; letter-spacing:.5px; display:block; margin-bottom:8px; }
#{{ $modalId }} .pr-textarea     {
    width:100%; border:1px solid #E2E8F0; background:#F7FAFC; border-radius:10px;
    padding:12px 14px; font-family:'Inter',sans-serif; font-size:.9rem; resize:none;
    transition:border-color .2s, box-shadow .2s; color:#2D3748; outline:none;
}
#{{ $modalId }} .pr-textarea:focus { border-color: {{ $accentColor }}; box-shadow:0 0 0 3px {{ $accentColor }}22; background:#fff; }

/* ── Star Picker ────────────────────────── */
#{{ $modalId }} .pr-star-picker  { display:flex; gap:6px; margin-bottom:6px; }
#{{ $modalId }} .pr-star-btn     {
    background:none; border:none; padding:2px; cursor:pointer;
    font-size:2rem; color:#E2E8F0; transition:color .15s, transform .15s;
}
#{{ $modalId }} .pr-star-btn.hovered,
#{{ $modalId }} .pr-star-btn.selected { color:#ffc107; transform:scale(1.12); }
#{{ $modalId }} .pr-rating-label { font-size:.8rem; color:#A0AEC0; height:18px; transition:color .2s; }

/* ── Upload Zone ────────────────────────── */
#{{ $modalId }} .pr-upload-zone  {
    display:flex; flex-direction:column; align-items:center; gap:6px;
    border:2px dashed #CBD5E0; border-radius:12px; padding:20px;
    cursor:pointer; background:#F7FAFC; transition:border-color .2s, background .2s;
}
#{{ $modalId }} .pr-upload-zone:hover { border-color:{{ $accentColor }}; background:#fff; }
#{{ $modalId }} .pr-upload-icon  { font-size:2rem; color:#CBD5E0; }
#{{ $modalId }} .pr-upload-text  { font-size:.88rem; font-weight:600; color:#4A5568; }
#{{ $modalId }} .pr-upload-hint  { font-size:.75rem; color:#A0AEC0; }

/* ── Image Previews ─────────────────────── */
#{{ $modalId }} .pr-img-preview  { display:flex; flex-wrap:wrap; gap:8px; margin-top:10px; }
#{{ $modalId }} .pr-preview-item {
    position:relative; width:68px; height:68px;
    border-radius:8px; overflow:hidden; border:1px solid #E2E8F0;
}
#{{ $modalId }} .pr-preview-item img { width:100%; height:100%; object-fit:cover; display:block; }
#{{ $modalId }} .pr-preview-remove {
    position:absolute; top:3px; right:3px; width:18px; height:18px;
    background:rgba(0,0,0,.55); color:#fff; border:none; border-radius:50%;
    font-size:8px; cursor:pointer; display:flex; align-items:center; justify-content:center;
    transition:background .15s;
}
#{{ $modalId }} .pr-preview-remove:hover { background:rgba(0,0,0,.8); }

/* ── Buttons ────────────────────────────── */
#{{ $modalId }} .pr-btn-cancel   {
    background:transparent; border:1px solid #E2E8F0; border-radius:8px;
    padding:9px 22px; font-family:'Inter',sans-serif; font-weight:600;
    font-size:.875rem; color:#718096; cursor:pointer; transition:all .2s;
}
#{{ $modalId }} .pr-btn-cancel:hover { background:#EDF2F7; border-color:#CBD5E0; }
#{{ $modalId }} .pr-btn-submit   {
    border:none; border-radius:8px; padding:9px 26px;
    font-family:'Inter',sans-serif; font-weight:700; font-size:.875rem;
    color:#fff; cursor:pointer; min-width:140px; display:inline-flex;
    align-items:center; justify-content:center; gap:4px; transition:filter .2s, box-shadow .2s;
    box-shadow:0 2px 8px rgba(0,0,0,.12);
}
#{{ $modalId }} .pr-btn-submit:hover   { filter:brightness(.9); box-shadow:0 4px 14px rgba(0,0,0,.2); }
#{{ $modalId }} .pr-btn-submit:disabled { opacity:.6; cursor:not-allowed; }
</style>

{{-- ====================== SCRIPT ====================== --}}
<script>
(function () {
    const bid     = '{{ $bid }}';
    const modalId = '{{ $modalId }}';
    const accent  = '{{ $accentColor }}';
    const labels  = { 0:'Nhấn vào sao để chọn điểm', 1:'Rất tệ 😞', 2:'Tệ 😕', 3:'Bình thường 😐', 4:'Tốt 😊', 5:'Tuyệt vời! 🤩' };

    /* ── Move modal to <body> ── */
    function mountModal() {
        const el = document.getElementById(modalId);
        if (el && el.parentNode !== document.body) document.body.appendChild(el);
    }
    document.readyState === 'loading'
        ? document.addEventListener('DOMContentLoaded', mountModal)
        : mountModal();

    /* ── Star picker ── */
    const picker    = document.getElementById('starPicker-' + bid);
    const ratingInp = document.getElementById('ratingInput-' + bid);
    const ratingLbl = document.getElementById('ratingLabel-' + bid);
    const starBtns  = picker ? [...picker.querySelectorAll('.pr-star-btn')] : [];
    let   curRating = 0;

    function paintStars(hoverVal) {
        starBtns.forEach(b => {
            const v = +b.dataset.val;
            b.classList.toggle('hovered',  v <= hoverVal && hoverVal > curRating);
            b.classList.toggle('selected', v <= curRating);
        });
    }

    starBtns.forEach(btn => {
        btn.addEventListener('mouseenter', () => paintStars(+btn.dataset.val));
        btn.addEventListener('mouseleave', () => paintStars(0));
        btn.addEventListener('click', () => {
            curRating = +btn.dataset.val;
            if (ratingInp) ratingInp.value = curRating;
            if (ratingLbl) { ratingLbl.textContent = labels[curRating]; ratingLbl.style.color = accent; }
            paintStars(0);
        });
    });

    /* ── Image upload + preview ── */
    const imgInput   = document.getElementById('imgInput-' + bid);
    const imgPreview = document.getElementById('imgPreview-' + bid);
    let   selFiles   = [];

    if (imgInput) {
        imgInput.addEventListener('change', function () {
            [...this.files].forEach(file => {
                if (selFiles.length >= 5 || !file.type.startsWith('image/')) return;
                selFiles.push(file);
                const r = new FileReader();
                r.onload = e => {
                    const wrap = document.createElement('div');
                    wrap.className = 'pr-preview-item';
                    wrap.innerHTML = `<img src="${e.target.result}" alt="preview"><button type="button" class="pr-preview-remove" data-name="${file.name}"><i class="fas fa-times"></i></button>`;
                    wrap.querySelector('.pr-preview-remove').onclick = function () {
                        selFiles = selFiles.filter(f => f.name !== this.dataset.name);
                        wrap.remove();
                    };
                    imgPreview.appendChild(wrap);
                };
                r.readAsDataURL(file);
            });
            this.value = '';
        });
    }

    /* ── Form submit ── */
    const form = document.getElementById('reviewForm-' + bid);
    if (form) {
        form.addEventListener('submit', async function (e) {
            e.preventDefault();
            if (curRating < 1) {
                if (ratingLbl) { ratingLbl.textContent = '⚠ Vui lòng chọn số sao!'; ratingLbl.style.color = '#e53e3e'; }
                return;
            }

            const submitBtn  = form.querySelector('.pr-btn-submit');
            const submitText = form.querySelector('.submit-text');
            const submitLoad = form.querySelector('.submit-loading');
            submitBtn.disabled = true;
            submitText.classList.add('d-none');
            submitLoad.classList.remove('d-none');

            const fd = new FormData(this);
            fd.delete('images[]');
            selFiles.forEach(f => fd.append('images[]', f));

            try {
                const res  = await fetch('{{ route("frontend.reviews.store") }}', {
                    method : 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body   : fd
                });
                const data = await res.json();

                if (data.success) {
                    bootstrap.Modal.getInstance(document.getElementById(modalId))?.hide();
                    form.reset();
                    selFiles = [];
                    if (imgPreview) imgPreview.innerHTML = '';
                    curRating = 0;
                    if (ratingInp) ratingInp.value = 0;
                    if (ratingLbl) { ratingLbl.textContent = labels[0]; ratingLbl.style.color = ''; }
                    paintStars(0);
                    alert('✅ Cảm ơn bạn! Đánh giá đã được ghi nhận.');
                } else {
                    alert('❌ ' + (data.message || 'Vui lòng thử lại.'));
                }
            } catch {
                alert('❌ Lỗi kết nối. Vui lòng kiểm tra mạng và thử lại.');
            } finally {
                submitBtn.disabled = false;
                submitText.classList.remove('d-none');
                submitLoad.classList.add('d-none');
            }
        });
    }

    /* ── Reset on modal close ── */
    document.addEventListener('hidden.bs.modal', function (e) {
        if (e.target.id !== modalId) return;
        curRating = 0;
        if (ratingInp) ratingInp.value = 0;
        if (ratingLbl) { ratingLbl.textContent = labels[0]; ratingLbl.style.color = ''; }
        paintStars(0);
    });
})();
</script>
@endif
