<?php

use Illuminate\Support\Facades\Route;

use App\Models\User;
use App\Models\Post;

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

// Read users posts
Route::get('user/{id}/posts', function ($id) {
   $user = User::findOrFail($id);
   echo "<ul>";
   foreach($user->posts as $post){
       echo "<li>$post->title</li>";
   }
   echo "</ul>";
});

//Create new user post
Route::get('create/user/{id}/post', function($id){
    $user = User::findOrFail($id);
    $post = new Post(['title' => "Elegance", 'content' => "I am a man of elegant taste, as you can plainly see."]);
    $user->posts()->save($post);
    echo "User post has been saved";
    echo "<br><br>";
    echo $post;
});

//Update user specified post
Route::get('update/user/{id}/post/{post_id}', function($id, $post_id){
    $user = User::findOrFail($id);
    foreach($user->posts as $post){
        if ($post->id === (int)$post_id){
            $post->title = 'Breasts';
            $post->save();
            echo "<h2>Post $post_id updated!</h2>";
        }
    }
});

//deleting Users specified post
Route::get('delete/user/{id}/post/{post_id}', function($id, $post_id){
    $user = User::findorFail($id)->posts()->where('id', $post_id)->first()->delete();
    echo "<h2>User Post Deleted</h2>";
});


