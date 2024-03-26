@extends('layouts.auth')

@section('breadcrumb')
Login
@endsection

@section('form_page')
    <form action="{{ url('login') }}" method="POST" class="d-flex flex-column gap-4 p-4">
        <div>
            <p class="display-3">Login</p>
            <p class="text-muted">Let's get back to your account</p>
        </div>

        <div class="form-group">
            <label for="inputEmail">Email Address</label>
            <input type="email" name="inputEmail" id="inputEmail" class="form-control">
        </div>

        <div class="form-group">
            <label for="inputPassword">Password</label>
            <input type="password" name="inputPassword" id="inputPassword" class="form-control">
        </div>

        <div class="form-group">
            <input type="submit" value="Login" class="btn btn-primary px-4 py-2 w-auto">
        </div>
    </form>
@endsection
