<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SlideRequest extends FormRequest
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
            'name' => 'nullable|string|max:258',
            // 'image' => 'nullable|image|mimes:jpeg,png,webp,jpg,gif,svg|max:2048',   
            'image' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'image.required' => 'Image is required!',
        ];
    }
}
