<?php

// This class Represents the category
Class View {
    private $table_name = "view";

    public $dbconn;

    public function __construct( $dbc ) {
        $this->dbconn = $dbc->dbc;
    }

     // object properties    
     public $view_id;
     public $product_id;
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
        product_id=:product_id,
        user_id=:user_id";
        
        $sql = $this->dbconn->prepare($query);

        $this->product_id = sanitize_text_fields($this->product_id);
        $this->user_id = sanitize_text_fields($this->user_id);


        $sql->bindParam(":product_id", $this->product_id);
        $sql->bindParam(":user_id", $this->user_id);
    

        if ($sql->execute()) {
            return true;
        }

        return false;
    }

    function read_one() {
        $query = "SELECT * FROM ". $this->table_name . " WHERE view_id=:view_id";
        $sql = $this->dbconn->prepare($query);

        $sql->bindParam(":view_id", $this->view_id);
        $sql->execute();

        $result = $sql->fetch(PDO::FETCH_OBJ);
        
        return $result;
    }

    function get_by_id() {
        $query = "SELECT * FROM ". $this->table_name . " WHERE user_id=:user_id GROUP BY product_id";
        $sql = $this->dbconn->prepare($query);

        $sql->bindParam(":user_id", $this->user_id);
        $sql->execute();

        $result = $sql->fetchall(PDO::FETCH_OBJ);
        
        return $result;
    }

    // delete the view
    function delete(){
    
        // delete query
        $query = "DELETE FROM " . $this->table_name . " WHERE view_id=:view_id";
    
        // prepare query
        $sql = $this->dbconn->prepare($query);
    
        // bind id of record to delete
        $sql->bindParam(":view_id", $this->view_id);
    
        // execute query
        if($sql->execute()){
            return true;
        }
    
        return false;
    }

    function update(){
        
        $sql = "UPDATE " . $this->table_name . " SET
        product_id=:product_id,
        user_id=:user_id WHERE view_id=:view_id";
    
        
        // prepare query
        $sql = $this->dbconn->prepare($sql);


        $this->product_id = sanitize_text_fields($this->product_id);
        $this->user_id = sanitize_text_fields($this->user_id);

        $sql->bindParam(":product_id", $this->product_id);
        $sql->bindParam(":user_id", $this->user_id);
        $sql->bindParam(":view_id", $this->view_id);

        // execute query
        if($sql->execute()){
            return true;
        }
    
        return false;
    }

}