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
	    $module->image = 'http://' . $_SERVER['HTTP_HOST'] . "/public/modules/" . $module->code . '/logo-md.png';
    	$module->regular_payment = true;
	    $module->min_price = $min_price;
	    $module->is_demo_key = true;

    	// If module is free we will override some params 
    	// to fit `purchased` module type
    	
    	// Remove all `active` keys from module type
	    foreach ($module->types as $key => $type)
	    {
	    	unset($module->types[$key]->active);
	    }

	    // Check if inserted key is DEMO key
	    if ($module->inserted_key != 'DEMO' OR $module->inserted_key == 'DEMO' AND $module->days_left <= 0)
	    {
	    	$module->is_demo_key = false;
	    	$module->purchased_key = 'FREE';
	    	$module->module_type = $module->types{0}->id;
	    	$module->expired_at = '-';
	    	$module->days_left = '-';
	    	$module->purchased = true;
	    	$module->types{0}->active = true;
	    }
	    
	    return $module;
	}
	
}