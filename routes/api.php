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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


// List articles
Route::get('articles','ArticleController@index');

// List all titles
Route::get('title','ArticleController@getAllTitles');

// List all titles
Route::post('titleByClass','ArticleController@getByTitle');

//List single article
Route::get('article/{id}','ArticleController@show');

//Create new article
Route::post('article','ArticleController@store');

//Update article
// Route::put('article','ArticleController@store');

//Update descrption
Route::put('updatePicture','ArticleController@updateDescription');

//Delete article
Route::delete('delete','ArticleController@destroy');
