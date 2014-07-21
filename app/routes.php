<?php

use License\Repositories\KeyRepository;
use License\Handlers\LicenseHandler;


// Handlers
Event::subscribe('License\Handlers\LicenseHandler');

// Routes
Route::get('modules/get', '\License\Controllers\ModulesController@get');
Route::resource('modules', '\License\Controllers\ModulesController');
Route::resource('pay', '\License\Controllers\PayController');

// Get actual version of the script
Route::get('version', function()
{
	return Response::json(array(
		'version' => '0.2'
	))->setCallback(Input::get('callback'));
});



// for test only
Route::get('/', function() { return 'home'; });
Route::post('/', function() { return 'home post'; });


// Log an url if route was not found
App::error(function(Exception $exception, $code)
{
    if ($exception instanceof Symfony\Component\HttpKernel\Exception\NotFoundHttpException)
    {
        Log::error('NotFoundHttpException Route: ' . Request::url() );
    }

    Log::error($exception);
});


// Display all SQL executed in Eloquent
// Event::listen('illuminate.query', function($query)
// {
//     echo "\n\n\n";
//     print_r($query);
//     echo "\n\n\n";
// });


// window.open('http://dev.website-builder.ru/pay?domain=opencart.dev&email=testemail@gmail.com&module_code=menu');