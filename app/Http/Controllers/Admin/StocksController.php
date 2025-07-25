<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductVariation;
use Illuminate\Routing\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Requests\VariationRequest;
use App\Models\Expense;
use App\Models\ExpenseItem;
use Illuminate\Support\Facades\Redirect;

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

    public function showProduct($productId)
    {
        $product = Product::with(['variants', 'category', 'brand'])->withSum('variants', 'quantity')->find($productId);
        if (!$product) {
            return Redirect::route('admin.stocks');
        }
        return view('admin.stock', compact('product'));
    }

    public function addStock(ProductRequest $req)
    {
        $prodData = $req->validated();
        // dd($prodData, $req->all());
        $addedProduct = Product::create($prodData);
        $newProdId = $addedProduct->id;

        // TODO : Generate the SKU for each variations

        $this->addVariationsToDB($newProdId, $prodData);
        return redirect()->back();
    }

    public function addVariations(VariationRequest $req)
    {

        $this->addVariationsToDB($req->product_id, $req->validated());
        return redirect()->back();
    }

    private function addVariationsToDB($parentProductId, $variationsData)
    {
        $expense = Expense::create([]);
        $totalExpense = 0;
        for ($indexValue = 0; $indexValue < $variationsData['variations_count']; $indexValue++) {

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
                'cost_price' => $variationsData['variation_cost_price'][$indexValue],
            ];
            $variant = ProductVariation::create($variantData);
            $expenseData = [
                'expense_id' => $expense->id,
                'variation_id' => $variant->id,
                'quantity' => $variantData['quantity'],
                'price' => $variantData['cost_price'],
                'total_price' => $variantData['quantity'] * $variantData['cost_price'] 
            ];
            ExpenseItem::create($expenseData);
            $totalExpense += $expenseData['total_price'];
        }
        $expense->total_price = $totalExpense;
        $expense->save();
    }
}
