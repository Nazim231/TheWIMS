<?php

namespace App\Http\Controllers\Employee;

use App\Models\User;
use App\Models\Product;
use App\Models\ShopsStock;
use App\Models\ProductVariation;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class StocksController extends Controller {

    public function index() {
        $shop = User::with('shop')->find(Auth::user()->id)->shop;
        $shopStocks = ShopsStock::with('product')->find($shop->id);
        return view('employees.stocks', compact('shop', 'shopStocks'));
    }

    public function addStockToShopPage() {
        $products = Product::all();
        return view('employees.select_stocks', compact('products'));
    }

    public function getSelectedProductVariations(Request $req) {
        $validator = Validator::make($req->all(), [
            'selected_products' => 'required|array',
            'selected_products.*' => 'exists:products,id'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }

        $productVariations = ProductVariation::whereIn('product_id', $req->selected_products)->with('product')->get();
        return view('employees.select_variations', compact('productVariations'));
    }
}