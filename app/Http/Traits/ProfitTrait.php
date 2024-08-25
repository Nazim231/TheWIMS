<?php

namespace App\Http\Traits;

use Carbon\Carbon;
use App\Models\InvoiceProduct;
use Illuminate\Support\Facades\DB;

trait ProfitTrait
{
    private function getWeekProfitFromDB()
    {
        $currentWeek = Carbon::now()->weekOfYear;
        $lastWeek = $currentWeek - 1;
        $profitsCollection = InvoiceProduct::join('product_variations as pv', 'variation_id', 'pv.id')
            ->join('invoices', 'invoice_id', 'invoices.id')
            ->selectRaw('WEEK(invoices.created_at) as week')
            ->selectRaw('SUM(total_price - (cost_price * invoice_products.quantity)) as profit')
            ->whereRaw('WEEK(invoices.created_at) = ?', [$currentWeek])
            ->orWhereRaw('WEEK(invoices.created_at) = ?', [$lastWeek])
            ->groupBy(DB::raw('WEEK(invoices.created_at)'))
            ->get();

        $profit = [
            'current' => 0,
            'previous' => 0,
        ];
        foreach ($profitsCollection as $profitItem) {
            $profitType = $profitItem->week == $currentWeek ? 'current' : 'previous';
            $profit[$profitType] = $profitItem->profit;
        }
        return $profit;
    }

    public function getWeeklyProfit()
    {
        $profit = $this->getWeekProfitFromDB();
        $currProfit = $profit['current'];
        $prevProfit = $profit['previous'];
        $weekProfitPercent = number_format(($currProfit - $prevProfit) * 100 / ($prevProfit ?? 1), 1);
        $profit['weekProfitPercent'] = $weekProfitPercent;
        
        return (object) $profit;
    }
}
