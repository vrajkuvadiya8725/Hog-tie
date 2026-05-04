@extends('layouts.app')

@section('title', 'My Orders')

@section('content')

<h1 class="h3 mb-4">My Orders</h1>

@if($orders->isEmpty())
    <div class="alert alert-info">
        No orders found.
    </div>
@else

    <div class="bg-white shadow-sm rounded p-3">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Order ID</th>
                    <th>Total</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>View</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>

                    {{-- ✅ IMAGE --}}
                    <td>
                        @php
                            $firstItem = $order->items->first();
                        @endphp

                        @if($firstItem && $firstItem->product_image)
                            <img src="{{ asset('storage/' . $firstItem->product_image) }}"
                                 width="60"
                                 height="60"
                                 style="object-fit: cover; border-radius: 6px;">
                        @else
                            <span class="text-muted">No Image</span>
                        @endif
                    </td>

                    {{-- ORDER ID --}}
                    <td>#{{ $order->id }}</td>

                    {{-- TOTAL --}}
                    <td>₹{{ number_format($order->total_amount, 2) }}</td>

                    {{-- PAYMENT --}}
                    <td>
                        @if($order->payment_status == 'paid')
                            <span class="badge bg-success">Paid</span>
                        @else
                            <span class="badge bg-warning">Pending</span>
                        @endif
                    </td>

                    {{-- STATUS --}}
                    <td>
                        <span class="badge bg-info">{{ ucfirst($order->status) }}</span>
                    </td>

                    {{-- DATE --}}
                    <td>{{ $order->created_at->format('d M Y') }}</td>

                    {{-- VIEW --}}
                    <td>
                        <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-dark">
                            View
                        </a>
                    </td>

                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endif

@endsection