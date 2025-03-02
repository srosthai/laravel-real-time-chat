<?php

namespace App\Http\Requests;

use App\Rules\NoSpaceContaine;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ChangePasswordRequest
 */
class ChangePasswordRequest extends FormRequest
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
        $rules['password'] = ['required', 'string', 'min:8', 'max:30', 'confirmed', new NoSpaceContaine()];

        return $rules;
    }
}
