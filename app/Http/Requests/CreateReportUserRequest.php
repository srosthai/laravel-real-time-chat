<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateReportUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->sanitize();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'reported_to' => ['required', 'integer', 'exists:users,id'],
            'notes' => ['required', 'string'],
        ];
    }

    public function sanitize()
    {
        $input = $this->all();

        $input['notes'] = isset($input['notes']) ? htmlspecialchars($input['notes']) : '';

        $this->replace($input);
    }
}
