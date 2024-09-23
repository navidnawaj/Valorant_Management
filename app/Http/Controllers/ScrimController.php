<?php

namespace App\Http\Controllers;

use App\Models\Scrim;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ScrimController extends Controller
{
    public function index()
    {
        $scrim_requests = Scrim::query()
                                ->with('teamFrom')
                                ->where('team_to_id', auth()->id())
                                ->where('status', 'pending')
                                ->latest()
                                ->get();

        $scrim_scheduled = Scrim::query()
                                ->with('teamFrom','teamTo')
                                ->where(function($q){
                                    return $q->where('team_to_id', auth()->id())
                                                ->orWhere('team_from_id', auth()->id());
                                })
                                ->where('status','!=', 'pending')
                                ->where('status','!=', 'rejected')
                                ->latest()
                                ->get()
                                ->map(function($scrim){
                                    $scrim->is_me = $scrim->team_from_id == auth()->id();
                                    return $scrim;
                                });

        return view('scrims', compact('scrim_requests', 'scrim_scheduled'));
    }

    public function request(Request $request)
    {
        $request->validate([
            'team_id' => 'required',
            'date' => 'required|date|after:now',
            'time' => 'required',
        ]);

        Scrim::create([
            'team_from_id' => auth()->id(),
            'team_to_id' => $request->team_id,
            'date' => Carbon::parse($request->date)->format('Y-m-d'),
            'time' => Carbon::parse($request->time)->format('H:i:s'),
        ]);

        session()->flash('success', 'Scrim request sent successfully');
        return back();
    }


    public function acceptScrim(Scrim $scrim)
    {
        $scrim->update([
            'status' => 'accepted',
        ]);

        session()->flash('success', 'Scrim request accepted successfully');
        return back();
    }

    public function rejectScrim(Scrim $scrim)
    {
        $scrim->update([
            'status' => 'rejected',
        ]);

        session()->flash('success', 'Scrim request rejected successfully');
        return back();
    }

    public function submitScrim(Request $request){
        $request->validate([
            'scrim_id' => 'required',
            'image' => 'required|image',
        ]);

        $scrim = Scrim::find($request->scrim_id);

        if($request->hasFile('image')){
            $image = $request->file('image');
            $imageName = time().'.'.$image->extension();
            $image->move(public_path('images'), $imageName);
            $scrim->image = $imageName;
        }
        $scrim->status = 'finished';
        $scrim->save();


        session()->flash('success', 'Scrim submitted successfully');
        return back();
    }
}
