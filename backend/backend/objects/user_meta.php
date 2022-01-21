<?php

// This class Represents the category
Class UserMeta {
    private $table_name = "user_meta";

    public $dbconn;

    public function __construct( $dbc ) {
        $this->dbconn = $dbc->dbc;
    }

     // object properties    
     public $user_meta_id;
     public $fname;
     public $lname;
     public $telephone;
     public $address;
     public $vat;
     public $bidder_rating;
     public $seller_rating;
     public $updated_at;
     public $user_id;


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
        fname=:fname,
        lname=:lname,
        telephone=:telephone,
        address=:address,
        vat=:vat,
        bidder_rating=:bidder_rating,
        seller_rating=:seller_rating,
        user_id=:user_id";

        
        $sql = $this->dbconn->prepare($query);

        $this->fname = sanitize_text_fields($this->fname);
        $this->lname = sanitize_text_fields($this->lname);
        $this->telephone = sanitize_text_fields($this->telephone);
        $this->address = sanitize_text_fields($this->address);
        $this->vat = sanitize_text_fields($this->vat);
        $this->bidder_rating = sanitize_text_fields($this->bidder_rating);
        $this->seller_rating = sanitize_text_fields($this->seller_rating);
        $this->user_id = sanitize_text_fields($this->user_id);


        $sql->bindParam(":fname", $this->fname);
        $sql->bindParam(":lname", $this->lname);
        $sql->bindParam(":telephone", $this->telephone);
        $sql->bindParam(":address", $this->address);
        $sql->bindParam(":vat", $this->vat);
        $sql->bindParam(":bidder_rating", $this->bidder_rating);
        $sql->bindParam(":seller_rating", $this->seller_rating);
        $sql->bindParam(":user_id", $this->user_id);
    
        if ($sql->execute()) {
            return true;
        }

        return false;
    }


    function read_one() {
        $query = "SELECT * FROM ". $this->table_name . " WHERE user_meta_id=:user_meta_id";
        $sql = $this->dbconn->prepare($query);

        $sql->bindParam(":user_meta_id", $this->user_meta_id);
        $sql->execute();

        $result = $sql->fetch(PDO::FETCH_OBJ);
        
        return $result;
    }

    // delete the user meta
    function delete(){
    
        // delete query
        $query = "DELETE FROM " . $this->table_name . " WHERE user_meta_id=:user_meta_id";
    
        // prepare query
        $sql = $this->dbconn->prepare($query);
    
        // bind id of record to delete
        $sql->bindParam(":user_meta_id", $this->user_meta_id);
    
        // execute query
        if($sql->execute()){
            return true;
        }
    
        return false;
    }

    function update(){

        $sql = "UPDATE " . $this->table_name . " SET
        fname=:fname,
        lname=:lname,
        telephone=:telephone,
        address=:address,
        vat=:vat WHERE user_meta_id=:user_meta_id";

        
        $sql = $this->dbconn->prepare($sql);

        $this->fname = sanitize_text_fields($this->fname);
        $this->lname = sanitize_text_fields($this->lname);
        $this->telephone = sanitize_text_fields($this->telephone);
        $this->address = sanitize_text_fields($this->address);
        $this->vat = sanitize_text_fields($this->vat);
 

        $sql->bindParam(":fname", $this->fname);
        $sql->bindParam(":lname", $this->lname);
        $sql->bindParam(":telephone", $this->telephone);
        $sql->bindParam(":address", $this->address);
        $sql->bindParam(":vat", $this->vat);
        $sql->bindParam(":user_meta_id", $this->user_meta_id);
 
         // execute query
         if($sql->execute()){
             return true;
         }
         return false;
     }

}