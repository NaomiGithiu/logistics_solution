<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'phone_number' => [
                'required',
                'regex:/^0\d{9}$/',  // Start with 0, followed by 9 digits (any digit)
                'digits:10',
            ],
            'email' => 'required|email|unique:users',
            'role' => 'required',
        ];
    }
    
    public function messages()
    {
        return [
            'phone_number.regex' => 'Phone number must start with 0 and contain exactly 10 digits.',
            'phone_number.digits' => 'Phone number must be exactly 10 digits.',
        ];
    }
    

    public function passedValidation()
    {
        $this->merge([
            'phone_number' => preg_replace('/^0/', '+254', $this->phone_number),
        ]);
    }
}
