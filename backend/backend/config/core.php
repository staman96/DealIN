<?php 
include 'connect.php';
include '../../objects/product.php';
include '../../objects/category.php';
include '../../objects/user.php';
include '../../objects/bid.php';
include '../../objects/view.php';
include '../../objects/user_meta.php';
include '../../objects/messages.php';

// Initialize Auctions DB Connection
$dbconn = new DBConnection();

function sanitize_text_fields($text) {
	return htmlspecialchars(strip_tags($text));
}

function printr($text) {
	echo '<pre>';
	print_r($text);
	echo '</pre>';
}

function clean($string) {
	$string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
 
	return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
 }

 
function stringEncryption($action, $string){
	$output = false;
	
	$encrypt_method = 'AES-256-CBC';                // Default
	$secret_key = 'Some#Random_Key!';               // Change the key!
	$secret_iv = '!IV@_$2';  // Change the init vector!
	
	// hash
	$key = hash('sha256', $secret_key);
	
	// iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
	$iv = substr(hash('sha256', $secret_iv), 0, 16);
	
	if( $action == 'encrypt' ) {
		$output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
		$output = base64_encode($output);
	}
	else if( $action == 'decrypt' ){
		$output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
	}
	
	return $output;
  }