
	<div class="jumbotron">
		<div class="container">
<?php
include_once("../master_db.php");
$db = masterDB::getDB();
$valid = true;
$num = 0; //Used to check if all the fields were filled, in that case it must be 3
$error_msg = ""; //Store the error message to be displayed in case of incorrect form input

// on teste si le fichier a été lancé à la main ou depuis l'interface
if(!isset($_SESSION)) //lancé indépendamment
{
  $confirmationPage="users/registration.php";
}
else 		// lancé depuis l'interface
{
	$confirmationPage="interface_web/index.php";
}


//Check if the user entered a username, and that it isn't already in use
if(isset($_POST["username"]) AND $_POST["username"] != ""){
	$num++;
	$res = $db->prepare("SELECT id FROM users WHERE username = ?");
	$res->execute(array($_POST["username"]));
	if($res->fetch()){
		$valid = false;
		$error_msg = $error_msg."Ce nom d'utilisateur existe déjà<br>";
	}
}

//Check if the user entered a password, and that it matches with the 'confirm' one
if(isset($_POST["pwd1"]) AND isset($_POST["pwd2"]) AND $_POST["pwd1"] != "" AND $_POST["pwd2"] != ""){
	$num++;
	if($_POST["pwd1"] != $_POST["pwd2"]){
		$valid = false;
		$error_msg = $error_msg."Les mots de passe entrés ne sont pas identiques !<br>";
	}
}

//Check if the user entered a mail address, and if it seems valid
if(isset($_POST["mail"]) AND $_POST["mail"] != ""){
	$num++;
	if(!preg_match("#^[a-z0-9._-]+@[a-z0-9._-]+\.[a-z]{2,4}$#",$_POST["mail"])){ //Regular expression to check the mail
		$valid = false;
		$error_msg = $error_msg."L'adresse mail entrée n'est pas valide<br>";
	}
}

//Check if all the field were filled using num
if($num <3){
	$valid = false;
	if($num >0){
		$error_msg = $error_msg."Tous les champs sont obligatoires !<br>";
	}
}

//If everything was filled properly, and if so create the user in the database
if($valid){
	echo "<h2>L'inscription à bien été prise en compte </h2>";
	echo $_POST["username"];
// Adds the user to the database
	$req = $db->prepare("INSERT INTO users (username, pwd, mail, user_group) VALUES(?,?,?,?)");
	$req->execute(array(
			strip_tags($_POST["username"]),
			sha1($_POST["pwd1"]),
			$_POST["mail"],
			$_POST["group"]
	));

// On prévient l'utilisateur en lui envoyant un email contenant ses identifiants.
include_once("../messenger/send/notifyUserAccount.php");

}
else{
echo $error_msg
?>
<!-- HMTL code for the registration form -->
<form action=<?php echo "../$confirmationPage" ?> method="post">
<h3>Inscription : </h3>
<p>
	Nom d'utilisateur : <input type="text" name="username" required><br><br>
	Mot de passe : <input type="password" name="pwd1" required><br><br>
	Confirmez le mot de passe : <input type="password" name="pwd2" required><br><br>
	Adresse e-mail : <input type="email" name="mail" required><br><br>
	Type d'utilisateur : <select name="group">
		<option value="correcteur">Correcteur</option>
		<option value="secretaire">Secretaire</option>
		<option value="jury">Membre du jury</option>
		<option value="chairman">Chairman</option>
		<option value="admin">Administrateur</option>
	</select><br><br>
	<input type="submit" name='page_to_load' value='register'><br>
</p>
</form>
<?php
}
?>
</div>
</div>
