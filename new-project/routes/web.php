<?php

use Illuminate\Support\Facades\Route;
use App\Models\Post;
use App\Models\User;
use App\Models\Country;
use App\Models\Photo;
use App\Models\Video;
use App\Models\Tag;
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

// Route::get('/', function () {



//     return "Hello, there";
// });

// Route::get('/about', function () {
//     return "Hi About Page";
// });

// Route::get('/contact', function () {
//     return "Hi contact Page";
// });

// Route::get('/post/{id}/', function ($id) {

//     return "Here is your variable: " . $id;

// });

// Route::get('/admin/posts/example', array( 'as' => 'admin.home' ,function(){

//     $url = route('admin.home');

//     return "this url is" . $url;


// }));

// // Route::get('/post/{data}', '\App\Http\Controllers\PostsController@index');

// Route::resource('posts', '\App\Http\Controllers\PostsController');

// Route::get('/contact', 'App\Http\Controllers\PostsController@contact');

// Route::get('/post/{id}', 'App\Http\Controllers\PostsController@show_post');

/*
|--------------------------------------------------------------------------
| DATABASE raw database queries
|--------------------------------------------------------------------------
*/

Route::get('/insert', function() {

    DB::insert('insert into posts(title, content) values(?, ?)', ['JavaScript', 'I also really like javaScript!']);

});

// Route::get('/read', function(){
//    $posts = DB::select('select * from posts where id = ?', [1]);

//    foreach($posts as $post) {
//        return $post->title;
//    }

// });

// Route::get('/update', function () {

//     $updated = DB::update('update posts set title = "new title" where id = ?', [1]);

// });

// Route::get('/delete', function () {

//     $deleted = DB::delete('delete from posts where id = ?', [1]);
// });

/*
|--------------------------------------------------------------------------
| ELOQUENT database (Object Relational Model)
|--------------------------------------------------------------------------
*/
//retrieve array of all table rows
Route::get('/read', function(){
    $posts = Post::all();

    foreach($posts as $post) {
        return $post->title;
    }

});
//find specific table row by id
Route::get('/find/{id}', function($id) {
    $post = Post::find((int)$id);

    return $post->title;
});
//find specific table row based on columns
Route::get('/findwhere', function () {
    $posts = POST::where('id', 3)->orderBy('id', 'desc')->take(1)->get();

    return $posts;

});

Route::get('/findmore', function (){
    // $posts = Post::findOrFail(1);
    // return $posts;

    $posts = Post::where('users_count', '<', 50)->firstorFail();
});

//ORM Object Relational Mapper

//create new row
Route::get('/basicinsert', function() {
    $post = new Post;
    $post->title = "I want to learn Python";
    $post->content = "Python will hopefully be the next language I learn";
    $post->save();
});
//update row
Route::get('basicupdate', function () {
    $post = Post::find(3);
    $post->title = "JavaScript is best";
    $post->save();
});

//creating data - mass Assignment
Route::get('/create', function(){
    Post::create(["title" => "the create method", "content" => "Lots of learning to be had for PHP"]);
});

//updating
Route::get('/update', function(){
    Post::where('id', 2)->where('is_admin', 0)->update(['title'=>"New PHP Title", 'content'=>"instructor is actually pretty good"]);
});

//deleteing
Route::get('delete', function(){
    $post = Post::find(5);
    $post->delete();
});
Route::get('newdelete', function (){
    Post::destroy(4);
});
Route::get('deletemultiple', function(){
    Post::destroy(2,3);
});
// Route::get('deleteall', function() {
//     Post::all()->delete();

// });

//Soft delete (TRASHING)

Route::get('softdelete', function(){
    Post::find(2)->delete();
});
//retireve deleted items
Route::get('retrieve', function(){
    //does not return soft deleted item
    //return Post::find(2);

    //returns specific post if soft deleted or not
    // $post = Post::withTrashed()->where('id', 2)->get();
    // return $post;

    //returns all trashed posts and only trashed posts
    $posts = Post::onlyTrashed()->where('is_admin', 0)->get();
    return $posts;
});

//Restoring Soft Deletes

Route::get('restore', function(){

    Post::onlyTrashed()->where('is_admin', 0)->restore();

});

//permanently delete after soft delete

Route::get('forceddelete', function(){
    Post::onlyTrashed()->where('is_admin', 0)->forceDelete();
});

/*
|--------------------------------------------------------------------------
| ELOQUENT RELATIONSHIPS (Object Relational Model)
|--------------------------------------------------------------------------
*/
//one to one relationship
Route::get('/user/post/{id}', function($id){

    return User::find((int)$id)->post;

});

//inverse realtion ship
Route::get('/post/{id}/user', function($id){
    return Post::find((int)$id)->user;
});

//One to many relationship
Route::get('/user/{id}/posts', function($id){
    $user = User::find((int)$id);
    //return $user->posts;
    foreach($user->posts as $post){
        echo $post . "<br>";
    }
});

//many to many relationship (pivot table)
Route::get('user/{id}/roles', function($id){
    // $user = User::find((int)$id);
    // foreach($user->roles as $role){
    //     return $role;
    // }

    $role = User::find($id)->roles()->orderBy('name', "desc")->get();
    return $role;
});


//Many to Many intermediate table
Route::get('user/{id}/pivot', function($id){
    $user = User::find((int)$id);
    foreach($user->roles as $role){
        echo $role->pivot->created_at;
    }
});

//relational queries
Route::get('user/country/{id}', function($id){
    $country = Country::find((int)$id);
    foreach($country->posts as $post){
        echo $post->title . "<br>";
    }
});

//PolyMorphic Relation

route::get('user/{id}/photos', function($id){
    $user = User::find((int)$id);
    foreach($user->photos as $photo) {
        echo $photo->path . "<br><br>";
    }
});

route::get('post/{id}/photos', function($id){
    $post = Post::find((int)$id);
    foreach($post->photos as $photo) {
        echo $photo->path . "<br><br>";
    }
});

route::get('photo/{id}/post', function($id){
    $photo = Photo::findOrFail((int)$id);
    $post = Post::find((int)$photo->imageable_id);
    echo $post . "<br><br>";
});

route::get('video/{id}/tags', function($id){
    $video = Video::find((int)$id);
   // echo $video;
    foreach($video->tags as $tag){
        echo $tag->name . "<br><br>";
    }
});

route::get('tag/{id}/owners', function($id){
    $tag = Tag::find((int)$id);

    echo "<H1>Tag: $tag->name<H1>";

    echo "<h3>Posts</h3><br><br>";
    foreach($tag->posts as $post){
        echo $post . '<br><br>';
    }

    echo "<h3>Videos</h3><br><br>";
    foreach($tag->videos as $video){
        echo $video . '<br><br>';
    }
});
route::get('', function(){});
route::get('', function(){});
route::get('', function(){});
