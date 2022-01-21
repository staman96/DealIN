<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include '../../config/core.php';

$category = new Category($dbconn);

$data = json_decode(file_get_contents("php://input"));

if (!empty($data)) {
   
    $category->category_slug = $data->category_slug;
    $results = $category->getbyCategorySlug();

    if ( isset($results->category_name) ) {

        http_response_code(200);
        echo json_encode($results);

    } else {

        http_response_code(404);
        echo json_encode(array("message" => "Product does not exist."));

    }

}