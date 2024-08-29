<?php

namespace App\Http\Traits;

use Carbon\Carbon;
use App\Models\Invoice;
use App\Models\InvoiceProduct;
use App\Models\OrderApproval;
use App\Models\ShopOrderProduct;

trait ItemSoldTrait
{
    use TimePeriodValueTrait;

    /**
     * ! PROBLEM :: If some quantity is approved from the orders, then entire approved quantity is added
     * * Create another table for warehouse order approval history which will help to get the history of approved
     * * items of shop orders with shop_id, order_id, variation_id, approved_quantity 
     */
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
