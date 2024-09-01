<?php

namespace App\Http\Traits;

use App\Models\OrderApproval;

trait ProfitTrait
{
    use TimePeriodValueTrait;

    private function getProfitFromDB($type)
    {
        $current = $this->getCurrentDateValue($type);
        $previous = $current - 1;
        
        $filter = $type . '(order_approvals.created_at)';
        $profitsCollection = OrderApproval::selectRaw('SUM(order_approvals.quantity - (price - cost_price)) as profit')
            ->selectRaw($filter . ' as time_period')
            ->join('product_variations as pv', 'variation_id', 'pv.id')
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

    public function getProfits($type = 'week')
    {
        $profit = $this->getProfitFromDB($type);

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
