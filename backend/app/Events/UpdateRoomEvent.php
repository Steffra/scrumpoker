<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;

use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Room;


class UpdateRoomEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $room;

    public function __construct($room)
    {
        $this->room = Room::with('players')->where('id', $room->id)->first()->toArray();
        if (!$room->is_showing_cards) {
            foreach ($this->room['players'] as $key => $player) {
                if ($player['card'] != null) {
                    $this->room['players'][$key]['card'] = ' ';
                }
            }
        }
    }

    public function broadcastOn()
    {
        return (new Channel($this->room['id']));
    }
}
