<?php

namespace App\Http\Traits;

use App\Models\Expense;
use App\Models\ProductVariation;
use Carbon\Carbon;

trait ExpenseTrait
{
    use TimePeriodValueTrait;

    private function getExpenseFromDB($type)
    {
        $current = $this->getCurrentDateValue($type);
        $previous = $current - 1;
        $filter = $type . '(created_at)';
        $expenseCollection = Expense::selectRaw($filter . ' as time_period')
            ->selectRaw('SUM(total_price) as total_price')
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
            $expenses[$expenseType] = $expenseItem->total_price ?? 0;
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
