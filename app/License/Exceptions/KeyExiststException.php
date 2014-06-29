<?php namespace License\Exceptions;


use App;
use Response;
use View;


class KeyExiststException extends BaseException {}


App::error(function(KeyExiststException $e, $code) {
	return Response::make(
		View::make('common/key-exists-exception')
			->with('params', $e->getParams())
	);
});