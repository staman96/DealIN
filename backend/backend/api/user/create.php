<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include '../../config/core.php';

$user = new User($dbconn);
$userC = $userM = False;

// Get Data
$data = json_decode(file_get_contents("php://input"));

if (!empty($data)) {

    $user->user_name = $data->user_name;
    $user->user_password = stringEncryption('encrypt', $data->user_password);
    $user->email = $data->email;


    if ($data->source == 'front') {
        $users_meta = new UserMeta($dbconn);
        $lastInsID = $user->create();
        if ($lastInsID) {
            $userC = True;
            $users_meta->fname = $data->fname;
            $users_meta->lname = $data->lname;
            if ($data->telephone) {
                $users_meta->telephone = $data->telephone;
            }
            if ($data->address) {
                $users_meta->address = $data->address;
            }
            if ($data->vat) {
                $users_meta->vat = $data->vat;
            }
            $users_meta->bidder_rating = 0;
            $users_meta->seller_rating = 0;
            $users_meta->user_id = $lastInsID;
            $userM = True;
            $users_meta->create();
        }
    }

    if($userC && $userM){
        // set response code - 201 created
        http_response_code(201);

        // tell the user
        echo json_encode(array("message" => "User was created."));
    } 
        // if unable to create the user, tell the user
        else{
        
            // set response code - 503 service unavailable
            http_response_code(503);
        
            // tell the user
            echo json_encode(array("message" => "Unable to create user."));
        }
    }
    // tell the user data is incomplete
    else{ 
        // set response code - 400 bad request
        http_response_code(400);
     
        // tell the user
        echo json_encode(array("message" => "Unable to create user. Data is incomplete."));
    }

    /*
      {
        "user_id": "1",
        "user_name": "staman96",
        "user_password": "MWQ5bllpUUpWajliRm5hMGpWMDQzQT09",
        "email": "staman96@gmail.com",
        "user_roles": "1",
        "user_status": "1",
        "activation_key": null,
        "created_at": "2019-09-06 13:59:13",
        "updated_at": "2019-09-07 13:36:51"
    }
   
*/