<?php namespace License\Repositories;


use License\Exceptions\ModuleNotFoundException;
use License\Models\Module;
use DB;
use View;


class ModuleRepository 
{
	
	/**
	 * Simply get all avalible modules
	 *
	 * @return mixed
	 */
	public function all($domain)
	{
		// Here we are going to get module active keys
		$module_keys = "
			SELECT `k`.`expired_at` 
            FROM `keys` as `k` 
            WHERE 
            	`k`.`module_code` = `m`.`code` AND 
            	`k`.`domain` = '" . $domain . "' 
            LIMIT 1 
		";

		// Get module type id
		$module_type = "
			SELECT `k`.`module_type` 
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
				DB::raw("(" . $module_keys . ") as key_expired_at"),
				DB::raw("(" . $module_type . ") as module_type")
			)
			->get();

		return $this->format($modules);
	}


	/**
	 * Get module by it's code
	 *
	 * @return array
	 */
	public function getModule($module_code, $domain)
	{
		// Get module type id
		$module_type = "
			SELECT `k`.`module_type` 
            FROM `keys` as `k` 
            WHERE 
            	`k`.`module_code` = `m`.`code` AND 
            	`k`.`domain` = '" . $domain . "' 
            LIMIT 1 
		";

		return (array) DB::table('modules as m')
			->select(
				'm.*',
				DB::raw("(" . $module_type . ") as module_type")
			)
			->where('code', $module_code)
			->first();
	}


	/**
	 * Find module by its code with all data (types, selected type)
	 *
	 * @return mixed
	 */
	public function find($module_code, $domain)
	{
		// Get module id in order to use build in relations in framework
		$module = $this->getModule($module_code, $domain);

		// Ok, we have found some module
		if ($module)
		{
			$module_info = $this->populateModuleWithTypes($module, $domain);

			return $module_info;
		}

		throw new ModuleNotFoundException("Module not found", 0, NULL, array(
			'module_code' => $module_code
		));
	}


	/**
	 * Get types of every module.
	 *
	 * Also we will set active state to the purchased module
	 *
	 * @return mixed
	 */
	public function getModulesTypes($modules, $domain)
	{
		foreach ($modules['apps'] as $key => $module)
		{
			$modules['apps'][$key] = $this->populateModuleWithTypes($module, $domain);
		}

		return $modules;
	}


	/**
	 * Get the best tariff type of module
	 *
	 * @return mixed
	 */
	public function getBestModuleType($module_code)
	{
		return DB::table('module_type AS mt')
			->select('mt.*')
			->where(
				'mt.module_id',
				DB::raw("(SELECT id FROM modules WHERE code = 'menu' LIMIT 1)")
			)
			->orderBy('mt.price', 'DESC')
			->first()
			->id;
	}


	/**
	 * Populate simple module with type (with calculated price)
	 *
	 * @return mixed
	 */
	private function populateModuleWithTypes($module, $domain)
	{
		// Get module types (basic, pro...)
		$module_types = Module::find($module['id'])->types->toArray();
		$purchased_modules = $this->getPurchasedModules($domain);

		// Set active state to the module type
		foreach ($module_types as $key => $type)
		{
			// Set active module type if purchased
			if (isset($module['module_type']) AND $module['module_type'] == $type['id'])
			{
				$module_types[$key]['active'] = true;
			}

			// Calculate version price
			if ( ! $this->moduleIsPurchased($module['code'], $purchased_modules))
			{
				$module_types[$key]['price'] += $module['price'];
			}
		}

		// Store module types
		$module['types'] = $module_types;

		return $module;
	}


	/**
	 * Get list of purchased modules to the domain
	 *
	 * @return mixed
	 */
	private function getPurchasedModules($domain)
	{
		return DB::table('keys as k')
			->where('domain', $domain)
			->where('active', 1)
			->where('key', '!=', 'DEMO')
			->groupBy('module_code')
			->lists('module_code');
	}


	/**
	 * Chekc if module to domain exists
	 *
	 * @return bool
	 */
	private function moduleIsPurchased($module_code, $purchased_modules)
	{
		foreach ($purchased_modules as $code)
		{
			if ($code == $module_code)
			{
				return true;
			}
		}

		return false;
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
				"id" 			=> $module->id,
		    	"image" 		=> 'http://' . $_SERVER['HTTP_HOST'] . "/public/modules/" . $module->code . '/logo-md.png',
				"title" 		=> $module->name,
				"category" 		=> $module->category,
				"updated_at" 	=> "Updated " . $module->updated_at,
				"price" 		=> $module->price . "$",
		        "code" 			=> $module->code,
		        "module_type" 	=> $module->module_type,
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