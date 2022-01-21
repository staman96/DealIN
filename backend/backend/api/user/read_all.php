<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include '../../config/core.php';

$user = new User($dbconn);

$data = json_decode(file_get_contents("php://input"));
    
    $results = $user->read_all( (empty($data) ? "*" : implode("," , $data) ) );

    if ( !empty($results) ) {

        http_response_code(200);
        
        echo json_encode($results);

    } else {

        http_response_code(404);
        echo json_encode(array("message" => "User does not exist."));

    }

