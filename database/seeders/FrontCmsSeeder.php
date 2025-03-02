<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FrontCmsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $frontCms = [
            [
                'key'   => 'sub_heading',
                'value' => 'Powerful Laravel Chat Solution',
            ],
            [
                'key'   => 'description',
                'value' => 'Fully integrated Feature Rich Laravel Chat App Solution to enable your users to connect with each other.',
            ],
            [
                'key'   => 'landing_image',
                'value' => '',
            ],
            [
                'key'   => 'feature_title_1',
                'value' => 'beautiful design',
            ],
            [
                'key'   => 'feature_title_2',
                'value' => 'realtime conversations',
            ],
            [
                'key'   => 'feature_title_3',
                'value' => 'privacy',
            ],
            [
                'key'   => 'feature_title_4',
                'value' => 'easy installation',
            ],
            [
                'key'   => 'feature_text_1',
                'value' => 'Beautiful and easy to use design to give best possible experience.',
            ],
            [
                'key'   => 'feature_text_2',
                'value' => 'Feature rich Realtime Chat like media uploads and read receipt and much more.',
            ],
            [
                'key'   => 'feature_text_3',
                'value' => 'Privacy fist approach. Host on your own server and be in a control of your data.',
            ],
            [
                'key'   => 'feature_text_4',
                'value' => 'Easy installation with complete docs. Just traditional Laravel Setup. Nothing Extra.',
            ],
            [
                'key'   => 'features_image',
                'value' => '',
            ],
            [
                'key'   => 'testimonials_image_1',
                'value' => '',
            ],
            [
                'key'   => 'testimonials_image_2',
                'value' => '',
            ],
            [
                'key'   => 'testimonials_image_3',
                'value' => '',
            ],
            [
                'key'   => 'testimonials_name_1',
                'value' => 'John Doe',
            ],
            [
                'key'   => 'testimonials_name_2',
                'value' => 'John Doe',
            ],
            [
                'key'   => 'testimonials_name_3',
                'value' => 'John Doe',
            ],
            [
                'key'   => 'testimonials_comment_1',
                'value' => 'Awesome Chat app solution. Very easy to install and Use.',
            ],
            [
                'key'   => 'testimonials_comment_2',
                'value' => 'Great chat app to empower users with chat and connect them with each other.',
            ],
            [
                'key'   => 'testimonials_comment_3',
                'value' => 'Amazing Chat Solution with great set of features with great UI.',
            ],
            [
                'key'   => 'start_chat_title',
                'value' => 'Start using Chat now',
            ],
            [
                'key'   => 'start_chat_subtitle',
                'value' => 'Start using Chat now',
            ],
            [
                'key'   => 'start_chat_image',
                'value' => '',
            ],
            [
                'key'   => 'footer_description',
                'value' => 'We are a group of creative nerds making awesome stuff for Web &amp; Mobile. We just love to contribute to open source technologies. We always try to build something which helps developers to save their time. so they can spend a bit more time with their friends &amp; family.',
            ],
            [
                'key'   => 'feature_image_1',
                'value' => '',
            ],
            [
                'key'   => 'feature_image_2',
                'value' => '',
            ],
            [
                'key'   => 'feature_image_3',
                'value' => '',
            ],
            [
                'key'   => 'feature_image_4',
                'value' => '',
            ],
        ];

        foreach ($frontCms as $cms) {
            \App\Models\FrontCms::create($cms);
        }
    }
}
