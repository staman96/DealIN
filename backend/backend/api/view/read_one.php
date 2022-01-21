<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include '../../config/core.php';

$view = new View($dbconn);

$data = json_decode(file_get_contents("php://input"));

if (!empty($data)) {
   
    $view->view_id = intval($data->view_id);
    $results = $view->read_one();

    if ( isset($results->view_id) ) {

        http_response_code(200);
        echo json_encode($results);

    } else {

        http_response_code(404);
        echo json_encode(array("message" => "View does not exist."));

    }

}