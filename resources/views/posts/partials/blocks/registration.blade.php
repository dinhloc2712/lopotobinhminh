@php
    // Standardized premium layout values
    $maxWidth     = '900px';
    $borderRadius = '24px';
    $shadowClass  = 'shadow-lg';
    
    // Default Spacing
    $mt = '60px'; $mb = '60px';
    $pt = '40px'; $pb = '40px'; $pl = '40px'; $pr = '40px';

    $accentColor = $content['accent_color'] ?? '#004a80';
    $titleColor  = $content['title_color']  ?? '#0f172a';
    $btnTxtColor = $content['btn_text_color'] ?? '#ffffff';

    $title    = $content['title']    ?? 'ĐĂNG KÝ TÀI KHOẢN';
    $subtitle = $content['subtitle'] ?? 'Tham gia cùng cộng đồng Lốp Ô Tô Bình Minh để nhận ưu đãi đặc quyền.';
    $btnLabel = $content['button_label'] ?? 'Tạo tài khoản ngay';
    $image    = $content['image']    ?? null;
    $reverse  = !empty($content['reverse_layout']);

    $containerStyle = "margin: {$mt} auto;";
    $wrapperStyle   = "max-width: {$maxWidth}; margin-left: auto; margin-right: auto;";
    $cardStyle      = "background-color: #ffffff; border-radius: {$borderRadius}; padding: {$pt} {$pr} {$pb} {$pl}; border: none;";

    $bid = 'reg-' . ($block->id ?? uniqid());
@endphp

<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap">

<div id="{{ $bid }}" style="{{ $containerStyle }}" class="registration-block-wrapper">
    <div style="{{ $wrapperStyle }}">
        <div class="registration-card {{ $shadowClass }}" style="{{ $cardStyle }}">
            <div class="row g-5 align-items-center {{ $reverse ? 'flex-row-reverse' : '' }}">
                
                {{-- Left Side: Form --}}
                <div class="col-lg-7">
                    <div class="{{ $reverse ? 'ps-lg-2' : 'pe-lg-2' }}">
                        <div class="mb-4">
                            <h2 class="fw-bold mb-2" style="color: {{ $titleColor }}; letter-spacing: -0.5px; font-size: 1.8rem;">{{ $title }}</h2>
                            @if($subtitle)
                                <p class="text-muted mb-0" style="font-size: 0.95rem; line-height: 1.6;">{{ $subtitle }}</p>
                            @endif
                        </div>

                        @if (session('success'))
                            <div class="alert alert-success border-0 rounded-4 p-3 mb-4 d-flex align-items-center shadow-sm">
                                <i class="fas fa-check-circle me-2 fs-4"></i>
                                <div class="fw-semibold">{{ session('success') }}</div>
                            </div>
                        @endif

                        <form action="{{ route('register') }}" method="POST" class="needs-validation" novalidate>
                            @csrf
                            <input type="hidden" name="redirect_to" value="{{ $content['redirect_to'] ?? '' }}">
                            <div class="row g-3">
                                {{-- Identity Fields --}}
                                <div class="col-md-6">
                                    <label class="reg-label">Họ và tên <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="reg-input" placeholder="Nguyễn Văn A" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="reg-label">Số điện thoại <span class="text-danger">*</span></label>
                                    <input type="tel" name="phone_number" class="reg-input" placeholder="090 123 4567" required>
                                </div>
                                <div class="col-12">
                                    <label class="reg-label">Địa chỉ Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="reg-input" placeholder="email@example.com" required>
                                </div>

                                {{-- Password Fields --}}
                                <div class="col-md-6">
                                    <label class="reg-label">Mật khẩu <span class="text-danger">*</span></label>
                                    <div class="position-relative">
                                        <input type="password" name="password" id="{{ $bid }}-pass" class="reg-input pe-5" placeholder="••••••••" required>
                                        <button type="button" class="btn-toggle-pass" onclick="togglePass('{{ $bid }}-pass')">
                                            <i class="far fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="reg-label">Nhập lại mật khẩu <span class="text-danger">*</span></label>
                                    <input type="password" name="password_confirmation" class="reg-input" placeholder="••••••••" required>
                                </div>

                                {{-- Address Selection (4-part) --}}
                                <div class="col-12">
                                    <label class="reg-label">Địa chỉ thường trú <span class="text-danger">*</span></label>
                                    <div class="row g-2">
                                        <div class="col-md-4">
                                            <select name="province" id="{{ $bid }}-province" class="reg-select" required>
                                                <option value="" disabled selected>Tỉnh / Thành phố</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <select name="district" id="{{ $bid }}-district" class="reg-select" required disabled>
                                                <option value="" disabled selected>Quận / Huyện</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <select name="ward" id="{{ $bid }}-ward" class="reg-select" required disabled>
                                                <option value="" disabled selected>Phường / Xã</option>
                                            </select>
                                        </div>
                                        <div class="col-12 mt-2">
                                            <input type="text" name="street_address" class="reg-input" placeholder="Số nhà, tên đường, tổ/phố..." required>
                                        </div>
                                    </div>
                                </div>

                                {{-- CTA --}}
                                <div class="col-12 mt-4">
                                    <button type="submit" class="btn-reg-submit w-100 shadow-sm" style="background-color: {{ $accentColor }}; color: {{ $btnTxtColor }};">
                                        {{ $btnLabel }} <i class="fas fa-arrow-right ms-2"></i>
                                    </button>
                                    <p class="text-center mt-3 mb-0 small text-muted">
                                        Bạn đã có tài khoản? <a href="{{ route('admin.login') }}" class="fw-bold" style="color: {{ $accentColor }}; text-decoration: none;">Đăng nhập ngay</a>
                                    </p>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Right Side: Image/Graphics --}}
                <div class="col-lg-5 d-none d-lg-block">
                    <div class="text-center position-relative">
                        <div class="circle-decoration" style="background-color: {{ $accentColor }}08;"></div>
                        @if($image)
                            <img src="{{ $image }}" class="img-fluid rounded-4 position-relative" alt="Register" style="max-height: 400px; object-fit: contain; z-index: 2;">
                        @else
                            <div class="reg-placeholder-container position-relative" style="z-index: 2;">
                                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center shadow-sm" style="width: 240px; height: 240px; border: 10px solid #fff;">
                                    <i class="fas fa-user-plus text-primary opacity-25" style="font-size: 80px;"></i>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    #{{ $bid }} { font-family: 'Outfit', sans-serif !important; }
    #{{ $bid }} .registration-card { transition: all 0.3s ease; }
    
    #{{ $bid }} .reg-label {
        font-size: 0.75rem; 
        font-weight: 700; 
        text-transform: uppercase; 
        color: #64748b; 
        margin-bottom: 6px; 
        display: block;
        letter-spacing: 0.5px;
    }

    #{{ $bid }} .reg-input, #{{ $bid }} .reg-select {
        width: 100%;
        padding: 12px 16px;
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        font-size: 0.9rem;
        color: #1e293b;
        transition: all 0.2s ease;
    }

    #{{ $bid }} .reg-input:focus, #{{ $bid }} .reg-select:focus {
        background-color: #ffffff;
        border-color: {{ $accentColor }};
        box-shadow: 0 0 0 4px {{ $accentColor }}15;
        outline: none;
    }

    #{{ $bid }} .reg-select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%2394a3b8' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 12px center;
        background-size: 18px;
    }

    #{{ $bid }} .btn-toggle-pass {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        border: none;
        background: none;
        color: #94a3b8;
        padding: 4px;
        cursor: pointer;
    }

    #{{ $bid }} .btn-reg-submit {
        border-radius: 14px;
        padding: 14px;
        font-weight: 700;
        font-size: 1rem;
        border: none;
        transition: all 0.3s ease;
    }

    #{{ $bid }} .btn-reg-submit:hover {
        filter: brightness(1.1);
        transform: translateY(-2px);
        box-shadow: 0 10px 20px -5px {{ $accentColor }}40 !important;
    }

    #{{ $bid }} .circle-decoration {
        width: 380px;
        height: 380px;
        border-radius: 50%;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 1;
    }

    @media (max-width: 768px) {
        #{{ $bid }} { margin: 30px 15px !important; }
        #{{ $bid }} .registration-card { padding: 30px 20px !important; }
    }
</style>

<script>
    function togglePass(id) {
        const inp = document.getElementById(id);
        if (inp) {
            inp.type = inp.type === 'password' ? 'text' : 'password';
            const icon = event.currentTarget.querySelector('i');
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        }
    }

    (function() {
        const pSel = document.getElementById('{{ $bid }}-province');
        const dSel = document.getElementById('{{ $bid }}-district');
        const wSel = document.getElementById('{{ $bid }}-ward');

        // Fetch Provinces
        fetch('https://provinces.open-api.vn/api/p/')
            .then(res => res.json())
            .then(data => {
                data.forEach(p => {
                    const opt = new Option(p.name, p.code);
                    pSel.add(opt);
                });
            });

        pSel.addEventListener('change', function() {
            const code = this.value;
            dSel.disabled = true;
            wSel.disabled = true;
            dSel.innerHTML = '<option value="" disabled selected>Quận / Huyện</option>';
            wSel.innerHTML = '<option value="" disabled selected>Phường / Xã</option>';

            if (code) {
                fetch(`https://provinces.open-api.vn/api/p/${code}?depth=2`)
                    .then(res => res.json())
                    .then(data => {
                        data.districts.forEach(d => {
                            const opt = new Option(d.name, d.code);
                            dSel.add(opt);
                        });
                        dSel.disabled = false;
                    });
            }
        });

        dSel.addEventListener('change', function() {
            const code = this.value;
            wSel.disabled = true;
            wSel.innerHTML = '<option value="" disabled selected>Phường / Xã</option>';

            if (code) {
                fetch(`https://provinces.open-api.vn/api/d/${code}?depth=2`)
                    .then(res => res.json())
                    .then(data => {
                        data.wards.forEach(w => {
                            const opt = new Option(w.name, w.code);
                            wSel.add(opt);
                        });
                        wSel.disabled = false;
                    });
            }
        });
    })();
</script>
