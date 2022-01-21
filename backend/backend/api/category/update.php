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


    $check= new Category($dbconn);
    $check->category_id= (isset($data->category_id) ? intval($data->category_id) : 0 );
    
    $results = $check->read_one();

    if(!isset($results->category_id)){

     // set response code - 503 service unavailable
     http_response_code(503);
    
     // tell the user
     echo json_encode(array("message" => "Category id doesnt't exist."));

     exit;
    }

    
    $category->category_name = $data->category_name;
    $category->category_product_count = $data->category_product_count;
    $category->category_slug = $data->category_slug;
    $category->category_status = $data->category_status;
    $category->category_created_at = $data->category_created_at;
    $category->category_updated_at = $data->category_updated_at;

   
    $category->category_id = intval($data->category_id);

    // update the category
    if($category->update()){
    
        // set response code - 200 OK
        http_response_code(200);
    
        // tell the user
        echo json_encode(array("message" => "Category was updated."));
    }
    
    // if unable to update the category
    else{
    
        // set response code - 503 service unavailable
        http_response_code(503);
    
        // tell the user
        echo json_encode(array("message" => "Unable to update category."));
    }

    /*
     {
        "category_id": "3",
        "category_name": "collectibles",
        "category_product_count": "0",
        "category_slug": "collectibles",
        "category_status": "0",
        "category_created_at": "2019-09-06 14:00:40",
        "category_updated_at": "2019-09-06 14:01:02"
    }
    */

}