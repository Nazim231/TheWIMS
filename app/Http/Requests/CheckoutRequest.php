<?php

namespace App\Http\Requests;

use App\Models\Shop;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Merge additional data or format data before validation
     */
    protected function prepareForValidation(): void
    {
        if (!($this->data)) return;

        $product_ids = [];
        $product_quantities = [];
        $shop_id = Shop::where('emp_id', Auth::user()->id)->get()[0]->id;

        foreach ($this->data as $value) {
            $product_ids[] = $value['id'];
            $product_quantities[] = $value['quantity'];
        }

        $this->merge([
            'product_ids' => $product_ids,
            'product_quantities' => $product_quantities,
            'shop_id' => $shop_id,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $numProducts = sizeof($this->product_ids ?? []);

        return [
            'product_ids' => 'required | array | min:1',
            'product_ids.*' => 'numeric | integer | gt:0 | exists:shops_stock,id,shop_id,' . $this->shop_id,
            'product_quantities' => 'required | array | min:' . $numProducts . '| max:' . $numProducts,
            'product_quantities.*' => 'numeric | integer | gt:0 | quantity_in_range',
        ];
    }
}
