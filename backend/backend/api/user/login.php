<?php
// required headers
header("Access-Control-Allow-Origin: * ");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With, X-PINGOTHER");

include '../../config/core.php';

$user = new User($dbconn);

$data = json_decode(file_get_contents("php://input"));

if (!empty($data)) {

    $encPassword = stringEncryption('encrypt', $data->user_password);

    $user->email = $data->email;
    $user->user_password = $encPassword;
    // $user->user_password = $data->user_password;

    $results = $user->auth();

    if ( isset($results->user_id) ) {

        setcookie("auth", stringEncryption('encrypt', 'success_login'), time()+3600);

        http_response_code(200);
        echo json_encode($results);

    } else {

        http_response_code(404);
        echo json_encode(array("message" => "User does not exist."));

    }

}