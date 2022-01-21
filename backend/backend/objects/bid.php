<?php

// This class Represents the bid
Class Bid {
    private $table_name = "bid";

    public $dbconn;

    public function __construct( $dbc ) {
        $this->dbconn = $dbc->dbc;
    }

     // object properties    
     public $bid_id;
     public $bid_amount;
     public $bid_time;
     public $user_id;
     public $product_id;


     function read_all($fields) {
        $query = "SELECT ".$fields. " FROM " .$this->table_name;

        $sql = $this->dbconn->prepare($query);

            // Execute The Build Query 
        $sql->execute();
        
        // Assign All Results in Variable and fetch the data as OBJECT
        $result = $sql->fetchall(PDO::FETCH_OBJ);

        return $result;      
     }


     function create() {
        // query to insert record
        $query = "INSERT INTO " . $this->table_name . " SET
        bid_amount=:bid_amount,
        user_id=:user_id,
        product_id=:product_id";
        
        $sql = $this->dbconn->prepare($query);

        $this->bid_amount = sanitize_text_fields($this->bid_amount);
        $this->user_id = sanitize_text_fields($this->user_id);
        $this->product_id = sanitize_text_fields($this->product_id);

        $sql->bindParam(":bid_amount", $this->bid_amount);
        $sql->bindParam(":user_id", $this->user_id);
        $sql->bindParam(":product_id", $this->product_id);
    

        if ($sql->execute()) {
            return true;
        }

        return false;
    }


    function currentBid() {
        $query = "SELECT * FROM ".$this->table_name." WHERE product_id=:product_id ORDER BY bid_amount DESC LIMIT 1";
    
        // prepare query
        $sql = $this->dbconn->prepare($query);
    
        // bind id of record to delete
        $sql->bindParam(":product_id", $this->product_id);       
        
        $sql->execute();

        $result = $sql->fetch(PDO::FETCH_OBJ);
        
        return $result;
    }



    // delete the bid
    function delete(){
    
        // delete query
        $query = "DELETE FROM " . $this->table_name . " WHERE bid_id=:bid_id";
    
        // prepare query
        $sql = $this->dbconn->prepare($query);
    
        // bind id of record to delete
        $sql->bindParam(":bid_id", $this->bid_id);
    
        // execute query
        if($sql->execute()){
            return true;
        }
    
        return false;
    }


    function update(){
        
        $sql = "UPDATE " . $this->table_name . " SET
        bid_amount=:bid_amount,
        bid_time=:bid_time,
        user_id=:user_id,
        product_id=:product_id WHERE bid_id=:bid_id";

        
        // prepare query
        $sql = $this->dbconn->prepare($sql);

        $this->bid_amount = sanitize_text_fields($this->bid_amount);
        $this->bid_time	= sanitize_text_fields($this->bid_time);
        $this->user_id = sanitize_text_fields($this->user_id);
        $this->product_id = sanitize_text_fields($this->product_id);

        $sql->bindParam(":bid_amount", $this->bid_amount);
        $sql->bindParam(":bid_time", $this->bid_time);
        $sql->bindParam(":user_id", $this->user_id);
        $sql->bindParam(":product_id", $this->product_id);
        $sql->bindParam(":bid_id", $this->bid_id);

        // execute query
        if($sql->execute()){
            return true;
        }
    
        return false;
    }

    
    function read_one() {
        $query = "SELECT * FROM ". $this->table_name . " WHERE bid_id=:bid_id";
        $sql = $this->dbconn->prepare($query);

        $sql->bindParam(":bid_id", $this->bid_id);
        $sql->execute();

        $result = $sql->fetch(PDO::FETCH_OBJ);
        
        return $result;
    }


    // returns bids for specific user_id
    function bids_per_user() {
        // $query = "SELECT * FROM ". $this->table_name . " WHERE user_id=:user_id GROUP BY product_id";
        $query = "SELECT b.user_id, b.bid_amount, b.product_id, p.product_name, p.auction_ends, b.bid_time, p.product_slug FROM ".$this->table_name." as b INNER JOIN product as p where b.product_id = p.product_id AND b.user_id=:user_id";
        $sql = $this->dbconn->prepare($query);

        $sql->bindParam(":user_id", $this->user_id);
        $sql->execute();

        $result = $sql->fetchall(PDO::FETCH_OBJ);
        
        return $result;
    }
    

    // returns bids for specific user_id for specific product_id
    function bids_per_product() {
        $query = "SELECT * FROM ". $this->table_name . " WHERE product_id=:product_id";
        $sql = $this->dbconn->prepare($query);

        $sql->bindParam(":product_id", $this->product_id);

        $sql->execute();

        $result = $sql->fetchall(PDO::FETCH_OBJ);
        
        return $result;
    }

}

