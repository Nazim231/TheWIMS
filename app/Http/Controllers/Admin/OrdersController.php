<?php

namespace App\Http\Controllers\Admin;

use App\Models\ShopOrder;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Http\Requests\ApproveShopOrderRequest;
use App\Models\ProductVariation;
use App\Models\ShopOrderProduct;
use App\Models\ShopsStock;

class OrdersController extends Controller
{

    public function index()
    {
        $orders = ShopOrder::withCount('products')->with('shop')->get();
        return view('admin.orders', compact('orders'));
    }

    public function showOrder(Request $req, $id)
    {
        $order = ShopOrder::where('id', $id)->with('products', 'products.variation.product')->get()[0];
        $req->session()->put('order_id', $id);

        /** 
         * 'wh_stock_quantities', 'wh_stock_names' will store the variations quantities
         * and names from warehouse for further validation, 
         * initializing them to null when page is loaded
         */
        $req->session()->remove('wh_stock_quantities');
        $req->session()->remove('wh_stock_names');
        $req->session()->remove('order_product_ids');

        return view('admin.order', compact('order'));
    }

    public function approveOrder(ApproveShopOrderRequest $req)
    {
        // updating order product quantities
        $order_products = ShopOrderProduct::whereIn('id', $req->order_product_ids)->get();
        $productVariationIds = [];
        foreach ($order_products as $key => $product) {
            $product->requested_quantity = $product->requested_quantity - $req->approved_quantity[$key];
            $product->approved_quantity = $product->approved_quantity + $req->approved_quantity[$key];
            $productVariationIds[] = $product->variation_id;
            $product->save();
        }

        
        $numOrderProducts = $order_products->count();
        $numApprovedOrderProducts = $order_products->where('requested_quantity', 0)->count();
        // updating order status
        $order = ShopOrder::find($req->order_id);
        $order->status = $numOrderProducts == $numApprovedOrderProducts ? 'Completed' : 'Partially Delivered';
        $order->save();
        
        $variationsToUpdate = array_combine($productVariationIds, $req->approved_quantity);

        // decrease warehouse product variation quantities
        $whProducts = ProductVariation::whereIn('id', $productVariationIds)->get();
        foreach ($whProducts as $whProdVariation) {
            $whProdVariation->quantity -= $variationsToUpdate[$whProdVariation->id];
            $whProdVariation->save();
        }

        // add the stock to the shop stocks
        $existingStocks = ShopsStock::where('shop_id', $order->shop_id)
            ->whereIn('variation_id', $productVariationIds)
            ->get();

        foreach ($existingStocks as $stock) {
            $stock->quantity += $variationsToUpdate[$stock->variation_id];
            $stock->save();
            unset($variationsToUpdate[$stock->variation_id]);
        }

        foreach ($variationsToUpdate as $variationId => $quantity) {
            $stock = new ShopsStock();
            $stock->shop_id = $order->shop_id;
            $stock->variation_id = $variationId;
            $stock->quantity = $quantity;
            $stock->save();
        }

        return redirect()->route('admin.order');
    }
}
