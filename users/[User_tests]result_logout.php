<?php
include("logout.php");
$arr=array();
$arr[]=assert(empty($_SESSION));
$arr[]=assert(empty($_COOKIES));
echo var_dump($arr);
?>
