<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignupRequest;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(LoginRequest $req)
    {
        if (Auth::attempt(['email' => $req->email, 'password' => $req->password])) {
            return redirect('home');
        } else {
            return redirect()->back()->withErrors([
                'auth_failed' => 'The provided credentials do not match our records.'
            ])->withInput();
        }
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
}
