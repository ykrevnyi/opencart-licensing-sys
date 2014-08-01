<?php namespace License\Repositories;


use License\Exceptions\ModuleNotFoundException;
use License\Output\ModuleListOutput;
use License\Models\Module;
use DB;
use View;
use Input;

use License\Services\ModuleSelector\ModuleSelector;
use License\Services\Module\Module as ModuleService;
use License\Services\Module\ModuleList as ModuleServiceList;



class ModuleRepository 
{

	private $domain;
	private $language_code = 'en';


	function __construct($domain)
	{
		$this->setLanguage(Input::get('language_code', 'en'));
		$this->domain = $domain;
	}

	
	/**
	 * Simply get all avalible modules
	 *
	 * @return mixed
	 */
	public function all()
	{
		$module = new ModuleServiceList(
			$this->domain, 
			$this->language_code
		);

		return $module->all();
	}


	/**
	 * Find module by its code with all data (types, selected type)
	 *
	 * @return mixed
	 */
	public function find($module_code)
	{
		$module = new ModuleService(
			$module_code, 
			$this->domain, 
			$this->language_code
		);

		$module_info = $module->create()->info();
		
		// // Ok, we have found some module
		if ($module_info)
		{
			return $module_info;
		}

		throw new ModuleNotFoundException("Module not found", 0, NULL, array(
			'module_code' => $module_code
		));
	}

	// ?store


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


	/**
	 * Set repository language
	 *
	 * @return void
	 */
	private function setLanguage($language_code)
	{
		if (empty($language_code) OR $language_code != 'ru')
		{
			$language_code = 'en';
		}
		
		$this->language_code = $language_code;
	}


}