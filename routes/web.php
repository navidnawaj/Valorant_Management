<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScrimController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/invite',[PlayerController::class,'index'])->name('invite');
    Route::post('/invite/player',[PlayerController::class,'store'])->name('invite.player');
    Route::get('/delete/player/{user}',[PlayerController::class,'delete'])->name('player.delete');

    Route::get('/dashboard',[DashboardController::class,'dashboard'])->name('dashboard');

    Route::get('/scrims',[ScrimController::class,'index'])->name('scrims');
    Route::get('/scrims/accept/{scrim}',[ScrimController::class,'acceptScrim'])->name('scrims.accept');
    Route::get('/scrims/reject/{scrim}',[ScrimController::class,'rejectScrim'])->name('scrims.reject');
    Route::post('/scrims/submit',[ScrimController::class,'submitScrim'])->name('scrims.submit');
    Route::post('/dashboard/request',[ScrimController::class,'request'])->name('scrim.request');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
