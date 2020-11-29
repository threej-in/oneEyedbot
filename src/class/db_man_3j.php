<?php
/**
 * PHP script for database manipulation
 * @package oneEyedBot
 * @author threej[Jitendra Pal]
 * @version 0.1.0
*/

/**
 * Initiate new connection to the database server
 */
class db_connection{

  public $connection;

  function __construct(){

    $dbconnection = new mysqli(DBSERVER, DBUSERNAME, DBPASSWORD, DBNAME);
    if($dbconnection->connect_errno){
      COM::send_log("Database connection error :( ".mysqli_error($dbconnection));
      die;
    }

    $this->connection = $dbconnection;
  }

  function close(){
    return $this->connection->close();
  }

}
