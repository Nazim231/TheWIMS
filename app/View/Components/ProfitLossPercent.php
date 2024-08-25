<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ProfitLossPercent extends Component
{
    /**
     * Component props
     */
    public $type;
    public $value;
    public $period;
    /**
     * Create a new component instance.
     */
    public function __construct($value, $period)
    {
        $this->type = $value > 0 ? 'profit' : 'loss';
        $this->value = $value;
        $this->period = $period;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.profit-loss-percent');
    }
}
