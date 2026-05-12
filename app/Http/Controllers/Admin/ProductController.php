<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 15);
        $sortColumn = $request->input('sort', 'created_at');
        $sortOrder = $request->input('order', 'desc');
        $status = $request->input('status');
        $categoryId = $request->input('category_id');

        $query = Product::with('category')
            ->when($search, function ($q) use ($search) {
                $q->where(function($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%");
                });
            })
            ->when($status, function ($q) use ($status) {
                $q->where('status', $status);
            })
            ->when($categoryId, function ($q) use ($categoryId) {
                $q->where('category_id', $categoryId);
            });

        if (in_array($sortColumn, ['name', 'status', 'created_at', 'price', 'stock'])) {
            $query->orderBy($sortColumn, $sortOrder);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $products = $query->paginate($perPage)->appends($request->all());
        $categories = ProductCategory::all();

        return view('admin.products.index', compact('products', 'categories', 'search', 'sortColumn', 'sortOrder', 'status', 'categoryId'));
    }

    public function create()
    {
        $categories = ProductCategory::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:product_categories,id',
            'price' => 'nullable|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'thumbnail' => 'nullable|image|max:2048',
            'images.*' => 'nullable|image|max:2048',
            'video_urls' => 'nullable|string',
        ]);

        $data = $request->except(['thumbnail', 'images', 'video_urls']);
        
        // Ensure numeric fields are not null since database doesn't allow null
        $data['price'] = $request->input('price') ?? 0;
        $data['sale_price'] = $request->input('sale_price') ?? 0;
        $data['stock'] = $request->input('stock') ?? 0;

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('products', 'public');
        }

        $videoUrls = [];
        if ($request->filled('video_urls')) {
            $lines = explode("\n", $request->input('video_urls'));
            foreach ($lines as $line) {
                $cleanLine = trim($line);
                if (filter_var($cleanLine, FILTER_VALIDATE_URL)) {
                    $videoUrls[] = $cleanLine;
                }
            }
        }

        $imagesData = [
            'video_urls' => $videoUrls,
            'gallery' => []
        ];

        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $file) {
                $images[] = $file->store('products', 'public');
            }
            $imagesData['gallery'] = $images;
        }
        $data['images'] = $imagesData;

        $product = Product::create($data);

        return redirect()->route('admin.products.index')->with('success', 'Thêm sản phẩm thành công!');
    }

    public function edit(Product $product)
    {
        $categories = ProductCategory::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:product_categories,id',
            'price' => 'nullable|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'thumbnail' => 'nullable|image|max:2048',
            'new_images.*' => 'nullable|image|max:2048',
            'video_urls' => 'nullable|string',
        ]);

        $data = $request->except(['thumbnail', 'images', 'new_images', 'video_urls', 'existing_images', 'delete_thumbnail']);
        
        // Ensure numeric fields are not null since database doesn't allow null
        $data['price'] = $request->input('price') ?? 0;
        $data['sale_price'] = $request->input('sale_price') ?? 0;
        $data['stock'] = $request->input('stock') ?? 0;

        if ($request->input('delete_thumbnail') == '1' || $request->hasFile('thumbnail')) {
            if ($product->thumbnail && Storage::disk('public')->exists($product->thumbnail)) {
                Storage::disk('public')->delete($product->thumbnail);
            }
            if ($request->input('delete_thumbnail') == '1') {
                $data['thumbnail'] = null;
            }
        }

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('products', 'public');
        }

        $oldImages = $product->images;
        $oldGallery = (is_array($oldImages) && isset($oldImages['gallery'])) ? $oldImages['gallery'] : (is_array($oldImages) && !isset($oldImages['video_url']) && !isset($oldImages['video_urls']) ? $oldImages : []);

        $videoUrls = [];
        if ($request->filled('video_urls')) {
            $lines = explode("\n", $request->input('video_urls'));
            foreach ($lines as $line) {
                $cleanLine = trim($line);
                if (filter_var($cleanLine, FILTER_VALIDATE_URL)) {
                    $videoUrls[] = $cleanLine;
                }
            }
        }

        $imagesData = [
            'video_urls' => $videoUrls
        ];

        $keptImages = $request->input('existing_images', []);
        
        $deletedImages = array_diff($oldGallery, $keptImages);
        foreach($deletedImages as $delImg) {
            if (Storage::disk('public')->exists($delImg)) {
                Storage::disk('public')->delete($delImg);
            }
        }

        $finalGallery = array_values($keptImages);

        if ($request->hasFile('new_images')) {
            foreach ($request->file('new_images') as $file) {
                $finalGallery[] = $file->store('products', 'public');
            }
        }
        
        $imagesData['gallery'] = $finalGallery;
        $data['images'] = $imagesData;

        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', 'Cập nhật sản phẩm thành công!');
    }

    public function destroy(Product $product)
    {
        if ($product->thumbnail && Storage::disk('public')->exists($product->thumbnail)) {
            Storage::disk('public')->delete($product->thumbnail);
        }
        $oldImages = $product->images;
        $oldGallery = (is_array($oldImages) && isset($oldImages['gallery'])) ? $oldImages['gallery'] : (is_array($oldImages) && !isset($oldImages['video_url']) && !isset($oldImages['video_urls']) ? $oldImages : []);
        foreach ($oldGallery as $oldImage) {
            if (Storage::disk('public')->exists($oldImage)) {
                Storage::disk('public')->delete($oldImage);
            }
        }
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Xoá sản phẩm thành công!');
    }
}
