@php
    $blockId = $block->id ?? rand(1000, 9999);
    $categoryId = $content['category_id'] ?? null;
    $itemsPerPage = $content['items_per_page'] ?? null;
    $itemsLimit = $content['items_limit'] ?? null;
    $ajaxPagination = !empty($content['ajax_pagination']);

    $postsQuery = \App\Models\Post::with('blocks')->where('status', 'published')->latest();
    if (!empty($categoryId)) {
        $postsQuery->where('category_id', $categoryId);
    }
    if (!empty($itemsLimit)) {
        $postsQuery->limit($itemsLimit);
    }

    $pageName = 'page_' . $blockId;
    if (!empty($itemsPerPage) && $itemsPerPage > 0) {
        $postsList = $postsQuery
            ->paginate($itemsPerPage, ['*'], $pageName)
            ->withQueryString()
            ->fragment('post-grid-' . $blockId);
    } else {
        $postsList = $postsQuery->get();
    }
@endphp

<div id="post-grid-{{ $blockId }}" class="post-grid-container" data-ajax="{{ $ajaxPagination ? 'true' : 'false' }}">
    <div class="{{ !empty($content['full_width']) ? 'container-fluid px-0' : 'container' }}">
        @if ($postsList->count() > 0)
            <div class="row g-4 mb-5">
                @foreach ($postsList as $p)
                    @php
                        $displayType = $content['display_type'] ?? '';
                    @endphp
                    <div class="col-12 col-md-6 col-lg-4 d-flex">
                        <a href="{{ route('posts.show', $p->slug ?: $p->id) }}"
                            class="text-decoration-none group rounded-4 overflow-hidden d-block article-related-item shadow-sm w-100 d-flex flex-column"
                            style="background-color: #fafafa; transition: transform 0.3s ease, box-shadow 0.3s ease;">

                            @if ($p->thumbnail)
                                <img src="{{ asset($p->thumbnail) }}" alt="{{ $p->title }}" class="w-100 m-0"
                                    style="height: 220px; object-fit: cover; display: block; filter: brightness(0.98);">
                            @else
                                <div class="w-100 m-0 bg-secondary" style="height: 220px;"></div>
                            @endif

                            <div class="p-4 d-flex flex-column flex-grow-1"
                                style="border: 1px solid #f0f0f0; border-top: none; border-radius: 0 0 16px 16px;">

                                {{-- Hiển thị mặc định bài viết --}}
                                <h6 class="fw-bold lh-base mb-2"
                                    style="color: {{ $content['text_color'] ?? '#004a80' }}; font-size: 16px; text-transform: uppercase;">
                                    {{ $p->title }}
                                </h6>
                                @if (!empty($p->summary))
                                    <p class="text-secondary mb-0 mt-2"
                                        style="font-size: 14.5px; line-height: 1.5; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; text-transform: none;">
                                        {{ \Illuminate\Support\Str::limit(strip_tags($p->summary), 150) }}
                                    </p>
                                @endif
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

            @if (method_exists($postsList, 'hasPages') && $postsList->hasPages())
                <style>
                    /* Ẩn dòng chữ "Showing 1 to 10 of... " của Laravel Bootstrap 5 Pagination mặc định */
                    #post-grid-{{ $blockId }} .pagination-wrapper nav>div.d-sm-flex {
                        justify-content: center !important;
                    }

                    #post-grid-{{ $blockId }} .pagination-wrapper nav>div.d-sm-flex>div:first-child {
                        display: none !important;
                    }

                    /* Bỏ nền, bỏ viền của các nút phân trang */
                    #post-grid-{{ $blockId }} .pagination-wrapper .page-link {
                        border: none !important;
                        background: transparent !important;
                        color: #6c757d;
                        font-weight: 500;
                    }

                    /* Nổi bật số trang hiện tại */
                    #post-grid-{{ $blockId }} .pagination-wrapper .page-item.active .page-link {
                        background: transparent !important;
                        color: #034166 !important;
                        font-weight: bold;
                    }

                    /* Ẩn hoàn toàn các nút bị vô hiệu hóa (Trang 1 thì ẩn lùi, Trang cuối thì ẩn tới) */
                    #post-grid-{{ $blockId }} .pagination-wrapper .page-item.disabled {
                        display: none !important;
                    }
                </style>
                <div class="d-flex justify-content-center mt-4 mb-3 pagination-wrapper">
                    {{ $postsList->links('pagination::bootstrap-5') }}
                </div>
            @endif
        @else
            <div class="text-center py-5 text-muted">
                <i class="fas fa-folder-open mb-3 fs-3 text-secondary opacity-50"></i>
                <p class="mb-0">Chưa có bài viết nào trong mục này.</p>
            </div>
        @endif
    </div>
</div>

@if ($ajaxPagination)
    <script>
        (function() {
            const container = document.getElementById('post-grid-{{ $blockId }}');
            if (!container) return;

            container.addEventListener('click', function(e) {
                const link = e.target.closest('.pagination a');
                if (link) {
                    e.preventDefault();
                    const url = link.href;

                    // Thêm hiệu ứng loading đơn giản
                    container.style.opacity = '0.5';
                    container.style.pointerEvents = 'none';

                    fetch(url)
                        .then(r => r.text())
                        .then(html => {
                            const parser = new DOMParser();
                            const doc = parser.parseFromString(html, 'text/html');
                            const newContainer = doc.getElementById('post-grid-{{ $blockId }}');
                            if (newContainer) {
                                container.innerHTML = newContainer.innerHTML;
                                container.style.opacity = '1';
                                container.style.pointerEvents = 'auto';

                                // Cuộn lên đầu khối nếu cần
                                window.scrollTo({
                                    top: container.getBoundingClientRect().top + window.scrollY -
                                        100,
                                    behavior: 'smooth'
                                });
                            }
                        })
                        .catch(err => {
                            console.error('AJAX Pagination Error:', err);
                            window.location.href = url; // Fallback
                        });
                }
            });
        })();
    </script>
@endif
