<?php

namespace App\Http\Requests;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class AssignEmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()->is_admin;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'employee' => 'required | numeric | exists:users,id',
            'shop' => 'required | numeric | exists:shops,id',
        ];
    }
    
    public function after(): array {

        return [
            function (Validator $validator) {
                if ($this->isEmployeeAdmin() || $this->isEmployeeAlreadyAssigned()) {
                    $validator->errors()->add(
                        'invalid_employee',
                        'Selected user can\'t be assigned as an employee to this shop'
                    );
                }
            }
        ];
    }

    private function isEmployeeAdmin() {
        $result = User::find($this->employee)->where('is_admin', 0)->get();
        return !(sizeof($result) > 0);
    }

    private function isEmployeeAlreadyAssigned() {
        $result = Shop::where('emp_id', $this->employee)->get();
        return sizeof($result) > 0;
    }
}
