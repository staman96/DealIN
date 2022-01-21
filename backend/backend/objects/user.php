<?php

// This class Represents the category
Class User {
    private $table_name = "user";

    public $dbconn;

    public function __construct( $dbc ) {
        $this->dbconn = $dbc->dbc;
    }


     // object properties    
     public $user_id;
     public $user_name;
     public $user_password;
     public $email;
     public $user_role;
     public $user_status;
     public $created_at;
     public $updated_at;


     function read_all($fields) {
        $query = "SELECT ".$fields. " FROM " .$this->table_name .' AS u INNER JOIN user_meta as m WHERE u.user_id = m.user_id';
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
        user_name=:user_name,
        user_password=:user_password,
        email=:email";
        
        $sql = $this->dbconn->prepare($query);


        $this->user_name = sanitize_text_fields($this->user_name);
        $this->user_password = sanitize_text_fields($this->user_password);
        $this->email = sanitize_text_fields($this->email);
    

        $sql->bindParam(":user_name", $this->user_name);
        $sql->bindParam(":user_password", $this->user_password);
        $sql->bindParam(":email", $this->email);


        if ($sql->execute()) {
            return $this->dbconn->lastInsertId();
        }

        return false;
    }


    function read_one() {
        $query = "SELECT * FROM ". $this->table_name . " AS u INNER JOIN user_meta AS m WHERE u.user_id = m.user_id AND u.user_id=:user_id";

        $sql = $this->dbconn->prepare($query);

        $sql->bindParam(":user_id", $this->user_id);
        $sql->execute();

        $result = $sql->fetch(PDO::FETCH_OBJ);
        
        return $result;
    }


    // delete the user
    function delete(){
    
        // delete query
        $query = "DELETE FROM " . $this->table_name . " WHERE user_id=:user_id";
    
        // prepare query
        $sql = $this->dbconn->prepare($query);
    
        // bind id of record to delete
        $sql->bindParam(":user_id", $this->user_id);
    
        // execute query
        if($sql->execute()){
            return true;
        }
    
        return false;
    }


    function check_email($email) {

        $query = "SELECT  *  FROM ". $this->table_name . " WHERE email=:email";

        $sql = $this->dbconn->prepare($query);  

        $sql->bindParam(":email", $email);

        $sql->execute();
        $result = $sql->fetch(PDO::FETCH_OBJ);

        return $result;
    }
    
    
    function auth() {   
        $query = "SELECT * FROM " . $this->table_name . " AS u INNER JOIN user_meta AS m WHERE u.user_id = m.user_id AND u.email=:email AND u.user_password=:user_password";

        $sql = $this->dbconn->prepare($query);

        $sql->bindParam(":email", $this->email);
        $sql->bindParam(":user_password", $this->user_password);

        // Execute The Build Query 
        $sql->execute();
        
        // Assign All Results in Variable and fetch the data as OBJECT
        $result = $sql->fetch(PDO::FETCH_OBJ);

        return $result;              
    }


    function update(){

        $sql = "UPDATE " . $this->table_name . " SET 
        user_name=:user_name,
        user_role=:user_role,
        user_status=:user_status WHERE user_id=:user_id";

    
        // prepare query
        $sql = $this->dbconn->prepare($sql);


        $this->user_name = sanitize_text_fields($this->user_name);
        $this->user_role = sanitize_text_fields($this->user_role);
        $this->user_status = sanitize_text_fields($this->user_status);

       
        $sql->bindParam(":user_name", $this->user_name);
        $sql->bindParam(":user_role", $this->user_role);
        $sql->bindParam(":user_status", $this->user_status);
        $sql->bindParam(":user_id", $this->user_id);

        // execute query
        if($sql->execute()){
            return true;
        }
        return false;
    }

    function update_full(){

        $sql = "UPDATE " . $this->table_name . " SET 
        user_name=:user_name,
        user_password=:user_password,
        user_role=:user_role ,
        user_status=:user_status WHERE user_id=:user_id";

    
        // prepare query
        $sql = $this->dbconn->prepare($sql);


        $this->user_name = sanitize_text_fields($this->user_name);
        $this->user_password = sanitize_text_fields($this->user_password);
        // $this->email = sanitize_text_fields($this->email);
        $this->user_role = sanitize_text_fields($this->user_role);
        $this->user_status = sanitize_text_fields($this->user_status);

       
        $sql->bindParam(":user_name", $this->user_name);
        $sql->bindParam(":user_password", $this->user_password);
        // $sql->bindParam(":email", $this->email);
        $sql->bindParam(":user_role", $this->user_role);
        $sql->bindParam(":user_status", $this->user_status);
        $sql->bindParam(":user_id", $this->user_id);

        // execute query
        if($sql->execute()){
            return true;
        }
        return false;
    }

}