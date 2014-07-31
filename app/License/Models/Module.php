<?php namespace License\Models;


class Module extends \Eloquent
{

	public function types()
	{
		return $this->hasMany('License\Models\ModuleType');
	}

	public function lang11($language_code = 'en')
	{
		return $this->hasOne('License\Models\ModuleTypeLanguage');
	}
	
}