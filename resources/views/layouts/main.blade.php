<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>TheWiMS | @yield('breadcrumb')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        html,
        body {
            height: 100% !important;
        }

        .nav-link.active {
            background: white;
            color: black !important;
        }
    </style>
</head>

<body>
    <div class="container-fluid h-100">
        <div class="row h-100">
            {{-- Side Nav --}}
            <aside class="col-2 bg-dark h-100">
                <p class="display-4 text-white p-4">The<span class="fw-bold">WIMS</span></p>
                <nav class="nav flex-column gap-4">
                    <a href="{{ route('home') }}"
                        class="nav-link p-4 rounded text-white {{ Route::is('home') ? 'active' : '' }}">Home</a>
                    <a href="{{ route('shops') }}"
                        class="nav-link p-4 rounded text-white {{ Route::is('shops') ? 'active' : '' }}">Shops</a>
                    <a href="{{ route('employees') }}"
                        class="nav-link p-4 rounded text-white {{ Route::is('employees') ? 'active' : '' }}">Employees</a>
                    <a href="{{ route('categories') }}"
                        class="nav-link p-4 rounded text-white {{ Route::is('categories') ? 'active' : '' }}">Categories</a>
                    <a href="{{ route('stocks') }}"
                        class="nav-link p-4 rounded text-white {{ Route::is('stocks') ? 'active' : '' }}">Stocks</a>
                </nav>
            </aside>
            {{-- Main Content --}}
            <div class="col-10 p-4">
                @yield('main-content')
            </div>
        </div>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@yield('script')

</html>
