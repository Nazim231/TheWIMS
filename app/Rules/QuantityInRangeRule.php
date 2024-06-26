<?php

namespace App\Rules;

use Closure;
use App\Models\ShopsStock;
use Illuminate\Support\Str;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class QuantityInRangeRule implements DataAwareRule, ValidationRule
{
    protected $data;

    /**
     * Accessing data received in the request
     */
    public function setData($data)
    {
        $this->data = (object) $data;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $index = explode('.', $attribute)[1];
        $product_id = $this->data->product_ids[$index];

        $stock_qty = ShopsStock::where('shop_id', $this->data->shop_id)->where('id', $product_id)->get()[0]->quantity;
        if ($value > $stock_qty) {
            $fail('Invalid quantity for product: ' . $this->data->data[$product_id]['name'] . '(' . $this->data->data[$product_id]['SKU'] . ')');
        }
    }
}
