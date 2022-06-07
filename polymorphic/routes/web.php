<?php

use Illuminate\Support\Facades\Route;

use App\models\Staff;
use App\models\Product;
use App\models\Photo;

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

//polymorphic insert
Route::get('staff/{id}/create/{path}', function ($id, $path) {
    $staff = Staff::findOrFail($id);
    $staff->photos()->create(['path' => $path . '.png']);
});

//Read data
Route::get('staff/{id}/photos', function ($id) {
    $staff = Staff::findOrFail($id);
    echo "<h2>$staff->name's Photos </h2><ul>";
    foreach($staff->photos as $photo){
        echo "<li>$photo->path</li>";
    }
    echo "<br>";
});

//Update data
Route::get('staff/{id}/updatepath/{path}', function ($id, $path) {
    $staff = Staff::findOrFail($id);
    $photo = $staff->photos->first();
    //echo $photo;
    $photo->path = $path . ".jpg";
    $photo->save();
});

Route::get('/delete/{id}', function ($id) {
    Photo::find($id)->delete();
});

//Assign currently existing photos to another staff member;
Route::get('staff/{id}/assign/{photo_id}', function ($id, $photo_id) {
    $staff = Staff::findOrFail($id);
    $photo = Photo::findOrFail($photo_id);
    $staff->photos()->save($photo);
});

Route::get('staff/{id}/unassign/{photo_id}', function ($id, $photo_id) {
    $staff = Staff::findOrFail($id);

    $staff->photos()->whereId($photo_id)->update(['imageable_id' => '', 'imageable_type' => '' ]);
});

