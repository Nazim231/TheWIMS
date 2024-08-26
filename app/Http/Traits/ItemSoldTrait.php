<?php

namespace App\Http\Traits;

use Carbon\Carbon;
use App\Models\Invoice;
use App\Models\InvoiceProduct;

trait ItemSoldTrait
{
    private function getSoldItemsFromDB($type)
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

        $filter = $type . '(created_at)';
        $quantityCollection = Invoice::selectRaw($filter . ' as time_period')
            ->selectRaw('sum(quantity) as quantity')
            ->join('invoice_products as ip', 'invoice_id', 'invoices.id')
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
        if ($currQuantity == 0)
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
