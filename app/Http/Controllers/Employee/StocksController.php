<?php

namespace App\Http\Controllers\Employee;

use Carbon\Carbon;
use App\Models\Shop;
use App\Models\User;
use App\Models\Product;
use App\Models\ShopsStock;
use Illuminate\Http\Request;
use App\Models\ProductVariation;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\PlaceShopStockOrder;
use App\Models\ShopOrder;
use App\Models\ShopOrderProduct;

class StocksController extends Controller
{

    public function index()
    {
        try {
            $shopId = Shop::where('emp_id', Auth::user()->id)->limit(1)->get()[0]->id;
        } catch (\Throwable $th) {
            return redirect()->route('employee.home');
        }
        
        //! Below query needs to be refactored using Models
        $shopStocks = DB::select('select p.name as name, sum(ss.quantity) as quantity, count(*) as variations, min(pv.price) as min_price, max(pv.price) as max_price, min(pv.mrp) as min_mrp, max(pv.mrp) as max_mrp from shops_stock AS ss join product_variations AS pv on ss.variation_id = pv.id join products AS p on pv.product_id = p.id where shop_id = ' . $shopId . ' group by pv.product_id');
        
        return view('employees.stocks', compact('shopStocks'));
    }

    public function addStockToShopPage()
    {
        $products = Product::all();
        return view('employees.select_stocks', compact('products'));
    }

    public function getSelectedProductVariations(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'selected_products' => 'required|array',
            'selected_products.*' => 'exists:products,id'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }

        $productVariations = ProductVariation::whereIn('product_id', $req->selected_products)->with('product')->get();
        return redirect()->route('employee.stocks.view.variations')->with('productVariations', $productVariations);
    }

    public function viewProductVariations(Request $req)
    {
        $productVariations = $req->session()->get('productVariations');
        if ($productVariations == null)
            return redirect()->route('employee.products.request.page');
        return view('employees.select_variations', compact('productVariations'));
    }

    public function placeOrder(PlaceShopStockOrder $req)
    {
        $variationAndQty = array_combine($req->selected_variations, $req->ordered_quantity);
        $newOrder = ShopOrder::create($req->validated());

        foreach ($variationAndQty as $id => $qty) {
            $dataToInsert[] = [
                'order_id' => $newOrder->id,
                'variation_id' => $id,
                'requested_quantity' => $qty,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        if (sizeof($dataToInsert) > 0) {
            $result = ShopOrderProduct::insert($dataToInsert);
            return $result ?  redirect()->route('employee.stocks')->with('order_placed', 'Your Order have been placed successfully with Order ID: ' . $newOrder->id) : redirect()->back();
        } else {
            return abort(402);
        }
    }
}
