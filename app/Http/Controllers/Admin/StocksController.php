<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductVariation;
use Illuminate\Routing\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Requests\VariationRequest;

/*
 ! PROBLEM :: problem in selecting variations to add
 ! When variations are selected non-linearly from Select Variations 
 ! To Add Table the variation_selected returns the array of only 
 ! selected i.e., if variation 1 & 3 are selected then 
 ! variation_selected array will have array of length 2 (0, 1).
*/
class StocksController extends Controller
{
    public function showPage()
    {
        $stocks = Product::withSum('variants', 'quantity')->with(['category', 'brand'])->get();
        $brands = Brand::all();
        $categories = Category::all();
        return view('admin.stocks', compact('stocks', 'categories', 'brands'));
    }

    public function showProduct($productId) {
        $product = Product::with(['variants', 'category', 'brand'])->withSum('variants', 'quantity')->find($productId);
        return view('admin.stock', compact('product'));
    }

    public function addStock(ProductRequest $req)
    {
        $prodData = $req->validated();
        $addedProduct = Product::create($prodData);
        $newProdId = $addedProduct->id;

        // TODO : Generate the SKU for each variations

        $variationsCount = $req->variation_numbers * $req->sub_variation_numbers;
        $this->addVariationsToDB($newProdId, $variationsCount, $req->validated);
        return redirect()->back();
    }

    public function addVariations(VariationRequest $req) {
        // dd($req->all());
        $variationsCount = $req->variation_numbers * $req->sub_variation_numbers;
        $this->addVariationsToDB($req->product_id, $variationsCount, $req->validated());
        return redirect()->back();
    }

    private function addVariationsToDB($parentProductId, $variationsCount, $variationsData ) {
        
        for ($indexValue = 0; $indexValue < $variationsCount; $indexValue++ ) {
            $variantData = [
                'product_id' => $parentProductId,
                'sku' => $variationsData['variation_sku'][$indexValue],
                'price' => $variationsData['variation_selling_price'][$indexValue],
                'mrp' => $variationsData['variation_mrp'][$indexValue],
                'weight' => $variationsData['variation_weight'][$indexValue] ?? null,
                'height' => $variationsData['variation_height'][$indexValue] ?? null,
                'length' => $variationsData['variation_length'][$indexValue] ?? null,
                'color' => $variationsData['variation_color'][$indexValue] ?? null,
                'size' => $variationsData['variation_size'][$indexValue] ?? null,
                'quantity' => $variationsData['variation_qty'][$indexValue],
            ];
            ProductVariation::create($variantData);
        }
    }
}
