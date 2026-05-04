@extends('layouts.app')

@section('title', 'Order #'.$order->id.' - Hog Tie')

@section('content')
<div class="bg-white rounded shadow-sm p-3 p-md-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">Order #{{ $order->id }}</h1>
        <a href="{{ route('orders.invoice', $order) }}" class="btn btn-outline-dark btn-sm">Download Invoice</a>
    </div>
    <p class="mb-1"><strong>Payment Method:</strong> {{ strtoupper($order->payment_method) }}</p>
    <p class="mb-1"><strong>Payment Status:</strong> {{ ucfirst($order->payment_status) }}</p>
    <p class="mb-3"><strong>Delivery Address:</strong> {{ $order->recipient_name }}, {{ $order->address_line }}, {{ $order->city }}, {{ $order->state }} - {{ $order->postal_code }}</p>

    @foreach($order->items as $item)
        <div class="d-flex justify-content-between border-bottom py-2">
            <span>{{ $item->product_name }} x {{ $item->quantity }}</span>
            <span>Rs {{ number_format((float) $item->line_total, 2) }}</span>
        </div>
    @endforeach
    <div class="d-flex justify-content-between pt-3">
        <strong>Total</strong>
        <strong>Rs {{ number_format((float) $order->total_amount, 2) }}</strong>
    </div>
</div>
@endsection
