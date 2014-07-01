<?php namespace License\Exceptions;


use App;
use Response;
use View;


class ModuleNotFoundException extends BaseException {}


App::error(function(ModuleNotFoundException $e, $code) {
	return Response::make(
		View::make('common/module-not-found-exception')
			->with('params', $e->getParams())
	);
});