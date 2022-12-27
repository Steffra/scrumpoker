<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;

use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Room;


class UpdateLobbyEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $rooms;

    public function __construct()
    {
        $this->rooms = Room::with('players')->get()->toArray();
    }

    public function broadcastOn()
    {

        return (new Channel('lobby'));
    }
}
