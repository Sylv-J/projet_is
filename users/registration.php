<?php
include_once("../master_db.php");
$db = masterDB::getDB();
$valid = true;
$num = 0; //must be 3 to go
$error_msg = "";
if(isset($_POST["username"]) AND $_POST["username"] != ""){
	$num++;
	$res = $db->prepare("SELECT id FROM users WHERE username = ?");
	$res->execute(array($_POST["username"]));
	if($res->fetch()){
		$valid = false;
		$error_msg = $error_msg."Ce nom d'utilisateur existe déjà<br>";
	}
}
if(isset($_POST["pwd1"]) AND isset($_POST["pwd2"]) AND $_POST["pwd1"] != "" AND $_POST["pwd2"] != ""){
	$num++;
	if($_POST["pwd1"] != $_POST["pwd2"]){
		$valid = false;
		$error_msg = $error_msg."Les mots de passe entrés ne sont pas identiques !<br>";
	}
}
if(isset($_POST["mail"]) AND $_POST["mail"] != ""){
	$num++;
	if(!preg_match("#^[a-z0-9._-]+@[a-z0-9._-]+\.[a-z]{2,4}$#",$_POST["mail"])){
		$valid = false;
		$error_msg = $error_msg."L'adresse mail entrée n'est pas valide<br>";
	}
}
if($num <3){
	$valid = false;
	if($num >0){
		$error_msg = $error_msg."Tous les champs sont obligatoires !<br>";
	}
}

if($valid){
	echo "Votre inscription à bien été prise en compte";
	// MAJ DB
	$req = $db->prepare("INSERT INTO users VALUES('',:username,:pwd,:mail,:group)");
	$req->execute(array(
		"username" => strip_tags($_POST["username"]),
		"pwd" => sha1($_POST["pwd1"]),
		"mail" => $_POST["mail"],
		"group" => $_POST["group"]
	));
}
else{
echo $error_msg
?>
<form action="registration.php" method="post">
<h3>Inscription : </h3>
<p>
	Nom d'utilisateur : <input type="text" name="username" required><br><br>
	Mot de passe : <input type="password" name="pwd1" required><br><br>
	Confirmez le mot de passe : <input type="password" name="pwd2" required><br><br>
	Adresse e-mail : <input type="email" name="mail" required><br><br>
	<input type="hidden" name ="group" value="correcteur">
	<input type="submit" value="Valider"><br>
</p>
</form>
<?php
}
?>