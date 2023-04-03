<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductTypeRequest extends FormRequest
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
        if(request()->isMethod('put')) {
            return [
                'name'=>'required',
                'description'=> 'required',
                'image' => 'required|image|mimes:jpeg,png,webp,jpg,gif,svg|max:2048',
            ];
        } else {
            return [
                'name'=>'required',
                'description'=> 'required',
            ];
        }
    }

    public function messages()
    {
        if(request()->isMethod('put')) {
            return [
                'name.required' => 'Name of Type Product is required!',
                'description.required' => 'Description of Product Type is required!',
                'image.required' => 'Image of Product Type is required!'
            ];
        } else {
            return [
                'name.required' => 'Name of Type Product is required!',
                'description.required' => 'Description of Product Type is required!',
            ];   
        }
    }
}
