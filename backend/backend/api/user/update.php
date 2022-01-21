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

$data = json_decode(file_get_contents("php://input"));

if (!empty($data)) {

    
    if(!empty($data->user_password)){
        $user->user_name = $data->user_name;
        $user->user_role = $data->user_role;
        $user->user_status = $data->user_status;
        $user->user_password = stringEncryption('encrypt', $data->user_password);
        $user->user_id = intval($data->user_id);
        $success = $user->update_full();
    }
    else{
        $user->user_name = $data->user_name;
        $user->user_role = $data->user_role;
        $user->user_status = $data->user_status;
        $user->user_id = intval($data->user_id);
        $success = $user->update();
    }
    
    $users_meta = new UserMeta($dbconn);
    
    if ($success) {
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
        $users_meta->user_meta_id = $user->user_id;
        $userM = True;
        $users_meta->update();
    }

   
    if($userC && $userM){
        // set response code - 201 updated
        http_response_code(201);

        // tell the user
        echo json_encode(array("message" => "User was updated."));
    } 
        // if unable to update the user, tell the user
        else{
        
            // set response code - 503 service unavailable
            http_response_code(503);
        
            // tell the user
            echo json_encode(array("message" => "Unable to update user."));
        }
    }
    // tell the user data is incomplete
    else{ 
        // set response code - 400 bad request
        http_response_code(400);
     
        // tell the user
        echo json_encode(array("message" => "Unable to update user. Data is incomplete."));
    }

    
    /*
     {
        "user_id": "1",
        "user_name": "sasiSISIsaso",
        "user_role": "0",
        "user_status": "1",
        "fname": "stamatIS",
        "lname": "manolAS",
        "telephone": "0",
        "address": "11364",
        "vat": "0123"
    }
    */

