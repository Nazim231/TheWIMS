<?php

namespace App\Providers;

use App\Http\Service\RuleExtender;
use App\Rules\QuantityInRangeRule;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Validator::extend('quantity_in_range', RuleExtender::extend(QuantityInRangeRule::class));
    }
}
