<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include '../../config/core.php';

$user = new User($dbconn);
//$userC = $userM = False;

$data = json_decode(file_get_contents("php://input"));

if (!empty($data)) {

    $user->user_id = intval($data->user_id);
    $results = $user->read_one();

    if ( isset($results->user_name) ) {

        http_response_code(200);
        echo json_encode($results);

    } else {

        http_response_code(404);
        echo json_encode(array("message" => "User does not exist."));

    }

}