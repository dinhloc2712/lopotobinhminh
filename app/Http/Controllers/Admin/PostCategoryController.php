<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\PostCategory;
use Illuminate\Support\Str;

class PostCategoryController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('view_category');
        $search = $request->input('search');
        $perPage = $request->input('per_page', 15);
        $sortColumn = $request->input('sort', 'created_at');
        $sortOrder = $request->input('order', 'desc');

        $query = PostCategory::withCount('posts')
            ->when($search, function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });

        // Allow sorting by posts_count is a bit tricky if using withCount, 
        // but standard columns are fine.
        if (in_array($sortColumn, ['name', 'slug', 'created_at', 'posts_count'])) {
            $query->orderBy($sortColumn, $sortOrder);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $categories = $query->paginate($perPage)->appends($request->all());

        return view('admin.post_categories.index', compact('categories', 'search', 'sortColumn', 'sortOrder'));
    }

    public function create()
    {
        $this->authorize('create_category');
        return view('admin.post_categories.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create_category');
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:post_categories,slug',
            'description' => 'nullable|string'
        ]);

        PostCategory::create($request->all());

        return redirect()->route('admin.post-categories.index')->with('success', 'Tạo chuyên mục thành công!');
    }

    public function edit(PostCategory $postCategory)
    {
        $this->authorize('update_category');
        return view('admin.post_categories.edit', compact('postCategory'));
    }

    public function update(Request $request, PostCategory $postCategory)
    {
        $this->authorize('update_category');
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:post_categories,slug,' . $postCategory->id,
            'description' => 'nullable|string'
        ]);

        $postCategory->update($request->all());

        return redirect()->route('admin.post-categories.index')->with('success', 'Cập nhật chuyên mục thành công!');
    }

    public function destroy(PostCategory $postCategory)
    {
        $this->authorize('delete_category');
        if ($postCategory->posts()->count() > 0) {
            return back()->with('error', 'Không thể xoá chuyên mục đang có bài viết!');
        }
        
        $postCategory->delete();
        return redirect()->route('admin.post-categories.index')->with('success', 'Xoá chuyên mục thành công!');
    }
}
