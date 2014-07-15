<?php namespace License\Repositories;


use License\Exceptions\ModuleNotFoundException;
use License\Output\ModuleFormaterInterface;
use License\Output\ModuleListFormater;
use License\Output\ModuleFormFormater;
use License\Models\Module;
use DB;
use View;


class ModuleRepository 
{

	/**
	 * Base selector of the modules
	 *
	 * @return mixed
	 */
	private function selectModules($domain)
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

		// Check if module was purchased
		$purchased_key = "
			SELECT `k`.`key` 
            FROM `keys` as `k` 
            WHERE 
            	`k`.`module_code` = `m`.`code` AND 
            	`k`.`domain` = '" . $domain . "' AND 
            	`k`.`key` != 'DEMO' 
            LIMIT 1 
		";


		// Fetch modules info
		return DB::table('modules as m')
			->select(
				'm.*',
				DB::raw("(" . $module_keys . ") as key_expired_at"),
				DB::raw("(" . $module_type . ") as module_type"),
				DB::raw("(" . $purchased_key . ") as purchased_key")
			);
	}
	
	/**
	 * Simply get all avalible modules
	 *
	 * @return mixed
	 */
	public function all($domain)
	{
		$modules = (array) $this->selectModules($domain)->get();
		$modules = $this->getModulesTypes($modules, $domain);

		return $this->format(new ModuleListFormater, $modules);
	}


	/**
	 * Get module by it's code
	 *
	 * @return array
	 */
	public function getModule($module_code, $domain)
	{
		return (array) $this->selectModules($domain)
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
		foreach ($modules as $key => $module)
		{
			$modules[$key] = (object) $this->populateModuleWithTypes($module, $domain);
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
		$module = (array) $module;

		// Get module types (basic, pro...)
		$module_types = Module::find($module['id'])->types->toArray();
		$purchased_modules = $this->getPurchasedModules($domain);

		// Set active state to the module type
		foreach ($module_types as $key => $type)
		{
			// Set active module type if purchased
			if (isset($module['module_type']) AND $module['module_type'] == $type['id'])
			{
				if ( ! $this->moduleIsPurchased($module['code'], $purchased_modules))
				{
					$module_types[$key]['is_trial'] = true;
				}
				
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
	public function format(ModuleFormaterInterface $formater, $modules)
	{
		$result = array();

		foreach ($modules as $module)
		{
			$result[] = $formater->format($module);
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


	/**
	 * Increment amount of module downloads
	 *
	 * @return void
	 */
	public function incrementDownload($module_code)
	{
		$module = Module::whereCode($module_code)->first();
		$module->increment('downloads');
	}


}