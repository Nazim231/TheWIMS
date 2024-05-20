<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>TheWiMS | @yield('breadcrumb')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/app.css'])
</head>

<body>
    <div class="container-fluid h-100">
        <div class="row h-100">
            {{-- Side Nav --}}
            <aside class="col-2 bg-dark h-100 d-flex flex-column">
                <p class="display-4 text-white p-4">The<span class="fw-bold">WIMS</span></p>
                <nav class="nav flex-column gap-4">
                    <a href="{{ route('admin.home') }}"
                        class="nav-link p-4 rounded text-white {{ Route::is('admin.home') ? 'active' : '' }}">Home</a>
                    <a href="{{ route('admin.stocks') }}"
                        class="nav-link p-4 rounded text-white {{ Route::is('admin.stocks*') ? 'active' : '' }}">Stocks</a>
                    <a href="{{ route('admin.order') }}"
                        class="nav-link p-4 rounded text-white {{ Route::is('admin.order*') ? 'active' : '' }}">Orders</a>
                    <a href="{{ route('admin.shops') }}"
                        class="nav-link p-4 rounded text-white {{ Route::is('admin.shops*') ? 'active' : '' }}">Shops</a>
                    <a href="{{ route('admin.employees') }}"
                        class="nav-link p-4 rounded text-white {{ Route::is('admin.employees*') ? 'active' : '' }}">Employees</a>
                    <a href="{{ route('admin.categories') }}"
                        class="nav-link p-4 rounded text-white {{ Route::is('admin.categories*') ? 'active' : '' }}">Categories</a>
                </nav>
                <a href="{{ route('auth.logout') }}" class="btn btn-outline-light mt-auto mb-4 p-3">Logout</a>
            </aside>
            {{-- Main Content --}}
            <div class="col-10 p-4">
                @yield('main-content')
            </div>
        </div>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
@yield('scripts')

</html>
