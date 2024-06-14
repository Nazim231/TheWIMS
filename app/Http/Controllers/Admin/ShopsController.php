<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AssignEmployeeRequest;
use App\Models\Shop;
use App\Models\User;
use App\Models\ShopOrder;
use Illuminate\Http\Request;
use App\Http\Requests\ShopRequest;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class ShopsController extends Controller
{

    public function showPage()
    {
        $shops = Shop::all();
        return view('admin.shops', compact('shops'));
    }

    public function addShop(ShopRequest $req)
    {
        $addedShop = Shop::create($req->validated());

        if (!$addedShop) {
            return back()->withErrors(['failed_message' => 'Failed to add shop, please try again']);
        }

        return Redirect::route('admin.shops');
    }

    public function showShop($id)
    {
        $products = DB::select(
            'SELECT p.name AS product_name, COUNT(pv.product_id) AS variation_count, 
                    s.id, s.name, s.address, u.name AS emp_name, SUM(ss.quantity) AS quantity 
                    FROM shops AS s
                    LEFT JOIN users AS u ON s.emp_id = u.id 
                    LEFT JOIN shops_stock AS ss ON s.id = ss.shop_id 
                    LEFT JOIN product_variations AS pv ON ss.variation_id = pv.id 
                    LEFT JOIN products AS p ON pv.product_id = p.id 
                    WHERE s.id = ' . $id . ' GROUP BY pv.product_id'
        );

        if (sizeof($products) == 0) {
            // shop doesn't exist or invalid shop id
            return Redirect::route('admin.shops');
        }

        $shopOrders = ShopOrder::where('shop_id', $id)->withCount('products')->orderBy('id', 'desc')->limit(5)->get();

        $shop = (object) [
            'id' => $products[0]->id,
            'name' => $products[0]->name,
            'emp_name' => $products[0]->emp_name,
            'address' => $products[0]->address,
            'products' => $products
        ];

        return view('admin.shop', compact('shop', 'shopOrders'));
    }

    public function assignEmpToShop(AssignEmployeeRequest $req)
    {
        $shop = Shop::find($req->shop);
        $shop->emp_id = $req->employee;
        $shop->save();

        return back();
    }
}
