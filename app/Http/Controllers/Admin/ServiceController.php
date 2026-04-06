<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create_services');
        $validated = $request->validate([
            'name'        => 'required|max:255',
            'category'    => 'required|in:studyAbroad,job,language,tourism',
            'amount'      => 'required|numeric',
            'commission'  => 'required|numeric',
            'duration'    => 'nullable',
            'description' => 'nullable',
        ]);

        Service::create($validated);

        return redirect()->back()->with([
            'success' => 'Thêm mới thành công!',
            'tab' => 'service'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->authorize('update_services');
        $validated = $request->validate([
            'name'        => 'required|max:255',
            'category'    => 'required|in:studyAbroad,job,language,tourism',
            'amount'      => 'required|numeric',
            'commission'  => 'required|numeric',
            'duration'    => 'nullable',
            'description' => 'nullable',
        ]);

        try {
            $service = Service::findOrFail($id);
            $service->update($validated);

            return redirect()->back()->with([
                'success' => 'Cập nhật gói dịch vụ thành công!',
                'tab' => 'service'
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->authorize('delete_services');
            $service = Service::find($id);

            if (!$service) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Không tìm thấy dịch vụ này.'
                ], 404);
            }

            if ($service->orders()->exists()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Không thể xóa dịch vụ này vì đã có đơn hàng liên quan.'
                ], 400);
            }

            $service->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Xóa dịch vụ thành công.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Có lỗi xảy ra, vui lòng thử lại sau.'
            ], 500);
        }
    }
}
