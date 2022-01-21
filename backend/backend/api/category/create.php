<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include '../../config/core.php';

$category = new Category($dbconn);

// Get Data
$data = json_decode(file_get_contents("php://input"));

if (!empty($data)) {

    $category->category_name = $data->category_name;
    $category->category_product_count = $data->category_product_count;
    $category->category_slug = $data->category_slug;
    $category->category_status = $data->category_status;
    $category->category_created_at = $data->category_created_at;
    $category->category_updated_at = $data->category_updated_at;

    if($category->create()){
        // set response code - 201 created
        http_response_code(201);

        // tell the user
        echo json_encode(array("message" => "Category was created."));
    } 
        // if unable to create the category, tell the user
        else{
        
            // set response code - 503 service unavailable
            http_response_code(503);
        
            // tell the user
            echo json_encode(array("message" => "Unable to create category."));
        }
    }
    // tell the user data is incomplete
    else{ 
        // set response code - 400 bad request
        http_response_code(400);
     
        // tell the user
        echo json_encode(array("message" => "Unable to create category. Data is incomplete."));
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