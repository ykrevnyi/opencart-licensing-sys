<?php namespace License\Output;


use License\Output\ModuleOutputInterface;


abstract class BaseModuleOutput implements ModuleOutputInterface {


	/**
	 * Simple format function
	 *
	 * @return mixed
	 */
	public function format($module) { return $module; }


	/**
	 * Create timestamps for the module (days left)
	 *
	 * @return mixed
	 */
	protected function timestamps($module)
	{
		$days_left = NULL;
        $expired_at = NULL;
        
		// If there is some module with key it will
		// have an key_expired_at field
		if ($module->key_expired_at)
	    {
	    	$key_expired_at = strtotime($module->key_expired_at);

	        // Get normal dates
	        $today = time();
	        $seconds_left = $key_expired_at - $today;

	        // Module will be expired in 01/06/2014
	        $expired_at = gmdate("d-m-Y", $key_expired_at);
	        
	        // Check if we have some `use` time
	        if ($seconds_left > 0)
	        {
		        // Module will be expired in N days
	        	$days_left = ceil($seconds_left / 3600 / 24);
	        }
	    }

	    // Update module data
	    $module->image = 'http://' . $_SERVER['HTTP_HOST'] . "/public/modules/" . $module->code . '/logo-md.png';
	    $module->days_left = $days_left;
	    $module->expired_at = $expired_at;
	    $module->key_expired_at_timestamp = strtotime($module->key_expired_at);

	    return $module;
	}


	/**
	 * Calculate the cheapest module type
	 *
	 * @return Number
	 */
	protected function getCheapestType($types)
	{
		$min = 0;

		foreach ($types as $type)
		{
			$min = ($type->price < $min) ? $type->price : $min;
		}

		return $min;
	}

}