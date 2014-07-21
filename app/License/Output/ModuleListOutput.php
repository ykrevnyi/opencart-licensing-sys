<?php namespace License\Output;


use License\Output\BaseModuleOutput;


class ModuleListOutput extends BaseModuleOutput {

	/**
	 * Simple format function
	 *
	 * @return mixed
	 */
	public function format($modules)
	{
		foreach ($modules as $key => $module)
		{
		    // Check if module was purchased
		    $module_purchased = empty($module->purchased_key) ? false : true;

		    // Get min price
		    $min_price = $this->getCheapestType($module->types);

		    // Update module data 
		    $module->regular_payment = true;
		    $module->module_purchased = $module_purchased;
		    $module->min_price = $min_price;

		    // Update module in modules list
		    $modules[$key] = $module;
		}
	    
	    return $modules;
	}
	
}