@extends('layouts.app')

@section('meta_title', $post->meta_title ?: $post->title)
@section('meta_description', $post->meta_description)
@section('meta_keywords', $post->meta_keywords)
@section('meta_image', $post->thumbnail ? asset($post->thumbnail) : null)

@section('content')
    @php
        $stickyOffset = 0;
        $courseInfoContent = $post->blocks->firstWhere('type', 'course_info')?->content ?? [];
    @endphp
    @foreach ($post->blocks as $block)
        @php
            $content = $block->content;
        @endphp
        <section id="block-{{ $block->id }}"
            class="block-section block-{{ $block->type }} {{ $block->type !== 'header' ? 'reveal' : 'sticky-top' }}" style="{{ $block->style }};
                                    @if ($block->type === 'header') z-index: {{ 1020 - $loop->index }}; 
                                    top: {{ $stickyOffset }}px; @endif">
            @if (!in_array($block->type, ['header', 'footer', 'hero_content', 'product_description']))
                @includeIf('posts.partials.blocks.shared_title', ['content' => $content])
            @endif
            @if ($block->type === 'post_grid')
                @includeIf('posts.partials.blocks.post_grid', ['content' => $content, 'courseInfoContent' => $courseInfoContent])
            @else
                @includeIf('posts.partials.blocks.' . $block->type, ['content' => $content])
            @endif
        </section>

        @php
            if ($block->type === 'header') {
                $stickyOffset += 70; // Cộng dồn chiều cao min-h của các header
            }
        @endphp
    @endforeach

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var offcanvasElements = document.querySelectorAll('.offcanvas');
            offcanvasElements.forEach(function (el) {
                el.addEventListener('show.bs.offcanvas', function () {
                    var section = this.closest('section.block-header');
                    if (section) {
                        section.dataset.originalZIndex = section.style.zIndex;
                        section.style.setProperty('z-index', '1060', 'important');
                    }
                });
                el.addEventListener('hidden.bs.offcanvas', function () {
                    var section = this.closest('section.block-header');
                    if (section) {
                        if (section.dataset.originalZIndex) {
                            section.style.zIndex = section.dataset.originalZIndex;
                        } else {
                            section.style.removeProperty('z-index');
                        }
                    }
                });
            });
        });
    </script>
@endsection