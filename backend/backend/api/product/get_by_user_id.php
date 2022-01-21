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

    $product->user_id = $data->user_id;
   
    $results = $product->get_by_user_id();
    

    if ( !empty($results) ) {
        http_response_code(200);
        if ( !empty($results->bid_amount) ){
            $results->bid_amount = intval($results->bid_amount);
        }
        if ( !empty($results->auction_starting_price) ){
            $results->auction_starting_price = intval($results->auction_starting_price);
        }
        if ( !empty($results->product_buy_price) ){
            $results->product_buy_price = intval($results->product_buy_price);
        }
        if ( !empty($results->product_status) ){
            $results->product_status = intval($results->product_status);
        }
        if ( !empty($results->user_id) ){
            $results->user_id = intval($results->user_id);
        }
        echo json_encode($results);

    } else {
        http_response_code(404);
        echo json_encode(array("message" => "Product does not exist."));
    }

}