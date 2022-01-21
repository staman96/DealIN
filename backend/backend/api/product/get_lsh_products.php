<?php
// required headers

/*
Για τον υπολογισμό των προτεινόμενων αντικειμένων χρησιμοποιήσαμε τον αλγόριθμο Levenshtein ο οποίος αποτελεί μέρος
του Locality sensitive hashing.
Στην υλοποίησή μας ο Levenshtein παίρνει σαν input τα ονόματα των προϊόντων που είναι αποθηκευμένα στη βάση δεδομέ-
νων και αν ο χρήστης έχει κάνει προσφορές (bids) σε προϊόντα τότε βάζουμε σε έναν πίνακα (με php) τα ονόματα των 
προϊόντων αυτών και τα αποτελέσματα φιλτράρονται με βάσει τις κοντινότερες αποστάσεις των ονομάτων αυτών. 
Διαφορετικά ο αλγόριθμος θα φιλτράρει τα προιόντα βάσει του ιστορικού των επισκέψεων (views) του χρήστη σε κάθε
προϊόν που έχει δει.
*/

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include '../../config/core.php';

$data = json_decode(file_get_contents("php://input"));

if (!empty($data)) {
    $userID = $data->user_id;
    $bids = new Bid($dbconn);
    $bids->user_id = $userID;
    $bid_results = $bids->bids_per_user();
    if (empty($bid_results)) {
        $view = new View($dbconn);
        $view->user_id = $userID;
        $view_results = $view->get_by_id();
    }
    $lsh_products = (!empty($bid_results) ? $bid_results : $view_results);

    if (!empty($lsh_products)) {
        $not_in = '';
        foreach ($lsh_products as $removed) {
            $not_in .= $removed->product_id . ',';
        }
        $not_in = rtrim($not_in, ',');

        $getProducts = new Product($dbconn);
        $product_results = $getProducts->read_all_lsh('*', $not_in);
        
        if (!empty($product_results)) {
            $product_name = array();
            $c = 0;
            foreach ($product_results as $product) {
               $product_names[$c] = $product->product_name;
               $c ++;
            }
            $lsh_name_found = array();
            $q = 0;
            foreach ($lsh_products as $compare) {
                    $product = new Product($dbconn);
                    $product->product_id = $compare->product_id;
                    $compare_result = $product->read_one();
                    $input = $compare_result->product_name;

                    // no shortest distance found, yet
                    $shortest = -1;
                        
                    // loop through words to find the closest
                    foreach ($product_names as $word) {
                    
                    // calculate the distance between the input word,
                    // and the current word
                    $lev = levenshtein($input, $word);
                
                    // check for an exact match
                    if ($lev == 0) {
                
                        // closest word is this one (exact match)
                        $closest = $word;
                        $shortest = 0;
                
                        // break out of the loop; we've found an exact match
                        break;
                    }
                
                    // if this distance is less than the next found shortest
                    // distance, OR if a next shortest word has not yet been found
                    if ($lev <= $shortest || $shortest < 0) {
                        // set the closest match, and shortest distance
                        $closest  = $word;
                        $shortest = $lev;
                    }
                }
                
                $lsh_name_found[$q] = $closest;
                $q ++;
            }   
        }
        $results = array();
        foreach ($lsh_name_found as $name) {
            $product = new Product($dbconn);
            $product->product_name = $name;
            $result = $product->read_by_name();
            array_push($results, $result);
        }
        http_response_code(200);
        echo json_encode($results);
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "No Products Found"));
    }
   
} else {
    // set response code - 400 bad request
    http_response_code(400);
    // tell the user
    echo json_encode(array("message" => "Data is incomplete."));
}