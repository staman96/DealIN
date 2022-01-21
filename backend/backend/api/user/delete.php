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
    
if (!empty($data)) {
   
    $user->user_id = intval($data->user_id);

    // delete the user
    if($user->delete()){
    
        // set response code - 200 ok
        http_response_code(200);
    
        // tell the user
        echo json_encode(array("message" => "User was deleted."));
    }
    
    // if unable to delete the user
    else{
    
        // set response code - 503 service unavailable
        http_response_code(503);
    
        // tell the user
        echo json_encode(array("message" => "Unable to delete user."));
    }

}