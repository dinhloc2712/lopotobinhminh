<footer class="footer py-5"
    style="color: {{ $content['text_color'] ?? '#ffffff' }}; background-color: {{ $content['bg_color'] ?? '#2D3748' }};">
    <div class="{{ !empty($content['full_width']) ? 'container-fluid px-0' : 'container' }} overflow-hidden">
        <div class="row g-5">
            {{-- Thông tin công ty --}}
            <div class="col-lg-4 text-start">
                @if (!empty($content['logo_url']))
                    <img loading="lazy" src="{{ $content['logo_url'] }}" alt="Logo" class="mb-4"
                        style="height: 50px; object-fit: contain;">
                @endif
                <p class="opacity-75 mb-4 pe-lg-4" style="line-height: 1.8;">
                    {{ $content['about_text'] ?? 'Vinayuuki - Đơn vị hàng đầu về giải pháp thiết kế và phát triển landing page chuyên nghiệp.' }}
                </p>

                {{-- Mạng xã hội --}}
                @if (!empty($content['socials']))
                    <div class="d-flex gap-3 social-links">
                        @foreach ($content['socials'] as $social)
                            <a href="{{ $social['url'] }}"
                                class="text-decoration-none bg-white bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center shadow-sm hover-translate-y"
                                style="width: 38px; height: 38px; color: inherit; transition: all 0.3s ease;">
                                <i class="{{ $social['icon'] ?? 'fab fa-facebook' }}"></i>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Các cột liên kết --}}
            @if (!empty($content['columns']))
                @foreach ($content['columns'] as $column)
                    <div class="col-lg-2 col-md-4 text-start">
                        <h6 class="fw-bold text-uppercase small mb-4 opacity-75">{{ $column['title'] ?? 'Danh mục' }}
                        </h6>
                        <ul class="list-unstyled mb-0">
                            @foreach ($column['links'] as $link)
                                <li class="mb-3">
                                    <a href="{{ $link['url'] }}"
                                        class="text-decoration-none opacity-50 hover-opacity-100 transition-all"
                                        style="color: inherit;">
                                        {{ $link['label'] ?? 'Liên kết' }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            @endif
        </div>

        <hr class="my-5 opacity-10">

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="small opacity-50">
                {{ $content['copyright'] ?? 'Copyright © 2026 VINAYUUKI - design by KBTECH' }}
            </div>
            <div class="small opacity-50 d-flex gap-4">
                <a href="#"
                    class="text-decoration-none text-white opacity-75 hover-opacity-100 transition-all">Chính sách bảo
                    mật</a>
                <a href="#"
                    class="text-decoration-none text-white opacity-75 hover-opacity-100 transition-all">Điều khoản dịch
                    vụ</a>
            </div>
        </div>
    </div>
</footer>

<style>
    .social-links a:hover {
        background-color: var(--bs-primary) !important;
        color: white !important;
        transform: translateY(-5px);
    }

    .hover-opacity-100:hover {
        opacity: 1 !important;
    }

    .transition-all {
        transition: all 0.3s ease;
    }
</style>
