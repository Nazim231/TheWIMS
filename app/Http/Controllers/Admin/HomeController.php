<?php

namespace App\Http\Controllers\Admin;

use App\Http\Traits\ExpenseTrait;
use App\Http\Traits\ItemSoldTrait;
use App\Http\Traits\ProfitTrait;
use App\Http\Traits\RevenueTrait;
use App\Http\Traits\TrendingTrait;
use Illuminate\Routing\Controller;

class HomeController extends Controller
{
    use ProfitTrait, ItemSoldTrait, ExpenseTrait, RevenueTrait, TrendingTrait;

    public function index()
    {
        $profit = $this->getProfits();
        $item_sold = $this->getSoldItems();
        $expense = $this->getExpenses();
        $revenue = $this->getRevenues();
        $trendings = $this->getTrendings();

        return view('admin.home', compact('profit', 'item_sold', 'expense', 'revenue', 'trendings'));
    }
}
