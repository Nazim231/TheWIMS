<?php

namespace App\Http\Requests;

use App\Rules\UniqueSKURule;
use Illuminate\Foundation\Http\FormRequest;

class VariationRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation
     */
    protected function prepareForValidation(): void
    {
        if ($this->variation_selected == null || sizeof($this->variation_selected) == 0) return;

        $variationNumbers = sizeof($this->variation_selected);

        $this->merge([
            'variation_selected' => array_slice($this->variation_selected, 0, $variationNumbers),
            'variation_sku' => array_slice($this->variation_sku, 0, $variationNumbers),
            'variation_selling_price' => array_slice($this->variation_selling_price, 0, $variationNumbers),
            'variation_mrp' => array_slice($this->variation_mrp, 0, $variationNumbers),
            'variation_color' => array_slice($this->variation_color, 0, $variationNumbers),
            'variation_size' => array_slice($this->variation_size, 0, $variationNumbers),
            'variation_qty' => array_slice($this->variation_qty, 0, $variationNumbers),
            'cost_price' => array_slice($this->cost_price, 0, $variationNumbers),
            'variations_count' => $variationNumbers,
        ]);

    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        // TODO : Check if any variation is selected or not.
        return [
            'product_id' => 'required | gt:0 | exists:products,id',
            'variation_sku' => ['required', 'min:1', new UniqueSKURule],
            'variation_selling_price' => 'required | min:1',
            'variation_mrp' => 'required | min:1',
            'cost_price' => 'required | min:1',
            'variation_qty' => 'required | min:1',
            // Validate for Variation & Sub-Variation Type
            'variation_type' => 'required_if:variation,on',
            'sub_variation_type' => 'required_if:sub_variation,on',
            'variation_size' => '',
            'variation_color' => '',
            'variation_sku.*' => 'required_if:variation_selected.*,on|unique:product_variations,sku',
            'variation_selling_price.*' => 'required_if:variation_selected.*,on | gt:0 | lte:variation_mrp.*',
            'variation_mrp.*' => 'required_if:variation_selected.*,on | gt:0',
            'cost_price.*' => 'required_if:variation_selected.*,on | gt:0',
            'variation_qty.*' => 'required_if:variation_selected.*,on | gt:0',
            'variations_count' => '',
        ];
    }

    /**
     * Customized messages for failed validations
     * 
     * @return array
     */
    public function messages(): array
    {
        return [
            'product_id' => 'Invalid product id',
            'variation_type.required_if' => 'Please select variation type',
            'sub_variation_type.required_if' => 'Please select sub variation type',
            // TODO : Write more messages for the validation rules.
            'variation_qty' => 'Please enter the variation(s) quantity',
            'variation_sku' => 'Please specify the variation SKU(s)',
            'variation_selling_price' => 'Please enter the variation(s) Price',
            'variation_mrp' => 'Please enter the variation(s) MRP',
            'variation_qty.*.required_if' => 'One or more variation(s) does not have quantities',
            'variation_qty.*.gt' => 'One or more variations(s) have invalid quantity',
            'variation_sku.*.required_if' => 'One of more variation(s) does not have SKU',
            'variation_sku.*.unique' => 'Variation(s) SKU already exists, please specify another SKU',
            'variation_selling_price.*.required_if' => 'One or more variation(s) does not contain Selling Price',
            'variation_selling_price.*.gt' => 'Variation(s) have invalid selling price',
            'variation_selling_price.*.lte' => 'Variation(s) selling price cannot be greater than the MRP of variation',
            'variation_mrp.*.required_if' => 'One or more variation(s) does not have MRP',
            'variation_mrp.*.gt' => 'Variation(s) have invalid MRP',
        ];
    }
}
