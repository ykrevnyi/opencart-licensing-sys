<?php namespace License\Services\Module;


use License\Services\ModuleSelector\ModuleSelector;
use License\Output\ModuleListOutput;


class ModuleList 
{

	// Special (helper) class that will return query for selecting modules
	private $moduleSelector;

	// 
	private $module_list;

	
	function __construct($domain, $language_code)
	{
		$this->moduleSelector = new ModuleSelector($domain, $language_code);
	}


	/**
	 * Get list of modules
	 *
	 * @return mixed
	 */
	public function all()
	{
		$output = new ModuleListOutput;

		$modules = $this->moduleSelector->all();
		$modules = $this->getModulesTypes($modules);

		return $output->format($modules);
	}


	/**
	 * Get types of every module.
	 *
	 * Also we will set active state to the purchased module
	 *
	 * @return mixed
	 */
	private function getModulesTypes($modules)
	{
		foreach ($modules as $key => $module)
		{
			$modules[$key] = $this->moduleSelector->populateWithTypes($module);
		}

		return $modules;
	}

}