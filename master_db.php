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
			$this->db = new PDO("mysql:host=localhost","root","",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
			$this->db->query("CREATE DATABASE IF NOT EXISTS projetis");
			$this->db->query("use projetis");
		}
		catch(Exception $e){
			die("Fatal error while loading database : ".$e->getMessage());
		}
	}
}