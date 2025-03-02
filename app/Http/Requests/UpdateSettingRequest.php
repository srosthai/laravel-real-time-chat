<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'app_name'          => 'required|string',
            'company_name'      => 'required|string',
            'app_logo'          => 'mimes:jpeg,png,jpg',
            'favicon_logo'      => 'mimes:jpeg,png,jpg',
            'pwa_icon'          => 'dimensions:max_width=512,max_height=512|mimes:jpeg,png,jpg',
            'enable_group_chat' => 'integer',
        ];
    }

    public function messages()
    {
        return [
            'pwa_icon.dimensions' => 'PWA icon must be 30x30',
        ];
    }
}
