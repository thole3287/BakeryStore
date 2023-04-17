<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BranchRequest extends FormRequest
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
            'name' => 'required|string|max:258',
            'address' => 'required|string',
            'time_open' => 'required|string',
            // 'image' => 'required|image|mimes:jpeg,png,webp,jpg,gif,svg|max:2048',
            'image' => 'required'
        ];
        // if(request()->isMethod('post')) {
            
        // } else {
        //     return [
        //         'name' => 'required|string|max:258',
        //         'description' => 'nullable|string',
        //         'image' => 'nullable|image|mimes:jpeg,png,webp,jpg,gif,svg|max:2048',
        //     ];
        // }
    }

    public function messages()
    {
        return [
            'name.required' => 'Name is required!',
            'address.required' => 'Address is required!',
            'time_open.required' => 'Time Open is required!',
            'image.required' => 'Image is required!',
        ];
        // if(request()->isMethod('post')) {
            
        // } else {
        //     return [
        //         'name.required' => 'Name is required!',
        //         'description.required' => 'Descritpion is required!',
        //     ];
        // }
       
    }
}
