@extends('layouts.app')

@section('title', 'Admin Dashboard - Hog Tie')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Hog Tie Admin Dashboard</h1>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h2 class="h5 mb-3">Add Category</h2>
                <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" class="row g-2">
                    @csrf
                    <div class="col-12"><input class="form-control" name="name" placeholder="Category name" required></div>
                    <div class="col-12"><input class="form-control" type="file" name="image" accept="image/*"></div>
                    <div class="col-12"><button class="btn btn-dark">Add Category</button></div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h2 class="h5 mb-3">Add Product</h2>
                <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="row g-2">
                    @csrf
                    <div class="col-12"><input class="form-control" name="name" placeholder="Product name" required></div>
                    <div class="col-6"><input class="form-control" type="number" min="1" name="quantity" placeholder="Default quantity" required></div>
                    <div class="col-6"><input class="form-control" type="number" min="0" name="stock" placeholder="Stock" required></div>
                    <div class="col-12"><input class="form-control" type="number" min="0" step="0.01" name="price" placeholder="Price" required></div>
                    <div class="col-12">
                        <select class="form-select" name="category_id" required>
                            <option value="">Select category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12"><input class="form-control" type="file" name="image" accept="image/*"></div>
                    <div class="col-12"><button class="btn btn-dark">Add Product</button></div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h2 class="h5 mb-3">Categories</h2>
                @forelse($categories as $category)
                    <div class="border rounded p-2 mb-2">
                        <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data" class="row g-2 mb-2">
                            @csrf @method('PUT')
                            <div class="col-12"><input class="form-control form-control-sm" name="name" value="{{ $category->name }}" required></div>
                            <div class="col-12"><input class="form-control form-control-sm" type="file" name="image" accept="image/*"></div>
                            <div class="col-auto"><button class="btn btn-sm btn-outline-primary">Edit</button></div>
                        </form>
                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </div>
                @empty
                    <p class="text-secondary mb-0">No categories yet.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h2 class="h5 mb-3">Products</h2>
                @forelse($products as $product)
                    <div class="border rounded p-2 mb-2">
                        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="row g-2 mb-2">
                            @csrf @method('PUT')
                            <div class="col-12"><input class="form-control form-control-sm" name="name" value="{{ $product->name }}" required></div>
                            <div class="col-6"><input class="form-control form-control-sm" type="number" min="1" name="quantity" value="{{ $product->quantity }}" required></div>
                            <div class="col-6"><input class="form-control form-control-sm" type="number" min="0" name="stock" value="{{ $product->stock }}" required></div>
                            <div class="col-12"><input class="form-control form-control-sm" type="number" min="0" step="0.01" name="price" value="{{ $product->price }}" required></div>
                            <div class="col-12">
                                <select class="form-select form-select-sm" name="category_id" required>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" @selected($product->category_id === $category->id)>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12"><input class="form-control form-control-sm" type="file" name="image" accept="image/*"></div>
                            <div class="col-auto"><button class="btn btn-sm btn-outline-primary">Edit</button></div>
                        </form>
                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </div>
                @empty
                    <p class="text-secondary mb-0">No products yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
