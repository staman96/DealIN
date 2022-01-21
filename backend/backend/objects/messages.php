<?php

// This class Represents the category
Class Messages {
    private $table_name = "message";

    public $dbconn;

    public function __construct( $dbc ) {
        $this->dbconn = $dbc->dbc;
    }

     // object properties    
     public $message_id;
     public $message_text;
     public $message_title;
     public $message_created_at;
     public $message_on_read;
     public $sender_user_id;
     public $receiver_user_id;
     public $product_id;
     public $sender_name;

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
        message_id=:message_id,
        message_text=:message_text,
        message_title=:message_title,
        message_created_at=:message_created_at,
        message_on_read=:message_on_read,
        sender_user_id=:sender_user_id,
        receiver_user_id=:receiver_user_id,
        product_id=:product_id,
        sender_name=:sender_name,
        receiver_name=:receiver_name";
        
        $sql = $this->dbconn->prepare($query);
        $date = date('Y-m-d h:s:i');
        $this->message_id = sanitize_text_fields($this->message_id);
        $this->message_text = sanitize_text_fields($this->message_text);
        $this->message_title = sanitize_text_fields($this->message_title);
        $this->message_created_at = $date;
        $this->message_on_read = sanitize_text_fields($this->message_on_read);
        $this->sender_user_id = sanitize_text_fields($this->sender_user_id);
        $this->receiver_user_id = sanitize_text_fields($this->receiver_user_id);
        $this->product_id = sanitize_text_fields($this->product_id);
        $this->sender_name = sanitize_text_fields($this->sender_name);
        $this->receiver_name = sanitize_text_fields($this->receiver_name);

        $sql->bindParam(":message_id", $this->message_id);
        $sql->bindParam(":message_text", $this->message_text);
        $sql->bindParam(":message_title", $this->message_title);
        $sql->bindParam(":message_created_at", $this->message_created_at);
        $sql->bindParam(":message_on_read", $this->message_on_read);
        $sql->bindParam(":sender_user_id", $this->sender_user_id);
        $sql->bindParam(":receiver_user_id", $this->receiver_user_id);
        $sql->bindParam(":product_id", $this->product_id);
        $sql->bindParam(":sender_name", $this->sender_name);
        $sql->bindParam(":receiver_name", $this->receiver_name);

        if ($sql->execute()) {
            return true;
        }

        return false;
    }

    function read_one() {
        $query = "SELECT * FROM ". $this->table_name . " WHERE message_id=:message_id";
        $sql = $this->dbconn->prepare($query);

        $sql->bindParam(":message_id", $this->message_id);
        $sql->execute();

        $result = $sql->fetch(PDO::FETCH_OBJ);
        
        return $result;
    }

    // delete the log
    function delete(){
    
        // delete query
        $query = "DELETE FROM " . $this->table_name . " WHERE message_id=:message_id";
    
        // prepare query
        $sql = $this->dbconn->prepare($query);
    
        // bind id of record to delete
        $sql->bindParam(":message_id", $this->message_id);
    
        // execute query
        if($sql->execute()){
            return true;
        }
    
        return false;
    }

    function update(){
        
        $sql = "UPDATE " . $this->table_name . " SET
        message_text=:message_text,
        message_title=:message_title,
        message_created_at=:message_created_at,
        message_on_read=:message_on_read,
        sender_user_id=:sender_user_id,
        receiver_user_id=:receiver_user_id,
        product_id=:product_id WHERE message_id=:message_id";
    
        
        // prepare query
        $sql = $this->dbconn->prepare($sql);



        $this->message_text	= sanitize_text_fields($this->message_text);
        $this->message_title = sanitize_text_fields($this->message_title);
        $this->message_created_at = sanitize_text_fields($this->message_created_at);
        $this->message_on_read = sanitize_text_fields($this->message_on_read);
        $this->sender_user_id = sanitize_text_fields($this->sender_user_id);
        $this->receiver_user_id = sanitize_text_fields($this->receiver_user_id);
        $this->product_id = sanitize_text_fields($this->product_id);
        $this->message_id = sanitize_text_fields($this->message_id);

       
        $sql->bindParam(":message_text", $this->message_text);
        $sql->bindParam(":message_title", $this->message_title);
        $sql->bindParam(":message_created_at", $this->message_created_at);
        $sql->bindParam(":message_on_read", $this->message_on_read);
        $sql->bindParam(":sender_user_id", $this->sender_user_id);
        $sql->bindParam(":receiver_user_id", $this->receiver_user_id);
        $sql->bindParam(":product_id", $this->product_id);
        $sql->bindParam(":message_id", $this->message_id);

        // execute query
        if($sql->execute()){
            return true;
        }
    
        return false;
    }
    function updateGroupMessages($messages_ids) {
        $query = "UPDATE message SET message_on_read = 1 WHERE message_id IN (".$messages_ids.")";
        $sql = $this->dbconn->prepare($query);
        // execute query
        if($sql->execute()){
            return true;
        }
    
        return false;
    }
    function getGroupMessages() {
        // $query = "SELECT * FROM " .$this->table_name ." WHERE receiver_user_id =:receiver_user_id GROUP BY product_id";
        $query = "SELECT *,GROUP_CONCAT(message_id, '') as messages_ids FROM " .$this->table_name ." WHERE receiver_user_id=:receiver_user_id GROUP BY product_id ORDER BY message_id DESC";
        $sql = $this->dbconn->prepare($query);
        $sql->bindParam(":receiver_user_id", $this->receiver_user_id);
        $sql->execute();
        $result = $sql->fetchall(PDO::FETCH_OBJ);
        return $result;
    }
    function getConversationMessages() {
        // $query = "SELECT * FROM message WHERE (receiver_user_id=:receiver_user_id AND sender_user_id=:sender_user_id ) OR (receiver_user_id=:sender_user_id AND sender_user_id=:receiver_user_id) and product_id=:product_id ORDER BY message_id DESC";
        $query = "SELECT * FROM message WHERE product_id =:product_id AND (receiver_user_id = :receiver_user_id or sender_user_id = :receiver_user_id) ORDER BY message_id DESC";
        // echo $query;
        $sql = $this->dbconn->prepare($query);
        $sql->bindParam(":receiver_user_id", $this->receiver_user_id);
        // $sql->bindParam(":sender_user_id", $this->sender_user_id);
        $sql->bindParam(":product_id", $this->product_id);
        $sql->execute();
        // $this->dbconn->debugDumpParams();
        $result = $sql->fetchall(PDO::FETCH_OBJ);
        return $result;      
    }
    // function findunreadMessages() {
    //     $sql = "SELECT count(*) as total FROM message WHERE product_id = 491 AND (receiver_user_id = 1 or sender_user_id = 1) AND message_on_read = 0";

    // }
    // function getbyReceiverID() {
    //     $query = "SELECT * FROM " .$this->table_name ." WHERE receiver_user_id=:receiver_user_id";

    //     $sql = $this->dbconn->prepare($query);

    //     $sql->bindParam(":receiver_user_id", $this->receiver_user_id);
    //     $sql->execute();

    //     // Assign All Results in Variable and fetch the data as OBJECT
    //     $result = $sql->fetchall(PDO::FETCH_OBJ);
        
    //     return $result;       
    // }

    // function getbySenderID() {
    //     $query = "SELECT * FROM " .$this->table_name ." WHERE sender_user_id=:sender_user_id";

    //     $sql = $this->dbconn->prepare($query);

    //     $sql->bindParam(":sender_user_id", $this->sender_user_id);
    //     $sql->execute();

    //     // Assign All Results in Variable and fetch the data as OBJECT
    //     $result = $sql->fetchall(PDO::FETCH_OBJ);
        
    //     return $result;         
    // }
}