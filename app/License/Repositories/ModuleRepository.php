<?php namespace License\Repositories;


use License\Exceptions\ModuleNotFoundException;
use License\Output\ModuleFormaterInterface;
use License\Output\ModuleListFormater;
use License\Output\ModuleFormFormater;
use License\Models\Module;
use DB;
use View;
use Input;

use License\Services\ModuleSelector\ModuleSelector;
use License\Services\ModuleType\Module as ModuleService;
use License\Services\ModuleType\ModuleList as ModuleServiceList;



class ModuleRepository 
{

	private $domain;
	private $language_code = 'en';


	function __construct($domain, $language_code) {
		$this->setLanguage(Input::get('language_code', 'en'));

		$this->domain = $domain;
		$this->language_code = $language_code;
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