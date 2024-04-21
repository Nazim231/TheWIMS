<?php

namespace App\Http\Controllers\Employee;

use App\Models\Shop;
use App\Models\ShopOrder;
use Illuminate\Routing\Controller;

class OrdersController extends Controller {

    public function index() {
        $shopId = Shop::with('shopOwner')->limit(1)->get('id')[0]->id;
        $orderHistory = ShopOrder::where('shop_id', $shopId)->withCount('products')->get();
        return view('employees.orders', compact('orderHistory'));
    }
}