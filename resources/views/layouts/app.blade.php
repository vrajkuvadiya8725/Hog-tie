<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Hog Tie')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .brand { font-weight: 700; letter-spacing: 0.5px; }
        .section-title { font-weight: 700; margin-bottom: 1rem; }
        .card-img-top { height: 180px; object-fit: cover; }
    </style>
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top">
        <div class="container">
            <a class="navbar-brand brand" href="{{ route('home') }}"><img src="{{ asset('images/logo.jpeg') }}" alt="Hog Tie" width="100" height="100"></a>
            <form class="d-flex flex-grow-1 mx-lg-4 my-2 my-lg-0" action="{{ route('home') }}" method="GET">
                <input class="form-control" type="search" name="search" placeholder="Search products..." value="{{ request('search') }}">
            </form>
            <div class="d-flex gap-2">
                @auth
                    <a href="{{ route('cart.index') }}" class="btn btn-outline-dark btn-sm">
                        Cart ({{ auth()->user()->cart?->items()->sum('quantity') ?? 0 }})
                    </a>
                    @if(auth()->check() && auth()->user()->is_admin)
    <a href="/admin" class="btn btn-dark">Admin</a>
@endif
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="btn btn-dark btn-sm" type="submit">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-dark btn-sm">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-dark btn-sm">Register</a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="py-4">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @yield('content')
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
