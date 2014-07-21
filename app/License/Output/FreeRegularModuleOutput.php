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
    	$module->regular_payment = true;
	    $module->module_purchased = $module_purchased;
	    $module->min_price = $min_price;

    	// If module is free we will override some params 
    	// to fit `purchased` module type
    	
    	// Remove all `active` keys from module type
	    foreach ($module->types as $key => $type)
	    {
	    	unset($module->types[$key]->active);
	    }

    	$module->purchased_key = 'FREE';
    	$module->module_type = $module->types{0}->id;
    	$module->expired_at = '-';
    	$module->days_left = '-';
    	$module->module_purchased = true;
    	$module->types{0}->active = true;
	    
	    return $module;
	}
	
}