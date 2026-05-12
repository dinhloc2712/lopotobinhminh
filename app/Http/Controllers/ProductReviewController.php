<?php

namespace App\Http\Controllers;

use App\Models\ProductReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating'     => 'required|integer|min:1|max:5',
            'comment'    => 'nullable|string|max:2000',
            'images.*'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120', // 5MB each
        ]);

        // Handle image uploads
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('reviews', 'public');
                $imagePaths[] = $path;
            }
        }

        ProductReview::create([
            'product_id' => $request->product_id,
            'user_id'    => auth()->id() ?? null,
            'rating'     => $request->rating,
            'comment'    => $request->comment,
            'images'     => count($imagePaths) > 0 ? $imagePaths : null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cảm ơn bạn! Đánh giá của bạn đã được ghi nhận.',
        ]);
    }
}
