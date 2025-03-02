<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFrontCmsRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'sub_heading'            => 'required|max:50',
            'description'            => 'required|max:700',
            'landing_image'          => 'mimes:jpeg,png,jpg',
            'feature_title_1'        => 'required|max:50',
            'feature_title_2'        => 'required|max:50',
            'feature_title_3'        => 'required|max:50',
            'feature_title_4'        => 'required|max:50',
            'feature_text_1'         => 'required|max:150',
            'feature_text_2'         => 'required|max:150',
            'feature_text_3'         => 'required|max:150',
            'feature_text_4'         => 'required|max:150',
            'feature_image_1'        => 'mimes:jpeg,png,jpg',
            'feature_image_2'        => 'mimes:jpeg,png,jpg',
            'feature_image_3'        => 'mimes:jpeg,png,jpg',
            'feature_image_4'        => 'mimes:jpeg,png,jpg',
            'features_image'         => 'mimes:jpeg,png,jpg',
            'testimonials_name_1'    => 'required|max:30',
            'testimonials_name_2'    => 'required|max:30',
            'testimonials_name_3'    => 'required|max:30',
            'testimonials_comment_1' => 'required|max:250',
            'testimonials_comment_2' => 'required|max:250',
            'testimonials_comment_3' => 'required|max:250',
            'testimonials_image_1'   => 'mimes:jpeg,png,jpg',
            'testimonials_image_2'   => 'mimes:jpeg,png,jpg',
            'testimonials_image_3'   => 'mimes:jpeg,png,jpg',
            'start_chat_title'       => 'required|max:30',
            'start_chat_subtitle'    => 'required|max:100',
            'start_chat_image'       => 'mimes:jpeg,png,jpg',
            'footer_description'     => 'required|max:600',
        ];
    }

    /**
     * @return string[]
     */
    public function attributes()
    {
        return [
            'feature_title_1'        => 'title 1',
            'feature_title_2'        => 'title 2',
            'feature_title_3'        => 'title 3',
            'feature_title_4'        => 'title 4',
            'feature_text_1'         => 'feature description',
            'feature_text_2'         => 'feature description',
            'feature_text_3'         => 'feature description',
            'feature_text_4'         => 'feature description',
            'testimonials_name_1'    => 'testimonials name',
            'testimonials_name_2'    => 'testimonials name',
            'testimonials_name_3'    => 'testimonials name',
            'testimonials_comment_1' => 'testimonials comment',
            'testimonials_comment_2' => 'testimonials comment',
            'testimonials_comment_3' => 'testimonials comment',
        ];
    }
}
