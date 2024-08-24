<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>TheWiMS | @yield('breadcrumb')</title>
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"> --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/css/admin.css'])
</head>

<body>
    <div class="container-fluid h-100">
        <div class="row h-100">
            {{-- Side Nav --}}
            <aside class="sidebar">
                <div class="header">
                    <img src="{{ @asset('images/app-logo.svg') }}" alt="app-logo" class="app-logo">
                </div>
                <nav class="nav-items">
                    <a href="{{ route('admin.home') }}" class="{{ Route::is('admin.home') ? 'active' : '' }}">
                        <img src="{{ @asset('images/icons/ic-dashboard.svg') }}" alt="">
                        <span>Home</span>
                    </a>
                    <a href="{{ route('admin.stocks') }}" class="{{ Route::is('admin.stocks*') ? 'active' : '' }}">
                        <img src="{{ @asset('images/icons/ic-stocks.svg') }}" alt="">
                        <span>Stocks</span>
                    </a>
                    <a href="{{ route('admin.order') }}" class="{{ Route::is('admin.order*') ? 'active' : '' }}">
                        <img src="{{ @asset('images/icons/ic-orders.svg') }}" alt="">
                        <span>Orders</span>
                    </a>
                    <a href="{{ route('admin.shops') }}" class="{{ Route::is('admin.shops*') ? 'active' : '' }}">
                        <img src="{{ @asset('images/icons/ic-shops.svg') }}" alt="">
                        <span>Shops</span>
                    </a>
                    <a href="{{ route('admin.employees') }}"
                        class="{{ Route::is('admin.employees*') ? 'active' : '' }}">
                        <img src="{{ @asset('images/icons/ic-users.svg') }}" alt="">
                        <span>Employees</span>
                    </a>
                    <a href="{{ route('admin.categories') }}"
                        class="{{ Route::is('admin.categories*') ? 'active' : '' }}">
                        <img src="{{ @asset('images/icons/ic-category.svg') }}" alt="">
                        <span>Categories</span>
                    </a>
                </nav>
                <div class="footer">
                    <span href="{{ route('admin.stocks') }}" class="profile">
                        <span>
                            <img src="{{ @asset('images/icons/ic-user.svg') }}" alt="">
                            Hi, {{ Auth::user()->name }}
                        </span>
                        <a href="{{ route('auth.logout') }}" class="btn-logout">
                            <img src="{{ @asset('images/icons/ic-logout.svg') }}" alt="">
                        </a>    
                    </span>
                </div>
            </aside>
            {{-- Main Content --}}
            <div class="main-content">
                @yield('main-content')
            </div>
        </div>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
@yield('scripts')

</html>
