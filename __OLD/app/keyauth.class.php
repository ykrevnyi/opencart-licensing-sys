<?php

/**
 *  PHP Key Generation and Authentication Class
 * 
 *  Using default settings, this class can generate
 *  about 33 million unique keys and if changed can
 *  technically generate an infinite amount of keys. 
 * 
 *  Here is the math to figure the number:
 * 
 *  ((26 + 10)^4) * 4 * 5 = 33 592 320
 *  ((26_LETTERS + 10_DIGITS) * CHUNKS * PARTS)
 * 
 *  Thats using this key format:
 *  ABCD-1234-EFGH-5678-IJKL = 33m
 *
 * @author Ben Phelps
 * @version 1.2
 **/

/**
 * PHP Key Generation and Authentication Class
 */
class Keyauth {

	//!//!//!//!//!//!//!//!/
	//!// CCONFIG START //!//
	//!//!//!//!//!//!//!//!/
	
	/**
	 * MySQL Database name to use
	 * @var string
	 */
	public $db_name		= "license_dev";
	
	/**
	 * MySQL Table name to use, this should be "keys" unless you changed the
	 * table name in the sql scheme that is included.
 	 * @var string
	 */
	public $db_table	= "keys";
	
	/**
	 * MySQL Database username
	 * @var string
	 */
	public $db_user		= "license_dev";
	/**
	 * MySQL Database password
	 * @var string
	 */
	public $db_pass		= "license_dev";
	
	/**
	 * MySQL Database hostname
	 * @var string
	 */
	public $db_host		= "localhost";
	
	
	//!//!//!//!//!//!//!//!/
	//!// CCONFIG STOP //!//
	//!//!//!//!//!//!//!//!/
	
	/**
	 * Number of characters in each chunk.
	 * This is the core part of the key
	 * @var integer
	 */
	public $key_chunk	= 4;
	
	/**
	 * Number of chunks in each key.
	 * This add a new section with the chunk length to the key.
	 * @var integer
	 */
	public $key_part	= 5;
	
	/**
	 * This is placed at the beginning of each key if set.
	 * This would be a constant in each key generated.
	 * @var string
	 */
	public $key_pre		= "";
	
	/**
	 * This is placed at the end of each key if set.
	 * This would be a constant in each key generated.
	 * @var string
	 */
	public $key_post	= "";
	
	
	/**
	 * If set to TRUE, the key will be split at each key part by the key divider.
	 * @var boolean
	 */
	public $key_split	= TRUE;
	
	/**
	 * The key divider, this is placed between each key part if key_split is TRUE.
	 * @var string
	 */
	public $key_div		= "-";
	
	/**
	 * If set, the key will be stored along with this string, when authenticating,
	 * the user would need to supply a valid key and it matching key string. If
	 * set the key will be stored in the database even if key_store is FALSE.
	 * Requires key_unique to be set TRUE.
	 * @var string
	 */
	public $key_match	= "";
	
	/**
	 * If TRUE, the key will be stored in the database even if there is no key_match set.
	 * Requires key_unique to be set TRUE
	 * @var boolean
	 */
	public $key_store	= FALSE;
	
	/**
	 * How long the key is valid for in seconds, if 0, key never expires.
	 * @var integer
	 */
	public $key_time 	= 0;
	
	
	/**
	 * A widely used variable, sets a key for use in the class.
	 * @var string
	 */
	public $key_temp	= "";
	
	
	/**
	 * If set TRUE, they class will check if a key of the same value exists in the database.
	 * If a key does exist, they generation class will try and create another key.
	 * @var boolean
	 */
	public $key_unique	 	= FALSE;


	/**
	 * Validate by module code
	 *
	 * @var string
	 */
	public $module_code = '';


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
	
	/**
	 * Connects to the database
	 */
	function __construct()
	{ // Open __construct
		
		// Try and connect to the MySQL database
		$this->db = mysql_connect($this->db_host, $this->db_user, $this->db_pass);
		
		// Check if we connected to the database
		if( !$this->db )
		{
			
			// trigger a fatal error because connection failed and stop the script
			trigger_error("Failed to connect to MySQL! (MySQL Said: " . mysql_error($this->db) . ")", E_USER_ERROR);
				
		}
		else
		{
			
			// try and select the mysql database
			mysql_select_db($this->db_name, $this->db) 
				or // unless it fails
			// then send out a fatal error and stop the script
			trigger_error("Failed to select database! (MySQL Said: " . mysql_error($this->db) . ")", E_USER_ERROR);
			
		}
		
		
	} // Close __construct
	
	/**
	 * Clean a user supplied string
	 * @param string|integer Input to be cleaned
	 * @return string|integer Input cleaned
	 */
	private function clean($value)
	{ // Open Function clean
		
		// clean the input of SQL Injection
		if (get_magic_quotes_gpc())
		{
			
			// remove the slashes that magic_quotes added
			$value = stripslashes($value);
			
		}
		
		if (!is_numeric($value))
		{
			
			// escape and ' or " to remove SQL Injections
			$value = mysql_real_escape_string($value);
		
		}

		return $value;
		
	} // Close Function clean
	
	/**
	 * Check if a key of the same name exist in the database
	 * @return boolean True if key is unique
	 */
	private function check_key()
	{ // Open Function check_key
		
		// Selct ID from the Database where key matches key_temp
		$sql = "SELECT `id` FROM `{$this->db_table}` WHERE `key` = '".$this->clean($this->key_temp)."' AND active = '1';";
		
		// Run the SQL Query
		$query = mysql_query($sql, $this->db);
		
		// Count number of rows returned
		$count = mysql_num_rows($query);
		
		// if we get exactly one row returned
		if($count == 1)
		{
			
			// key is not unique
			return FALSE;
			
		}
		else
		{
			
			// got 0 rows back, key is unique
			return TRUE;
			
		}
		
	} // Close Function check_key



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
		// Select ID, Match, Expire from database where key and match string match
		$sql = "SELECT `id`, `match`, `expire` 
				FROM `{$this->db_table}`
				WHERE `match` = '".$this->clean($this->key_match)."'
				AND `module_code` = '".$this->clean($this->module_code)."'
				AND `active` = 1;";

		// run the MySQL query
		$query = mysql_query($sql, $this->db);
		
		// Count the number of rows returned
		$count = mysql_num_rows($query);
		
		// if we got a row back, there is a key that matched in the database
		if($count === 1)
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
	{ // Open Function store_key

		// If key_time = 0, dont store a timestamp, if it != 0, store a
		// timestamp with key_time added.
		$time = ($this->key_time!=0?(time()+$this->key_time):0);

		if (!$this->key_temp) {
			$this->key_temp  = "TEST";
		}
		
		// SQL to insert the key into the databse
		if ( ! is_null($this->transaction_id))
		{
			$sql = "INSERT into `{$this->db_table}` (`id`, `key`, `match`, `expire`, `active`, `module_code`, `transaction_id`) 
				VALUES (NULL, '".$this->clean($this->key_temp)."', '".$this->clean($this->key_match)."', {$time}, '1', '".$this->clean($this->module_code)."', '".$this->clean($this->transaction_id)."');";
		}
		else
		{
			$sql = "INSERT into `{$this->db_table}` (`id`, `key`, `match`, `expire`, `active`, `module_code`) 
				VALUES (NULL, '".$this->clean($this->key_temp)."', '".$this->clean($this->key_match)."', {$time}, '1', '".$this->clean($this->module_code)."');";
		}
		
		// run the SQL insert into the database
		$query = mysql_query($sql, $this->db);
		
		
		// check if the query was successful
		if($query){
			
			// return true if the update was successful
			return TRUE;
			
		}
		else {
			
			// return a notice if the update failed
			trigger_error("Error storing key! MySQL Said: (" . mysql_error($this->db) . ")", E_USER_NOTICE);
		}
		
		
	} // Close function store_key
	
	/**
	 * Pulls key info from the database
	 * @return array|boolean Array of key info, or false if not found
	 */
	public function key_info()
	{ // Open Function key_info
		
		// Select everything from database where key and match string match
		$sql = "SELECT * FROM `{$this->db_table}` 
				WHERE `key` = '".$this->clean($this->key_temp)."'
				AND `match` = '".$this->clean($this->key_match)."';";
				
		// Run the SQL
		$query = mysql_query($sql, $this->db);
		
		// Count number of rows returned
		$count = mysql_num_rows($query);
		
		// make sure we get one row
		if($count == 1)
		{
			
			// pull an array from the results and send it back
			$array = mysql_fetch_array($query);
			return $array;
			
		}
		else
		{
			
			// no key was found, return false
			return FALSE;
			
		}
		
	} // Close Function key_info
		
	/**
	 * Activate a key so it will validate when checked
	 * @return boolean True if successful 
	 */
	public function activate()
	{ // Open activate function
		
		// The update SQL query to activate a key
		$sql = "UPDATE `{$this->db_table}` SET `active` =  '1' WHERE `key` = '".$this->clean($this->key_temp)."';";
		
		// run the sql to activate the key
		$query = mysql_query($sql, $this->db);
		
		// check if the query was successful
		if($query)
		{
			
			// return true if the update was successful
			return TRUE;
			
		}
		else {
			
			// send a notice if the update failed
			trigger_error("Error activating key! MySQL Said: (" . mysql_error($this->db) . ")", E_USER_NOTICE);
			
		}
		
	} // Close activate function
	
	/**
	 * deActivate a key so it will NOT validate when checked
	 * @return boolean True if successful 
	 */
	public function deactivate()
	{ // Open deactivate function
		
		// The update SQL query to deactivate a key
		$sql = "UPDATE `{$this->db_table}` SET `active` =  '0' WHERE `key` = '".$this->clean($this->key_temp)."';";
		
		// run the sql to deactivate the key
		$query = mysql_query($sql, $this->db);
		
		// check if the query was successful
		if($query)
		{
			
			// return true if the update was successful
			return TRUE;
			
		}
		else {
			
			// send a notice if the update failed
			trigger_error("Error deactivating key! MySQL Said: (" . mysql_error($this->db) . ")", E_USER_NOTICE);
			
		}
		
	} // Close deactivate fuction
	
	/**
	 * Returns TRUE/FALSE if a key is valid
	 * @return boolean True if key is valid
	 **/
	public function auth()
	{ // Open Fucntion auth
		
		// Select ID, Match, Expire from database where key and match string match
		$sql = "SELECT `id`, `match`, `expire` 
				FROM `{$this->db_table}`
				WHERE `key` = '".$this->clean($this->key_temp)."'
				AND `match` = '".$this->clean($this->key_match)."'
				AND `module_code` = '".$this->clean($this->module_code)."'
				AND `active` = 1;";

		// run the MySQL query
		$query = mysql_query($sql, $this->db);
		
		// Count the number of rows returned
		$count = mysql_num_rows($query);
		
		
		// if we got a row back, there is a key that matched in the database
		if($count === 1)
		{	
			
			// Fetch the row returned
			$rows = mysql_fetch_row($query);
			
			// Check if the key expired
			// if expire os less than the current time and it does not equal 0
			// the key timestamp is in the past, so the key expired
			if($rows[2] < time() && $rows[2] != 0){
				
				// key expired, return false
				return FALSE;
				
			}
			// Date is still in the future or not set, so key has not expired
			else {
				
				// return true
				return TRUE;
			}
		}
		
		// there was no row returned, so no key in the database
		else
		{
			
			// return false
			return FALSE;
			
		}
		
	} // Close Function auth
	
	/**
	 * Returns TRUE/FALSE if a key is valid
	 * @return boolean True if key is valid
	 **/
	public function singleauth()
	{ // Open Fucntion singleauth
		
		// Select ID, Match, Expire from database where key and match string match
		$sql = "SELECT `id`, `match`, `expire` 
				FROM `{$this->db_table}`
				WHERE `key` = '".$this->clean($this->key_temp)."'
				AND `match` = '".$this->clean($this->key_match)."'
				AND `active` = 1;";

		// run the MySQL query
		$query = mysql_query($sql, $this->db);
		
		// Count the number of rows returned
		$count = mysql_num_rows($query);
		
		
		// if we got a row back, there is a key that matched in the database
		if($count === 1)
		{	
			
			// Fetch the row returned
			$rows = mysql_fetch_row($query);
			
			// Check if the key expired
			// if expire os less than the current time and it does not equal 0
			// the key timestamp is in the past, so the key expired
			if($rows[2] < time() && $rows[2] != 0){
				
				// key expired, return false
				return FALSE;
				
			}
			// Date is still in the future or not set, so key has not expired
			else {
				
				// deactivate the key since its a oneshot use
				$this->deactivate();
				
				// return true
				return TRUE;
			}
		}
		
		// there was no row returned, so no key in the database
		else
		{
			
			// return false
			return FALSE;
			
		}
		
	} // Close Function singleauth
	
	/**
	 * Update a match key
	 * @return boolean|E_USER_NOTICE
	 **/
	public function update_match()
	{
		$sql = "UPDATE `keys`
				SET `match` = '".$this->clean($this->key_match)."'
				WHERE `key` = '".$this->clean($this->key_temp)."';";
				
		// run the sql to change the match key
		$query = mysql_query($sql, $this->db);
        
		// check if the query was successful
		if($query)
		{
        
			// return true if the update was successful
			return TRUE;
        
		}
		else {
        
			// send a notice if the update failed
			trigger_error("Error updating match key! MySQL Said: (" . mysql_error($this->db) . ")", E_USER_NOTICE);
        
		}
	}
	
	/**
	 * Generate the key, checking for unique
	 * @return string The generated key
	 */
	public function generate_key()
	{ // Open Function generate_key
		
		if ($this->need_demo_key == TRUE)
		{
			$this->key_temp = "DEMO";
			$this->store_key();

			return $this->key_temp;
		}

		// hush PHP be settings varables
		$key = "";
		
		// loop through each key part
		for($i=0;$i!=$this->key_part;$i++)
		{ // Start part loop
			
			// add random character to current part
			for($x=0;$x!=$this->key_chunk;$x++)
			{ // start chunk loop
				
				// generate a random character or number and append it to the string
				$key .= (
						// Generate a random bit switch, 1=number, 0=letter
						mt_rand()&1==1 ?
							// Generate a random number
							chr(mt_rand($this->num_range_low,$this->num_range_high))
							:
							// Genreate a radnom letter
							chr(mt_rand($this->chr_range_low,$this->chr_range_high))
						);
						
			} // end chunk loop
			
			// If key_split is true, add the key_div, else, add nothing
			$key .= $this->key_split?$this->key_div:"";	
			
		} // end part loop
		
		// trim any extra dividers
		$this->key_temp = trim($this->key_pre.$this->key_div.$key.$this->key_post, $this->key_div);
		
		// check if key_unique is set
		if(!$this->key_unique){
			
			// it was not, so just send back this key
			return $this->key_temp;
			
		}
		// key_uniqe = TRUE so we need to check the key and store it
		else {
			
			// check the database for a key like this one
			if($this->check_key())
			{
				// The key was not in the database, so we have a unique key, now we check
				// if we are going to store it in the database
				
				
				// Check if we have a match string set
				if($this->key_match !== ""){
					
					// We do have a match string set, so store it in the database
					
					// Store the key
					$this->store_key();
				
				}
				// are we set to store without match string
				if($this->key_store === TRUE && $this->key_match === "")
				{
					
					// We have key_store set to TRUE and no match string, so we store it in the database
					
					// store the key
					$this->store_key();
					
				}
				
				// no key found, send this one
				return $this->key_temp;
			
			}
			
			// there WAS a key found!!! (should very rarely happen)
			else
			{	
				
				// key was found in db, lets try to get a new one
				
				// call this class and start all over trying for a new UNIQUE key
				$this->generate_key();
			}
			
		}
		
	} // Close Function generate_key
	
	/**
	 * Convert an Array to XML
	 */
	function toxml($data, $rootNodeName = 'data', $xml=null)
	{ // Open function toxml
		
		// turn off compatibility mode as simple xml throws a wobbly if you don't.
		if (ini_get('zend.ze1_compatibility_mode') == 1)
		{
			ini_set ('zend.ze1_compatibility_mode', 0);
		}
		
		// check if we are in a recursion
		if ($xml == null)
		{
			
			// we are not, so add a header
			$xml = simplexml_load_string("<?xml version='1.0' encoding='utf-8'?><$rootNodeName />");
			
		}
		
		// loop through the data passed in.
		foreach($data as $key => $value)
		{
			
			// no numeric keys in our xml
			if (is_numeric($key))
			{
				
				// make string key...
				$key = "unknownNode_". (string) $key;
				
			}
			
			// replace anything not alpha numeric
			$key = preg_replace('/[^a-z]/i', '', $key);
			
			// if there is another array found recrusively call this function
			if (is_array($value))
			{
				
				$node = $xml->addChild($key);
				// recrusive call.
				toXml($value, $rootNodeName, $node);
				
			}
			else 
			{
				
				// add single node.
                $value = htmlentities($value);
				$xml->addChild($key,$value);
				
			}
			
		}
		
		// pass back as string
		return $xml->asXML();
		
	} // close Function toxml
	
	/**
	 * return a key check in json format
	 * @return json Array of data about the string
	 **/
	public function api_json()
	{ // Open Function api_json
		
		// check if the key is valid
		$valid = $this->auth($this->key_temp);
		
		// if it is, build a json array
		if($valid)
		{
			
			// pull key info from the database
			$array = $this->key_info();
			
			// send back valid = true
			$json['valid']  = true;
			
			// send back key ID
			$json['info']['id'] = $array[0];
			
			// send back key match string
			$json['info']['match']  = empty($array[2])?"NA":$array[2];
			
			// send back key expire time stamp
			$json['info']['expire'] = empty($array[3])?"NA":$array[3];

			$json['info']['full_data'] = $array;

			// Get normal dates
			$today = time();
			$seconds_left = $array['expire'] - $today;
			
			// Module will be expired in N days
			$days_left = floor($seconds_left/3600/24);

			// Module will be expired in 01/06/2014
			$expired_at = gmdate("d-m-Y", $array['expire']);

			$json['info']['dates'] = array(
				'expired_at' => $expired_at,
				'days_left' => $days_left
			);

		}
		// key was not valid, send back false
		else
		{
			// send back false
			$json['valid']  = false;
		}
		
		// send the json string
		return json_encode($json);
		
	} // Close Function api_json
	
	/**
	 * return a key check in json format
	 * @return json Array of data about the string
	 **/
	public function api_xml()
	{ // Open function api_xml
		
		// check if the key is valid
		$valid = $this->auth($this->key_temp);
		
		// if it is, build a xml array
		if($valid)
		{
			
			// pull key info from the database
			$array = $this->key_info();
			
			// send back valid = true
			$xml['valid']  = true;
			
			// send back key ID
			$xml['info']['id'] = $array[0];
			
			// send back key match string
			$xml['info']['match']  = empty($array[2])?"NA":$array[2];
			
			// send back key expire time stamp
			$xml['info']['expire'] = empty($array[3])?"NA1":$array[3];

		}
		// key was not valid, send back false
		else
		{
			// send back false
			$xml['valid']  = false;
		}
		
		// convert the array to xml
		$xml_string = toxml($xml, 'key');
		
		// send the xml
		return $xml_string;
		
	} // Close Function api_xml
	
}

?>