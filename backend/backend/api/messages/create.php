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

    $message->message_text = $data->message_text;
    $message->message_title = $data->message_title;
    $message->message_on_read = 0;
    $message->sender_user_id = $data->sender_user_id;
    $message->receiver_user_id = $data->receiver_user_id;
    $message->product_id = $data->product_id;

    $find_sender = new User($dbconn);
    $find_sender->user_id = $data->sender_user_id;
    $sender = $find_sender->read_one();

    $find_receiver = new User($dbconn);
    $find_receiver->user_id = $data->receiver_user_id;
    $reciever = $find_receiver->read_one();


    $message->sender_name = $sender->user_name;
    $message->receiver_name = $reciever->user_name;

    if($message->create()){
        // set response code - 201 created
        http_response_code(201);

        // tell the user
        echo json_encode(array("message" => "Message was created."));
    } 
        // if unable to create the category, tell the user
        else{
        
            // set response code - 503 service unavailable
            http_response_code(503);
        
            // tell the user
            echo json_encode(array("message" => "Unable to create Message."));
        }
    }
    // tell the user data is incomplete
    else{ 
        // set response code - 400 bad request
        http_response_code(400);
     
        // tell the user
        echo json_encode(array("message" => "Unable to create Message. Data is incomplete."));
    }

    /*
      {
        "category_name": "collectibles",
        "category_product_count": "0",
        "category_slug": "collectibles",
        "category_status": null,
        "category_created_at": "2019-09-06 14:00:40",
        "category_updated_at": "2019-09-06 14:01:02"
    }
   
*/