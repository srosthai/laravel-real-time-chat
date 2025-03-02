<?php

namespace App\Http\Requests\API;

use App\Models\Group;
use Illuminate\Foundation\Http\FormRequest;

class CreateGroupRequest extends FormRequest
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
        return Group::$rules;
    }

    public function sanitize()
    {
        $input = $this->all();

        $input['name'] = isset($input['name']) ? htmlspecialchars($input['name']) : '';
        $input['description'] = isset($input['description']) ? htmlspecialchars($input['description']) : '';

        $this->replace($input);
    }
}
