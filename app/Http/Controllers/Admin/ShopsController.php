<?php

namespace App\Http\Controllers\Admin;

use App\Models\Shop;
use App\Http\Requests\ShopRequest;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;

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
}
