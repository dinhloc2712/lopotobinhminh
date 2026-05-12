<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\ProductCategory;
use Illuminate\Support\Str;

class ProductCategoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 15);
        $sortColumn = $request->input('sort', 'created_at');
        $sortOrder = $request->input('order', 'desc');

        $query = ProductCategory::withCount('products')
            ->when($search, function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });

        if (in_array($sortColumn, ['name', 'slug', 'created_at', 'products_count'])) {
            $query->orderBy($sortColumn, $sortOrder);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $categories = $query->paginate($perPage)->appends($request->all());

        return view('admin.product_categories.index', compact('categories', 'search', 'sortColumn', 'sortOrder'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:product_categories,slug',
            'description' => 'nullable|string'
        ]);

        ProductCategory::create($request->all());

        return redirect()->route('admin.product-categories.index')->with('success', 'Tạo chuyên mục sản phẩm thành công!');
    }

    public function update(Request $request, ProductCategory $productCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:product_categories,slug,' . $productCategory->id,
            'description' => 'nullable|string'
        ]);

        $productCategory->update($request->all());

        return redirect()->route('admin.product-categories.index')->with('success', 'Cập nhật chuyên mục sản phẩm thành công!');
    }

    public function destroy(ProductCategory $productCategory)
    {
        if ($productCategory->products()->count() > 0) {
            return back()->with('error', 'Không thể xoá chuyên mục đang có sản phẩm!');
        }
        
        $productCategory->delete();
        return redirect()->route('admin.product-categories.index')->with('success', 'Xoá chuyên mục sản phẩm thành công!');
    }
}
