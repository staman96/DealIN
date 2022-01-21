<?php

// This class Represents a product
Class Product {
    private $table_name = "product";

    public $dbconn;

    public function __construct( $dbc ) {
        $this->dbconn = $dbc->dbc;
    }

     // object properties    
     public $product_id;
     public $product_name;
     public $categories = array();
     public $auction_starting_price;
     public $product_buy_price;
     public $product_description;
     public $auction_current_bid;
     public $auction_first_bid;    
     public $auction_total_bids;
     public $auction_starts;
     public $auction_ends;
     public $product_status;
     public $product_image;
     public $product_osm_long;
     public $product_osm_lat;
     public $product_osm_country;
     public $product_location;
     public $product_slug;
     public $product_updated_at;
     public $product_created_at;
     public $user_id; 
     
     
     // read all products
     function read_all($fields) {
        //  Select All Data
        $query = "SELECT ". $fields ." FROM " . $this->table_name;
         // prepare query statement
        $sql = $this->dbconn->prepare($query);
  	    // Execute The Build Query 
        $sql->execute();
        // Assign All Results in Variable and fetch the data as OBJECT
        $result = $sql->fetchall(PDO::FETCH_OBJ);
        return $result;
     }


     // create a product
     function create() {
            // query to insert record
            $query = "INSERT INTO " . $this->table_name . " SET
                product_name=:product_name,
                auction_starting_price=:auction_starting_price,
                current_value=:current_value,
                product_description=:product_description,
                auction_ends=:auction_ends,
                product_osm_long=:product_osm_long,
                product_osm_lat=:product_osm_lat,
                product_osm_country=:product_osm_country,
                product_slug=:product_slug,
                product_image=:product_image,
                user_id=:user_id";
                
            $sql = $this->dbconn->prepare($query);

            $this->product_name = sanitize_text_fields($this->product_name);
            $this->auction_starting_price = sanitize_text_fields($this->auction_starting_price);
            $this->current_value = sanitize_text_fields($this->current_value);
            $this->product_description = sanitize_text_fields($this->product_description);
            $this->auction_ends = sanitize_text_fields($this->auction_ends);
            $this->product_osm_long = sanitize_text_fields($this->product_osm_long);
            $this->product_osm_lat = sanitize_text_fields($this->product_osm_lat);
            $this->product_osm_country = sanitize_text_fields($this->product_osm_country);
            $this->product_slug = sanitize_text_fields($this->product_slug);
            $this->product_image = $this->product_image;
            $this->user_id = sanitize_text_fields($this->user_id);
        
            $sql->bindParam(":product_name", $this->product_name);
            $sql->bindParam(":auction_starting_price", $this->auction_starting_price);
            $sql->bindParam(":current_value", $this->current_value);
            $sql->bindParam(":product_description", $this->product_description);
            $sql->bindParam(":auction_ends", $this->auction_ends);
            $sql->bindParam(":product_osm_long", $this->product_osm_long);
            $sql->bindParam(":product_osm_lat", $this->product_osm_lat);
            $sql->bindParam(":product_osm_country", $this->product_osm_country);
            $sql->bindParam(":product_slug", $this->product_slug);
            $sql->bindParam(":product_image", $this->product_image);
            $sql->bindParam(":user_id", $this->user_id);

            if ($sql->execute()) {
                return $this->dbconn->lastInsertId();
            }
            return false;
    }


    // read one product
    function read_one() {
        $query = "SELECT * FROM ". $this->table_name . " WHERE product_id=:product_id";
        $sql = $this->dbconn->prepare($query);

        $sql->bindParam(":product_id", $this->product_id);
        $sql->execute();

        $result = $sql->fetch(PDO::FETCH_OBJ);
        
        return $result;
    }


    // delete the product
    function delete(){
        // delete query
        $query = "DELETE FROM " . $this->table_name . " WHERE product_id=:product_id";
        // prepare query
        $sql = $this->dbconn->prepare($query);
        // bind id of record to delete
        $sql->bindParam(":product_id", $this->product_id);
        // execute query
        if($sql->execute()){
            return true;
        }
        return false;
    }


    // update the product
    function update(){
        $sql = "UPDATE " . $this->table_name . " SET
        product_name=:product_name,
        auction_starting_price=:auction_starting_price,
        current_value=:current_value,
        product_description=:product_description ,
        auction_ends=:auction_ends,
        product_osm_long=:product_osm_long ,
        product_osm_lat=:product_osm_lat ,
        product_osm_country=:product_osm_country WHERE product_id=:product_id";
    
        // prepare query
        $sql = $this->dbconn->prepare($sql);

        $this->product_name = sanitize_text_fields($this->product_name);
        $this->auction_starting_price = sanitize_text_fields($this->auction_starting_price);
        $this->current_value = sanitize_text_fields($this->auction_starting_price);
        $this->product_description = sanitize_text_fields($this->product_description);
        $this->auction_ends = sanitize_text_fields($this->auction_ends);
        $this->product_osm_long = sanitize_text_fields($this->product_osm_long);
        $this->product_osm_lat = sanitize_text_fields($this->product_osm_lat);
        $this->product_osm_country = sanitize_text_fields($this->product_osm_country);
        $this->product_id = intval($this->product_id);
        
        $sql->bindParam(":product_name", $this->product_name);
        $sql->bindParam(":auction_starting_price", $this->auction_starting_price);
        $sql->bindParam(":current_value", $this->auction_starting_price);
        $sql->bindParam(":product_description", $this->product_description);
        $sql->bindParam(":auction_ends", $this->auction_ends);
        $sql->bindParam(":product_osm_long", $this->product_osm_long);
        $sql->bindParam(":product_osm_lat", $this->product_osm_lat);
        $sql->bindParam(":product_osm_country", $this->product_osm_country);
        $sql->bindParam(":product_id", $this->product_id);

        // execute query
        if($sql->execute()){
            return true;
        }
        return false;
    }


    function productSearch($data) {

        // {"category" : "1", "description" : "Test product new","e_price": "189", "s_price" : "150", "location" : "USA"}

        $sql = "SELECT * FROM category_has_product as c INNER JOIN product as p WHERE c.product_id = p.product_id ";

        if (isset($data->category)) {
            $sql .= " AND c.category_id = ".$data->category ." ";
        }
        if (isset($data->description)) {
            $sql .= " AND p.product_description LIKE '%".sanitize_text_fields($data->description)."%' ";
        }
        if (isset($data->s_price) && isset($data->e_price)) {
            $sql .= " AND p.current_value BETWEEN '".$data->s_price."' AND '".$data->e_price."' ";
        }
        if (isset($data->location)) {
            $sql .= " AND product_osm_country LIKE '%".sanitize_text_fields($data->location)."%'";
        }

        $sql .= ' GROUP BY p.product_id;';

        echo $sql;
    }


    function getProductsByCategory($category) {
        $sql = "SELECT * from product as p INNER JOIN category_has_product as c WHERE p.product_id = c.product_id AND c.category_id = ".intval($category);
            
        $sql = $this->dbconn->prepare($sql);
        $sql->execute();

        // Assign All Results in Variable and fetch the data as OBJECT
        $result = $sql->fetchall(PDO::FETCH_OBJ);
        return $result;
    }

    // Curl Funcion
    function getProductsByPassedDate() {
        $sql = "SELECT * FROM product WHERE auction_ends <= NOW() AND product_status = 1";
            
        $sql = $this->dbconn->prepare($sql);
        $sql->execute();

        // Assign All Results in Variable and fetch the data as OBJECT
        $result = $sql->fetchall(PDO::FETCH_OBJ);
        return $result;       
    }


    function updateCurrentValue() {

        if (isset($this->auction_first_bid)) {
            $query = " ,auction_first_bid=:auction_first_bid ";
        } else {
            $query = "";
        }
        $sql = "UPDATE " . $this->table_name . " SET
        current_value=:current_value ".$query." WHERE product_id=:product_id";

        $sql = $this->dbconn->prepare($sql);

        if (isset($this->auction_first_bid)) {
            $this->auction_first_bid = $this->auction_first_bid;
            $sql->bindParam(":auction_first_bid", $this->auction_first_bid);
        }

        $this->current_value = intval($this->current_value);
        $this->product_id = intval($this->product_id);
        $sql->bindParam(":current_value", $this->current_value);
        $sql->bindParam(":product_id", $this->product_id);
  
        if($sql->execute()){
            return true;
        }
        return false;      
    }


    function updateProductStatus() {
        $sql = "UPDATE " . $this->table_name . " SET
        product_status=:product_status WHERE product_id=:product_id";

        $sql = $this->dbconn->prepare($sql);

        $this->product_status = intval($this->product_status);
        $this->product_id = intval($this->product_id);

        $sql->bindParam(":product_status", $this->product_status);
        $sql->bindParam(":product_id", $this->product_id);

        // execute query
        if($sql->execute()){
            return true;
        }
        return false;
    }

    // get the product by its url
    function getbyProductSlug() {
        $query = "SELECT * FROM ". $this->table_name . " WHERE product_slug=:product_slug";
        // prepare query
        $sql = $this->dbconn->prepare($query);

        $sql->bindParam(":product_slug", $this->product_slug);
        // Execute The Build Query 
        $sql->execute();

        // Assign All Results in Variable and fetch the data as OBJECT
        $result = $sql->fetch(PDO::FETCH_OBJ);
        return $result;
    }

    
    function get_by_user_id() {
        $query = "SELECT * FROM ". $this->table_name . " WHERE user_id=:user_id";

        $sql = $this->dbconn->prepare($query);

        $sql->bindParam(":user_id", $this->user_id);
        $sql->execute();

        $result = $sql->fetchall(PDO::FETCH_OBJ);
        return $result; 
    }


    function get_by_status(){

        $query = "SELECT * FROM ". $this->table_name . " WHERE product_status=1";

        $sql = $this->dbconn->prepare($query);
        $sql->execute();

        $result = $sql->fetchall(PDO::FETCH_OBJ);
        return $result; 
    }


    function get_status_currentvalue(){

        // $query = "SELECT product_status,current_value,product_id FROM " . $this->table_name . " WHERE product_id=:product_id";
        // $query = "SELECT p.product_status,p.current_value,p.product_id, (SELECT COUNT(*) as total_bids FROM bid WHERE product_id = p.product_id) as total_bids FROM product as p INNER JOIN bid as b WHERE p.product_id = b.product_id AND p.product_id=:product_id GROUP BY p.product_id ORDER BY p.current_value DESC";
        $query = "SELECT p.product_status,p.current_value,p.product_id, (SELECT COUNT(*) FROM bid WHERE product_id = p.product_id) as total_bids FROM product as p WHERE p.product_id=:product_id";
        $sql = $this->dbconn->prepare($query);

        $this->product_id = intval($this->product_id);

        $sql->bindParam(":product_id", $this->product_id);
        $sql->execute();

        $result = $sql->fetch(PDO::FETCH_OBJ);
        return $result; 
    }

    // This function is used in get_lsh_products.php
    function read_all_lsh($fields, $array) {
        //  Select All Data
        $query = "SELECT ". $fields ." FROM " . $this->table_name. " WHERE product_id NOT IN (".$array."); ";
        
         // prepare query statement
        $sql = $this->dbconn->prepare($query);
  	    // Execute The Build Query 
        $sql->execute();
        
        // Assign All Results in Variable and fetch the data as OBJECT
        $result = $sql->fetchall(PDO::FETCH_OBJ);
        return $result;
     }


    // get all product fields by its name
     function read_by_name() {
        $query = "SELECT * FROM ". $this->table_name . " WHERE product_name=:product_name";
        $sql = $this->dbconn->prepare($query);

        $sql->bindParam(":product_name", $this->product_name);
        $sql->execute();

        $result = $sql->fetch(PDO::FETCH_OBJ);
        return $result;
    }
    
    function auctionStarts() {
        $sql = "UPDATE " . $this->table_name . " SET auction_starts = '".date("Y-m-d h:i:s")."', product_status= 1 WHERE product_id=:product_id";

        $sql = $this->dbconn->prepare($sql);
        $this->product_id = $this->product_id;
        $sql->bindParam(":product_id", $this->product_id);

        // execute query
        if($sql->execute()){
            return true;
        }
        return false;      
    }
}