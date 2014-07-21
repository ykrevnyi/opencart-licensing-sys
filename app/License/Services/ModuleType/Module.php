<?php namespace License\Services\ModuleType;


use License\Services\ModuleSelector\ModuleSelector;
use License\Services\ModuleType\TypeInterface;
use License\Services\ModuleType\ModuleType;
use License\Services\ModuleType\RegularPaymentType;
use License\Services\ModuleType\OneTimePaymentType;
use License\Output\ModuleOutput;


class Module 
{

	// Module code
	private $code;

	// Simply the module type (regular price or one time purchase)
	private $type;

	// Special (helper) class that will return query for selecting modules
	private $moduleSelector;

	// Module info
	private $info;

	
	function __construct($module_code, $domain, $language_code)
	{
		$this->moduleSelector = new ModuleSelector($domain, $language_code);
		
		$this->code = $module_code;
	}


	/**
	 * Set base information of the module
	 *
	 * @return void
	 */
	public function create($module = NULL)
	{
		$this->info = empty($module) ? $this->fetch() : $module;

		// Resolve module type
		if ($this->info->regular_payment) {
			$type = new RegularPaymentType;
		} else {
			$type = new OneTimePaymentType;
		}

		$this->setType($type);

		return $this;
	}


	/**
	 * Get module info
	 *
	 * @return mixed
	 */
	public function info()
	{
		// Create new module type validator
		$v = new TypeValidator($this->info);

		// Resolve formater type
		if ($this->isRegular())
		{
			$output = $v->isFree() ? new FreeRegularModuleOutput : new RegularModuleOutput;
		}
		else
		{
			$output = $v->isFree() ? new FreeModuleOutput : new ModuleOutput;
		}
		
		return $output->format($this->info);
	}


	/**
	 * Define if module price is regular (every year) or static (one time purchase)
	 *
	 * @return bool
	 */
	public function isRegular()
	{
		return $this->type->isRegular();
	}


	/**
	 * Set module type (regular or one time purchase)
	 *
	 * @return void
	 */
	private function setType(TypeInterface $type)
	{
		$this->type = $type;
	}


	/**
	 * Get module by it's code
	 *
	 * @return mixed
	 */
	private function fetch()
	{
		$module = $this->moduleSelector->find($this->code);
		$module = $this->moduleSelector->populateWithTypes($module);

		return $module;
	}

}