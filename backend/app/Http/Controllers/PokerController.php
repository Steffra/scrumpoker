<?php

namespace App\Http\Controllers;

use App\Events\UpdateRoomEvent;
use App\Events\UpdateLobbyEvent;
use App\Services\PokerService;
use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Player;

class PokerController extends Controller
{
    private PokerService $PokerService;

    public function __construct(PokerService $PokerService)
    {
    }

    public function room(Request $request, string $id = "")
    {
        return Room::with('players')->where('id', $id)->first();
    }

    public function rooms(Request $request)
    {
        $data = $this->getPostDataFromRequest($request);
        if (!Player::where('id', $data['playerId'])->first() || Player::where('id', $data['playerId'])->first()->name != $data['playerName']) {
            abort(401, 'Unauthorized');
        }

        return Room::with('players')->get();
    }

    public function createroom(Request $request)
    {
        $room = new Room();
        $data = $this->getPostDataFromRequest($request);
        $room->name = $data['name'];
        $room->is_showing_cards = false;
        $room->save();
        return $room;
    }

    public function toggleRoom(Request $request, string $id = "")
    {
        $room = Room::find($id);
        $room->is_showing_cards = !$room->is_showing_cards;
        $room->save();
        if (!$room->is_showing_cards) {
            foreach (Player::where('room_id', $room->id)->get() as $player) {
                $player->card  = null;
                $player->save();
            }
        }
        event(new UpdateRoomEvent($room));
    }

    public function createplayer(Request $request, string $name = "")
    {
        $player = new Player();
        $player->name = $name;
        $player->save();
        return $player;
    }

    public function joinroom(Request $request)
    {
        $data = $this->getPostDataFromRequest($request);

        $player = Player::where('id', $data['player'])->first();
        $originalRoom =  Room::find($player->room_id);
        $player->room_id = $data['room'];
        $player->card = null;
        $player->save();

        if ($originalRoom && $originalRoom->players->isEmpty()) {
            $originalRoom->is_showing_cards = false;
            $originalRoom->save();
        }
        event(new UpdateLobbyEvent());
        if ($data['room']) event(new UpdateRoomEvent(Room::find($data['room'])));
        if ($originalRoom) event(new UpdateRoomEvent($originalRoom));
        return  $data;
    }

    public function setPlayerCardValue(Request $request)
    {
        $data = $this->getPostDataFromRequest($request);
        $player = Player::where('id', $data['player'])->first();

        if (Room::find($player->room_id)->first()->is_showing_cards) {
            return $data;
        }

        if ($player->card == $data['card']) {
            $player->card = null;
        } else {
            $player->card = $data['card'];
        }

        $player->save();
        event(new UpdateRoomEvent(Room::find($player->room_id)));
        return  $data;
    }

    private function getPostDataFromRequest(Request $request): array
    {
        $content = $request->getContent();
        if ($content == null) {
            return [];
        }
        return json_decode($content, true);
    }
}
