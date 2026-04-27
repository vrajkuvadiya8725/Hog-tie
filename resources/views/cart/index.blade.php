@extends('layouts.app')

@section('title', 'Your Cart - Hog Tie')

@section('content')
<h1 class="h3 mb-3">Your Cart</h1>

@if($cart->items->isEmpty())
    <div class="alert alert-info">Your cart is empty. Add products from the homepage.</div>
@else
    <div class="bg-white rounded shadow-sm p-3 p-md-4">
        @foreach($cart->items as $item)
            <div class="row align-items-center g-3 border-bottom py-3">
                <div class="col-md-2">
                    @if($item->product->image_path)
                        <img src="{{ asset('storage/'.$item->product->image_path) }}" class="img-fluid rounded" alt="{{ $item->product->name }}">
                    @endif
                </div>
                <div class="col-md-4">
                    <h2 class="h6 mb-1">{{ $item->product->name }}</h2>
                    <p class="mb-0 text-secondary">Price: Rs {{ number_format((float) $item->product->price, 2) }}</p>
                </div>
                <div class="col-md-3">
                    <form action="{{ route('cart.items.update', $item) }}" method="POST" class="d-flex gap-2">
                        @csrf
                        @method('PATCH')
                        <input type="number" name="quantity" min="1" max="{{ $item->product->stock }}" value="{{ $item->quantity }}" class="form-control form-control-sm">
                        <button class="btn btn-outline-primary btn-sm">Update</button>
                    </form>
                </div>
                <div class="col-md-2 text-md-end">
                    <strong>Rs {{ number_format((float) ($item->quantity * $item->product->price), 2) }}</strong>
                </div>
                <div class="col-md-1 text-md-end">
                    <form action="{{ route('cart.items.destroy', $item) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-outline-danger btn-sm">X</button>
                    </form>
                </div>
            </div>
        @endforeach
        <div class="d-flex justify-content-between align-items-center mt-4">
            <h3 class="h5 mb-0">Total: Rs {{ number_format((float) $totalAmount, 2) }}</h3>
            <a href="{{ route('checkout.create') }}" class="btn btn-dark">Proceed to Checkout</a>
        </div>
    </div>
@endif
@endsection
