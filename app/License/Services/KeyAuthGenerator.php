<?php namespace License\Services;


use License\Models\Key;


class KeyAuthGenerator 
{

	const DEMO_KEY_CODE = "DEMO";

	public $key_chunk = 4;
	public $key_part = 5;
	public $key_pre = "";
	public $key_post = "";
	public $key_split = TRUE;
	public $key_div = "-";
	
	private $num_range_low = 48;
	private $num_range_high = 57;
	private $chr_range_low = 65;
	private $chr_range_high = 90;
	

	/**
	 * Give away a brand new key
	 *
	 * @return void
	 */
	public function make($is_trial)
	{
		// That `is_trial` variable define if we need to generate demo key
		if ($is_trial)
		{
			return self::DEMO_KEY_CODE;
		}

		return $this->generate();
	}


	/**
	 * Check if a key of the same name exist in the database
	 * @return boolean True if key is unique
	 */
	private function check($key)
	{
		$key = Key::where('key', $key)->where('active', 1)->first();

		// If we get exactly one row returned
		return $key ? false : true;
	}


	/**
	 * Generate the key, checking for unique
	 * @return string The generated key
	 */	
	private function generate()
	{
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
		$result_key = trim($this->key_pre . $this->key_div . $key . $this->key_post, $this->key_div);

		// Check the database for a key like this one
		if($this->check($result_key))
		{
			// The key was not in the database, so we have a unique key
			return $result_key;
		}
		
		// There WAS a key found!!! (should very rarely happen)
		else
		{	
			// Key was found in db, lets try to get a new one
			// Call this class and start all over trying for a new UNIQUE key
			return $this->generate();
		}
	}


}