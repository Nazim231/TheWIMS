<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Routing\Controller;
use App\Http\Requests\ProductRequest;

class StocksController extends Controller
{
    public function showPage()
    {
        $stocks = Product::all();
        $brands = Brand::all();
        $categories = Category::all();
        return view('admin.stocks', compact('stocks', 'categories', 'brands'));
    }

    public function addStock(ProductRequest $req)
    {

        // TODO : Prepare the logic for adding the product to the product and product_variation table
        $addedProduct = Product::create($req->validated());
        $newProdId = $addedProduct->id;

        // TODO : Generate the SKU for each variations
        /** 
         * format: BRD-PRD-VAR-CLR-SIZE
         * BRD = Brand Code
         * PRD = Product Code
         * VAR = Variation Code
         * CLR = COLOR CODE
         * SIZE
        */
        
        // adding the variations
        if ($req->variations == 'on') {

        }
        return redirect()->back();
    }
}
