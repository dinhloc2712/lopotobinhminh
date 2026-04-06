<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Role;
use App\Models\Service;
use App\Models\User;
use App\Models\PaymentReceipt;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class OrdersManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('view_orders');
        $tab = $request->get('tab', 'orders');
        $orders = collect();
        $services = collect();

        $sortColumn = $request->get('sort_by', 'order_code');
        $sortOrder = $request->get('sort_order', 'asc');
        $stats = null;

        if ($tab === 'service') {
            $query = Service::query();

            if ($request->filled('search')) {
                $query->where('name', 'like', '%' . $request->search . '%');
            }

            if ($request->filled('category')) {
                $query->where('category', $request->category);
            }

            $limit = $request->input('per_page', 20);
            $services = $query->latest()->limit($limit)->get();
        } else {
            $query = Order::query()->with(['customer', 'referrer', 'service', 'payments']);

            if ($request->filled('search_order')) {
                $search = $request->search_order;

                $query->where(function ($q) use ($search) {
                    $q->where('order_code', 'like', "%{$search}%")
                        ->orWhereHas('customer', function ($q2) use ($search) {
                            $q2->where('name', 'like', "%{$search}%");
                        });
                });
            }

            if ($request->filled('search_status') && $request->search_status !== 'all') {
                $query->where('status', $request->search_status);
            }

            if ($request->filled('search_time') && $request->search_time !== 'all') {
                $now = Carbon::now();

                switch ($request->search_time) {
                    case 'month':
                        $query->whereMonth('created_at', $now->month)
                            ->whereYear('created_at', $now->year);
                        break;

                    case 'quarter':
                        $startOfQuarter = $now->copy()->startOfQuarter();
                        $endOfQuarter = $now->copy()->endOfQuarter();

                        $query->whereBetween('created_at', [$startOfQuarter, $endOfQuarter]);
                        break;

                    case 'year':
                        $query->whereYear('created_at', $now->year);
                        break;
                }
            }


            $allowedSorts = ['order_code', 'created_at'];

            if (in_array($sortColumn, $allowedSorts)) {
                $query->orderBy($sortColumn, $sortOrder);
            } else {
                $query->orderBy('order_code', 'asc');
            }

            $perPage = $request->input('per_page_order', 20);

            $paymentSubquery = PaymentReceipt::selectRaw('order_id, SUM(amount) as total_collected')->groupBy('order_id');
            $statsQuery = Order::query()
                ->leftJoin('services', 'orders.service_id', '=', 'services.id')
                ->leftJoinSub($paymentSubquery, 'pr', function ($join) {
                    $join->on('orders.id', '=', 'pr.order_id');
                });

            if ($request->filled('search_order')) {
                $search = $request->search_order;
                $statsQuery->where(function ($q) use ($search) {
                    $q->where('orders.order_code', 'like', "%{$search}%")
                        ->orWhereHas('customer', fn($q2) => $q2->where('name', 'like', "%{$search}%"));
                });
            }
            if ($request->filled('search_status') && $request->search_status !== 'all') {
                $statsQuery->where('orders.status', $request->search_status);
            }
            if ($request->filled('search_time') && $request->search_time !== 'all') {
                $now = Carbon::now();
                match ($request->search_time) {
                    'month'   => $statsQuery->whereMonth('orders.created_at', $now->month)->whereYear('orders.created_at', $now->year),
                    'quarter' => $statsQuery->whereBetween('orders.created_at', [$now->copy()->startOfQuarter(), $now->copy()->endOfQuarter()]),
                    'year'    => $statsQuery->whereYear('orders.created_at', $now->year),
                    default   => null,
                };
            }

            $stats = $statsQuery->selectRaw('
                COUNT(orders.id)              AS total_orders,
                COALESCE(SUM(services.amount), 0)     AS total_amount,
                COALESCE(SUM(services.commission), 0) AS total_commission,
                COALESCE(SUM((
                    SELECT SUM(amount)
                    FROM payment_receipts pr
                    WHERE pr.order_id = orders.id
                )), 0) AS total_collected
            ')->first();

            $orders = $query->latest()->paginate($perPage);
        }

        $allServices = Service::orderBy('name')->get();
        $referrers = User::with('role')->orderBy('name')->get(['id', 'name', 'code', 'role_id'])->where('role.name', 'refferer');
        $customers = User::with('role')->orderBy('name')->get(['id', 'name', 'phone', 'email', 'code', 'role_id'])->where('role.name', 'customer');

        $stats = $stats ?? null;

        return view('admin.orders-management.index', compact('orders', 'services', 'tab', 'allServices', 'referrers', 'customers', 'stats', 'sortColumn', 'sortOrder'));
    }

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
        $this->authorize('create_orders');
        $isNewUser = $request->input('is_new_user') === 'true';

        if ($isNewUser) {
            $request->validate([
                'new_customer_name'  => 'required|string|max:255',
                'new_customer_email' => 'required|email|max:255|unique:users,email',
                'new_customer_phone' => 'nullable|string|max:20',
                'service_id'         => 'required|exists:services,id',
                'referrer_id'        => 'nullable|exists:users,id',
            ], [
                'new_customer_name.required'  => 'Vui lòng nhập họ tên học viên.',
                'new_customer_email.required' => 'Vui lòng nhập email học viên.',
                'new_customer_email.unique'   => 'Email này đã tồn tại trong hệ thống.',
                'service_id.required'         => 'Vui lòng chọn gói dịch vụ.',
            ]);

            $customerRole = Role::firstOrCreate(
                ['name' => 'customer'],
                ['display_name' => 'Khách hàng', 'guard_name' => 'web']
            );

            $newUser = User::create([
                'name'     => $request->new_customer_name,
                'email'    => $request->new_customer_email,
                'phone'    => $request->new_customer_phone ?? null,
                'password' => Hash::make('password'),
                'role_id'  => $customerRole->id,
            ]);

            $customerId = $newUser->id;
        } else {
            $request->validate([
                'customer_id' => 'required|exists:users,id',
                'service_id'  => 'required|exists:services,id',
                'referrer_id' => 'nullable|exists:users,id',
            ], [
                'customer_id.required' => 'Vui lòng chọn khách hàng.',
                'service_id.required'  => 'Vui lòng chọn gói dịch vụ.',
            ]);

            $customerId = $request->customer_id;
        }

        $year      = now()->format('y');
        $lastOrder = Order::whereYear('created_at', now()->year)
            ->orderByDesc('id')
            ->first();

        $nextIndex = $lastOrder
            ? ((int) substr($lastOrder->order_code, -3)) + 1
            : 1;

        $orderCode = 'ORD-' . $year . '-' . str_pad($nextIndex, 3, '0', STR_PAD_LEFT);

        Order::create([
            'order_code'  => $orderCode,
            'customer_id' => $customerId,
            'service_id'  => $request->service_id,
            'referrer_id' => $request->referrer_id ?: null,
            'status'      => 'pending',
        ]);

        return redirect()
            ->route('admin.orders.index', ['tab' => 'orders'])
            ->with('success', "Tạo đơn hàng {$orderCode} thành công!");
    }

    public function show($id)
    {
        $this->authorize('view_orders');
        $order = Order::with(['customer', 'payments.creator', 'service', 'referrer'])
            ->findOrFail($id);

        $totalContractValue = $order->service->amount ?? 0;
        $totalPaid = $order->payments->sum('amount');
        $balance = $totalContractValue - $totalPaid;


        return response()->json([
            'success' => true,
            'data'    => $order,
            'meta'    => [
                'total_contract' => $totalContractValue,
                'total_paid'     => $totalPaid,
                'balance'        => $balance,
                'balance_formatted' => number_format($balance) . 'đ',
            ]
        ]);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
