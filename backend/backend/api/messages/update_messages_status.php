<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include '../../config/core.php';

$message = new Messages($dbconn);

// Get Data
$data = json_decode(file_get_contents("php://input"));
if (!empty($data)) {
    $messages_ids = rtrim($data->messages_ids, ',');
    $results = $message->updateGroupMessages($messages_ids);
    if (!empty($results)) {
        http_response_code(201);
        echo json_encode($results);
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "No Results Found"));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "No Data Found"));   
}