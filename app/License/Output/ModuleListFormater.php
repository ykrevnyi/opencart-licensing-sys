<?php namespace License\Output;


class ModuleListFormater implements ModuleFormaterInterface 
{


	/**
	 * Format module that will be shown in the modules list
	 *
	 * @return array
	 */
	public function format($module)
	{
	    return array(
			"id" 			=> $module->id,
	    	"image" 		=> 'http://' . $_SERVER['HTTP_HOST'] . "/public/modules/" . $module->code . '/logo-md.png',
			"title" 		=> $module->name,
			"category" 		=> $module->category,
			"updated_at" 	=> "Updated " . $module->updated_at,
			"min_price" 	=> $this->getCheapestModuleType($module->types),
	        "code" 			=> $module->code
		);
	}


	/**
	 * Get the cheapest module type
	 *
	 * @return Number
	 */
	private function getCheapestModuleType($types)
	{
		$min = 0;

		foreach ($types as $type)
		{
			if ($type['price'] < $min)
			{
				$min = $type['price'];
			}
		}

		return $min;
	}

}