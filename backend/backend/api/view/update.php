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


    $check= new View($dbconn);
    $check->view_id= (isset($data->view_id) ? intval($data->view_id) : 0 );
    
    $results = $check->read_one();

    if(!isset($results->view_id)){

     // set response code - 503 service unavailable
     http_response_code(503);
    
     // tell the user
     echo json_encode(array("message" => "View doesnt't exist."));

     exit;
    }

    
    $view->product_id = $data->product_id;
    $view->auction_won = $data->auction_won;
    $view->view_created_at = $data->view_created_at;
    $view->user_id = $data->user_id;

   
    $view->view_id = intval($data->view_id);

    // update the view
    if($view->update()){
    
        // set response code - 200 OK
        http_response_code(200);
    
        // tell the user
        echo json_encode(array("message" => "View was updated."));
    }
    
    // if unable to update the view
    else{
    
        // set response code - 503 service unavailable
        http_response_code(503);
    
        // tell the user
        echo json_encode(array("message" => "Unable to update view."));
    }

    /*
     {
        "view_id": "1",
        "product_id": "1",
        "user_id": "1"
    }
    */

}