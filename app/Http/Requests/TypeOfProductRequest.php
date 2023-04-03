<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TypeOfProductRequest extends FormRequest
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

        if(request()->isMethod('post')) {
            return [
                'name' => 'required|string|max:258',
                'id_type' => 'required',
                'description' => 'required|string',
                'unit_price' => 'required',
                'promotion_price' => 'required',
                'image' => 'required|image|mimes:jpeg,png,webp,jpg,gif,svg|max:2048',
                'stock' => 'required',
                'unit' => 'required',
                'new' => 'required'
            ];
        } else {
            return [
                'name' => 'required|string|max:258',
                'id_type' => 'required',
                'description' => 'required|string',
                'unit_price' => 'required',
                'promotion_price' => 'required',
                'image' => 'nullable|image|mimes:jpeg,png,webp,jpg,gif,svg|max:2048',     
                'stock' => 'required',          
                'unit' => 'required',
                'new' => 'required'
            ];
        }
        // return [
        //     //
        // ];
    }

    public function messages()
    {
        if(request()->isMethod('post')) {
            return [
                'name.required' => 'Name is required!',
                'id_type.required' => 'Type Products is required!',
                'description.required' => 'Descritpion is required!',
                'unit_price.required' => 'Unit Price is required!',
                'promotion_price.required' => 'Promotion Price is required!',
                'image.required' => 'Image is required!',
                'stock.required' => 'Number of products in stock is required!',
                'unit.required' => 'Unit is required!',
                'new.required' => 'New is required!'
            ];
        } else {
            return [
                'name.required' => 'Name is required!',
                'id_type.required' => 'Type Products is required!',
                'description.required' => 'Descritpion is required!',
                'unit_price.required' => 'Unit Price is required!',
                'promotion_price.required' => 'Promotion Price is Required!',
                'stock.required' => 'Number of products in stock is required!',
                'unit.required' => 'Unit is required!',
                'new.required' => 'New is required!'
            ];   
        }
    }
}
