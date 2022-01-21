<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include '../../config/core.php';

$bid = new Bid($dbconn);

$data = json_decode(file_get_contents("php://input"));
    
if (!empty($data)) {


    $check= new Bid($dbconn);
    $check->bid_id= (isset($data->bid_id) ? intval($data->bid_id) : 0 );
    
    $results = $check->read_one();

    if(!isset($results->bid_id)){

     // set response code - 503 service unavailable
     http_response_code(503);
    
     // tell the user
     echo json_encode(array("message" => "Bid id doesnt't exist."));

     exit;
    }

    
    $bid->bid_amount = $data->bid_amount;
    $bid->bid_time = $data->bid_time;
    $bid->user_id = $data->user_id;
    $bid->product_id = $data->product_id;
   
    $bid->bid_id = intval($data->bid_id);

    // update the bid
    if($bid->update()){
    
        // set response code - 200 OK
        http_response_code(200);
    
        // tell the user
        $results->bid_amount = intval($results->bid_amount);
        echo json_encode(array("message" => "Bid was updated."));
    }
    
    // if unable to update the bid
    else{
    
        // set response code - 503 service unavailable
        http_response_code(503);
    
        // tell the user
        echo json_encode(array("message" => "Unable to update bid."));
    }

    /*
     {
        "bid_id": "3",
        "bid_amount": "10.00",
        "bid_time": "2019-09-07 13:34:31",
        "user_id": "1",
        "product_id": "1"
    }
    */

}