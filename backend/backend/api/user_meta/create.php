<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include '../../config/core.php';

$user_meta = new UserMeta($dbconn);

// Get Data
$data = json_decode(file_get_contents("php://input"));

if (!empty($data)) {

    $user_meta->fname = $data->fname;
    $user_meta->lname = $data->lname;
    $user_meta->telephone = $data->telephone;
    $user_meta->address = $data->address;
    $user_meta->vat = $data->vat;
    $user_meta->bidder_rating = $data->bidder_rating;
    $user_meta->seller_rating = $data->seller_rating;
    $user_meta->user_id = $data->user_id;

    if($user_meta->create()){
        // set response code - 201 created
        http_response_code(201);

        // tell the user
        echo json_encode(array("message" => "User meta created."));
    } 
        // if unable to create the user_meta, tell the user
        else{
        
            // set response code - 503 service unavailable
            http_response_code(503);
        
            // tell the user
            echo json_encode(array("message" => "Unable to create user meta."));
        }
    }
    // tell the user data is incomplete
    else{ 
        // set response code - 400 bad request
        http_response_code(400);
     
        // tell the user
        echo json_encode(array("message" => "Unable to create user meta. Data is incomplete."));
    }

    /*

    {
        "fname": "STAMATIS",
        "lname": "MANOLAS",
        "telephone": "6955064738",
        "address": "elikonos 73",
        "vat": "9765246575",
        "bidder_rating": null,
        "seller_rating": null,
        "user_id": "3"
    }
   
*/