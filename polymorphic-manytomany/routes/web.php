<?php

use Illuminate\Support\Facades\Route;
use App\Models\Video;
use App\Models\Post;
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

//create post, and create and add tag
Route::get('create/post/tag', function () {
    $post = Post::create(['name' => 'my first post']);
    $tag = Tag::create(['name' => 'first creations']);
    $post->tags()->save($tag);

    echo '<h2>Post created and tagged</h2>';

    $video = Video::create(['name' => 'My first Video']);
    $video->tags()->save($tag);

    echo '<h2>Video created and tagged</h2>';
});

//Read Post and tag
Route::get('{media}/{id}/tags', function($media, $id) {
    if ($media === "post"){
        $media = Post::findOrFail($id);
    } elseif ($media === 'video'){
        $media = Video::findOrFail($id);
    }
    echo "<h2>$media->name</h2>";
    echo "<ul>";
    foreach($media->tags as $tag){
        echo "<li>$tag->name</li>";
    }
    echo "</ul>";
});

//Update tag name
Route::get('update/tag/{id}', function($id){
    $tag = Tag::findOrFail($id)->update(['name'=>'new tag']);

    echo "<h2>Tag updated</h2>";
});

//assign tag to media
Route::get('{media}/{id}/assign/{tag_id}', function($media, $id, $tag_id){
    if ($media === "post"){
        $media = Post::findOrFail($id);
    } elseif ($media === 'video'){
        $media = Video::findOrFail($id);
    }

    $media->tags()->sync([$tag_id]);
    echo "<h2>$media->name assigned tag: " . Tag::find($tag_id)->name . "</h2>";
});

//remove tag from media
Route::get('{media}/{id}/delete/{tag_id}', function($media, $id, $tag_id){
    if ($media === "post"){
        $media = Post::findOrFail($id);
    } elseif ($media === 'video'){
        $media = Video::findOrFail($id);
    }

    foreach($media->tags as $tag){
        if ($tag->id === (int)$tag_id){
            echo "<h2>Deleting tag: $tag->name from $media->name </h2>";
            $media->tags()->delete($tag->id);
        }
    }
});


Route::get('', function(){});


Route::get('', function(){});
