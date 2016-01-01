<?php
session_start();
include_once("../master_db.php");
$db = masterDB::getDB();
//Use cookies to reconnect the user
if(!isset($_SESSION["id"])){
	if(isset($_COOKIE["username"]) AND isset($_COOKIE["pwd"])){
		$req = $db->prepare("SELECT id, user_group FROM users WHERE username = ? AND pwd = ?");
		$req->execute(array($_COOKIE["username"],$_COOKIE["pwd"]));
		if($res = $req->fetch()){
			$_SESSION["id"] = $res["id"];
			$_SESSION["username"] = $_POST["username"];
			$_SESSION["group"] = $res["user_group"];
		}
	}
}
