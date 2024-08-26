<?php

namespace App\Http\Traits;

use App\Models\ProductVariation;
use Carbon\Carbon;

trait ExpenseTrait
{
    private function getExpenseFromDB($type)
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

        $filter = $type . '(updated_at)';
        $expenseCollection = ProductVariation::selectRaw($filter . ' as time_period')
            ->selectRaw('SUM(cost_price * quantity) as expense')
            ->whereRaw($filter . ' = ?', [$current])
            ->orWhereRaw($filter . ' = ?', [$previous])
            ->groupByRaw($filter)
            ->get();

        $expenses = [
            'current' => 0,
            'previous' => 0,
        ];
        foreach ($expenseCollection as $expenseItem) {
            $expenseType = $expenseItem->time_period == $current ? 'current' : 'previous';
            $expenses[$expenseType] = $expenseItem->expense;
        }
        return $expenses;
    }

    public function getExpenses($type = 'week')
    {
        $expense = $this->getExpenseFromDB($type);

        $currExpense = $expense['current'];
        $prevExpense = $expense['previous'];
        if ($currExpense == 0)
            $expense['first_period_percent'] = -100;
        else if ($prevExpense == 0)
            $expense['first_period_percent'] = +100;
        else {
            $percent = number_format(($currExpense - $prevExpense) * 100 / $prevExpense, 1);
            $expense['first_period_percent'] = $percent;
        }

        return (object) $expense;
    }
}
