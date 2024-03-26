<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>TheWims | @yield('breadcrumb') </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        html, body {
        height: 100vh !important;
    }
    </style>
</head>
<body>
    <div class="container h-100 w-100 d-flex justify-content-center align-items-center">
        <div class="border rounded row w-100 h-75 align-items-center">
            <div class="col d-flex justify-content-center align-items-center">
                <p class="display-1">TheWIMS</p>
            </div>
            <div class="col border-start">
                @yield('form_page')
            </div>
        </div>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@yield('scripts')
</html>