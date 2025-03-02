<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Class UserEvent
 */
class UserEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $data;

    private $userIds;

    /**
     * Create a new event instance.
     *
     * @param  array  $data
     * @param  int|array  $userIds
     */
    public function __construct($data, $userIds)
    {
        $this->data = $data;
        $this->userIds = $userIds;
    }

    /**
     * @return array|PrivateChannel
     */
    public function broadcastOn()
    {
        $channels = [];

        if (is_array($this->userIds)) {
            foreach ($this->userIds as $userId) {
                array_push($channels, new PrivateChannel('user.'.$userId));
            }

            return $channels;
        }

        return new PrivateChannel('user.'.$this->userIds);
    }

    /**
     * @return array
     */
    public function broadcastWith(): array
    {
        return $this->data;
    }
}
