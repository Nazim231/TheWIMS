<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductVariation;
use Illuminate\Routing\Controller;
use App\Http\Requests\ProductRequest;

class StocksController extends Controller
{
    public function showPage()
    {
        $stocks = Product::withSum('variants', 'quantity')->with(['category', 'brand'])->get();
        $brands = Brand::with('products')->get();
        $categories = Category::all();
        return view('admin.stocks', compact('stocks', 'categories', 'brands'));
    }

    public function addStock(ProductRequest $req)
    {
        $prodData = $req->validated();
        $addedProduct = Product::create($prodData);
        $newProdId = $addedProduct->id;

        // TODO : Generate the SKU for each variations

        // adding the variations
        foreach ($req->variation_selected as $indexValue => $variantStatus) {
            $variantData = [
                'product_id' => $newProdId,
                'sku' => $prodData['variation_sku'][$indexValue],
                'price' => $prodData['variation_selling_price'][$indexValue],
                'mrp' => $prodData['variation_mrp'][$indexValue],
                'weight' => $prodData['variation_weight'][$indexValue] ?? null,
                'height' => $prodData['variation_height'][$indexValue] ?? null,
                'length' => $prodData['variation_length'][$indexValue] ?? null,
                'color' => $prodData['variation_color'][$indexValue] ?? null,
                'size' => $prodData['variation_size'][$indexValue] ?? null,
                'quantity' => $prodData['variation_qty'][$indexValue],
            ];
            ProductVariation::create($variantData);
        }
        return redirect()->back();
    }
}
