<?php

namespace App\Http\Traits;

use Carbon\Carbon;

trait TimePeriodValueTrait
{
    public function getCurrentDateValue($type) : int
    {
        switch ($type) {
            case 'month':
                return Carbon::now()->month;
                break;
            case 'year':
                return Carbon::now()->year;
                break;
            default:
                return Carbon::now()->weekday(Carbon::SUNDAY)->weekOfYear;
        }

    }
}