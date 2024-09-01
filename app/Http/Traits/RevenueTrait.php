<?php

namespace App\Http\Traits;

use App\Models\OrderApproval;

trait RevenueTrait
{
    use TimePeriodValueTrait;

    private function getRevenueFromDB($type)
    {
        $current = $this->getCurrentDateValue($type);
        $previous = $current - 1;

        $filter = $type . '(order_approvals.created_at)';
        $revenueCollection = OrderApproval::selectRaw('SUM(order_approvals.quantity * price) as revenue')
            ->selectRaw($filter . ' as time_period')
            ->join('product_variations as pv', 'variation_id', 'pv.id')
            ->whereRaw($filter . ' = ?', [$current])
            ->orWhereRaw($filter . ' = ?', [$previous])
            ->groupByRaw($filter)
            ->get();
        $revenues = [
            'current' => 0,
            'previous' => 0,
        ];
        foreach ($revenueCollection as $revenueItem) {
            $revenueType = $revenueItem->time_period == $current ? 'current' : 'previous';
            $revenues[$revenueType] = $revenueItem->revenue;
        }
        return $revenues;
    }

    public function getRevenues($type = 'week')
    {
        $revenue = $this->getRevenueFromDB($type);

        $currRevenue = $revenue['current'];
        $prevRevenue = $revenue['previous'];
        if ($currRevenue == 0)
            $revenue['first_period_percent'] = -100;
        else if ($prevRevenue == 0)
            $revenue['first_period_percent'] = +100;
        else {
            $percent = number_format(($currRevenue - $prevRevenue) * 100 / $prevRevenue, 1);
            $revenue['first_period_percent'] = $percent;
        }
        return (object) $revenue;
    }
}
