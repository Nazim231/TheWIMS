<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\WarehouseStock;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class StocksController extends Controller
{
    public function showPage() {
        $stocks = WarehouseStock::all();
        $categories = Category::all();
        return view('admin.stocks', compact('stocks', 'categories'));
    }


}
