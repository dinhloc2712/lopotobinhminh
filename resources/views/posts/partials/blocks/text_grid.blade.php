@php
    $items_limit = !empty($content['items_limit']) ? (int)$content['items_limit'] : 5;
    $category_id = !empty($content['category_id']) ? $content['category_id'] : 2;
    
    // Typography Settings
    $title_color = !empty($content['title_color']) ? $content['title_color'] : '#001d3d';
    $title_size = !empty($content['title_font_size']) ? (int)$content['title_font_size'] : 20;
    $accent_color = !empty($content['accent_color']) ? $content['accent_color'] : '#e31824';

    $post_title_color = !empty($content['post_title_color']) ? $content['post_title_color'] : '#0f172a';
    $post_title_hover_color = !empty($content['post_title_hover_color']) ? $content['post_title_hover_color'] : $accent_color;
    $post_title_size = !empty($content['post_title_font_size']) ? (int)$content['post_title_font_size'] : 18;
    $post_title_small_size = !empty($content['post_title_small_font_size']) ? (int)$content['post_title_small_font_size'] : 15;

    $excerpt_color = !empty($content['excerpt_color']) ? $content['excerpt_color'] : '#64748b';
    $excerpt_size = !empty($content['excerpt_font_size']) ? (int)$content['excerpt_font_size'] : 14;
    $excerpt_clamp = isset($content['excerpt_line_clamp']) ? (int)$content['excerpt_line_clamp'] : 3;

    // View All Settings
    $va_text = !empty($content['view_all_text']) ? $content['view_all_text'] : 'XEM TẤT CẢ';
    $va_link = !empty($content['view_all_link']) ? $content['view_all_link'] : '#';
    $va_bg = !empty($content['view_all_bg_color']) ? $content['view_all_bg_color'] : '#ffffff';
    $va_color = !empty($content['view_all_text_color']) ? $content['view_all_text_color'] : $accent_color;
    $va_size = !empty($content['view_all_font_size']) ? (int)$content['view_all_font_size'] : 11;
    $va_radius = isset($content['view_all_border_radius']) ? (int)$content['view_all_border_radius'] : 50;

    // Frame & Display settings
    $frame_max_width = !empty($content['container_max_width']) ? $content['container_max_width'] : '1200px';
    if (is_numeric($frame_max_width)) $frame_max_width .= 'px';
    $frame_shadow = !empty($content['box_shadow']) && $content['box_shadow'] !== 'none' ? $content['box_shadow'] : '';
    $frame_radius = !empty($content['frame_border_radius']) ? $content['frame_border_radius'] : '8px';
    $padding_top = !empty($content['padding_top']) ? $content['padding_top'] : '40px';
    $padding_bottom = !empty($content['padding_bottom']) ? $content['padding_bottom'] : '40px';
    $padding_left = !empty($content['padding_left']) ? $content['padding_left'] : '0px';
    $padding_right = !empty($content['padding_right']) ? $content['padding_right'] : '0px';

    $query = \App\Models\Post::where('status', 'published');
    if ($category_id) {
        $query->where('category_id', $category_id);
    }
    
    // Exclude current post if viewing a post detail
    if (isset($post) && $post->id) {
        $query->where('id', '!=', $post->id);
    }
    
    $grid_posts = $query->with('blocks')->latest()->limit($items_limit)->get();

    // Helper function to get excerpt with fallback to blocks
    $getPostExcerpt = function($post) {
        $text = !empty($post->summary) ? $post->summary : '';
        
        if (empty($text) && $post->blocks) {
            foreach ($post->blocks as $block) {
                if ($block->type === 'text' && !empty($block->content['body'])) {
                    $text = $block->content['body'];
                    break;
                }
            }
        }
        
        if (empty($text)) return '';
        
        // Clean up text
        $text = strip_tags($text);
        $text = html_entity_decode($text);
        $text = str_replace(['&nbsp;', "\xc2\xa0"], ' ', $text); // Handle non-breaking spaces
        $text = trim(preg_replace('/\s+/', ' ', $text)); // Collapse multiple spaces
        
        return \Illuminate\Support\Str::limit($text, 250);
    };

    // Helper function to get correct image URL
    $getImageUrl = function($thumb) {
        if (!$thumb) return 'https://placehold.co/800x500?text=No+Image';
        if (str_contains($thumb, 'media/file/')) {
            $parts = explode('media/file/', $thumb);
            return url('media/file/' . end($parts));
        }
        if (!str_starts_with($thumb, 'http')) {
            return asset('storage/' . $thumb);
        }
        return $thumb;
    };
@endphp

<div class="text-grid-block" style="padding: {{ $padding_top }} {{ $padding_right }} {{ $padding_bottom }} {{ $padding_left }};">
    <div class="{{ !empty($content['full_width']) ? 'container-fluid px-4' : 'container' }}" 
         style="max-width: {{ $frame_max_width }} !important; margin: 0 auto;">
        
        {{-- Modern Industrial Section Header --}}
        <div class="section-header-industrial d-flex justify-content-between align-items-center mb-4 pb-2">
            <div class="d-flex align-items-center gap-2">
                <div class="accent-bar" style="background-color: {{ $accent_color }}; width: 4px; height: 24px;"></div>
                <h3 class="fw-bold mb-0 text-uppercase" style="color: {{ $title_color }}; font-size: {{ $title_size }}px; letter-spacing: 0.02em;">
                    {{ $content['title'] ?? 'TIN TỨC MỚI NHẤT' }}
                </h3>
            </div>
            <a href="{{ $va_link }}" 
               class="view-all-industrial text-decoration-none d-flex align-items-center transition-all p-0 border-0"
               style="background: none; color: {{ $va_color }};">
                <span class="fw-bold text-uppercase" style="font-size: {{ $va_size }}px; letter-spacing: 0.05em;">{{ $va_text }}</span>
            </a>
        </div>

        @if($grid_posts->isEmpty())
            <div class="text-center py-5 text-muted">
                <p>Chưa có tin tức nào được tìm thấy.</p>
            </div>
        @else
            @foreach($grid_posts->chunk(5) as $chunk)
                <div class="row g-4 mb-4">
                    {{-- Large Card --}}
                    @if($large_p = $chunk->first())
                        <div class="col-lg-6">
                            <div class="post-card-industrial large-card h-100 bg-white overflow-hidden border transition-all {{ $frame_shadow }}"
                                 style="border-color: #f1f5f9; border-radius: {{ $frame_radius }};">
                                
                                <a href="{{ route('posts.show', $large_p->slug) }}" class="d-block position-relative overflow-hidden" style="aspect-ratio: 16/10;">
                                    <img src="{{ $getImageUrl($large_p->thumbnail) }}" 
                                         alt="{{ $large_p->title }}" 
                                         class="w-100 h-100 transition-transform hover-scale" 
                                         style="object-fit: cover;">
                                </a>

                                <div class="p-4">
                                    <h4 class="fw-bold mb-3 line-clamp-2">
                                        <a href="{{ route('posts.show', $large_p->slug) }}" class="text-decoration-none transition-all hover-accent" style="color: {{ $post_title_color }}; font-size: {{ $post_title_size }}px;">
                                            {{ $large_p->title }}
                                        </a>
                                    </h4>
                                    <p class="mb-0 overflow-hidden" 
                                       style="color: {{ $excerpt_color }}; font-size: {{ $excerpt_size }}px; display: -webkit-box; -webkit-line-clamp: {{ $excerpt_clamp }}; -webkit-box-orient: vertical;">
                                        {{ $getPostExcerpt($large_p) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Small Grid --}}
                    @if($chunk->count() > 1)
                        <div class="col-lg-6">
                            <div class="row g-3 h-100">
                                @foreach($chunk->slice(1, 4) as $small_p)
                                    <div class="col-sm-6">
                                        <div class="post-card-industrial small-card h-100 bg-white overflow-hidden border transition-all {{ $frame_shadow }}"
                                             style="border-color: #f1f5f9; border-radius: {{ $frame_radius }}; d-flex flex-column;">
                                            
                                            <a href="{{ route('posts.show', $small_p->slug) }}" class="d-block position-relative overflow-hidden" style="aspect-ratio: 16/9;">
                                                <img src="{{ $getImageUrl($small_p->thumbnail) }}" 
                                                     alt="{{ $small_p->title }}" 
                                                     class="w-100 h-100 transition-transform hover-scale" 
                                                     style="object-fit: cover;">
                                            </a>

                                            <div class="p-3 flex-grow-1">
                                                <h6 class="fw-bold mb-0 line-clamp-2" style="line-height: 1.5;">
                                                    <a href="{{ route('posts.show', $small_p->slug) }}" class="text-decoration-none transition-all hover-accent" style="color: {{ $post_title_color }}; font-size: {{ $post_title_small_size }}px;">
                                                        {{ $small_p->title }}
                                                    </a>
                                                </h6>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        @endif
    </div>
</div>

<style>
.post-card-industrial {
    transition: transform 0.4s cubic-bezier(0.165, 0.84, 0.44, 1), box-shadow 0.4s ease;
}
.post-card-industrial:hover {
    transform: translateY(-5px);
    border-color: {{ $accent_color }}40 !important;
    box-shadow: none !important;
}
.hover-scale {
    transition: transform 0.8s cubic-bezier(0.165, 0.84, 0.44, 1);
}
.post-card-industrial:hover .hover-scale {
    transform: scale(1.08);
}
.hover-accent:hover {
    color: {{ $post_title_hover_color }} !important;
}
.view-all-industrial {
    /* No transition per user request */
}
.view-all-industrial:hover {
    color: {{ $accent_color }} !important;
    background: none !important;
    box-shadow: none !important;
}
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
