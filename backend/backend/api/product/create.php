<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include '../../config/core.php';

$product = new Product($dbconn);

$productC = False;

// Get Data
$data = json_decode(file_get_contents("php://input"));

if (!empty($data)) {

    $code_base64 = str_replace('data:image/jpeg;base64,','',$data->product_image);
    $code_binary = base64_decode($code_base64);

    $f = finfo_open();

    $mime_type = finfo_buffer($f, $code_binary, FILEINFO_MIME_TYPE);
    $split = explode( '/', $mime_type );
    $ext = $split[1]; 

    // $image = imagecreatefromstring($code_binary);
    file_put_contents('c:\\xampp\\htdocs\\dit\\deal-in\\backend\\uploads\\'.$data->product_name.'.'.$ext, $code_binary);

    $product->product_name = $data->product_name;
    $product->auction_starting_price = $data->auction_starting_price;
    $product->current_value = $data->auction_starting_price;
    $product->product_description = $data->product_description;
    $product->auction_ends = $data->auction_ends;
    $product->product_osm_long = $data->product_osm_long;
    $product->product_osm_lat = $data->product_osm_lat;
    $product->product_osm_country = $data->product_osm_country;
    $product->product_image = 'https://dealin.gr/deal-in/backend/uploads/'.$data->product_name.'.'.$ext;
    $product->product_slug = str_replace("?", "", str_replace('--', '-', strtolower(clean($data->product_name))));
    $product->user_id = $data->user_id;

    $result = $product->create();
    $productC = True;

    if ($result > 0 && !empty($data->categories)) {
        
        foreach ($data->categories as $category) {
            $bindCats = new Category($dbconn);
            $bindCats->bindProductsToCategories($category, $result);
        }
    }
    // $product->create()

    if($productC){
        // set response code - 201 created
        http_response_code(201);

        // tell the user
        echo json_encode(array("message" => "Product was created."));
    } 
        // if unable to create the product, tell the user
        else{
        
            // set response code - 503 service unavailable
            http_response_code(503);
        
            // tell the user
            echo json_encode(array("message" => "Unable to create product."));
        }
    }
    // tell the user data is incomplete
    else{ 
        // set response code - 400 bad request
        http_response_code(400);
     
        // tell the user
        echo json_encode(array("message" => "Unable to create product. Data is incomplete."));
    }

    /*
    {
	"product_name" : "jessy",
	"auction_starting_price" : "15",
	"current_value" : "60",
	"product_description" : "original toy story doll",
	"auction_ends" : "2018-06-01 00:35:07",
    "product_osm_long" : 0,
    "product_osm_lat" : 0,
    "product_osm_country" : "US",
    "product_slug" : "jessy",
	"user_id" : 1
}
*/