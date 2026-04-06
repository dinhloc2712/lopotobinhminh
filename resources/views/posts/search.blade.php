@extends('layouts.app')

@section('meta_title', 'Kết quả tìm kiếm cho: ' . ($query ?? ''))

@section('content')
<div class="container py-5">
    <div class="row mb-5">
        <div class="col-12 text-center">
            <h1 class="fw-bold mb-3">Kết quả tìm kiếm</h1>
            <p class="text-muted">Đang hiển thị kết quả cho: <strong>"{{ $query }}"</strong></p>
            <div class="mx-auto" style="max-width: 600px;">
                <form action="{{ route('search') }}" method="GET">
                    <div class="input-group rounded-pill overflow-hidden border shadow-sm">
                        <input type="text" name="q" class="form-control border-0 ps-4 py-3" 
                               value="{{ $query }}" placeholder="Tìm kiếm bài viết..." 
                               style="box-shadow: none;">
                        <button class="btn btn-primary px-4" type="submit">
                            <i class="fas fa-search me-2"></i> Tìm kiếm
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if($posts->count() > 0)
        <div class="row g-4">
            @foreach($posts as $post)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden" style="transition: transform 0.3s;">
                        @if($post->thumbnail)
                            <a href="{{ route('posts.show', $post->slug ?: $post->id) }}">
                                <img src="{{ asset($post->thumbnail) }}" class="card-img-top" alt="{{ $post->title }}" style="height: 200px; object-fit: cover;">
                            </a>
                        @endif
                        <div class="card-body p-4">
                            <h5 class="card-title fw-bold mb-3">
                                <a href="{{ route('posts.show', $post->slug ?: $post->id) }}" class="text-decoration-none text-dark">
                                    {{ $post->title }}
                                </a>
                            </h5>
                            @if($post->summary)
                                <p class="card-text text-secondary small mb-0">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($post->summary), 120) }}
                                </p>
                            @endif
                        </div>
                        <div class="card-footer bg-transparent border-0 p-4 pt-0">
                            <a href="{{ route('posts.show', $post->slug ?: $post->id) }}" class="btn btn-link p-0 text-primary fw-bold text-decoration-none small">
                                Xem thêm <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-5">
            {{ $posts->appends(['q' => $query])->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="fas fa-search-minus text-muted" style="font-size: 4rem;"></i>
            </div>
            <h3 class="fw-bold">Không tìm thấy kết quả nào</h3>
            <p class="text-muted">Rất tiếc, chúng tôi không tìm thấy bài viết nào phù hợp với từ khóa của bạn.</p>
            <a href="/" class="btn btn-outline-primary rounded-pill px-4 mt-3">Quay lại trang chủ</a>
        </div>
    @endif
</div>

<style>
    .card:hover {
        transform: translateY(-10px);
    }
    .pagination .page-link {
        border-radius: 50% !important;
        margin: 0 5px;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-color);
        border: 1px solid #dee2e6;
    }
    .pagination .page-item.active .page-link {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        color: white;
    }
</style>
@endsection
