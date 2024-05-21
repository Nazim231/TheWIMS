<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShopRequest extends FormRequest
{
    private $rules = [
        'name' => 'required',
        'address' => 'required'
    ];

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        if ($this->employee) {
            $this->merge([
                'emp_id' => $this->employee,
            ]);
            
            $this->rules['emp_id'] = 'required|numeric|exists:users,id|unique:shops,emp_id';
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return $this->rules;
    }
}
