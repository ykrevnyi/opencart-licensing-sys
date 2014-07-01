<?php


/**
 * Enable errors
 *
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);


/**
 * Autoload files
 *
 */
require 'vendor/autoload.php';


/**
 * Include the license auth class
 *
 */
require_once("app/keyauth.class.php");


/**
 * Check if the request has the right, or try to falsify
 *
 * @return bool
 */
function validateInterkassaResp($post_in)
{
	$key_to_sort = $post_in;
	$ik_co_id = $key_to_sort['ik_co_id'];
	$ik_sign = $key_to_sort['ik_sign'];
	$ik_am = $key_to_sort['ik_am'];
	$ik_inv_st = $key_to_sort['ik_inv_st'];

	// Forming a digital signature
	unset($key_to_sort['ik_sign']);
	ksort($key_to_sort, SORT_STRING);

	// Add to the array "secret key"
	array_push($key_to_sort, 'XRZxzNbDsQYzsWm2');

	// Concatenate values ​​by a ":"
	$signString = implode(':', $key_to_sort);

	// Take the MD5 hash in binary form by
	$sign = base64_encode(md5($signString, true));

	// Validate kassa results
	if(
		$ik_co_id == '5370b755bf4efccb31ad6f90' AND 
		$ik_inv_st == 'success' AND 
		$ik_sign == $sign
	)
	{
		return $ik_am;
	}
	else
	{
		return false;
	}
}


/**
 * If all goes good, we will store new stansaction and give a user new licence key
 *
 */
if(validateInterkassaResp($_POST))
{
	// Create new transaction
	$transaction = new Transaction;
	$transaction->ik_co_id 		= $_POST['ik_co_id'];
	$transaction->ik_co_prs_id 	= $_POST['ik_co_prs_id'];
	$transaction->ik_inv_id		= $_POST['ik_inv_id'];
	$transaction->ik_inv_st 	= $_POST['ik_inv_st'];
	$transaction->ik_inv_crt 	= $_POST['ik_inv_crt'];
	$transaction->ik_inv_prc 	= $_POST['ik_inv_prc'];
	$transaction->ik_trn_id 	= $_POST['ik_trn_id'];
	$transaction->ik_pm_no 		= $_POST['ik_pm_no'];
	$transaction->ik_desc 		= $_POST['ik_desc'];
	$transaction->ik_pw_via 	= $_POST['ik_pw_via'];
	$transaction->ik_am 		= $_POST['ik_am'];
	$transaction->ik_cur 		= $_POST['ik_cur'];
	$transaction->ik_co_rfn 	= $_POST['ik_co_rfn'];
	$transaction->ik_ps_price 	= $_POST['ik_ps_price'];
	$transaction->ik_sign 		= $_POST['ik_sign'];
	$transaction->save();

	// Get targer payment description
	$payment_description = htmlspecialchars_decode($_POST['ik_desc']);
	
	// Get customer email
	preg_match("/Email - (.*),/i", $payment_description, $matches);
	if (isset($matches[1]))
	{
		$email = $matches[1];
	}

	preg_match("/домен - (.*)\./i", $payment_description, $matches);
	if (isset($matches[1]))
	{
		$domain = $matches[1];
	}

	// Check target module info with transaction info
	$target_module = Module::where('code', $_POST['ik_pm_no'])->first();

	if ( ! $target_module OR $target_module->price < $_POST['ik_am'] OR $_POST['ik_cur'] != 'USD')
	{
		throw new Exception("Error");
	}

	// Check if key for that domain + menu code, already exists
	$key = Key::where('match', $domain)
		->where('module_code', $_POST['ik_pm_no'])
		->delete();

	// Create a new class instance
	$keyauth = new Keyauth;
	$keyauth->key_unique = TRUE;
	$keyauth->key_store = TRUE;
	$keyauth->key_time = 60*60*24*31;
	$keyauth->key_match = $domain;
	$keyauth->need_demo_key = FALSE;
	$keyauth->transaction_id = $transaction->id;
	$keyauth->module_code = $_POST['ik_pm_no'];

	$key = $keyauth->generate_key();


	// Send email with key info
	// Create message
	$message = "Вы приобрели ключ\n\n";
	$message .= "Модуль: " . Module::where('code', $_POST['ik_pm_no'])->first()->name . "\n";
	$message .= "Домен: " . $domain . "\n";
	$message .= "Email: " . $email . "\n";
	$message .= "Ваш ключ: " . $key . "\n";


	// Simply send the message
	$mail = new Mail();
	$mail->hostname = '';
	$mail->username = '';
	$mail->password = '';
	$mail->setTo('yuriikrevnyi@gmail.com');
	$mail->setFrom('admin@dev.website-builder.ru');
	$mail->setSender('Opencart modules');
	$mail->setSubject(html_entity_decode('Message from dev.website-builder.ru', ENT_QUOTES, 'UTF-8'));
	$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
	$mail->send();

	// Interact with module license key
	$ch = curl_init('http://' . $domain . '/teil_key_interact.php');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
	curl_setopt($ch, CURLOPT_POST, true);

	// Set data to send via POST method
	curl_setopt($ch, CURLOPT_POSTFIELDS, array(
		'key' => $key,
		'module_code' => $POST['ik_pm_no']
	));

	// Execute and decode from json
	$result = curl_exec($ch);

}
else
{
	die('Error! :(');
}


/*
	--- Demo responce: ---
	{
		"$transaction->ik_co_id":"5370b755bf4efccb31ad6f90",
		"$transaction->ik_co_prs_id":"201348095960",
		"$transaction->ik_inv_id":"27515915",
		"$transaction->ik_inv_st":"success",
		"$transaction->ik_inv_crt":"2014-05-12 15:16:10",
		"$transaction->ik_inv_prc":"2014-05-12 15:16:10",
		"$transaction->ik_trn_id":"",
		"$transaction->ik_pm_no":"PAY_0",
		"$transaction->ik_desc":"short payment description",
		"$transaction->ik_pw_via":"test_interkassa_test_xts",
		"$transaction->ik_am":"10.00",
		"$transaction->ik_cur":"USD",
		"$transaction->ik_co_rfn":"9.8500",
		"$transaction->ik_ps_price":"10.15",
		"$transaction->ik_sign":"F6sYsf2kS9JoBwMHxyE9bw=="
	}
*/