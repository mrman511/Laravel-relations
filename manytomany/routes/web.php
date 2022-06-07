<?php

use Illuminate\Support\Facades\Route;

use App\Models\User;
use App\Models\Role;

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

//reading user Roles
Route::get('user/{id}/roles', function ($id) {
    $user = User::findOrFail($id);
    echo "<h2>$user->name's Roles</h2>";
    echo "<ul>";
    foreach($user->roles as $role){
        echo "<li>$role->name</li>";
    }
    echo "</ul>";
});

//Creating Role
Route::get('create/role', function(){
    $role = new Role(['name'=>'CEO']);
    $role->save();
    echo "<h2>New Role Created: $role->name</h2>";
});

//assign role
Route::get('assign/user/{id}/role/{role_id}', function($id, $role_id){
    $user = User::findOrFail($id);
    $role = Role::findOrFail($role_id);
    $user->roles()->save($role);
    //$user->save();
    echo "<h2>User Role Added</h2>";
});

//Updating Role
Route::get('update/role/{id}', function($id){
    $role = Role::findOrFail($id);
    $role->name = "Administator";
    $role->save();
});

//Delete user role
Route::get('delete/user/{id}/role', function($id){
    $user = User::findOrFail($id);
    foreach($user->roles as $role){
        $role->where('name', 'Banned')->delete();
        // if ($role->name === 'Banned'){
        //     $user->roles()->delete($role);
        //     echo "<h2>Role Deleted</h2>";
        // }
    }
});

//properly assign role to user with attach
Route::get('user/{id}/attach/{role_id}', function($id, $role_id){
    $user = User::findOrFail($id);

    $user->roles()->attach($role_id);
});

//detach role from user
Route::get('user/{id}/removeban', function($id){
    $user = User::findOrFail($id);
    foreach($user->roles as $role){
        if ($role->name === "Banned"){
            //detach() passed without an argument will remove all Roles
            $user->roles()->detach($role->id);
            echo "<h2>Ban removed from $user->name</h2>";
        }
    }
});

//syncing
Route::get('user/{id}/sync', function($id){
    $user = User::findOrFail($id);
    $user->roles()->sync([2, 3]);
    echo "<h2>Roles added to $user->name</h2>";
});

