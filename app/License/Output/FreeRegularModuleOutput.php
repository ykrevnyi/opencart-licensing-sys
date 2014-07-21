<?php namespace License\Output;


use License\Output\BaseModuleOutput;


class FreeRegularModuleOutput extends BaseModuleOutput {

	/**
	 * Simple format function
	 *
	 * @return mixed
	 */
	public function format($module)
	{
		
		$module = $this->timestamps($module);

	    // Check if module was purchased
	    $module_purchased = empty($module->purchased_key) ? false : true;

	    // Get min price
	    $min_price = $this->getCheapestType($module->types);

	    // Update module data 
	    $module->module_purchased = $module_purchased;
	    $module->min_price = $min_price;

	    // Remove all `active` keys from module type
	    foreach ($module->types as $key => $type)
	    {
	    	// unset($module->types[$key]->active);
	    }
	    
	    return $module;
	}
	
}