<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include '../../config/core.php';

$product = new Product($dbconn);

$data = json_decode(file_get_contents("php://input"));
    
if (!empty($data)) {
   
    $product->product_id = intval($data->product_id);
    $results = $product->read_one();
    $categories = new Category($dbconn);
    $categories->product_id = $results->product_id;


    $catResults = $categories->deleteCats();

    // delete the product
    if($product->delete()){
    
        // set response code - 200 ok
        http_response_code(200);
    
        // tell the user
        echo json_encode(array("message" => "Product was deleted."));
    }
    
    // if unable to delete the product
    else{
    
        // set response code - 503 service unavailable
        http_response_code(503);
    
        // tell the user
        echo json_encode(array("message" => "Unable to delete product."));
    }

}