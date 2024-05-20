<?php

namespace App\Http\Controllers\Employee;

use App\Models\Shop;
use App\Models\ShopOrder;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class OrdersController extends Controller {

    public function index() {
        try {
            $shopId = Shop::where('emp_id', Auth::user()->id)->limit(1)->get()[0]->id;
        } catch (\Throwable $th) {
            return redirect()->route('employee.home');
        }

        $orderHistory = ShopOrder::where('shop_id', $shopId)->withCount('products')->get();
        return view('employees.orders', compact('orderHistory'));
    }

    public function showOrder($id) {

        $order = ShopOrder::where('id', $id)->with('products', 'products.variation.product')->get()[0];
        return view('employees.order', compact('order'));
    }
}