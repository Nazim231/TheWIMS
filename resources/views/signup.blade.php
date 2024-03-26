@extends('layouts.auth')

@section('breadcrumb')
    Sign Up
@endsection

@section('form_page')
    <form action="{{ route('auth.signup') }}" class="d-flex flex-column gap-4 p-4" method="POST">
        @csrf
        <div>
            <p class="display-3">Sign Up</p>
            <p class="text-muted">Let's create an account</p>
        </div>

        <div class="form-group">
            <label for="name">Full Name</label>
            <input type="text" class="form-control" name="name" id="name" value="{{ old('name')}}">
            @error('name')
                <p class="text-danger"> {{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" class="form-control" name="email" id="email" value="{{ old('email')}}">
            @error('email')
                <p class="text-danger"> {{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" name="password" id="password" value="{{ old('password')}}">
            @error('password')
                <p class="text-danger"> {{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="pasword_confirmation">Confirm Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" value="{{ old('password_confirmation')}}">
            @error('password_confirmation')
                <p class="text-danger"> {{ $message }}</p>
            @enderror
        </div>

        <div>
            <input type="submit" value="Create Account" class="btn btn-primary">
        </div>
    </form>
    <div class="text-center">
        <span>
            Already have an Account?
            <a href="{{ route('login') }}" class="btn btn-link">Login</a>
        </span>
    </div>
@endsection
