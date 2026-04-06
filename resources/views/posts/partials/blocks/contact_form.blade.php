<div class="{{ !empty($content['full_width']) ? 'container-fluid px-0' : 'container' }}">
    @php
        $title = $content['title'];
        $description = $content['description'];
        $submitLabel = $content['submit_label'];
        $image = $content['image'];
    @endphp

    <div class="row align-items-center">
        {{-- Phần Form --}}
        <div class="col-lg-6 p-3">
            <div class="pe-lg-4">
                <h2 class="fw-bold mb-3" style="color: {{ $content['text_color'] ?? '#000000' }};">
                    {{ $title }}
                </h2>
                @if (!empty($description))
                    <p class="text-muted mb-4 opacity-75">
                        {{ $description }}
                    </p>
                @endif

                <form action="{{ route('contacts.store') }}" method="POST" class="contact-form">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6 text-start">
                            <input type="text" name="name"
                                class="form-control bg-light border-0 py-3 px-4 shadow-none rounded-4"
                                placeholder="Nhập họ tên..." required>
                        </div>
                        <div class="col-md-6 text-start">
                            <input type="text" name="phone_number"
                                class="form-control bg-light border-0 py-3 px-4 shadow-none rounded-4"
                                placeholder="Nhập số điện thoại..." required>
                        </div>
                        <div class="col-md-12 text-start">
                            <input type="email" name="email"
                                class="form-control bg-light border-0 py-3 px-4 shadow-none rounded-4"
                                placeholder="Nhập email dự phòng...">
                        </div>
                        <div class="col-md-12 text-start">
                            <textarea name="address" class="form-control bg-light border-0 py-3 px-4 shadow-none rounded-4" rows="3"
                                placeholder="Nhập địa chỉ của bạn..."></textarea>
                        </div>
                        <div class="col-12 mt-4">
                            <button type="submit"
                                class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow-lg hover-translate-y">
                                {{ $submitLabel }} <i class="fas fa-paper-plane ms-2"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Phần Hình Ảnh --}}
        <div class="col-lg-6 p-3">
            <div class="text-center position-relative">
                <div class="position-absolute top-50 start-50 translate-middle bg-primary bg-opacity-10 rounded-circle"
                    style="width: 400px; height: 400px; z-index: -1; filter: blur(50px);"></div>
                @if (!empty($image))
                    <img src="{{ $image }}" class="img-fluid rounded-4 hover-scale-1.05"
                        alt="Contact Illustration" style="max-height: 500px; object-fit: contain;">
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    .contact-form .form-control:focus {
        background-color: #fff !important;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1) !important;
    }

    .hover-translate-y {
        transition: transform 0.3s ease;
    }

    .hover-translate-y:hover {
        transform: translateY(-5px);
    }

    .hover-scale-1.05 {
        transition: transform 0.5s ease;
    }

    .hover-scale-1.05:hover {
        transform: scale(1.05);
    }
</style>
