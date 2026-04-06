<div class="{{ !empty($content['full_width']) ? 'container-fluid px-0' : 'container' }} pt-4">
    <div class="rich-text-content position-relative">
        @php
            $body = $content['body'] ?? '';
            $showToc = $content['show_toc'] ?? false;
            $tocPosition = $content['toc_position'] ?? 'top'; // top, left, right
            $isArticleLayout = $content['is_article_layout'] ?? false;
            $relatedCategoryId = $content['related_category_id'] ?? null;
            $postTitle = $post->title ?? '';
            $postThumbnail = $post->thumbnail ?? '';
            $tocHtml = '';

            if ($showToc && !empty($body)) {
                // Sử dụng \App\Services\TOCGenerator
                $options = [
                    'list_type' => 'ul',
                    'toc_class' => 'toc-list list-unstyled mb-0',
                    'toc_item_class' => 'mb-2',
                    'toc_link_class' => 'text-decoration-none text-dark toc-link hover-primary small d-block',
                    'min_level' => 2,
                    'max_level' => 4,
                    'enable_toggle' => true,
                    'display_style' => 'none',
                ];

                $generator = new \App\Services\TOCGenerator($body, $options);
                $tocListHtml = $generator->generateTOC();

                if (!empty($tocListHtml)) {
                    $tocHtml .=
                        '<div class="custom-toc-container p-4 rounded-4 bg-light border ' .
                        ($tocPosition === 'top' ? 'mb-4' : '') .
                        '">';
                    $tocHtml .= $tocListHtml;
                    $tocHtml .= '</div>';

                    // Lấy nội dung body đã được gán id
                    $body = $generator->getProcessedHtml();
                }
            }

            $headerHtml = '';
            if ($isArticleLayout) {
                if ($postThumbnail) {
                    $headerHtml .=
                        '<img src="' .
                        asset($postThumbnail) .
                        '" alt="' .
                        htmlspecialchars($postTitle) .
                        '" class="w-100 mb-4" style="border-radius: 12px; object-fit: cover; max-height: 500px;">';
                }
                if ($postTitle) {
                    // Xác định màu sắc: dùng màu từ mảng $content hoặc mặc định là '#004a80'
                    $textColor = $content['text_color'] ?? '#004a80';

                    $headerHtml .=
                        '<h1 class="fw-bold mb-4 fs-4" style="color: ' .
                        $textColor .
                        ';">' .
                        htmlspecialchars($postTitle) .
                        '</h1>';
                }
            }
        @endphp

        @if ($isArticleLayout)
            <div class="row gx-lg-5">
                <div class="col-lg-8">
                    {!! $headerHtml !!}

                    @if (!empty($tocHtml))
                        @if ($tocPosition === 'top')
                            {!! $tocHtml !!}
                            <div class="content-body">
                                {!! $body !!}
                            </div>
                        @else
                            <div class="row {{ $tocPosition === 'left' ? 'flex-row' : 'flex-row-reverse' }}">
                                <div class="col-md-5 mb-4 mb-md-0 d-none d-md-block">
                                    <div class="sticky-toc" style="top: 100px; position: sticky;">
                                        {!! $tocHtml !!}
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="d-block d-md-none mb-4">
                                        {!! $tocHtml !!}
                                    </div>
                                    <div class="content-body">
                                        {!! $body !!}
                                    </div>
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="content-body">
                            {!! $body !!}
                        </div>
                    @endif
                </div>

                <div class="col-lg-4 mt-5 mt-lg-0">
                    {{-- Sidebar bài viết liên quan --}}
                    @php
                        $relatedPostsQuery = \App\Models\Post::where('status', 'published');
                        if (isset($post) && $post->id) {
                            $relatedPostsQuery->where('id', '!=', $post->id);
                        }
                        if (!empty($relatedCategoryId)) {
                            $relatedPostsQuery->where('category_id', $relatedCategoryId);
                        }
                        // Loại trừ bài viết đang chọn
                        $recentPosts = $relatedPostsQuery->latest()->take(5)->get();
                    @endphp

                    <div class="sticky-sidebar" style="top: 100px; position: sticky;">
                        <h5 class="fw-bold text-uppercase mb-2"
                            style="color: {{ $content['text_color'] ?? '#004a80' }}; font-size: 1.25rem;">Bài viết mới
                            nhất</h5>
                        <div style="width: 40px; height: 4px; background-color: #d1d5db; margin-bottom: 1.5rem;"></div>

                        <div class="related-posts d-flex flex-column gap-4">
                            @foreach ($recentPosts as $p)
                                <a href="{{ route('posts.show', $p->slug ?: $p->id) }}"
                                    class="text-decoration-none group rounded-4 overflow-hidden d-block article-related-item shadow-sm"
                                    style="background-color: #fafafa; transition: transform 0.3s ease, box-shadow 0.3s ease;">
                                    @if ($p->thumbnail)
                                        <img src="{{ asset($p->thumbnail) }}" alt="{{ $p->title }}"
                                            class="w-100 m-0"
                                            style="height: 220px; object-fit: cover; display: block; filter: brightness(0.98);">
                                    @endif
                                    <div class="p-4"
                                        style="border: 1px solid #f0f0f0; border-top: none; border-radius: 0 0 16px 16px;">
                                        <div class="text-secondary small mb-2 d-flex align-items-center"
                                            style="font-size: 13px;">
                                            <i class="far fa-calendar-alt me-1"></i>:
                                            {{ $p->created_at->format('d/m/Y') }}
                                        </div>
                                        <h6 class="fw-bold lh-base"
                                            style="color: {{ $content['text_color'] ?? '#004a80' }}; font-size: 16px; text-transform: uppercase;">
                                            {{ $p->title }}</h6>
                                        @if (!empty($p->summary))
                                            <p class="text-secondary mb-0 mt-3"
                                                style="font-size: 14.5px; line-height: 1.5; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; text-transform: none;">
                                                {{ \Illuminate\Support\Str::limit(strip_tags($p->summary), 150) }}
                                            </p>
                                        @endif
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @else
            @if (!empty($tocHtml))
                @if ($tocPosition === 'top')
                    {!! $tocHtml !!}
                    <div class="content-body">
                        {!! $body !!}
                    </div>
                @else
                    <div class="row gx-lg-5 {{ $tocPosition === 'left' ? 'flex-row' : 'flex-row-reverse' }}">
                        <div class="col-lg-3 mb-4 mb-lg-0 d-none d-lg-block">
                            <div class="sticky-toc" style="top: 100px; position: sticky;">
                                {!! $tocHtml !!}
                            </div>
                        </div>
                        <div class="col-lg-9">
                            <div class="d-block d-lg-none mb-4">
                                {!! $tocHtml !!}
                            </div>
                            <div class="content-body">
                                {!! $body !!}
                            </div>
                        </div>
                    </div>
                @endif
            @else
                <div class="content-body">
                    {!! $body !!}
                </div>
            @endif
        @endif
    </div>
</div>

<style>
    .rich-text-content h1,
    .rich-text-content h2,
    .rich-text-content h3 {
        margin-top: 2rem;
        margin-bottom: 1.25rem;
        color: #1a202c;
    }

    .rich-text-content h1:first-child,
    .rich-text-content h2:first-child {
        margin-top: 0;
    }

    .rich-text-content img {
        max-width: 100%;
        height: auto;
        border-radius: 12px;
        margin: 1.5rem 0;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }

    .rich-text-content p {
        margin-bottom: 1.25rem;
    }


    /* TOC Styles */
    .custom-toc-container {
        width: 100%;
    }

    .sticky-toc {
        max-height: calc(100vh - 120px);
        overflow-y: auto;
    }

    .sticky-toc::-webkit-scrollbar {
        width: 4px;
    }

    .sticky-toc::-webkit-scrollbar-thumb {
        background-color: #cbd5e1;
        border-radius: 4px;
    }

    .toc-link {
        transition: color 0.2s;
        display: block;
        line-height: 1.4;
    }

    .toc-link:hover,
    .hover-primary:hover {
        color: #0d6efd !important;
    }

    .custom-toc-container ul ul {
        padding-left: 1.5rem;
        list-style: none;
        margin-top: 0.5rem;
        margin-bottom: 0.5rem;
    }

    .custom-toc-container ul ul li {
        margin-bottom: 0.5rem;
    }

    /* Style for marker lines using CSS pseudo-elements (Optional) */
    .custom-toc-container>ul>li {
        position: relative;
    }

    .toc-header:hover h6 {
        color: #0d6efd !important;
    }

    .toc-header.active .toc-toggle-icon {
        transform: rotate(180deg);
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Toggle TOC function defined globally to be used by onclick
        window.toggleTOC = function(header) {
            const $header = $(header);
            const $body = $header.next('.toc-body');

            if ($header.hasClass('active')) {
                // Đóng: trượt lên + mờ dần
                $header.removeClass('active');
                $body.animate({
                    height: 'hide',
                    opacity: 0
                }, 350);
            } else {
                // Mở: trượt xuống + rõ dần
                $header.addClass('active');
                $body.animate({
                    height: 'show',
                    opacity: 1
                }, 350);
            }
        };

        // Smooth scroll for TOC links
        document.querySelectorAll('.toc-link').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                const targetEl = document.querySelector(targetId);

                if (targetEl) {
                    // Offset cho fixed header nếu có 
                    const headerOffset = 80;
                    const elementPosition = targetEl.getBoundingClientRect().top;
                    const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

                    window.scrollTo({
                        top: offsetPosition,
                        behavior: "smooth"
                    });
                }
            });
        });
    });
</script>
