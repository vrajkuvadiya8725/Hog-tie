@extends('layouts.app')

@section('title', 'Checkout - Hog Tie')

@section('content')
<h1 class="h3 mb-3">Checkout</h1>

<div class="row g-4">
    <div class="col-lg-7">
        <form id="checkoutForm" action="{{ route('checkout.store') }}" method="POST" class="bg-white rounded shadow-sm p-3 p-md-4">
            @csrf

            <h2 class="h5 mb-3">Select Address</h2>
            @forelse($addresses as $address)
                <label class="d-block border rounded p-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="selected_address" value="existing:{{ $address->id }}" {{ old('selected_address', $loop->first ? 'existing:'.$address->id : null) === 'existing:'.$address->id ? 'checked' : '' }}>
                        <span class="form-check-label">
                            <strong>{{ $address->label }}</strong> - {{ $address->recipient_name }}, {{ $address->address_line }}, {{ $address->city }}, {{ $address->state }} - {{ $address->postal_code }}
                        </span>
                    </div>
                </label>
            @empty
                <p class="text-secondary">No saved addresses. Add a new address below.</p>
            @endforelse

            <div class="border rounded p-3 mt-3">
                <label class="form-check mb-3">
                    <input class="form-check-input" type="radio" name="selected_address" value="new" {{ old('selected_address') === 'new' || $addresses->isEmpty() ? 'checked' : '' }}>
                    <span class="form-check-label">Add New Address</span>
                </label>
                <div class="row g-2">
                    <div class="col-md-6"><input class="form-control" name="label" value="{{ old('label') }}" placeholder="Label (Home/Office)"></div>
                    <div class="col-md-6"><input class="form-control" name="recipient_name" value="{{ old('recipient_name', auth()->user()->name) }}" placeholder="Recipient name"></div>
                    <div class="col-12"><input class="form-control" name="phone" value="{{ old('phone') }}" placeholder="Phone"></div>
                    <div class="col-12"><input class="form-control" name="address_line" value="{{ old('address_line') }}" placeholder="Address line"></div>
                    <div class="col-md-4"><input class="form-control" name="city" value="{{ old('city') }}" placeholder="City"></div>
                    <div class="col-md-4"><input class="form-control" name="state" value="{{ old('state') }}" placeholder="State"></div>
                    <div class="col-md-4"><input class="form-control" name="postal_code" value="{{ old('postal_code') }}" placeholder="Postal code"></div>
                </div>
            </div>

            <h2 class="h5 mt-4 mb-3">Payment Method</h2>

            <div class="form-check mb-2">
                <input class="form-check-input" type="radio" name="payment_method" value="cod" checked>
                <label class="form-check-label">Cash on Delivery (COD)</label>
            </div>

            <div class="form-check mb-3">
                <input class="form-check-input" type="radio" name="payment_method" value="razorpay">
                <label class="form-check-label">Online (Razorpay)</label>
            </div>

            <button type="button" id="placeOrderBtn" class="btn btn-dark">Place Order</button>
        </form>
    </div>

    <div class="col-lg-5">
        <div class="bg-white rounded shadow-sm p-3 p-md-4">
            <h2 class="h5 mb-3">Order Summary</h2>
            @php $total = 0; @endphp
            @foreach($cart->items as $item)
                @php $line = $item->quantity * $item->product->price; $total += $line; @endphp
                <div class="d-flex justify-content-between border-bottom py-2">
                    <span>{{ $item->product->name }} x {{ $item->quantity }}</span>
                    <span>Rs {{ number_format((float) $line, 2) }}</span>
                </div>
            @endforeach
            <div class="d-flex justify-content-between pt-3">
                <strong>Total</strong>
                <strong>Rs {{ number_format((float) $total, 2) }}</strong>
            </div>
        </div>
    </div>
</div>

{{-- Razorpay Script --}}
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>
document.getElementById('placeOrderBtn').addEventListener('click', function () {

    let method = document.querySelector('input[name="payment_method"]:checked').value;

    if (method === 'cod') {
        document.getElementById('checkoutForm').submit();
    } 
    else if (method === 'razorpay') {

        var options = {
            "key": "{{ env('RAZORPAY_KEY') }}",
            "amount": "{{ $total * 100 }}",
            "currency": "INR",
            "name": "Hog Tie",
            "description": "Order Payment",
            "handler": function (response){

    let form = document.getElementById('checkoutForm');

    let input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'razorpay_payment_id';
    input.value = response.razorpay_payment_id;

    form.appendChild(input);

    // submit form first
    form.submit();

    // redirect to homepage
    setTimeout(function(){
        window.location.href = "/";
    }, 1000);
}
        };

        var rzp = new Razorpay(options);
        rzp.open();
    }
});
</script>

@endsection