<?php

use License\Repositories\KeyRepository;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/


Route::get('modules/get', '\License\Controllers\ModulesController@get');
Route::resource('modules', '\License\Controllers\ModulesController');

Route::get('/', function()
{
	$keyRepository = new KeyRepository;

	// if ($keyRepository->find('DEMO')) {
	// 	return 'hey!';
	// } else {
	// 	return false;
	// }
	
	// print_r($keyRepository->index()->toArray());

	
});
