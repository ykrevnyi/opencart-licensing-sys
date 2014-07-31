<?php 

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;


class User extends \Eloquent implements UserInterface, RemindableInterface {

	/**
	* The database table used by the model.
	*
	* @var string
	*/
	protected $table = 'users';
	public static $unguarded = true;
	/**
	* The attributes excluded from the model's JSON form.
	*
	* @var array
	*/
	protected $hidden = array('password', 'remember_token');

	public function getAuthIdentifier() { return $this->getKey(); }
	public function getAuthPassword() { return $this->password; }
	public function getReminderEmail() { return $this->email; }
	public function getRememberToken() { return $this->remember_token; }
	public function setRememberToken($value) { $this->remember_token = $value; }
	public function getRememberTokenName() { return 'remember_token'; }
	
}

