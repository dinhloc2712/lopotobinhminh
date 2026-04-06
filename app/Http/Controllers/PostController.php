<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Post;

class PostController extends Controller
{
    /**
     * Display the specified post.
     */
    public function show($slug)
    {
        $post = Post::where('slug', $slug)
            // ->where('status', 'published') // Tạm thời bỏ qua để bạn có thể xem bài ở trạng thái draft
            ->with(['blocks' => function ($query) {
                $query->orderBy('order', 'asc');
            }])
            ->firstOrFail();

        return view('posts.show', compact('post'));
    }

    /**
     * Search for posts.
     */
    public function search(\Illuminate\Http\Request $request)
    {
        $query = $request->input('q');
        $posts = Post::where('title', 'like', "%{$query}%")
            ->orWhere('summary', 'like', "%{$query}%")
            ->where('status', 'published')
            ->latest()
            ->paginate(12);

        return view('posts.search', compact('posts', 'query'));
    }
}
