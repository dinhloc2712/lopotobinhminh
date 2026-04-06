<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('view_branch');

        $search = $request->input('search');
        $status = $request->input('is_active');

        $query = Branch::withCount('departments');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhere('manager_name', 'like', "%{$search}%");
            });
        }

        if ($status !== null && $status !== '') {
            $query->where('is_active', $status);
        }

        $sortColumn = $request->get('sort_by', 'created_at');
        $sortOrder  = $request->get('sort_order', 'desc');
        $allowedSorts = ['name', 'code', 'is_active', 'created_at'];
        if (in_array($sortColumn, $allowedSorts)) {
            $query->orderBy($sortColumn, $sortOrder);
        } else {
            $query->latest();
        }

        $perPage  = $request->get('per_page', 15);
        $branches = $query->paginate($perPage)->withQueryString();

        return view('admin.branches.index', compact('branches', 'sortColumn', 'sortOrder'));
    }

    public function create()
    {
        $this->authorize('create_branch');
        return view('admin.branches.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create_branch');

        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'code'         => 'nullable|string|max:50|unique:branches,code',
            'address'      => 'nullable|string|max:500',
            'phone'        => 'nullable|string|max:20',
            'email'        => 'nullable|email|max:255',
            'manager_name' => 'nullable|string|max:255',
            'is_active'    => 'boolean',
            'notes'        => 'nullable|string',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        Branch::create($validated);

        return redirect()->route('admin.branches.index')
                         ->with('success', 'Thêm chi nhánh thành công.');
    }

    public function edit(Branch $branch)
    {
        $this->authorize('update_branch');
        return view('admin.branches.edit', compact('branch'));
    }

    public function update(Request $request, Branch $branch)
    {
        $this->authorize('update_branch');

        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'code'         => 'nullable|string|max:50|unique:branches,code,' . $branch->id,
            'address'      => 'nullable|string|max:500',
            'phone'        => 'nullable|string|max:20',
            'email'        => 'nullable|email|max:255',
            'manager_name' => 'nullable|string|max:255',
            'is_active'    => 'boolean',
            'notes'        => 'nullable|string',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $branch->update($validated);

        return redirect()->route('admin.branches.index')
                         ->with('success', 'Cập nhật chi nhánh thành công.');
    }

    public function destroy(Branch $branch)
    {
        $this->authorize('delete_branch');

        $branch->delete();

        return redirect()->route('admin.branches.index')
                         ->with('success', 'Xóa chi nhánh thành công.');
    }
}
