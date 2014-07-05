<?php

use License\Repositories\KeyRepository;
use License\Handlers\LicenseHandler;

// Handlers
Event::subscribe('License\Handlers\LicenseHandler');

// Routes
Route::get('modules/get', '\License\Controllers\ModulesController@get');
Route::resource('modules', '\License\Controllers\ModulesController');
Route::resource('pay', '\License\Controllers\PayController');




// for test only
Route::get('/', function()
{
	$keyRepository = new KeyRepository;

	return View::make('emails.key-created');
});


// Display all SQL executed in Eloquent
// Event::listen('illuminate.query', function($query)
// {
//     echo "\n\n\n";
//     print_r($query);
//     echo "\n\n\n";
// });


// window.open('http://dev.website-builder.ru/pay?domain=opencart.dev&email=testemail@gmail.com&module_code=menu');