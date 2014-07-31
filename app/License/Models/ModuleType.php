<?php namespace License\Models;


use DB;


class ModuleType extends \Eloquent {

	protected $table = 'module_type';


	/**
	 * Get the best tariff type of module
	 *
	 * @return mixed
	 */
	public function best($module_code)
	{
		return DB::table('module_type AS mt')
			->select('mt.*')
			->where(
				'mt.module_id',
				DB::raw("(SELECT id FROM modules WHERE code = '" . $module_code . "' LIMIT 1)")
			)
			->orderBy('mt.price', 'DESC')
			->first()
			->id;
	}

}