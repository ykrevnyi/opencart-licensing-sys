<?php namespace License\Controllers;

use License\Models\User;
use Cookie;
use Event;
use Input;
use View;


class UserController extends BaseController {

	public function login()
	{
		$data = Input::all();

		$rules = [
			'email' => 'required|email|min:6',
			'password'  => 'required|min:6'
		];

		$val = \Validator::make($data, $rules);

		if ($val->passes())
		{
			$auth = \Auth::attempt([ 
				'email' => $data['email'], 
				'password' => $data['password'] 
			], true);

			if ($auth)
				return \Redirect::to('/');	
		} 

		return \Redirect::to('/')->withErrors($val->errors()->add('error', 'Эл. почта или пароль неправильные!'));
	}

	public function register()
	{
		$data = Input::all();

		$rules = [
			'email' => 'required|email|min:6|unique:users',
			'password'  => 'required|min:6|same:repeat_password',
			'repeat_password'  => 'required|min:6'
		];

		$val = \Validator::make($data, $rules);

		if ($val->passes())
		{
			$user = \User::create([
				'email' => $data['email'],
				'password' => \Hash::make($data['password'])
			]);

			return \Redirect::to('/')->with(\Auth::login($user, 'true'));
		}


		return \Redirect::to('/registration')->withErrors($val->errors());
	}	
}
