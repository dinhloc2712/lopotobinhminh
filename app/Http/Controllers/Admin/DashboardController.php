<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $this->authorize('view_dashboard');


        // 5. Cơ cấu Nhân sự (Theo Role)
        $userStructureDB = User::with('role')
                                ->selectRaw('role_id, count(*) as total')
                                ->groupBy('role_id')
                                ->get();
                                
        $userStructure = ['labels' => [], 'series' => []];
        $totalUserStructure = 0;
        foreach ($userStructureDB as $item) {
            $label = $item->role ? ($item->role->display_name ?? $item->role->name) : 'Chưa phân quyền';
            $userStructure['labels'][] = $label;
            $userStructure['series'][] = $item->total;
            $totalUserStructure += $item->total;
        }
        
        $userStructureDetails = [];
        foreach ($userStructureDB as $item) {
            $label = $item->role ? ($item->role->display_name ?? $item->role->name) : 'Chưa phân quyền';
            $percent = $totalUserStructure > 0 ? round(($item->total / $totalUserStructure) * 100, 1) : 0;
            $userStructureDetails[] = [
                'name' => $label,
                'count' => $item->total,
                'percent' => $percent
            ];
        }
        
        // Handle empty structure
        if (empty($userStructure['labels'])) {
            $userStructure['labels'] = ['Chưa có dữ liệu'];
            $userStructure['series'] = [0];
        }

        return view('admin.dashboard', compact(
            'userStructure', 'userStructureDetails', 'totalUserStructure'
        ));
    }
}
