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
		// Check if our selected module type (with key `active`)
	    // is the cheapest 
	    $module_type_is_the_cheapest = true;

	    foreach ($this->module->types as $key => $type)
	    {
	    	if (
	    		! empty($type->active) 
    			AND $key > 0 
    			AND $this->module->purchased_key != 'DEMO' 
    			AND $this->module->days_left > 0)
	    	{
	    		$module_type_is_the_cheapest = false;
	    	}
	    }

	    // Check module type min price
	    $real_min_price = 99999;

	    foreach ($this->module->types as $type)
	    {
	    	if ($type->real_price < $real_min_price)
	    	{
	    		$real_min_price = $type->real_price;
	    	}
	    }

	    // If module type price is 0 AND we have selected cheapest module
	    // we will return new `module types` array
	    if ($module_type_is_the_cheapest AND $real_min_price <= 0)
	    {
	    	return true;
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