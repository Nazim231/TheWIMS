<?php

namespace App\Http\Controllers\Employee;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CustomerController extends Controller
{

    public function getCustomer(Request $req)
    {
        $mobileNum = $req->mobile_number ?? 0;
        $customer = Customer::where('mobile_number', $mobileNum)->limit(1)->get();
        if (sizeof($customer) > 0) {
            return response()->json(['name' => $customer[0]->name], 200);
        }

        return response()->json(['message' => 'Customer not found'],  404);
    }
}
