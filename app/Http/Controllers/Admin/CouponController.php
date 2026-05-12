<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $this->authorize("view_coupon");
        $query = \App\Models\Coupon::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            });
        }

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Sort
        $sortColumn = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        $allowedSorts = ['code', 'name', 'type', 'discount_amount', 'discount_percentage', 'status', 'start_date', 'expiry_date', 'created_at'];
        
        if (in_array($sortColumn, $allowedSorts)) {
            $query->orderBy($sortColumn, $sortOrder);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $coupons = $query->paginate($request->get('per_page', 10));

        return view('admin.coupons.index', compact('coupons', 'sortColumn', 'sortOrder'));
    }

    public function create()
    {
        $this->authorize("create_coupon");
        return view('admin.coupons.create');
    }

    public function store(Request $request)
    {
        $this->authorize("create_coupon");
        $data = $request->validate([
            'code' => 'required|unique:coupons,code',
            'name' => 'nullable|string',
            'description' => 'nullable|string',
            'type' => 'required|in:fixed,percent',
            'discount_amount' => 'nullable|numeric|min:0',
            'discount_percentage' => 'nullable|integer|min:0|max:100',
            'quantity' => 'required|integer|min:0',
            'min_order_value' => 'nullable|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'user_usage_limit' => 'required|integer|min:1',
            'start_date' => 'nullable|date',
            'expiry_date' => 'nullable|date',
            'status' => 'required|in:active,inactive,expired',
            'priority' => 'nullable|integer',
        ]);

        // Sanitize data
        $data['discount_amount'] = $request->input('type') === 'fixed' ? ($request->discount_amount ?? 0) : 0;
        $data['discount_percentage'] = $request->input('type') === 'percent' ? ($request->discount_percentage ?? 0) : 0;
        $data['min_order_value'] = $request->min_order_value ?? 0;
        $data['max_discount_amount'] = $request->max_discount_amount ?? 0;

        \App\Models\Coupon::create($data);

        return redirect()->route('admin.coupons.index')->with('success', 'Tạo mã giảm giá thành công');
    }

    public function edit(\App\Models\Coupon $coupon)
    {
        $this->authorize("update_coupon");
        return view('admin.coupons.edit', compact('coupon'));
    }

    public function update(Request $request, \App\Models\Coupon $coupon)
    {
        $this->authorize("update_coupon");
        $data = $request->validate([
            'code' => 'required|unique:coupons,code,' . $coupon->id,
            'name' => 'nullable|string',
            'description' => 'nullable|string',
            'type' => 'required|in:fixed,percent',
            'discount_amount' => 'nullable|numeric|min:0',
            'discount_percentage' => 'nullable|integer|min:0|max:100',
            'quantity' => 'required|integer|min:0',
            'min_order_value' => 'nullable|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'user_usage_limit' => 'required|integer|min:1',
            'start_date' => 'nullable|date',
            'expiry_date' => 'nullable|date',
            'status' => 'required|in:active,inactive,expired',
            'priority' => 'nullable|integer',
        ]);

        // Sanitize data
        $data['discount_amount'] = $request->input('type') === 'fixed' ? ($request->discount_amount ?? 0) : 0;
        $data['discount_percentage'] = $request->input('type') === 'percent' ? ($request->discount_percentage ?? 0) : 0;
        $data['min_order_value'] = $request->min_order_value ?? 0;
        $data['max_discount_amount'] = $request->max_discount_amount ?? 0;

        $coupon->update($data);

        return redirect()->route('admin.coupons.index')->with('success', 'Cập nhật mã giảm giá thành công');
    }

    public function destroy(\App\Models\Coupon $coupon)
    {
        $this->authorize("delete_coupon");
        $coupon->delete();
        return redirect()->route('admin.coupons.index')->with('success', 'Đã xóa mã giảm giá');
    }
}
