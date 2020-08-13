<?php

use App\Events\NewMessage;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::middleware('auth')
    ->get('/contacts', 'ContactController@index')
    ->name('contacts');
Route::middleware('auth')
    ->get('/contacts/{contact}/conversations', 'ConversationController@index')
    ->name('conversations');
Route::middleware('auth')
    ->post('/conversations', 'ConversationController@store')
    ->name('conversations.store');
