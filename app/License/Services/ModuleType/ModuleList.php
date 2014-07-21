<?php namespace License\Services\ModuleType;


use License\Services\ModuleSelector\ModuleSelector;


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

		return $output->format($this->info);
	}

}