<?php namespace License\Models;


class Module extends \Eloquent
{

	public function types()
	{
		return $this->hasMany('License\Models\ModuleType');
	}
	
}