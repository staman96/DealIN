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


    $check= new UserMeta($dbconn);
    $check->user_meta_id= (isset($data->user_meta_id) ? intval($data->user_meta_id) : 0 );
    
    $results = $check->read_one();

    if(!isset($results->user_meta_id)){

     // set response code - 503 service unavailable
     http_response_code(503);
    
     // tell the user
     echo json_encode(array("message" => "User meta doesnt't exist."));

     exit;
    }

    
    $user_meta->fname = $data->fname;
    $user_meta->lname = $data->lname;
    $user_meta->telephone = $data->telephone;
    $user_meta->address = $data->address;
    $user_meta->vat = $data->vat;
    $user_meta->bidder_rating = $data->bidder_rating;
    $user_meta->seller_rating = $data->seller_rating;

   
    $user_meta->user_meta_id = intval($data->user_meta_id);

    // update the user_meta
    if($user_meta->update()){
    
        // set response code - 200 OK
        http_response_code(200);
    
        // tell the user
        echo json_encode(array("message" => "User meta was updated."));
    }
    
    // if unable to update the user_meta
    else{
    
        // set response code - 503 service unavailable
        http_response_code(503);
    
        // tell the user
        echo json_encode(array("message" => "Unable to update user_meta."));
    }

    /*
    {
        "user_meta_id" : "1",
        "fname" : "max",
        "lname" : "maxopoulos",
        "telephone" : "6958741426",
        "address" : "elkonos 32",
        "vat" : "1234567890",
        "bidder_rating" : "10",
        "seller_rating" : "10",
         "user_id": "1"
    }

    */

}