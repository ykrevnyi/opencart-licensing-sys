<?php namespace License\Services\Module;


class TypeValidator
{

	private $module;


	function __construct($module) {
		$this->module = $this->timestamps($module);
	}

	
	/**
	 * Check if module is free (has `type` with price == 0)
	 *
	 * @return bool
	 */
	public function isFree()
	{
		foreach ($this->module->types as $key => $type)
		{
			if ($key == 0 AND empty($this->module->days_left) AND $type->real_price == 0 )
			{
				return true;
			}
		}

		return false;
	}


	private function timestamps($module)
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
	    $module->days_left = $days_left;
	    $module->expired_at = $expired_at;

	    return $module;
	}

}