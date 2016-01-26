
<?php
if(!isset($_SESSION))
{
  session_start();
}
header("refresh:3;URL=../interface_web/index.php");
$_SESSION = array();
session_destroy();
//erase cookies
setcookie("username","");
setcookie("pwd","");
?>
<div class="jumbotron">
  <div class="container">
    <h2>
    <center>
      Déconnexion réussie.<br>Vous allez être redirigé vers la <a href="../interface_web/index.php">page d'acceuil</a> automatiquement.
    </center>
  </h2>
  </div>
</div>
