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
    
    $results = $product->read_all( (empty($data) ? "*" : implode("," , $data) ) );

    if ( !empty($results) ) {

        http_response_code(200);
        if ( !empty($results->bid_amount) ){
            $results->bid_amount = intval($results->bid_amount);
        }
        if ( !empty($results->auction_starting_price) ){
            $results->auction_starting_price = intval($results->auction_starting_price);
        }

        if ( !empty($results->product_status) ){
            $results->product_status = intval($results->product_status);
        }
        if ( !empty($results->user_id) ){
            $results->user_id = intval($results->user_id);
        }
        $array = array();
        $q = 0;
        foreach ($results as $product) {
            $product_categories = new Category($dbconn);
            $product_categories->product_id = $product->product_id;
            $categories = $product_categories->getProductCategories();
            if (!empty($categories)) {
                $cats = array();
                $c = 0;
                foreach ($categories as $cat) {
                    $find_category = new Category($dbconn);
                    $find_category->category_id = $cat->category_id;
                    $category = $find_category->read_one();
                    $cats[$c] = $category->category_name;
                    $c ++;
                }
                $product->categories = $cats;
            }
            $array[$q] = $product;
            $q++;
        }

        echo json_encode($array);

    } else {

        http_response_code(404);
        echo json_encode(array("message" => "Product does not exist."));

    }


