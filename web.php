<?php

use App\Http\Controllers\ProfilerController;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::any('/',[UserController::class,'index']);
// signup logic
Route::get("/signup", [UserController::class,'signup'])->name('signup');
Route::match(['post'],'/signup/create',[UserController::class,'store'])->name('createaccount');
//login logic
Route::get("/login", [UserController::class,'login'])->name('login');
Route::post('/login/verify',[UserController::class,'verify'])->name('verify');
// forum
Route::get('/forum',function () {
    return view('main.forum');
})->middleware(['auth','role:admin.user']);
// thanks for registration
Route::get('/registration',fn() => view('main.registration'));
//market
Route::get('/market',fn() => view('main.market'))->middleware('auth');
//store
Route::match(['post','get'],'/store',fn() => view('main.store'))->middleware('auth');
//about
Route::match(['get','post'],'/aboutUs',fn(User $id,Request $request) => view('main.about',['id' => $id,'request' => $request]));
//user
Route::get('/profile/{id}',ProfilerController::class)->middleware('auth');