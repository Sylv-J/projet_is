<?php
class masterDB{
	
	private static $instance = null;
	private $db;
	
	public static function getDB(){
		// avoid double instantiation 
		if(is_null(static::$instance)){
			static::$instance = new masterDB();
		}
		return static::$instance->db;
	}
	
	private function __construct(){
		try{
			// DB's name is projetis ; login : "root" ; pwd : "" 
			$this->db = new PDO("mysql:host=localhost;dbname=projetis;charset=utf8","root","",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
		}
		catch(Exception $e){
			die("Fatal error while loading database : ".$e->getMessage());
		}
	}
}