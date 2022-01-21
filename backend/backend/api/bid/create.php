<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include '../../config/core.php';

$bid = new Bid($dbconn);

// Get Data
$data = json_decode(file_get_contents("php://input"));

if (!empty($data)) {

    $bid->bid_amount = $data->bid_amount;
    $bid->user_id = $data->user_id;
    $bid->product_id = $data->product_id;

    if($bid->create()){
        $product = new Product($dbconn);
        $product->product_id = $data->product_id;
        $cproduct = $product->read_one();
        
        if ($cproduct->current_value < $data->bid_amount) {
            $updateValue = new Product($dbconn);
            if ($cproduct->auction_first_bid == '0.00') {
                $updateValue->auction_first_bid = $data->bid_amount;
            }
            $updateValue->current_value = $data->bid_amount;
            $updateValue->product_id = $data->product_id;
            $updateValue->updateCurrentValue();
        } 

        // set response code - 201 created
        http_response_code(201);

        // tell the user
        echo json_encode(array("message" => "Bid was created."));
    } 
        // if unable to create the bid, tell the user
        else{
        
            // set response code - 503 service unavailable
            http_response_code(503);
        
            // tell the user
            echo json_encode(array("message" => "Unable to create bid."));
        }
    }
    // tell the user data is incomplete
    else{ 
        // set response code - 400 bad request
        http_response_code(400);
     
        // tell the user
        echo json_encode(array("message" => "Unable to create bid. Data is incomplete."));
    }

    /*
     {
        "bid_amount": "10.00",
        "user_id": "3",
        "product_id": "1"
    }
*/