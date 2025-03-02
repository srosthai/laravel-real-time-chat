<?php

namespace App\Http\Requests;

use InfyOm\Generator\Request\APIRequest;

/**
 * Class UpdateUserNotificationRequest
 */
class UpdateUserNotificationRequest extends APIRequest
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

    public function rules()
    {
        $rules = [
            'is_subscribed' => 'required',
        ];

        return $rules;
    }
}
