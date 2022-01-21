<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include '../../config/core.php';

$product = new Product($dbconn);

$data = json_decode(file_get_contents("php://input"));

if (!empty($data)) {
    $product->product_id = $data->product_id;
    $results = $product->get_status_currentvalue();

    if ( !empty($results)) {

        $results->current_value = floatval($results->current_value);
        $results->total_bids = intval($results->total_bids);
        http_response_code(200);
        echo json_encode($results);

    } else {
        http_response_code(404);
        echo json_encode(array("message" => "Product does not exist."));

    }

}