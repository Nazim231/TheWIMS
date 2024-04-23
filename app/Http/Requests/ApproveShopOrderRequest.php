<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class ApproveShopOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()->is_admin;
    }

    protected $stopOnFirstFailure = true;

    protected function prepareForValidation()
    {
        $this->merge([
            'stock_quantities' => session()->get('wh_stock_quantities'),
            'order_id' => session()->get('order_id'),
            'stock_names' => session()->get('wh_stock_names'),
            'order_product_ids' => session()->get('order_product_ids'),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $totalVariations = sizeof($this->stock_quantities);

        // TODO : Validate that the approved quantity can't be higher than the request quantity
        return [
            'approved_quantity' => 'required|array|min:1|max:' . $totalVariations,
            'approved_quantity.*' => 'required|numeric|gte:0|lte:stock_quantities.*',
            'order_id' => 'required|exists:shop_orders,id',
            'order_product_ids' => 'required',
        ];
    }

    public function messages(): array
    {
        $messages = [];

        foreach ($this->get('approved_quantity') as $key => $value) {

            $messages['approved_quantity.' . $key . '.required'] =
                'Please specify the <b>Approved Quantity</b> for <b>Product: ' . $this->stock_names[$key] . '</b>';

            $messages['approved_quantity.' . $key . '.numeric'] =
                'Quantity should be numeric for <b>Product: ' . $this->stock_names[$key] . '</b>';

            $messages['approved_quantity.' . $key . '.gte'] =
                'Quantity can\'t be less than 0 for <b> Product: ' . $this->stock_names[$key] . '</b>';

            $messages['approved_quantity.' . $key . '.lte'] =
                'Warehouse does not have ' . $this->approved_quantity[$key] . ' Quantity for <b> Product: ' . $this->stock_names[$key] . '</b>';
        }

        return $messages;
    }
}
