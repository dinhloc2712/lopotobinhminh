<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentReceipt;
use App\Models\Order;
use Illuminate\Http\Request;

class PaymentReceiptController extends Controller
{
    public function store(Request $request)
    {
        $this->authorize('create_payment_receipt');
        $validated = $request->validate([
            'order_id'       => 'required|exists:orders,id',
            'amount'         => 'required|numeric|min:1',
            'payment_method' => 'required|string',
            'payment_date'   => 'required|date',
            'note'           => 'nullable|string',
        ]);

        $payment = PaymentReceipt::create([
            'order_id'       => $validated['order_id'],
            'amount'         => $validated['amount'],
            'payment_method' => $validated['payment_method'],
            'payment_date'   => $validated['payment_date'],
            'note'           => $validated['note'],
            'created_by'     => auth()->id(),
        ])->load('creator');

        $order = Order::with('service')->findOrFail($validated['order_id']);
        $totalContractValue = $order->service->amount ?? 0;
        $totalPaid = PaymentReceipt::where('order_id', $order->id)->sum('amount');

        $statusUpdated = false;
        if ($totalPaid >= $totalContractValue && $order->status !== 'completed') {
            $order->update(['status' => 'completed']);
            $statusUpdated = true;
        }

        return response()->json([
            'success' => true,
            'payment' => $payment,
            'status_updated' => $statusUpdated,
            'new_status' => $order->status
        ]);
    }

    public function update(Request $request, PaymentReceipt $paymentReceipt)
    {
        $this->authorize('update_payment_receipt');
        $validated = $request->validate([
            'amount'         => 'required|numeric|min:1',
            'payment_method' => 'required|string',
            'payment_date'   => 'required|date',
            'note'           => 'nullable|string',
        ]);

        $oldAmount = $paymentReceipt->amount;

        $paymentReceipt->update([
            'amount'         => $validated['amount'],
            'payment_method' => $validated['payment_method'],
            'payment_date'   => $validated['payment_date'],
            'note'           => $validated['note'],
        ]);

        $order = Order::with('service')->findOrFail($paymentReceipt->order_id);
        $totalContractValue = $order->service->amount ?? 0;
        $totalPaid = PaymentReceipt::where('order_id', $order->id)->sum('amount');

        $statusUpdated = false;
        if ($totalPaid >= $totalContractValue && $order->status !== 'completed') {
            $order->update(['status' => 'completed']);
            $statusUpdated = true;
        } elseif ($totalPaid < $totalContractValue && $order->status === 'completed') {
            $order->update(['status' => 'pending']);
            $statusUpdated = true;
        }

        return response()->json([
            'success' => true,
            'payment' => $paymentReceipt->load('creator'),
            'status_updated' => $statusUpdated,
            'new_status' => $order->status,
            'amount_diff' => $validated['amount'] - $oldAmount
        ]);
    }
}
