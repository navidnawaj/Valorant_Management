<?php

namespace App\Http\Controllers;

use App\Models\TeamPlayer;
use App\Models\User;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    public function index()
    {
        return view('invite');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
        ]);

        // Player creation
        $password = rand(12345678, 999999999);
        $player = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($password),
            'role' => 'player',
        ]);

        // create team player
        $team_player = TeamPlayer::create([
            'team_id' => auth()->id(),
            'player_id' => $player->id,
        ]);

        // return [
        //     'name' => $player->name,
        //     'email' => $player->email,
        //     'password' => $password,
        //     'player' => $player,
        //     'player team' => $team_player->load('player', 'team'),
        // ];

        // sending mail
        // Mail::to($player->email)->send(new PlayerCreated($player));

        session()->flash('success', 'Player created successfully');

        return back();
    }
}
