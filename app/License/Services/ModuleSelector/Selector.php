<?php namespace License\Services\ModuleSelector;


use License\Services\ModuleSelector\SelectorInterface;
use DB;


class Selector implements SelectorInterface 
{

	protected $language_code;
	protected $domain;

	
	function __construct($domain, $language_code)
	{
		$this->domain = $domain;
		$this->language_code = $language_code;
	}


	public function make()
	{
		return $this->findModules();
	}


	/**
	 * Fetch modules info
	 *
	 * @return mixed
	 */
	private function findModules()
	{
		return DB::table('modules as m')
			->select(
				'm.*',
				DB::raw("(" . $this->getModuleKeys() . ") as key_expired_at"),
				DB::raw("(" . $this->getModuleType() . ") as module_type"),
				DB::raw("(" . $this->getPurchasedKey() . ") as purchased_key"),
				DB::raw("(" . $this->getName() . ") as name"),
				DB::raw("(" . $this->getDescription() . ") as description"),
				DB::raw("(" . $this->getCategory() . ") as category")
			);
	}


	/**
	 * Populate simple module with type (with calculated price)
	 *
	 * @return mixed
	 */
	public function populateWithTypes($module)
	{
		// Get module types (basic, pro...)
		$module_types = $this->types($module->id);
		$purchased_modules = $this->allPurchased();

		// Set active state to the module type
		foreach ($module_types as $key => $type)
		{
			// Set active module type if purchased
			if (isset($module->module_type) AND $module->module_type == $type->id)
			{
				if ( ! $this->isPurchased($module->code, $purchased_modules))
				{
					$module_types[$key]->is_trial = true;
				}
				
				$module_types[$key]->active = true;
			}

			// Calculate version price
			$module_types[$key]->real_price = $module_types[$key]->price;
			$module_types[$key]->real_max_price = $module_types[$key]->price + $module->price;

			if ( ! $this->isPurchased($module->code, $purchased_modules))
			{
				$module_types[$key]->price += $module->price;
			}
		}

		// Store module types
		$module->types = $module_types;

		return $module;
	}


	/**
	 * Chekc if module to domain exists
	 *
	 * @return bool
	 */
	public function isPurchased($module_code, $purchased_modules)
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


	// Here we are going to get module active keys
	private function getModuleKeys()
	{
		return "
			SELECT `k`.`expired_at` 
            FROM `keys` as `k` 
            WHERE 
            	`k`.`module_code` = `m`.`code` AND 
            	`k`.`domain` = '" . $this->domain . "' 
            LIMIT 1 
		";
	}


	// Get module type id
	private function getModuleType()
	{
		return "
			SELECT `k`.`module_type` 
            FROM `keys` as `k` 
            WHERE 
            	`k`.`module_code` = `m`.`code` AND 
            	`k`.`domain` = '" . $this->domain . "' 
            LIMIT 1 
		";
	}

	
	// Check if module was purchased
	private function getPurchasedKey()
	{
		return "
			SELECT `k`.`key` 
            FROM `keys` as `k` 
            WHERE 
            	`k`.`module_code` = `m`.`code` AND 
            	`k`.`domain` = '" . $this->domain . "' AND 
            	`k`.`key` != 'DEMO' 
            LIMIT 1 
		";
	}


	// Get localized name
	private function getName()
	{
		return "
			SELECT `ml`.`name` 
            FROM `modules_language` as `ml` 
            WHERE 
            	`ml`.`module_id` = `m`.`id` AND 
            	`ml`.`language_code` = '" . $this->language_code . "' 
            LIMIT 1 
		";
	}


	// Get localized description
	private function getDescription()
	{
		return "
			SELECT `ml`.`description` 
            FROM `modules_language` as `ml` 
            WHERE 
            	`ml`.`module_id` = `m`.`id` AND 
            	`ml`.`language_code` = '" . $this->language_code . "' 
            LIMIT 1 
		";
	}


	// Get localized category
	private function getCategory()
	{
		return "
			SELECT `ml`.`category` 
            FROM `modules_language` as `ml` 
            WHERE 
            	`ml`.`module_id` = `m`.`id` AND 
            	`ml`.`language_code` = '" . $this->language_code . "' 
            LIMIT 1 
		";
	}

}