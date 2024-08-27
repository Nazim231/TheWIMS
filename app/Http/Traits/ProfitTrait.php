<?php

namespace App\Http\Traits;

use Carbon\Carbon;
use App\Models\InvoiceProduct;
use Illuminate\Support\Facades\DB;

trait ProfitTrait
{
    use TimePeriodValueTrait;

    private function getWeekProfitFromDB($type)
    {
        $current = $this->getCurrentDateValue($type);
        $previous = $current - 1;
        
        $filter = $type . '(invoices.created_at)';
        $profitsCollection = InvoiceProduct::join('product_variations as pv', 'variation_id', 'pv.id')
            ->join('invoices', 'invoice_id', 'invoices.id')
            ->selectRaw($filter . ' as time_period')
            ->selectRaw('SUM(total_price - (cost_price * invoice_products.quantity)) as profit')
            ->whereRaw($filter . ' = ?', [$current])
            ->orWhereRaw($filter . ' = ?', [$previous])
            ->groupByRaw($filter)
            ->get();

        $profit = [
            'current' => 0,
            'previous' => 0,
        ];
        foreach ($profitsCollection as $profitItem) {
            $profitType = $profitItem->time_period == $current ? 'current' : 'previous';
            $profit[$profitType] = $profitItem->profit;
        }
        return $profit;
    }

    public function getWeeklyProfit($type = 'week')
    {
        $profit = $this->getWeekProfitFromDB($type);

        $currProfit = $profit['current'];
        $prevProfit = $profit['previous'];
        if ($currProfit == 0)
            $profit['first_period_percent'] = -100;
        else if ($prevProfit == 0)
            $profit['first_period_percent'] = +100;
        else {
            $percent = number_format(($currProfit - $prevProfit) * 100 / $prevProfit, 1);
            $profit['first_period_percent'] = $percent;
        }
        return (object) $profit;
    }
}
