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
Route::resource('admin', '\License\Controllers\UserController',
	[ 'only' => array('index', 'register') ] );	

Route::resource('admin.modules', '\License\Controllers\AdminModulesController');	


Route::get('/admin', function() 
{ 
	return View::make('admin.user.login'); 
});

Route::get('/admin/registration', function() 
{ 
	return View::make('admin.user.registration'); 
});

Route::get('/admin/logout', function() 
{ 
	Auth::logout(); 
	return Redirect::to('/admin'); 
});

Route::get('/admin/modules', '\License\Controllers\AdminModulesController@index');

Route::get('/admin/modules/download/{code}', '\License\Controllers\AdminModulesController@getDownload');

Route::get('/admin/modules/{code}/edit', '\License\Controllers\AdminModulesController@editModule');



Route::post('/admin/login', '\License\Controllers\UserController@login');
Route::post('/admin/registration', '\License\Controllers\UserController@register');
Route::post('/admin/modules', '\License\Controllers\AdminModulesController@updateModule');





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