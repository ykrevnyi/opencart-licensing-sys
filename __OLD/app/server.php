<?php

// Include the Class
require_once("keyauth.class.php");

// pull the key from post data
$key =  $_POST['key'];

// pull the match string from match data
$match = $_POST['domain'];

// pull the module code to be validated
$module_code = $_POST['module_code'];

// check if we got a key
if(!empty($key))
{
	
	// create a new instance of the class
	$keyauth = new Keyauth;

	// set the key match string
	$keyauth->key_match = $match;
	
	// set the temp key
	$keyauth->key_temp  = $key;

	// set the module code
	$keyauth->module_code  = $module_code;

	// set the content type to JSON
	header("Content-Type: application/json");

	// echo our api response
	echo $keyauth->api_json();
	
}


?>