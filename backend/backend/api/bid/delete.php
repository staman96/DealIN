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
   
    $bid->bid_id = intval($data->bid_id);

    // delete the bid
    if($bid->delete()){
    
        // set response code - 200 OK
        http_response_code(200);
    
        // tell the user
        echo json_encode(array("message" => "Bid was deleted."));
    }
    
    // if unable to delete the bid
    else{
    
        // set response code - 503 service unavailable
        http_response_code(503);
    
        // tell the user
        echo json_encode(array("message" => "Unable to delete bid."));
    }

}