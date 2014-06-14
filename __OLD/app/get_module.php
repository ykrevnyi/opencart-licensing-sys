<?php 

// Include the license auth class
require_once("keyauth.class.php");

// Create a new class instance
$keyauth = new Keyauth;


// Check if key for that domain + menu code, already exists
$keyauth->key_match = $_POST['domain'];
$keyauth->module_code = $_POST['module_code'];
$key_exists = $keyauth->key_to_module_exists();


// If there are no keys-to-domain-module we will generate one
if ($key_exists == FALSE)
{
    $keyauth->key_unique = TRUE;
    $keyauth->key_store = TRUE;
    $keyauth->key_time = 60*60*24*31;
    $keyauth->key_match = $_POST['domain'];
    $keyauth->need_demo_key = TRUE;
    $keyauth->module_code = $_POST['module_code'];

    $key = $keyauth->generate_key();
}


// Return module
$attachment_location = $_SERVER["DOCUMENT_ROOT"] . "/app/modules_repository/" . $_POST['module_code'] . '.zip';

if (file_exists($attachment_location)) {
    header($_SERVER["SERVER_PROTOCOL"] . " 200 OK");
    header("Cache-Control: public"); // needed for i.e.
    header("Content-Type: application/zip");
    header("Content-Transfer-Encoding: Binary");
    header("Content-Length:".filesize($attachment_location));
    header("Content-Disposition: attachment; filename=" . $_POST['module_code'] . '.zip');
    readfile($attachment_location);
    die();        
} else {
    die("Error: File not found.");
}