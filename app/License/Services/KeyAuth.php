<?php namespace License\Services;


use License\Repositories\KeyRepository;
use License\Repositories\ModuleRepository;
use License\Services\KeyAuthGenerator;
use License\Exceptions\KeyExiststException;
use License\Exceptions\KeyShouldHaveTimeException;


class KeyAuth 
{

	private $generator;

	private $keyRepo;
	private $modulesRepo;

	public $is_trial = false;

	public $key = "";
	public $domain = "";
	public $key_time = 0;
	public $expired_at = 0;
	public $module_code = "";
	public $module_type = NULL;
	public $transaction_id = NULL;


	function __construct() {
		$this->generator = new KeyAuthGenerator;
		$this->keyRepo = new KeyRepository;
		$this->modulesRepo = new ModuleRepository;
	}


	/**
	 * Create and store new key
	 *
	 * @return void
	 */
	public function make()
	{
		if ($this->exists())
		{
			throw new KeyExiststException("Key already exists", 0, NULL, array(
				'module_code' => $this->module_code,
				'domain' => $this->domain
			));
		}

		return $this->createKey();
	}


	/**
	 * Facade method to create trial key
	 *
	 * @return void
	 */
	public function trial($domain, $module_code)
	{
		$this->domain = $domain;
		$this->module_code = $module_code;
		$this->module_type = $this->modulesRepo->getBestModuleType($module_code);
		$this->is_trial = true;
		$this->key_time = 60*60*24*7;

		$this->make();
	}


	/**
	 * Check if key to:
	 * - domain
	 * - menu code
	 * already exist.
	 *
	 * @return bool
	 */
	private function exists()
	{
		$key = $this->keyRepo->findByModule(
			$this->module_code, 
			$this->domain
		);
		
		// If we got a row back, there is a key that matched in the database
		return $key ? true : false;
	}


	/**
	 * Stores the key in the database
	 * @return null
	 */
	private function createKey()
	{
		if ( ! $this->key_time)
		{
			throw new KeyShouldHaveTimeException("Key should have time");
		}

		$this->expired_at = time() + $this->key_time;

		// Generate key
		$this->key = $this->generator->make($this->is_trial);

		// Insert the key into the databse
		$key = $this->keyRepo->store($this->composeKeyParams());
		
		// Check if the query was successful
		return $key ? true : false;
	}


	/**
	 * Composer all needle key params into one fillable array
	 *
	 * @return mixed
	 */
	private function composeKeyParams()
	{
		return array(
			'domain' 			=> empty($this->domain) ? NULL : $this->domain,
			'key' 				=> $this->key,
			'module_code' 		=> $this->module_code,
			'module_type' 		=> $this->module_type,
			'transaction_id'	=> $this->transaction_id,
			'active' 			=> true,
			'expired_at' 		=> date('Y-m-d G:i:s', $this->expired_at)
		);
	}


	/**
	 * Pulls key info from the database
	 * @return array|boolean Array of key info, or false if not found
	 */
	public function key_info()
	{
		$key = $this->keyRepo->getByDomain(
			$this->key,
			$this->domain
		);
		
		// Make sure we get one row
		if(count($key))
		{
			// Pull an array from the results and send it back
			return $key;
		}
		else
		{
			// No key was found, return false
			return FALSE;
		}
	}


	/**
	 * deActivate a key so it will NOT validate when checked
	 * @return boolean True if successful 
	 */
	public function deactivate()
	{
		// Check if the query was successful
		if($this->keyRepo->deActivate($this->key))
		{
			// Return true if the update was successful
			return TRUE;
		}
		else
		{
			// Send a notice if the update failed
			throw new Exception("Can not update key status");
		}
	}


	/**
	 * Returns TRUE/FALSE if a key is valid
	 * @return boolean True if key is valid
	 **/
	public function auth()
	{
		$key = $this->keyRepo->validate(
			$this->key,
			$this->domain,
			$this->module_code
		);
		
		// If we got a row back, there is a key that matched in the database
		if(count($key))
		{	
			// Check if the key expired
			// If expire os less than the current time and it does not equal 0
			// The key timestamp is in the past, so the key expired
			if($key->expired_at < time() && $key->expired_at != 0)
			{
				// Key expired, return false
				return FALSE;
			}
			// Date is still in the future or not set, so key has not expired
			else
			{
				// Return true
				return TRUE;
			}
		}
		
		// There was no row returned, so no key in the database
		else
		{
			// Return false
			return FALSE;
		}
	}


	/**
	 * return a key check in json format
	 * @return json Array of data about the string
	 **/
	public function api_json()
	{
		
		// Check if the key is valid
		$valid = $this->auth($this->key);
		
		// If it is, build a json array
		if($valid)
		{
			// Pull key info from the database
			$key = $this->key_info();
			
			// Send back valid = true
			$json['valid']  = true;
			$json['info']['id'] = $key->id;
			$json['info']['domain']  = empty($key->domain) ? "NA" : $key->domain;
			$json['info']['expired_at'] = empty($key->expired_at) ? "NA" : $key->expired_at;

			$json['info']['full_data'] = $key;

			// Get normal dates
			$today = time();
			$seconds_left = $key->expired_at - $today;
			
			// Module will be expired in N days
			$days_left = floor($seconds_left / 3600 / 24);

			// Module will be expired in 01/06/2014
			$expired_at = gmdate("d-m-Y", $key->expired_at);

			$json['info']['dates'] = array(
				'expired_at' => $expired_at,
				'days_left' => $days_left
			);

		}

		// Key was not valid, send back false
		else
		{
			// Send back false
			$json['valid'] = false;
		}
		
		// Send the json string
		return json_encode($json);
	}


}