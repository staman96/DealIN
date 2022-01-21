<?php

// This class Represents the category
Class Category {
    private $table_name = "category";

    public $dbconn;

    public function __construct( $dbc ) {
        $this->dbconn = $dbc->dbc;
    }

     // object properties    
     public $category_id;
     public $category_name;
     public $category_product_count;
     public $category_slug;
     public $category_status;
     public $category_created_at;
     public $category_updated_at;


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
        category_name=:category_name,
        category_product_count=:category_product_count,
        category_slug=:category_slug,
        category_status=:category_status,
        category_created_at=:category_created_at,
        category_updated_at=:category_updated_at";
        
        $sql = $this->dbconn->prepare($query);

        $this->category_name = sanitize_text_fields($this->category_name);
        $this->category_product_count	= sanitize_text_fields($this->category_product_count);
        $this->category_slug = sanitize_text_fields($this->category_slug);
        $this->category_status = sanitize_text_fields($this->category_status);
        $this->category_created_at = sanitize_text_fields($this->category_created_at);
        $this->category_updated_at = sanitize_text_fields($this->category_updated_at);


        $sql->bindParam(":category_name", $this->category_name);
        $sql->bindParam(":category_product_count", $this->category_product_count);
        $sql->bindParam(":category_slug", $this->category_slug);
        $sql->bindParam(":category_status", $this->category_status);
        $sql->bindParam(":category_created_at", $this->category_created_at);
        $sql->bindParam(":category_updated_at", $this->category_updated_at);
    

        if ($sql->execute()) {
            return true;
        }

        return false;
    }

    function read_one() {
        $query = "SELECT * FROM ". $this->table_name . " WHERE category_id=:category_id";
        $sql = $this->dbconn->prepare($query);

        $sql->bindParam(":category_id", $this->category_id);
        $sql->execute();

        $result = $sql->fetch(PDO::FETCH_OBJ);
        
        return $result;
    }

    // delete the category
    function delete(){
    
        // delete query
        $query = "DELETE FROM " . $this->table_name . " WHERE category_id=:category_id";
    
        // prepare query
        $sql = $this->dbconn->prepare($query);
    
        // bind id of record to delete
        $sql->bindParam(":category_id", $this->category_id);
    
        // execute query
        if($sql->execute()){
            return true;
        }
    
        return false;
    }
    function deleteCats(){
    
        // delete query
        $query = "DELETE from category_has_product WHERE product_id =:product_id";
    
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
    

    function update(){
        
        $sql = "UPDATE " . $this->table_name . " SET 
        category_name=:category_name
        category_product_count=:category_product_count
        category_slug=:category_slug
        category_status=:category_status
        category_created_at=:category_created_at
        category_updated_at=:category_updated_at WHERE category_id=:category_id";

        
        // prepare query
        $sql = $this->dbconn->prepare($sql);


        $this->category_name = sanitize_text_fields($this->category_name);
        $this->category_product_count = sanitize_text_fields($this->category_product_count);
        $this->category_slug = sanitize_text_fields($this->category_slug);
        $this->category_status = sanitize_text_fields($this->category_status);
        $this->category_created_at = sanitize_text_fields($this->category_created_at);
        $this->category_updated_at = sanitize_text_fields($this->category_updated_at);


        $sql->bindParam(":category_name", $this->category_name);
        $sql->bindParam(":category_product_count", $this->category_product_count);
        $sql->bindParam(":category_slug", $this->category_slug);
        $sql->bindParam(":category_status", $this->category_status);
        $sql->bindParam(":category_created_at", $this->category_created_at);
        $sql->bindParam(":category_updated_at", $this->category_updated_at);
        $sql->bindParam(":category_id", $this->category_id);

        // execute query
        if($sql->execute()){
            return true;
        }
    
        return false;

    }

    function bindProductsToCategories($cat_id, $product_id) {
        $query = "INSERT INTO category_has_product SET
        category_id=:category_id,
        product_id=:product_id";

        $sql = $this->dbconn->prepare($query);

        $sql->bindParam(":category_id", intval($cat_id));
        $sql->bindParam(":product_id", intval($product_id)); 

        // execute query
        if($sql->execute()){
            return true;
        }
    
        return false;
        
    }

    function getbyCategorySlug() {
        $query = "SELECT * FROM ". $this->table_name . " WHERE category_slug=:category_slug";

        // prepare query
        $sql = $this->dbconn->prepare($query);

        $sql->bindParam(":category_slug", $this->category_slug);
        
        // Execute The Build Query 
        $sql->execute();
        
        // Assign All Results in Variable and fetch the data as OBJECT
        $result = $sql->fetch(PDO::FETCH_OBJ);

        return $result;
    }

    function getProductCategories() {
        $query = "SELECT * FROM category_has_product WHERE product_id =:product_id";

        // prepare query
        $sql = $this->dbconn->prepare($query);

        $sql->bindParam(":product_id", $this->product_id);
        
        // Execute The Build Query 
        $sql->execute();
        
        // Assign All Results in Variable and fetch the data as OBJECT
        $result = $sql->fetchall(PDO::FETCH_OBJ);

        return $result;       
    }

}

