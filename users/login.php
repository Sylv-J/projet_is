<?php
//on teste si le fichier est lancé seul, ou depuis l'interface
if(!isset($confirmationPage)) //lancé indépendamment
{
  $confirmationPage="users/login.php";
}
// si non la $confirmationPage a été définie dans l'interface et sera utilisée pour renvoyer la page après connexion

include_once("../master_db.php");
$db = masterDB::getDB();
$valid = false;
$error_msg = "";

/* Check if the couple username,pwd exists in DB
If it does, then it's a registered user and we can authenticate them*/
 if(isset($_POST["username"]) AND isset($_POST["pwd"])){
	if($_POST["username"] != "" AND $_POST["pwd"] != ""){
		$req = $db->prepare("SELECT id, user_group FROM users WHERE username = ? AND pwd = ?"); //SQL request
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

//If the user provided a valid authentication, then start a session to remember that
 if($valid){
   if(!isset($_SESSION))
   {
     session_start();
   }
	$_SESSION["id"] = $res["id"];
	$_SESSION["username"] = $_POST["username"];
	$_SESSION["group"] = $res["user_group"];
	if(isset($_POST["remember"])){
		// if the tickbox were ticked -> cookies
		setcookie("username",$_POST["username"],time()+3600*24*30,null,null,false,true);
		setcookie("pwd",$pwd,time()+3600*24*30,null,null,false,true);
	}
  if($confirmationPage=="users/login.php") //lancé indépendamment
  {
	echo "Connecté avec succés";
  }
  else  //lancé depuis l'interface, on doit l'actualiser
  {
  header("Location: ../$confirmationPage");
  }

}
else{
	echo $error_msg;
	// login page again
	?>
  <!-- Code HTML5 de la page de connexion -->
  <h2>Authentification</h2>

  <form action=<?php echo "../$confirmationPage" ?> method="post">
  <p>
  <div class="form-group">
      <input type="text" placeholder="Identifiant" name="username" class="form-control" required>
  </div>

  <div class="form-group">
      <input type="password" placeholder="Mot de passe" name="pwd" class="form-control" required>
  </div>

  <input type="submit" value="Connexion" class="btn btn-success">

  </p>
	</form>
  <form action='index.php' method='post'>
    <div class='list-group'>
      <button type='submit' name='page_to_load' value='register' class='btn'>S'inscrire</button>
    </div>
  </form>
<?php
}
?>
