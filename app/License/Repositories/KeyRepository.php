<?php namespace License\Repositories;


use License\Models\Key;


class KeyRepository 
{
	

	/**
	 * Validate key to all the criterias
	 *
	 * @return bool
	 */
	public function vaidate($key, $domain, $module_code)
	{
		Key::where('key', $key)
			->where('domain', $domain)
			->where('module_code', $module_code)
			->where('active', true)
			->first();
	}


	/**
	 * Simply find a key in the database
	 *
	 * @return mixed
	 */
	public function find($key)
	{
		return Key::where('key', $key)
			->where('active', 1)
			->get();
	}


	/**
	 * Find key that is relateded to the module 
	 * with specified domain.
	 *
	 * @param code - module code
	 * @return mixed
	 */
	public function findByModule($module_code, $domain)
	{
		return Key::where('domain', $domain)
			->where('module_code', $module_code)
			->where('active', true)
			->first();
	}


	/**
	 * Get key info where domain and key mathes
	 *
	 * @return mixed
	 */
	public function getByDomain($domain, $key)
	{
		return Key::where('key', $key)
			->where('domain', $domain)
			->where('active', true)
			->first();
	}


	/**
	 * Simple set active to be 0
	 *
	 * @return void
	 */
	public function deActivate($key)
	{
		$key = Key::whereKey($key);
		$key->active = false;
		$key->save();

		return true;
	}


}