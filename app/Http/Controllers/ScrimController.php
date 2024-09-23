<?php

namespace App\Http\Controllers;

use App\Models\Scrim;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ScrimController extends Controller
{
    public function index()
    {
        return view('scrims');
    }

    public function request(Request $request)
    {
        $request->validate([
            'team_id' => 'required|exists:users,id',
            'date' => 'required|date|after:now',
        ]);

        Scrim::create([
            'team_from_id' => auth()->id(),
            'team_to_id' => $request->team_id,
            'date' => Carbon::parse($request->date)->format('Y-m-d'), 
            'time' => Carbon::parse($request->time)->format('H:i:s'),
        ]);

        session()->flash('success', 'Scrim request sent successfully');
        return back();

        return [
            'team_id' => $request->team_id,
            'date' => $request->date,
            'time' => $request->time,
        ];

        
    }
 

    public function accept($id)
    {
        $scrim = Scrim::findOrFail($id);
        $scrim->update([
            'status' => 'accepted',
        ]);

        session()->flash('success', 'Scrim request accepted successfully');
        return back();
    }

    public function reject($id)
    {
        $scrim = Scrim::findOrFail($id);
        $scrim->update([
            'status' => 'rejected',
        ]);

        session()->flash('success', 'Scrim request rejected successfully');
        return back();
    }
}
