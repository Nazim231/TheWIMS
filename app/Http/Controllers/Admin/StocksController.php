<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class StocksController extends Controller
{
    public function showPage()
    {
        $stocks = Product::all();
        $categories = Category::all();
        return view('admin.stocks', compact('stocks', 'categories'));
    }

    public function addStock(ProductRequest $req)
    {
        // TODO : Prepare the logic for adding the product to the product and product_variation table
        return redirect()->back();
    }
}
