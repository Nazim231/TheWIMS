<?php

namespace App\Http\Controllers\Admin;

use App\Models\ShopOrder;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class OrdersController extends Controller {

    public function index() {
        $orders = ShopOrder::withCount('products')->with('shop')->get();
        return view('admin.orders', compact('orders'));
    }

    public function showOrder(Request $req, $id) {
        $order = ShopOrder::where('id', $id)->with('products', 'products.variation.product')->get()[0];
        return view('admin.order', compact('order'));
    }
}