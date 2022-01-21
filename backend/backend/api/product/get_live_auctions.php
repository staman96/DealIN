<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include '../../config/core.php';

$product = new Product($dbconn);
   
$results = $product->get_by_status();

if ( !empty($results)) {

    http_response_code(200);
    echo json_encode($results);

} else {
    http_response_code(404);
    echo json_encode(array("message" => "Product does not exist."));
}

