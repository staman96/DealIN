<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include '../../config/core.php';

$user_meta = new UserMeta($dbconn);

$data = json_decode(file_get_contents("php://input"));

if (!empty($data)) {
   
    $user_meta->user_meta_id = intval($data->user_meta_id);
    $results = $user_meta->read_one();

    if ( isset($results->user_meta_id) ) {

        http_response_code(200);
        echo json_encode($results);

    } else {

        http_response_code(404);
        echo json_encode(array("message" => "User meta does not exist."));

    }

}