<?php

namespace App\Http\Requests;

use App\Models\Brand;
use App\Models\Category;
use Illuminate\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
        if (sizeof($this->variation_selected) == 0 || $this->variation_selected == null) return;

        $this->merge([
            'variation_name' => array_slice($this->variation_name, 0, sizeof($this->variation_selected)),
            'variation_qty' => array_slice($this->variation_qty, 0, sizeof($this->variation_selected)),
            'variation_mrp' => array_slice($this->variation_mrp, 0, sizeof($this->variation_selected)),
            'variation_selling_price' => array_slice($this->variation_selling_price, 0, sizeof($this->variation_selected)),
        ]);

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|min:3|max:100',
            'brand' => 'required|gt:0',
            'category' => 'required|gt:0',
            'variation_type' => 'required_if:variation,on',
            'sub_variation_type' => 'required_if:variation,on',
            'variation_mrp' => 'required|min:1',
            "variation_mrp.*" => 'required_if:variation_selected.*,on',
            'variation_selling_price' => 'required|min:1',
            'variation_selling_price.*' => 'required_if:variation_selected.*,on|lte:variation_mrp.*',
            'variation_qty' => 'required|min:1',
            'variation_qty.*' => 'required_if:variation_selected.*,on|gt:0',
            'variation_name' => 'required|min:1',
            'variation_name.*' => 'required_if:variation_selected.*,on'
        ];
    }

    /**
     * Get the custom validation rules
     * 
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function after(): array
    {
        return [
            // validating category
            function (Validator $validator) {
                if (!$this->categoryExists()) {
                    $validator->errors()->add(
                        'category',
                        'The selected category does not exists'
                    );
                }
            },
            // validating brand
            function (Validator $validator) {
                if (!$this->brandExists()) {
                    $validator->errors()->add(
                        'brand',
                        'The selected brand does not exists'
                    );
                }
            }
        ];
    }

    /**
     * Customized messages for the failed validations
     * 
     * @return array
     */
    public function messages(): array
    {
        return [
            'name' => [
                'required' => 'Please enter product name',
                'min' => 'Product name should contain atleast 3 characters',
                'max' => 'Product name can\'t be greater than 100 characters',
            ],
            'brand.gt' => 'Please select product brand',
            'category.gt' => 'Please select product category',
            'variation_type.required_if' => 'Please select variation type',
            'sub_variation_type.required_if' => 'Please select sub variation type',
            'mrp' => [
                'required' => 'Please enter the MPR of the product(s)',
                'gt' => 'One or more products have invalid MRP',
            ],
            'selling_price' => [
                'required' => 'Please enter the selling price of the product(s)',
                'gt' => 'One or more products have invalid selling price',
                'lte' => 'Product(s) selling price can\'t be greater than the MRP of product(s)',
            ],
            'qty' => [
                'required' => 'Please enter the quantity of the Product(s)',
                'gt' => 'Invalid product quantity',
            ],
            'variation_name.required' => 'Please enter the product(s) variation name(s)',
        ];
    }

    /**
     * Check for the selected category exists or not
     */
    private function categoryExists(): bool
    {
        if ($this->category == 0) return true;
        $exists = Category::find($this->category);
        return $exists != null;
    }

    /**
     * Check for the selected brand exists or not
     */
    private function brandExists(): bool
    {
        if ($this->brand == 0) return true;
        $exists = Brand::find($this->brand);
        return $exists != null;
    }
}
