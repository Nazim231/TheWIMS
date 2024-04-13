<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignupRequest;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return $this->showPageIfNotAuthenticated('login');
    }

    public function login(LoginRequest $req)
    {
        if (Auth::attempt(['email' => $req->email, 'password' => $req->password])) {
            return  redirect()->route(Auth::user()->is_admin ? 'admin.home' : 'employee.home');
        } else {
            return redirect()->back()->withErrors([
                'auth_failed' => 'The provided credentials do not match our records.'
            ])->withInput();
        }
    }

    public function showSignUp() {
        return $this->showPageIfNotAuthenticated('signup');
    }
    public function createAccount(SignupRequest $req)
    {
        $user = User::create($req->validated());
        Auth::login($user);
        return redirect('home');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    private function showPageIfNotAuthenticated($view) {
        if (Auth::check()) {
            return redirect()->back();
        } else {
            return view($view);
        }
    }
}
