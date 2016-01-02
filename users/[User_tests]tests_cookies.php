<?php

include_once("[User_tests]tests_functions.php");
class Results_cookies {
	public function test_results_cookies(){
		$tests= new Tests_Users();
		$arr=array();
		echo "Users test page, n=> true means that the test n is passed, none means it failed the test";
		echo "<br>Don't forget to delete your cookies before starting";
		echo "<br>This test does NOT need to be passed when you first open the page, however, it should after reloading the page, since we see check that the 'remembering' function works"; 
		ob_start();
	//Working connexion for authenticated user, remembering.
		$info=array("username"=>"admin_name","pwd1"=>"adminpwd","pwd2"=>"adminpwd","mail"=>"admin@admin.fr","group"=>"admin");
		(call_user_func_array(array($tests,"registration"),$info));	
			
	/*00*/	$arr[]=assert($tests->login_error("admin_name","adminpwd", true)=="");
	/*01*/	$arr[]=assert($tests->login_valid("admin_name","adminpwd", true)==true);
		$cookies=$tests->login_cookies("admin_name","adminpwd",true);
	/*02*/	$arr[]=assert( $cookies["username"]=="admin_name");
	/*03*/	$arr[]=assert( $cookies["pwd"]==sha1("adminpwd"));
	/*04*/	$arr[]=assert( $cookies["PHPSESSID"]!="");
		$session=$tests->login_session("admin_name","adminpwd",true);
	/*05*/	$arr[]=assert($session["id"]!="");
	/*06*/	$arr[]=assert($session["username"]=="admin_name");
	/*07*/	$arr[]=assert($session["group"]=="admin");
	//Tests context_user.php, reconnecting a user with the cookies when the session was lost
		$sto=$_SESSION;
		unset($_SESSION);
		$_SESSION=$tests->user_context();
	/*08*/	$arr[]=assert($sto==$_SESSION); // En attente de code valide
	
		ob_end_clean();
		return var_dump($arr);	
	
	}
}
$resultats= new Results_cookies();
echo $resultats->test_results_cookies();


?>

