<?php
if(!isset($_SESSION))
{
  session_start();
}
$_SESSION = array();
session_destroy();
//erase cookies
setcookie("username","");
setcookie("pwd","");
echo "Vous avez été déconnecté";
?>
