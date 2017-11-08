<?php
//header('Content-Type: text/html; UTF-8');
class DB_Connect {
 
    // constructor
    function __construct() {
 
    }
 
    // destructor
    function __destruct() {
        // $this->close();
    }
 
    // Connecting to database
    public function connect() {
        require_once 'config.php';
        // connecting to mysql
        $con = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD,DB_DATABASE);
        // selecting database
        //mysqli_select_db($con,DB_DATABASE); 

        /*guidin*/
        mysqli_query($con,"SET NAMES 'utf8'");
        // return database handler
        return $con;
    }
 
    // Closing database connection
    public function close() {
        mysqli_close();
    }
 
}
 
?>
