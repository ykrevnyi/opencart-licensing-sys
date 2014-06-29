<?php namespace License\Repositories;


use License\Models\Module;
use DB;


class ModuleRepository 
{
	
	/**
	 * Simply get all avalible modules
	 *
	 * @return mixed
	 */
	public function all()
	{
		$domain = 'demo.domain';

		// Here we are going to get module active keys
		$module_keys = "
			SELECT `k`.`expired_at` 
            FROM `keys` as `k` 
            WHERE 
            	`k`.`module_code` = `m`.`code` AND 
            	`k`.`domain` = '" . $domain . "' 
            LIMIT 1 
		";

		// Fetch modules info
		$modules = DB::table('modules as m')
			->select(
				'm.*',
				DB::raw("(" . $module_keys . ") as key_expired_at")
			)
			->get();

		return $this->format($modules);
	}


	/**
	 * Find module by its code
	 *
	 * @return void
	 */
	public function find($module_code)
	{
		$module = Module::whereCode($module_code)->first();

		return $module;
	}


	/**
	 * Format modules list array
	 *
	 * @return mixed
	 */
	public function format($modules)
	{
		$result = array();

		foreach ($modules as $module)
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
		        
		        // Check if we have some `use` time
		        if ($seconds_left > 0)
		        {
			        // Module will be expired in N days
		        	$days_left = floor($seconds_left / 3600 / 24);
		        	
		        	// Module will be expired in 01/06/2014
		        	$expired_at = gmdate("d-m-Y", $key_expired_at);
		        }
		    }

		    $result['apps'][] = array(
		    	"image" 		=> 'http://' . $_SERVER['HTTP_HOST'] . "/public/modules/" . $module->code . '/logo-md.png',
				"title" 		=> $module->name,
				"category" 		=> $module->category,
				"updated_at" 	=> "Updated " . $module->updated_at,
				"price" 		=> $module->price . "$",
		        "system_name" 	=> $module->code,
		        "expired_at" 	=> $expired_at,
				"days_left" 	=> $days_left
			);
		}

		return $result;
	}


	/**
	 * Get module .zip file location
	 *
	 * @return void
	 */
	public function getModuleLocation($module_code)
	{
		$module_code = htmlspecialchars($module_code);

		return $_SERVER["DOCUMENT_ROOT"] . "/public/modules/" . $module_code . ".zip";
	}



}