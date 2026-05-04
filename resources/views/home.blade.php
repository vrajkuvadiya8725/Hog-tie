@extends('layouts.app')

@section('title', 'Hog Tie - Corporate Gifting')

@section('content')
<section class="mb-5">
    <div class="p-4 p-md-5 bg-white rounded shadow-sm">
        <h1 class="display-6 fw-bold">Corporate gifting made easy with Hog Tie</h1>
        <p class="text-secondary mb-0">Browse curated gift bundles by category, configure quantities, and submit bulk requests in minutes.</p>
    </div>
</section>

<section class="mb-5">
    <h2 class="section-title">Products by Category</h2>
    @forelse($categories as $category)
        <div class="bg-white p-3 p-md-4 rounded shadow-sm mb-4">
            <div class="d-flex align-items-center gap-3 mb-3">
                @if($category->image_path)
                    <img src="{{ asset('storage/'.$category->image_path) }}" alt="{{ $category->name }}" width="56" height="56" class="rounded object-fit-cover">
                @endif
                <h3 class="h5 mb-0">{{ $category->name }}</h3>
            </div>
            <div class="row g-3">
                @forelse($category->products as $product)
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100">
                            @if($product->image_path)
                                <img src="{{ asset('storage/'.$product->image_path) }}" class="card-img-top" alt="{{ $product->name }}">
                            @endif
                            <div class="card-body">
                                <h4 class="h6">{{ $product->name }}</h4>
                                <p class="mb-2 text-secondary">Stock: {{ $product->stock }}</p>
                                <p class="fw-semibold mb-2">Price: Rs {{ number_format((float) $product->price, 2) }}</p>
                                @auth
                                    <form action="{{ route('cart.items.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <button class="btn btn-outline-secondary btn-sm qty-minus" type="button">-</button>
                                            <input type="number" name="quantity" min="1" max="{{ $product->stock }}" value="{{ min($product->quantity, $product->stock) }}" class="form-control form-control-sm text-center qty-input" style="max-width: 80px;">
                                            <button class="btn btn-outline-secondary btn-sm qty-plus" type="button">+</button>
                                        </div>
                                        <button class="btn btn-dark btn-sm w-100" type="submit">Add to Cart</button>
                                    </form>
                                @else
                                    <p class="text-secondary mb-0">Login to add this product to cart.</p>
                                @endauth
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-secondary mb-0">No products added in this category yet.</p>
                @endforelse
            </div>
        </div>
    @empty
        <div class="alert alert-info">No categories available yet. Add some from admin panel.</div>
    @endforelse
</section>

<section class="mb-5">
    <h2 class="section-title">FAQ</h2>
    <div class="accordion" id="faqAccordion">
        @foreach($faqs as $faq)
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq{{ $faq->id }}">
                        {{ $faq->question }}
                    </button>
                </h2>
                <div id="faq{{ $faq->id }}" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">{{ $faq->answer }}</div>
                </div>
            </div>
        @endforeach
    </div>
</section>

<section class="mb-5">
    <h2 class="section-title">Testimonials</h2>
    <div class="row g-3">
        @foreach($testimonials as $testimonial)
            <div class="col-md-6">
                <div class="bg-white p-3 rounded shadow-sm h-100">
                    <p class="mb-2">"{{ $testimonial->message }}"</p>
                    <p class="mb-0 text-secondary"><strong>{{ $testimonial->name }}</strong> - {{ $testimonial->designation }}</p>
                </div>
            </div>
        @endforeach
    </div>
</section>

<section class="mb-5">
    <h2 class="section-title">Our Clients Say</h2>
    <div class="row g-3">
        @foreach($clientQuotes as $quote)
            <div class="col-md-4">
                <div class="bg-white p-3 rounded shadow-sm h-100">
                    <p class="mb-2">"{{ $quote->quote }}"</p>
                    <p class="mb-0 text-secondary">- {{ $quote->client_name }}</p>
                </div>
            </div>
        @endforeach
    </div>
</section>

<section class="mb-3">
    <h2 class="section-title">Bulk Order Inquiry</h2>
    @auth
        <form method="POST" action="{{ route('bulk-inquiries.store') }}" class="bg-white p-4 rounded shadow-sm">
            @csrf
            <div class="row g-3">
                <div class="col-md-6"><input name="name" value="{{ old('name', auth()->user()->name) }}" class="form-control" placeholder="Name" required></div>
                <div class="col-md-6"><input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" class="form-control" placeholder="Email" required></div>
                <div class="col-md-6"><input name="phone" value="{{ old('phone') }}" class="form-control" placeholder="Phone"></div>
                <div class="col-md-6"><input type="number" min="1" name="quantity" value="{{ old('quantity') }}" class="form-control" placeholder="Quantity" required></div>
                <div class="col-12"><input name="address_line" value="{{ old('address_line') }}" class="form-control" placeholder="Address line" required></div>
                <div class="col-md-4"><input name="city" value="{{ old('city') }}" class="form-control" placeholder="City" required></div>
                <div class="col-md-4"><input name="state" value="{{ old('state') }}" class="form-control" placeholder="State" required></div>
                <div class="col-md-4"><input name="postal_code" value="{{ old('postal_code') }}" class="form-control" placeholder="Postal code" required></div>
                <div class="col-12"><textarea name="note" rows="3" class="form-control" placeholder="Additional details">{{ old('note') }}</textarea></div>
                <div class="col-12"><button class="btn btn-dark">Submit Inquiry</button></div>
            </div>
        </form>
    @else
        <div class="alert alert-warning">Please login to submit a bulk order inquiry.</div>
    @endauth
</section>

<script>
document.querySelectorAll('.qty-minus').forEach((button) => {
    button.addEventListener('click', () => {
        const input = button.closest('form').querySelector('.qty-input');
        input.value = Math.max(parseInt(input.min || 1, 10), parseInt(input.value || 1, 10) - 1);
    });
});
document.querySelectorAll('.qty-plus').forEach((button) => {
    button.addEventListener('click', () => {
        const input = button.closest('form').querySelector('.qty-input');
        const max = parseInt(input.max || 999999, 10);
        input.value = Math.min(max, parseInt(input.value || 1, 10) + 1);
    });
});
</script>
@endsection
