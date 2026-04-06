<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Post;
use App\Models\PostCategory;
use App\Models\PostBlock;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('view_post');
        $search = $request->input('search');
        $perPage = $request->input('per_page', 15);
        $sortColumn = $request->input('sort', 'created_at');
        $sortOrder = $request->input('order', 'desc');
        $status = $request->input('status');
        $categoryId = $request->input('category_id');

        $query = Post::with('category')->withCount('blocks')
            ->when($search, function ($q) use ($search) {
                $q->where(function($sub) use ($search) {
                    $sub->where('title', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%");
                });
            })
            ->when($status, function ($q) use ($status) {
                $q->where('status', $status);
            })
            ->when($categoryId, function ($q) use ($categoryId) {
                $q->where('category_id', $categoryId);
            });

        if (in_array($sortColumn, ['title', 'slug', 'status', 'created_at', 'blocks_count'])) {
            $query->orderBy($sortColumn, $sortOrder);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $posts = $query->paginate($perPage)->appends($request->all());
        $categories = PostCategory::all();

        return view('admin.posts.index', compact('posts', 'categories', 'search', 'sortColumn', 'sortOrder', 'status', 'categoryId'));
    }

    public function create()
    {
        $this->authorize('create_post');   
        $categories = PostCategory::all();
        return view('admin.posts.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $this->authorize('create_post');   
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:posts,slug',
            'category_id' => 'nullable|exists:post_categories,id'
        ]);

        $post = Post::create($request->all());

        return redirect()->route('admin.posts.edit', $post->id)->with('success', 'Tạo bài viết thành công! Hãy bắt đầu thêm các khối nội dung.');
    }

    public function edit(Post $post)
    {
        $this->authorize('update_post');   
        $post->load(['blocks' => function ($query) {
            $query->orderBy('order', 'asc');
        }]);
        $categories = PostCategory::all();
        return view('admin.posts.edit', compact('post', 'categories'));
    }

    public function update(Request $request, Post $post)
    {
        $this->authorize('update_post');   
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:posts,slug,' . $post->id,
            'category_id' => 'nullable|exists:post_categories,id'
        ]);

        $post->update($request->all());

        return back()->with('success', 'Cập nhật thông tin cơ bản thành công!');
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete_post');   
        $post->delete();
        return redirect()->route('admin.posts.index')->with('success', 'Xoá bài viết thành công!');
    }

    public function duplicate(Post $post)
    {
        $this->authorize('create_post');
        $newPost = $post->replicate();
        $newPost->title = $post->title . ' (Bản sao)';
        $newPost->slug = $post->slug . '-copy-' . time();
        $newPost->status = 'draft';
        $newPost->save();

        foreach ($post->blocks as $block) {
            $newBlock = $block->replicate();
            $newBlock->post_id = $newPost->id;
            $newBlock->save();
        }

        return redirect()->route('admin.posts.index')->with('success', 'Đã nhân bản bài viết thành công!');
    }

    /**
     * AJAX action to save blocks
     */
    public function saveBlocks(Request $request, Post $post)
    {
        $blocksData = $request->input('blocks', []);
        
        // Delete blocks not in the list if needed, but usually we just sync
        $post->blocks()->delete();

        foreach ($blocksData as $index => $data) {
            PostBlock::create([
                'post_id' => $post->id,
                'type' => $data['type'],
                'content' => $data['content'],
                'order' => $index
            ]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Return all posts with their blocks (for the copy-block source picker)
     */
    public function blocksSource(Request $request)
    {
        $excludeId = $request->query('exclude');
        $posts = Post::with(['blocks' => fn($q) => $q->orderBy('order')])
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->orderBy('title')
            ->get(['id', 'title']);

        $blockNames = [
            'header' => 'Header Điều hướng', 'footer' => 'Footer Chân trang',
            'hero_content' => 'Hero / Giới thiệu', 'text' => 'Văn bản / Tiêu đề',
            'image' => 'Hình ảnh', 'video' => 'Video', 'grid' => 'Bố cục (Grid)',
            'accordion' => 'FAQ / Accordion', 'pricing' => 'Bảng giá', 'banner' => 'Banner',
            'testimonial' => 'Khách hàng nói gì?', 'slider' => 'Trình chiếu (Slider)',
            'cta' => 'Nút kêu gọi (CTA)', 'marquee' => 'Băng chuyền',
            'globe' => 'Địa cầu 3D', 'contact_form' => 'Mẫu liên hệ',
            'office_map' => 'Hệ thống văn phòng (Bản đồ)',
            'spacer' => 'Khoảng cách', 'divider' => 'Đường kẻ',
        ];

        $data = $posts->map(fn($post) => [
            'id'     => $post->id,
            'title'  => $post->title,
            'blocks' => $post->blocks->map(fn($b) => [
                'id'    => $b->id,
                'type'  => $b->type,
                'label' => $blockNames[$b->type] ?? ucfirst($b->type),
                'order' => $b->order,
            ]),
        ]);

        return response()->json($data);
    }

    /**
     * Copy selected blocks from another post into this post
     */
    public function copyBlocksFrom(Request $request, Post $post)
    {
        $request->validate([
            'block_ids'   => 'required|array',
            'block_ids.*' => 'integer|exists:post_blocks,id',
        ]);

        $maxOrder = $post->blocks()->max('order') ?? -1;
        $blocks   = PostBlock::whereIn('id', $request->block_ids)->orderBy('order')->get();

        foreach ($blocks as $i => $block) {
            PostBlock::create([
                'post_id' => $post->id,
                'type'    => $block->type,
                'content' => $block->content,
                'order'   => $maxOrder + 1 + $i,
            ]);
        }

        return response()->json(['success' => true, 'copied' => $blocks->count()]);
    }
}
