<?php

namespace App\Http\Requests;

/**
 * Class BlockUserRequest
 */
class BlockUserRequest
{
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules['blocked_to'] = 'required';
        $rules['is_blocked'] = 'required';

        return $rules;
    }
}
