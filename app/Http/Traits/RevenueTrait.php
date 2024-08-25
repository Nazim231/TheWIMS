<?php

namespace App\Http\Traits;

use App\Models\ShopsStock;
use Carbon\Carbon;

trait RevenueTrait
{
    private function getRevenueFromDB($type)
    {
        $current = '';
        switch ($type) {
            case 'month':
                $current = Carbon::now()->month;
                break;
            case 'year':
                $current = Carbon::now()->year;
                break;
            default:
                $current = Carbon::now()->weekOfYear;
        }
        $previous = $current - 1;

        $filter = $type . '(shops_stock.updated_at)';
        $revenueCollection = ShopsStock::join('product_variations as pv', 'variation_id', 'pv.id')
        ->selectRaw($filter . ' as time_period')
        ->selectRaw('SUM(shops_stock.quantity * pv.price) as revenue')
        ->whereRaw($filter . ' = ?', [$current])
        ->orWhereRaw($filter . ' = ?', [$previous])
        ->groupByRaw($filter)
        ->get();

        $revenues = [
            'current' => 0,
            'previous' => 0,
        ];
        foreach($revenueCollection as $revenueItem) {
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
        $percent = number_format(($currRevenue - $prevRevenue) * 100 / ($prevRevenue ?? 1), 1);
        $revenue['first_period_percent'] = $percent;
        return (object) $revenue;
    }
}

