<?php

namespace App\Http\Controllers\Employee;

use App\Models\User;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{

    public function index()
    {
        return view('employees.home');
    }
}
