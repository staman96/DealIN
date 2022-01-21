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


    $check= new Product($dbconn);
    $check->product_id= (isset($data->product_id) ? intval($data->product_id) : 0 );
    
    $results = $check->read_one();

    if(!isset($results->product_id)){

     // set response code - 503 service unavailable
     http_response_code(503);
    
     // tell the user
     echo json_encode(array("message" => "Product doesnt't exist."));

     exit;
    }

    $product->product_name = $data->product_name;
    $product->auction_starting_price = $data->auction_starting_price;
    $product->product_description = $data->product_description;
    $product->auction_ends = $data->auction_ends;
    $product->product_osm_long = $data->product_osm_long;
    $product->product_osm_lat = $data->product_osm_lat;
    $product->product_osm_country = $data->product_osm_country;
    $product->product_id = intval($data->product_id);

    // update the product
    if($product->update()){
        if ($data->categories) {
            $remove_categories = new Category($dbconn);
            $remove_categories->product_id = intval($data->product_id);
            $check = $remove_categories->deleteCats();
            foreach ($data->categories as $cat) {
                $bindCats = new Category($dbconn);
                $bindCats->bindProductsToCategories($cat, $data->product_id);
            }
        }
        // set response code - 200 OK
        http_response_code(200);
    
        // tell the user
        echo json_encode(array("message" => "Product was updated."));
    }
    
    // if unable to update the product
    else{
    
        // set response code - 503 service unavailable
        http_response_code(503);
    
        // tell the user
        echo json_encode(array("message" => "Unable to update product."));
    }

    /*
    {
        "product_id": "1",
        "product_name": "christopher radko | fritz n_ frosty sledding",
        "auction_starting_price": "1.00",
        "product_buy_out_price": null,
        "product_description": "brand new beautiful handmade european blown glass ornament from christopher radko. this particular ornament features a snowman paired with a little girl bundled up in here pale blue coat sledding along on a silver and blue sled filled with packages. the ornament is approximately 5_ tall and 4_ wide. brand new and never displayed, it is in its clear plastic packaging and comes in the signature black radko gift box. PLEASE READ CAREFULLY!!!! payment by cashier's check, money order, or personal check. personal checks must clear before shipping. the hold period will be a minimum of 14 days. I ship with UPS and the buyer is responsible for shipping charges. the shipping rate is dependent on both the weight of the package and the distance that package will travel. the minimum shipping/handling charge is $6 and will increase with distance and weight. shipment will occur within 2 to 5 days after the deposit of funds. a $2 surcharge will apply for all USPS shipments if you cannot have or do not want ups service. If you are in need of rush shipping, please let me know and I_will furnish quotes on availability. the BUY-IT-NOW price includes free domestic shipping (international winners and residents of alaska and hawaii receive a credit of like value applied towards their total) and, as an added convenience, you can pay with paypal if you utilize the feature. paypal is not accepted if you win the auction during the course of the regular bidding-I only accept paypal if the buy it now feature is utilized. thank you for your understanding and good luck! Free Honesty Counters powered by Andale! Payment Details See item description and Payment Instructions, or contact seller for more information. Payment Instructions See item description or contact seller for more information.",
        "auction_current_bid": null,
        "auction_first_bid": null,
        "auction_starts": "2001-12-03 18:10:40",
        "auction_ends": "2001-12-13 18:10:40",
        "product_status": "1",
        "product_image": null,
        "product_osm_long": null,
        "product_osm_lat": null,
        "product_osm_country": "USA",
        "product_location": null,
        "product_slug": "christopher-radko-fritz-n-frosty-sledding",
        "product_created_at": "2019-09-13 20:46:04",
        "product_updated_at": "2019-09-13 20:46:04",
        "user_id": "1"
    }
    */

}