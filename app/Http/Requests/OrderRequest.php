<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            // 'gender' => 'required|string|max:4',
            'email' => 'required|email',
            'address' => 'required|string',
            'phone' => 'required|numeric|max:10',
            'note' => 'nullable'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Customer Name must be required!',
            'name.string' => 'Customer Name must be string!',
            'name.max' => 'Customer Name up to 255 characters!',
            'email.required' => 'Email must be required!',
            'email.email' => 'Email must be in the correct format!',
            'address.required' => 'Address must be required!',
            'address.string' => 'Address must be string!',
            'phone.required' => 'Phone must be required!',
            'phone.numeric' => 'Phone must be numeric!',
            'phone.max' => 'Phone number up to 10 numbers!',
        ];
    }
}
