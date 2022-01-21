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
    $results = $bid->read_one();

    if ( isset($results->bid_name) ) {

        http_response_code(200);
        $results->bid_amount = intval($results->bid_amount);
        echo json_encode($results);

    } else {

        http_response_code(404);
        echo json_encode(array("message" => "Bid does not exist."));

    }
}