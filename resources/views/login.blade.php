@extends('layouts.auth')

@section('breadcrumb')
    Login
@endsection

@section('form_page')
    @error('auth_failed')
        <div class="alert alert-danger">
            {{$message}}
        </div>
    @enderror
    <form action="{{ route('auth.login') }}" method="POST" class="d-flex flex-column gap-4 p-4">
        @csrf
        <div>
            <p class="display-3">Login</p>
            <p class="text-muted">Let's get back to your account</p>
        </div>

        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ old('email')}}">
            @error('email')
                <p class="text-danger mt-1 mb-0">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" class="form-control">
            @error('password')
                <p class="text-danger mt-1 mb-0">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <input type="submit" value="Login" class="btn btn-primary px-4 py-2 w-auto">
        </div>
    </form>
    <div class="text-center">
        <span>Don't have an Account? <a href="{{ route('signup')}}" class="btn btn-link">Create Account</a></span>
    </div>
@endsection
