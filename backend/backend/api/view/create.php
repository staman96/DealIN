<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include '../../config/core.php';

$view = new View($dbconn);

// Get Data
$data = json_decode(file_get_contents("php://input"));

if (!empty($data)) {

    $view->product_id = $data->product_id;
    $view->user_id = $data->user_id;

    if($view->create()){
        // set response code - 201 created
        http_response_code(201);

        // tell the user
        echo json_encode(array("message" => "View was created."));
    } 
        // if unable to create the view, tell the user
        else{
        
            // set response code - 503 service unavailable
            http_response_code(503);
        
            // tell the user
            echo json_encode(array("message" => "Unable to create view."));
        }
    }
    // tell the user data is incomplete
    else{ 
        // set response code - 400 bad request
        http_response_code(400);
     
        // tell the user
        echo json_encode(array("message" => "Unable to create view. Data is incomplete."));
    }

    /*
       {
        "product_id": "1",
        "user_id": "1"
    }
   
*/