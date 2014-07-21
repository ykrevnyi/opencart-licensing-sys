<?php namespace License\Services\ModuleSelector;


use License\Services\ModuleSelector\Selector;
use DB;


class ModuleSelector extends Selector
{
	
	/**
	 * Get module by it's code
	 *
	 * @return mixed
	 */
	public function find($module_code)
	{
		return $this->make()
			->where('code', $module_code) 
			->first();
	}


	/**
	 * Get module types by it's id
	 *
	 * @return mixed
	 */
	public function types($module_id)
	{
		return DB::table('module_type as mt')
			->select(
				'mt.*',
				'mtl.name'
			)
			->join('module_type_language as mtl', 'mtl.module_type_id', '=', 'mt.id')
			->where('mt.module_id', $module_id)
			->where('mtl.language_code', $this->language_code)
			->get();
	}


	/**
	 * Get list of purchased modules to the domain
	 *
	 * @return mixed
	 */
	public function allPurchased()
	{
		return DB::table('keys as k')
			->where('domain', $this->domain)
			->where('active', 1)
			->where('key', '!=', 'DEMO')
			->groupBy('module_code')
			->lists('module_code');
	}

}