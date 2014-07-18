<?php namespace License\Output;


class ModuleFormFormater implements ModuleFormaterInterface 
{


	/**
	 * Format module that will be shown in the modules list
	 *
	 * @return array
	 */
	public function format($module)
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

	    // Check if module was purchased
	    $module_purchased = empty($module->purchased_key) ? false : true;

	    // Get min price
	    $min_price = $this->getCheapestModuleType($module->types);

	    // Check if this module has free version
	    $free_module_types = $this->isFreeModule($module, $days_left);
	    if ($free_module_types)
	    {
	    	// If module is free we will override some params 
	    	// to fit `purchased` module type
	    	$module->purchased_key = 'FREE';
	    	$module->module_type = $module->types[0]['id'];
	    	$expired_at = '-';
	    	$days_left = '-';
	    	$module_purchased = true;
	    	$free_module_types[0]['active'] = true;
	    	$module->types = $free_module_types;
	    }
	    
	    return array(
			"id" 			=> $module->id,
			"version"		=> $module->version,
			"purchased_key"	=> $module->purchased_key,
	    	"image" 		=> 'http://' . $_SERVER['HTTP_HOST'] . "/public/modules/" . $module->code . '/logo-md.png',
			"title" 		=> $module->name,
			"description" 	=> $module->description,
			"downloads" 	=> $module->downloads,
			"category" 		=> $module->category,
			"updated_at" 	=> "Updated " . $module->updated_at,
			"min_price"		=> $min_price,
	        "code" 			=> $module->code,
	        "module_type" 	=> $module->module_type,
	        "expired_at" 	=> $expired_at,
			"days_left" 	=> $days_left,
			"purchased" 	=> $module_purchased,
			"types"			=> $module->types
		);
	}


	/**
	 * Get the cheapest module type
	 *
	 * @return Number
	 */
	private function getCheapestModuleType($types)
	{
		$min = 0;

		foreach ($types as $type)
		{
			if ($type['price'] < $min)
			{
				$min = $type['price'];
			}
		}

		return $min;
	}


	/**
	 * Check if modules has free versions
	 *
	 * @return array|bool
	 */
	private function isFreeModule($module, $days_left)
	{
	    // Check if our selected module type (with key `active`)
	    // is the cheapest 
	    $module_type_is_the_cheapest = true;
	    $free_module_types = $module->types;

	    foreach ($free_module_types as $key => $type)
	    {
	    	if (
	    		! empty($type['active']) 
    			AND $key > 0 
    			AND $module->purchased_key != 'DEMO' 
    			AND $days_left > 0)
	    	{
	    		$module_type_is_the_cheapest = false;
	    	}

	    	// Here we will remove all the `active` keys
	    	unset($free_module_types[$key]['active']);
	    }

	    // Check module type min price
	    $real_min_price = 99999;

	    foreach ($module->types as $type)
	    {
	    	if ($type['real_max_price'] < $real_min_price)
	    	{
	    		$real_min_price = $type['real_max_price'];
	    	}
	    }

	    // If module type price is 0 AND we have selected cheapest module
	    // we will return new `module types` array
	    if ($module_type_is_the_cheapest AND $real_min_price <= 0)
	    {
	    	return $free_module_types;
	    }

	    return false;
	}


}