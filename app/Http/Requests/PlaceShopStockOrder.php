<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class PlaceShopStockOrder extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'ordered_quantity' => array_filter($this->ordered_quantity, function ($quantity) {
                return $quantity != null;
            }),
            'shop_id' => User::with('shop')->find(Auth::user()->id)->shop->id,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $numVariations = 0;
        if ($this->selected_variations) {
            $numVariations = sizeof($this->selected_variations);
        }

        return [
            'selected_variations' => 'required|array|min:1',
            'ordered_quantity' => 'required|array|min:' . $numVariations . '|max:' . $numVariations,
            'selected_variations.*' => 'exists:product_variations,id',
            'ordered_quantity.*' => 'required_if:selected_variations.*,selected_variaitons.*|numeric',
            'shop_id' => '',
        ];
    }

}
