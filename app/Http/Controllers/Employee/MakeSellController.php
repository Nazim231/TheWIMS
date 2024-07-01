<?php

namespace App\Http\Controllers\Employee;

use App\Http\Requests\CheckoutRequest;
use App\Models\Invoice;
use App\Models\InvoiceProduct;
use App\Models\Shop;
use App\Models\ShopsStock;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MakeSellController extends Controller
{

    public function index()
    {
        return view('employees.make_sell');
    }

    public function searchSKUinShop(Request $req)
    {
        $keyword = $req->keyword;
        $shopId = Shop::where('emp_id', Auth::user()->id)->get()[0]->id;
        $results = DB::select('SELECT ss.id, ss.variation_id, pv.SKU, pv.product_id, p.name AS product_name, ss.quantity, pv.price 
                        FROM product_variations AS pv
                        JOIN shops_stock AS ss ON pv.id = ss.variation_id
                        JOIN products AS p ON pv.product_id = p.id
                        WHERE shop_id = ' . $shopId . ' AND SKU LIKE \'%' . $keyword . '%\' OR p.name LIKE \'%' . $keyword . '%\'');

        $results = json_decode(json_encode($results));
        return response()->json($results);
    }

    public function checkoutCart(CheckoutRequest $req)
    {
        $data = (object) [...$req->validated(), "customer_id" => $req->customer_id, "products" => $req->data];
        $invoice = Invoice::create((array) $data);

        foreach ($data->products as $key =>  $value) {
            $stock = ShopsStock::find($key);
            $stock->quantity -= $value['quantity'];
            $stock->save();

            $value['invoice_id'] = $invoice->id;
            InvoiceProduct::create($value);
        }
        return response()->json(['message' => 'Checkout Completed']);
    }
}
