<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Configurable variables
    |--------------------------------------------------------------------------
    |
    | This option controls the default time for, before how much time user can delete message
    |
    | NOTE: time in minuts
    |
    */

    'delete_message_time' => env('DELETE_MESSAGE_TIME', 60),

    'delete_message_for_everyone_time' => env('DELETE_MESSAGE_FOR_EVERYONE_TIME', 5),
];
