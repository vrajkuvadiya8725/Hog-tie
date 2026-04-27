<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
    public function show(Request $request, Order $order)
    {
        abort_unless($order->user_id === $request->user()->id, 403);
        $order->load('items');

        return view('orders.show', ['order' => $order]);
    }

    public function onlinePayment(Request $request, Order $order)
    {
        abort_unless($order->user_id === $request->user()->id, 403);
        $order->load('items');

        return view('orders.online-payment', ['order' => $order]);
    }

    public function completeOnlinePayment(Request $request, Order $order)
    {
        abort_unless($order->user_id === $request->user()->id, 403);

        $order->update([
            'payment_status' => 'paid',
            'status' => 'confirmed',
        ]);

        return redirect()->route('orders.show', $order)->with('success', 'Online payment completed successfully.');
    }

    public function invoice(Request $request, Order $order): Response
    {
        abort_unless($order->user_id === $request->user()->id, 403);
        $order->load('items');

        $html = view('orders.invoice', ['order' => $order])->render();

        return response($html, 200, [
            'Content-Type' => 'text/html',
            'Content-Disposition' => 'attachment; filename="hog-tie-invoice-order-'.$order->id.'.html"',
        ]);
    }
    public function myOrders()
{
    $orders = auth()->user()
        ->orders()
        ->with('items')
        ->latest()
        ->get();

    return view('orders.my-orders', compact('orders'));
}
}
