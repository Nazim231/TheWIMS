<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\Rule;

class UniqueSKURule implements Rule
{
    public function passes($attribute, $value) {
        return count($value) == count(array_unique($value));
    }

    public function message() {
        return 'One or more variations have same SKU';
    }
}
