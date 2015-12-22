<?php
include_once("../master_db.php");
$db = masterDB::getDB();
$valid = false;
$error_msg = "";

 if(isset($_POST["username"]) AND isset($_POST["pwd"])){
	if($_POST["username"] != "" AND $_POST["pwd"] != ""){
		$req = $db->prepare("SELECT id, user_group FROM users WHERE username = ? AND pwd = ?");
		$pwd = sha1($_POST["pwd"]);
		$req->execute(array($_POST["username"],$pwd));
		if($res = $req->fetch()){
			$valid = true;
		}
		else{
			$error_msg = "Identifiants incorrects<br>";
		}
	}
 }
 
 if($valid){
	session_start();
	$_SESSION["id"] = $res["id"];
	$_SESSION["username"] = $_POST["username"];
	$_SESSION["group"] = $res["user_group"];
	if(isset($_POST["remember"])){
		setcookie("username",$_POST["username"],time()+3600*24*30,null,null,false,true);
		setcookie("pwd",$pwd,time()+3600*24*30,null,null,false,true);
	}
	echo "Connecté avec succès";
}
else{
	echo $error_msg;
	?>
	<h3>Connexion :</h3>
	<form action="login.php" method="post">
	<p>
	Nom d'utilisateur : <input type="text" name="username" required><br><br>
	Mot de passe : <input type="password" name="pwd" required><br><br>
	<input type="checkbox" name="remember" id="remember"><label for="remember">Rester connecté</label><br><br>
	<input type="submit" value="Connexion">
	</p>
	</form>
<?php 
} 
?>