<?php
/*
 * Class DBConnection
 * Create a database connection using PDO
 *
 */
Class DBConnection {

    public $driver = "mysql";
    public $host = "localhost";
    public $dbname = "deal_in";
    public $username = "root";
    public $password = "";

    // Database Connection
    public $dbc;

    /* function __construct
     * Opens the database connection
     * @param $config is an array of database connection parameters
     */
    public function __construct() {
        $this->getPDOConnection();
    }
    /* Function __destruct
     * Closes the database connection
     */
    public function __destruct() {
        $this->dbc = NULL;
    }
    /* Function getPDOConnection
     * Get a connection to the database using PDO.
     */
    private function getPDOConnection() {
        // Check if the connection is already established
        if ($this->dbc == NULL) {
            // Create the connection
            $dsn = "" .
                $this->driver .
                ":host=" . $this->host .
                ";dbname=" . $this->dbname.";charset=utf8";
            try {
                $this->dbc = new PDO( $dsn, $this->username, $this->password );
            } catch( PDOException $e ) {
                echo __LINE__.$e->getMessage();
            }
        }
    }
}