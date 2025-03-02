<?php

namespace App\Http\Requests;

use App\Models\ZoomMeeting;
use Illuminate\Foundation\Http\FormRequest;

class CreateMeetingRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return ZoomMeeting::$rules;
    }

    /**
     * @return string[]
     */
    public function messages()
    {
        return [
            'agenda.required' => 'Description field is required.',
        ];
    }
}
