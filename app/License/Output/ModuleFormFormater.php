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
	    
	    return array(
			"id" 			=> $module->id,
			"purchased_key"	=> $module->purchased_key,
	    	"image" 		=> 'http://' . $_SERVER['HTTP_HOST'] . "/public/modules/" . $module->code . '/logo-md.png',
			"title" 		=> $module->name,
			"category" 		=> $module->category,
			"updated_at" 	=> "Updated " . $module->updated_at,
			"min_price"		=> $this->getCheapestModuleType($module->types),
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

}