<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard()
    {
        if (auth()->user()->role == 'team') {
            $data['teams'] = User::where('role', 'team')->where('id', '!=', auth()->id())->get();
        }else {
            $data = [];
        }

        return view('dashboard', $data);
    }
}
