@extends('layouts.app')

@section('title', 'Online Payment - Hog Tie')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="bg-white rounded shadow-sm p-4">
            <h1 class="h4 mb-3">Razorpay Online Payment (Demo)</h1>
            <p class="mb-2">Order: #{{ $order->id }}</p>
            <p class="mb-2">Amount: <strong>Rs {{ number_format((float) $order->total_amount, 2) }}</strong></p>
            <p class="mb-4">Reference: {{ $order->payment_reference }}</p>
            <form method="POST" action="{{ route('orders.online-payment.complete', $order) }}">
                @csrf
                <button class="btn btn-dark">Complete Payment</button>
                <a href="{{ route('orders.show', $order) }}" class="btn btn-outline-secondary">Back to Order</a>
            </form>
        </div>
    </div>
</div>
@endsection
