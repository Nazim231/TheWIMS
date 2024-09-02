<?php

namespace App\Http\Traits;

use App\Models\ShopOrderProduct;
use Illuminate\Support\Facades\DB;
use App\Http\Traits\TimePeriodValueTrait;

trait TrendingTrait
{
    use TimePeriodValueTrait;

    private function getTrendingsFromDB($type)
    {
        $current = $this->getCurrentDateValue($type);

        $filter = $type . '(shop_order_products.created_at)';
        $trendingsCollection = ShopOrderProduct::select('SKU', 'cost_price', 'name', 'price')
            ->selectRaw('SUM(approved_quantity + requested_quantity) as week_sales')
            ->selectRaw('(SUM(approved_quantity + requested_quantity) / DAYOFWEEK(now())) as daily_sale')
            ->selectRaw($filter . ' as time_period')
            ->join('product_variations as pv', 'variation_id', 'pv.id')
            ->join('products', 'product_id', 'products.id')
            ->whereRaw($filter . ' = ?', [$current])
            ->groupBy('variation_id')
            ->groupByRaw($filter)
            ->orderBy('week_sales', 'desc')
            ->limit(5)
            ->get();

        return $trendingsCollection;
    }

    public function getTrendings($type = 'week')
    {
        return $this->getTrendingsFromDB($type);
    }
}
