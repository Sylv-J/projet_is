<?php
session_start();
$_SESSION = array();
session_destroy();

setcookie("username","");
setcookie("pwd","");
echo "Vous avez �t� d�connect�";