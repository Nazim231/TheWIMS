<?php

namespace App\Http\Traits;

use App\Models\OrderApproval;

trait ItemSoldTrait
{
    use TimePeriodValueTrait;

    private function getSoldItemsFromDB($type)
    {
        $current = $this->getCurrentDateValue($type);
        $previous = $current - 1;

        $filter = $type . '(created_at)';
        $quantityCollection = OrderApproval::selectRaw($filter . ' as time_period')
        ->selectRaw('SUM(quantity) as quantity')
        ->whereRaw($filter . ' = ?', [$current])
        ->orWhereRaw($filter . ' = ?', [$previous])
        ->groupByRaw($filter)
        ->get();

        $quantity = [
            'current' => 0,
            'previous' => 0
        ];
        foreach ($quantityCollection as $quantityItem) {
            $quantityType = $quantityItem->time_period == $current ? 'current' : 'previous';
            $quantity[$quantityType] = $quantityItem->quantity;
        }
        return $quantity;
    }

    public function getSoldItems($type = 'week')
    {
        $quantity = $this->getSoldItemsFromDB($type);
        $currQuantity = $quantity['current'];
        $prevQuantity = $quantity['previous'];
        if($currQuantity == $prevQuantity)
            $quantity['first_period_percent'] = 0;
        else if ($currQuantity == 0)
            $quantity['first_period_percent'] = -100;
        else if ($prevQuantity == 0)
            $quantity['first_period_percent'] = +100;
        else {
            $percent = number_format(($currQuantity - $prevQuantity) * 100 / $prevQuantity, 1);
            $quantity['first_period_percent'] = $percent;
        }

        return (object) $quantity;
    }
}
