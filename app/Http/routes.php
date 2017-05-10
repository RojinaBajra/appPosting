<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::group(['prefix' => 'api/v1/', 'namespace' => 'Api\v1'], function () {


	Route::resource('thread', 'ThreadController');
	Route::post('thread/{id}/comment', 'ThreadController@comment');
	 Route::put('thread/{id}/comment/{comment_id}', 'ThreadController@editComment');
	 Route::get('thread/{id}/viewReplies','ThreadController@viewReplies');
	 Route::delete('thread/{id}/comment/{comment_id}', 'ThreadController@deleteReply');
        
});
