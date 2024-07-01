<?php

namespace App\Http\Requests;

use App\Models\Customer;
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
        $user = Auth::user();
        $shop = Shop::where('emp_id', $user->id)->get()[0];

        foreach ($this->data as $value) {
            $product_ids[] = $value['id'];
            $product_quantities[] = $value['quantity'];
        }

        $this->merge([
            'product_ids' => $product_ids,
            'product_quantities' => $product_quantities,
            'emp_id' => $user->id,
            'emp_name' => $user->name,
            'shop_id' => $shop->id,
            'shop_name' => $shop->name,
            'shop_address' => $shop->address,
            'customer_id' => '',
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
            'emp_id' => 'required | integer',
            'emp_name' => 'required | string',
            'shop_id' => 'required | integer',
            'shop_name' => 'required | string',
            'shop_address' => 'required | string',
            'customer_name' => 'required | string',
            'customer_mobile' => 'required | integer',
            'total_amount' => 'required | numeric | gte:0'
        ];
    }

    /**
     * Handle a passed validation attempt.
     */
    protected function passedValidation(): void
    {
        $customer = Customer::where('mobile_number', $this->customer_mobile)->get();
        if (sizeof($customer ?? []) == 0) {
            $data = [
                'name' => $this->customer_name,
                'mobile_number' => $this->customer_mobile,
            ];
            $customer = Customer::create($data);
            $this->replace([...$this->all(), "customer_id" => $customer->id]);
        } else {
            $this->replace([...$this->all(), "customer_id" => $customer[0]->id]);
        }   
    }
}
