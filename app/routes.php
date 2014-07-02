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
Route::resource('pay', '\License\Controllers\PayController');

Route::get('/', function()
{
	$keyRepository = new KeyRepository;

	return View::make('emails.key-created');

	// if ($keyRepository->find('DEMO')) {
	// 	return 'hey!';
	// } else {
	// 	return false;
	// }
	
	// print_r($keyRepository->index()->toArray());

	
});


// Display all SQL executed in Eloquent
// Event::listen('illuminate.query', function($query)
// {
//     echo "\n\n\n";
//     print_r($query);
//     echo "\n\n\n";
// });


// window.open('http://dev.website-builder.ru/pay?domain=opencart.dev&email=testemail@gmail.com&module_code=menu');