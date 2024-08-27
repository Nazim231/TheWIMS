<?php

namespace App\Http\Controllers\Admin;

use App\Http\Traits\ExpenseTrait;
use App\Http\Traits\ItemSoldTrait;
use App\Http\Traits\ProfitTrait;
use App\Http\Traits\RevenueTrait;
use Illuminate\Routing\Controller;

class HomeController extends Controller
{
    use ProfitTrait;
    use ItemSoldTrait;
    use ExpenseTrait;
    use RevenueTrait;

    public function index()
    {
        /**
         * ! PROBLEM :: Expense & Revenue are estimated from entire data
         * * SOLUTION :: Create new table that will store the stock incoming & outgoing history 
         * *             and fetch the Expenses & Revenue from those tables.
         * 
         * ? Expenses table created and functionality added to add the entire spended amount (total sum of cost_price) on the product
         * ? while adding the new stock
         */
        $profit = $this->getWeeklyProfit();
        $item_sold = $this->getSoldItems();
        $expense = $this->getExpenses();
        $revenue = $this->getRevenues();

        return view('admin.home', compact('profit', 'item_sold', 'expense', 'revenue'));
    }
}
