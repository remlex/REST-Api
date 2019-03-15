<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
// http://localhost:8000/api/v1/users/
Route::group(['prefix' => 'v1', 'middleware' => 'cors'], function(){
    Route::post('/register', ['uses'=> 'AuthController@store']);
    Route::post('/signin', ['uses'=> 'AuthController@signin']);
    Route::get('/users', ['uses'=> 'AuthController@allUser']);
    Route::get('/users/{id}', ['uses'=> 'AuthController@allUserId']);
    Route::put('/users/update/{id}', ['uses'=> 'AuthController@updateUser']);
    Route::delete('/users/delete/{id}', ['uses'=> 'AuthController@deleteUser']);
});
