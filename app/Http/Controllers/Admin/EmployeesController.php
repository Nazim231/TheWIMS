<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Http\Requests\SignupRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class EmployeesController extends Controller
{
    public function showPage()
    {
        $employees = User::with('shop')->where('is_admin', 0)->get();
        return view('admin.employees', compact('employees'));
    }

    public function addEmployee(Request $req)
    {

        $validator = Validator::make($req->all(), [
            'email' => 'required | email | unique:users',
            'name' => 'required | min:3',
            'city' => 'required | min:3',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }

        $generatedPassword = Str::random(8);

        $userData = [
            'email' => $req->email,
            'password' => Hash::make($generatedPassword),
            'name' => $req->name,
            'city' => $req->city,
        ];

        $createdUser = User::create($userData);

        if (!$createdUser) {
            return redirect()->back()->withErrors(
                'create_emp_status',
                [
                    'status' => 0,
                    'message' => 'Failed to add employee, please try again'
                ]
            )->withInput();
        }

        return redirect()->back()->with(
            'create_emp_status',
            [
                'status' => 1,
                'message' => 'Employee added successfully',
                'password' => $generatedPassword
            ]
        );
    }

    public function getUnAssignedUsers()
    {
        $unassignedEmployees = User::select('id', 'name')->whereDoesntHave('shop')->where('is_admin', 0)->get();
        return response()->json(['employees' => $unassignedEmployees]);
    }
}
