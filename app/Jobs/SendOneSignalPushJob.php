<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use OneSignal;

class SendOneSignalPushJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $playerIds;

    private $heading;

    private $message;

    private $options;

    /**
     * Create a new job instance.
     *
     * @param  array  $playerIds
     * @param  string  $heading
     * @param  bool  $message
     * @param  array  $options
     */
    public function __construct($playerIds, $heading, $message, $options = [])
    {
        $this->playerIds = $playerIds;
        $this->heading = $heading;
        $this->message = $message;
        $this->options = $options;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->sendOneSignalPush($this->playerIds, $this->message, $this->heading, $this->options);
    }

    /**
     * @param  array  $playerIds
     * @param  string  $message
     * @param  string  $headings
     * @param  array  $options
     * @return void|bool
     */
    public function sendOneSignalPush($playerIds, $message, $headings, $options = [])
    {
        $parameters = [
            'headings' => [
                'en' => $headings,
            ],
            'contents' => [
                'en' => isset($options['image']) ? 'Image Sent !' : $message,
            ],
            'chrome_web_icon' => isset($options['image']) ? $options['image'] : '',
            'include_player_ids' => $playerIds,
        ];
        $result = OneSignal::sendNotificationCustom($parameters);

        return true;
    }
}
