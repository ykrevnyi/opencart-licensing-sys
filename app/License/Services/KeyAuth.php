<?php namespace License\Services;


use License\Repositories\KeyRepository;


class KeyAuth 
{
	
	/**
	 * Number of characters in each chunk.
	 * This is the core part of the key
	 * @var integer
	 */
	public $key_chunk = 4;

	
	/**
	 * Number of chunks in each key.
	 * This add a new section with the chunk length to the key.
	 * @var integer
	 */
	public $key_part = 5;

	
	/**
	 * This is placed at the beginning of each key if set.
	 * This would be a constant in each key generated.
	 * @var string
	 */
	public $key_pre = "";

	
	/**
	 * This is placed at the end of each key if set.
	 * This would be a constant in each key generated.
	 * @var string
	 */
	public $key_post = "";
	
	
	/**
	 * If set to TRUE, the key will be split at each key part by the key divider.
	 * @var boolean
	 */
	public $key_split = TRUE;

	
	/**
	 * The key divider, this is placed between each key part if key_split is TRUE.
	 * @var string
	 */
	public $key_div = "-";

	
	/**
	 * If set, the key will be stored along with this string, when authenticating,
	 * the user would need to supply a valid key and it matching key string. If
	 * set the key will be stored in the database even if key_store is FALSE.
	 * Requires key_unique to be set TRUE.
	 * @var string
	 */
	public $key_match = "";

	
	/**
	 * If TRUE, the key will be stored in the database even if there is no key_match set.
	 * Requires key_unique to be set TRUE
	 * @var boolean
	 */
	public $key_store = FALSE;

	
	/**
	 * How long the key is valid for in seconds, if 0, key never expires.
	 * @var integer
	 */
	public $key_time = 0;
	
	
	/**
	 * A widely used variable, sets a key for use in the class.
	 * @var string
	 */
	public $key_temp = "";
	
	
	/**
	 * If set TRUE, they class will check if a key of the same value exists in the database.
	 * If a key does exist, they generation class will try and create another key.
	 * @var boolean
	 */
	public $key_unique = FALSE;


	/**
	 * Validate by module code
	 *
	 * @var string
	 */
	public $module_code = "";


	/**
	 * Define of we want to generate `DEMO` key instead of real one
	 *
	 * @return bool
	 */
	public $need_demo_key = FALSE;


	/**
	 * Store transaction id
	 *
	 * @return number
	 */
	public $transaction_id = NULL;

	
	/**
	 * The low end of the random number ASCII range.
	 * @var integer
	 */
	private $num_range_low = 48;

	
	/**
	 * The high end of the random number ASCII range.
	 * @var integer
	 */
	private $num_range_high = 57;

	
	/**
	 * The low end of the random letter ASCII range.
	 * @var integer
	 */
	private $chr_range_low = 65;

	
	/**
	 * The high end of the random letter ASCII range.
	 * @var integer
	 */
	private $chr_range_high = 90;


	function __construct() {
		$this->keyRepository = new KeyRepository;
	}


	/**
	 * Clean a user supplied string
	 * @param string|integer Input to be cleaned
	 * @return string|integer Input cleaned
	 */
	private function clean($value)
	{
		// Clean the input of SQL Injection
		if (get_magic_quotes_gpc())
		{
			// Remove the slashes that magic_quotes added
			$value = stripslashes($value);
		}
		
		if (!is_numeric($value))
		{
			// Escape and ' or " to remove SQL Injections
			$value = mysql_real_escape_string($value);
		}

		return $value;
	}


	/**
	 * Check if a key of the same name exist in the database
	 * @return boolean True if key is unique
	 */
	private function check()
	{
		$key = $this->keyRepository->find($this->key_temp);

		// If we get exactly one row returned
		if(count($key))
		{
			// Key is not unique
			return FALSE;
		}
		else
		{
			// Got 0 rows back, key is unique
			return TRUE;
		}
	}


	/**
	 * Check if key to:
	 * - domain
	 * - menu code
	 * already exist.
	 *
	 * @return bool
	 */
	public function key_to_module_exists()
	{
		$key = $this->keyRepository->findByModule(
			$this->module_code, 
			$this->key_match
		);
		
		// If we got a row back, there is a key that matched in the database
		if(count($key))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}


	/**
	 * Stores the key in the database
	 * @return null
	 */
	private function store_key()
	{
		// If key_time = 0, dont store a timestamp, if it != 0, store a
		// Timestamp with key_time added.
		$time = 0;

		if ($this->key_time != 0) {
			$time = time() + $this->key_time;
		}

		// Set testing key
		if ( ! $this->key_temp)
		{
			$this->key_temp  = "TEST";
		}
		
		// Insert the key into the databse
		$new_key = $this->keyRepository->store(
			$this->key_temp,
			$this->key_match,
			$time,
			true,
			$this->module_code,
			$this->transaction_id
		);
		
		// Check if the query was successful
		if ($new_key)
		{
			// Return true if the update was successful
			return TRUE;
		}
		else
		{
			// Return a notice if the update failed
			throw new Exception("Error while storing key");
		}
	}


	/**
	 * Pulls key info from the database
	 * @return array|boolean Array of key info, or false if not found
	 */
	public function key_info()
	{
		$key = $this->keyRepository->getByDomain(
			$this->key_temp,
			$this->key_match
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
		if($this->keyRepository->deActivate($this->key_temp))
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
		$key = $this->keyRepository->validate(
			$this->key_temp,
			$this->key_match,
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
	 * Generate the key, checking for unique
	 * @return string The generated key
	 */
	public function generate_key()
	{
		if ($this->need_demo_key == TRUE)
		{
			$this->key_temp = "DEMO";
			$this->store_key();

			return $this->key_temp;
		}

		// Hush PHP be settings varables
		$key = "";
		
		// Loop through each key part
		for($i = 0; $i != $this->key_part; $i++)
		{
			// Add random character to current part
			for($x = 0; $x != $this->key_chunk; $x++)
			{
				// Generate a random character or number and append it to the string
				$key .= (
						// Generate a random bit switch, 1=number, 0=letter
						mt_rand()&1==1 ?
							// Generate a random number
							chr(mt_rand($this->num_range_low,$this->num_range_high))
							:
							// Genreate a radnom letter
							chr(mt_rand($this->chr_range_low,$this->chr_range_high))
						);
			}
			
			// If key_split is true, add the key_div, else, add nothing
			$key .= $this->key_split ? $this->key_div : "";	
		}
		
		// Trim any extra dividers
		$this->key_temp = trim($this->key_pre . $this->key_div . $key . $this->key_post, $this->key_div);
		
		// Check if key_unique is set
		if( ! $this->key_unique)
		{
			// It was not, so just send back this key
			return $this->key_temp;
		}

		// Key_uniqe = TRUE so we need to check the key and store it
		else
		{
			// Check the database for a key like this one
			if($this->check())
			{
				// The key was not in the database, so we have a unique key, now we check
				// If we are going to store it in the database
				// Check if we have a match string set
				if($this->key_match !== "")
				{
					// We do have a match string set, so store it in the database
					// Store the key
					$this->store_key();
				}

				// Are we set to store without match string
				if($this->key_store === TRUE && $this->key_match === "")
				{
					// We have key_store set to TRUE and no match string, so we store it in the database
					// Store the key
					$this->store_key();
				}
				
				// No key found, send this one
				return $this->key_temp;
			}
			
			// There WAS a key found!!! (should very rarely happen)
			else
			{	
				// Key was found in db, lets try to get a new one
				// Call this class and start all over trying for a new UNIQUE key
				$this->generate_key();
			}
		}
	}


	/**
	 * return a key check in json format
	 * @return json Array of data about the string
	 **/
	public function api_json()
	{
		
		// Check if the key is valid
		$valid = $this->auth($this->key_temp);
		
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
			$json['valid']  = false;
		}
		
		// Send the json string
		return json_encode($json);
	}


}