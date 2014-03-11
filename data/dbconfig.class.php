<?php 
class DBConfig { 
  public static $DB_CONNSTRING = "mysql:host=localhost;dbname=cursusphp"; 
  public static $DB_USERNAME   = "cursusgebruiker"; 
  public static $DB_PASSWORD   = "cursuspwd"; 
  
  public $dbh; // handle of the db connexion
  private static $instance;
  
  private function __construct()
    {
        $this->dbh = new PDO(self::$DB_CONNSTRING, self::$DB_USERNAME, self::$DB_PASSWORD);
    }
  
  public static function getInstance()
    {
        if (!isset(self::$instance))
        {
            $object = __CLASS__;
            self::$instance = new $object;
        }
        return self::$instance;
    }    
} 


