<?php namespace License\Exceptions;


use App;
use Response;
use View;


class KeyShouldHaveTimeException extends BaseException {}


App::error(function(KeyShouldHaveTimeException $e, $code) {
	return Response::make(
		View::make('common/key-should-have-time-exception')
			->with('params', $e->getParams())
	);
});