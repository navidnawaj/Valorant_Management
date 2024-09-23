<?php

namespace App\Http\Controllers;

use App\Models\TeamPlayer;
use App\Models\User;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    public function index()
    {
        $current_team = auth()->user();
        $team_players = $current_team->players->load('player');
        // return $team_players;

        return view('invite', compact('team_players'));
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
            // 'sponsor' => auth()->sponsor(),
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

    public function delete(User $user)
    {
        $user->delete();

        session()->flash('success', 'Player deleted successfully');
        return back();
    }
}
