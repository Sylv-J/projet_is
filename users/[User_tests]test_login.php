<?php 
include("[User_tests]tests_functions.php");
$test= new Tests_Users();
$test->registration("admin_name","adminpwd","adminpwd","admin@admin.fr","admin");
$test->login_session("admin_name","adminpwd",true);
?>
