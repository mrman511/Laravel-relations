<?php

use Illuminate\Support\Facades\Route;

use App\Models\User;
use App\Models\Address;

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
    echo "<h1>One to One</h1>";
});

Route::get('insert/{id}', function($id){

    $user = User::findOrFail((int)$id);
    $address = new Address;
    $address->name = "26 royal ave, New Westminster BC, Canada";
    $user->address()->save($address);
});

Route::get('update/{id}', function($id){
    $address = Address::where('user_id', (int)$id)->first();
    $address->name = '1776 w 29th ave Vancouver, BC, Canada';
    $address->save();
});


Route::get('read/{id}', function($id){
    $user = User::findOrFail((int)$id);
    echo $user->name . "<br><br>";
    echo "Address: " . $user->address->name;
});

Route::get('delete/{id}', function($id){
    $user = User::findOrFail((int)$id);
    $user->address()->delete();
});

// Route::get('', function(){});
// Route::get('', function(){});
