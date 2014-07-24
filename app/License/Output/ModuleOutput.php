<?php namespace License\Output;


use License\Output\BaseModuleOutput;


class ModuleOutput extends BaseModuleOutput {

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

	    if ( ! empty($module->purchased_key))
	    {
	    	$module->days_left = '-';
	    	$module->expired_at = '-';
	    }

	    // Get min price
	    $min_price = $this->getCheapestType($module->types);

	    // Update module data 
	    $module->image = 'http://' . $_SERVER['HTTP_HOST'] . "/public/modules/" . $module->code . '/logo-md.png';
	    $module->regular_payment = false;
	    $module->purchased = $module_purchased;
	    $module->min_price = $min_price;

	    if ($module->inserted_key == 'DEMO')
	    {
	    	$module->is_demo_key = true;
	    }
	    
	    return $module;
	}
	
}