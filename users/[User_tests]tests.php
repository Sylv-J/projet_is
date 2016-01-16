<?php
include_once("[User_tests]tests_functions.php");
class Results {
	public function test_results(){
		$tests= new Tests_Users();
		$arr=array();
		echo "Users test page, n=> true means that the test n is passed, none means it failed the test";
		echo "<br>Don't forget to delete your cookies before starting";
		echo "<br>Important : This test should be passed before, and after reloading the page";
		ob_start();
	// "Connexion tests for unauthenticated users or empty passwords/usernames";
	/*0*/	$arr[]=assert($tests->login_error("wrong_name","wrong_pwd", true)=="Identifiants incorrects<br>");
	/*1*/	$arr[]=assert($tests->login_error("wrong_name","wrong_pwd",false)=="Identifiants incorrects<br>");
	/*2*/	$arr[]=assert($tests->login_valid("wrong_name","wrong_pwd", true)==false);
	/*3*/	$arr[]=assert($tests->login_valid("wrong_name","wrong_pwd",false)==false);
	/*4*/	$arr[]=assert($tests->login_error("","wrong_pwd", true)=="");
	/*5*/	$arr[]=assert($tests->login_error("","wrong_pwd",false)=="");
	/*6*/	$arr[]=assert($tests->login_valid("","wrong_pwd", true)==false);
	/*7*/	$arr[]=assert($tests->login_valid("","wrong_pwd",false)==false);
	/*8*/	$arr[]=assert($tests->login_error("","", true)=="");
	/*9*/	$arr[]=assert($tests->login_error("","",false)=="");
	/*10*/	$arr[]=assert($tests->login_valid("","", true)==false);
	/*11*/	$arr[]=assert($tests->login_valid("","",false)==false);
	/*12*/	$arr[]=assert($tests->login_error("wrong_name","", true)=="");
	/*13*/	$arr[]=assert($tests->login_error("wrong_name","",false)=="");
	/*14*/	$arr[]=assert($tests->login_valid("wrong_name","", true)==false);
	/*15*/	$arr[]=assert($tests->login_valid("wrong_name","",false)==false);
	
	//"Cookies and Session tests for unauthenticated users or empty passwords/usernames";
	/*16*/	$arr[]=assert($tests->login_session("wrong_name","wrong_pwd", true)=="");
	/*17*/	$arr[]=assert($tests->login_session("wrong_name","wrong_pwd",false)=="");
		$cookies=$tests->login_cookies("wrong_name","wrond_pwd",true);
	/*18*/	$arr[]=assert( ( isset($cookies["username"])and isset($cookies["pwd"]) )==false );
		$cookies=$tests->login_cookies("wrong_name","wrond_pwd",false);
	/*19*/	$arr[]=assert( ( isset($cookies["username"])and isset($cookies["pwd"]) )==false );
	/*20*/	$arr[]=assert($tests->login_session("","wrong_pwd", true)=="");
	/*21*/	$arr[]=assert($tests->login_session("","wrong_pwd",false)=="");
		$cookies=$tests->login_cookies("","wrond_pwd",true);
	/*22*/	$arr[]=assert( ( isset($cookies["username"])and isset($cookies["pwd"]) )==false );
		$cookies=$tests->login_cookies("","wrond_pwd",false);
	/*23*/	$arr[]=assert( ( isset($cookies["username"])and isset($cookies["pwd"]) )==false );
	/*24*/	$arr[]=assert($tests->login_session("","", true)=="");
	/*25*/	$arr[]=assert($tests->login_session("","",false)=="");
		$cookies=$tests->login_cookies("","",true );
	/*26*/	$arr[]=assert( ( isset($cookies["username"])and isset($cookies["pwd"]) )==false );
		$cookies=$tests->login_cookies("","",false);
	/*27*/	$arr[]=assert( ( isset($cookies["username"])and isset($cookies["pwd"]) )==false );
	/*28*/	$arr[]=assert($tests->login_session("wrong_name","", true)=="");
	/*29*/	$arr[]=assert($tests->login_session("wrong_name","",false)=="");
		$cookies=$tests->login_cookies("wrong_name","",true );
	/*30*/	$arr[]=assert( ( isset($cookies["username"])and isset($cookies["pwd"]) )==false );
		$cookies=$tests->login_cookies("wrong_name","",false);
	/*31*/	$arr[]=assert( ( isset($cookies["username"])and isset($cookies["pwd"]) )==false );
	//Connexion tests for authenticated users, but the connexion is not established due to an error
		$info=array("username"=>"admin_name","pwd1"=>"adminpwd","pwd2"=>"adminpwd","mail"=>"admin@admin.fr","group"=>"admin");
		(call_user_func_array(array($tests,"registration"),$info));	
	/*32*/	$arr[]=assert($tests->login_error("wrong_name","adminpwd", true)=="Identifiants incorrects<br>");
	/*33*/	$arr[]=assert($tests->login_error("wrong_name","adminpwd",false)=="Identifiants incorrects<br>");
	/*34*/	$arr[]=assert($tests->login_valid("wrong_name","adminpwd", true)==false);
	/*35*/	$arr[]=assert($tests->login_valid("wrong_name","adminpwd",false)==false);
	/*36*/	$arr[]=assert($tests->login_error("admin_name","wrong_pwd", true)=="Identifiants incorrects<br>");
	/*37*/	$arr[]=assert($tests->login_error("admin_name","wrong_pwd",false)=="Identifiants incorrects<br>");
	/*38*/	$arr[]=assert($tests->login_valid("admin_name","wrong_pwd", true)==false);
	/*39*/	$arr[]=assert($tests->login_valid("admin_name","wrong_pwd",false)==false);
	

	//Cookies and Session tests for authenticated users but the connexion is not established due to an error
	


		$cookies=$tests->login_cookies("wrong_name","adwminpwd",true );
	/*40*/	$arr[]=assert( ( isset($cookies["username"])and isset($cookies["pwd"]) )==false );
		$cookies=$tests->login_cookies("wrong_name","adminpwd",false);
	/*41*/	$arr[]=assert( ( isset($cookies["username"])and isset($cookies["pwd"]) )==false );
	/*42*/	$arr[]=assert($tests->login_session("wrong_name","adminpwd", true)==false);
	/*43*/	$arr[]=assert($tests->login_session("wrong_name","adminpwd",false)==false);
		$cookies=$tests->login_cookies("admin_name","wrong_pwd",true);
	/*44*/	$arr[]=assert( ( isset($cookies["username"])and isset($cookies["pwd"]) )==false );
		$cookies=$tests->login_cookies("admin_name","wrong_pwd",false);
	/*45*/	$arr[]=assert( ( isset($cookies["username"])and isset($cookies["pwd"]) )==false );
	/*46*/	$arr[]=assert($tests->login_session("admin_name","wrong_pwd", true)==false);
	/*47*/	$arr[]=assert($tests->login_session("admin_name","wrong_pwd",false)==false);
	// Working connexion for authenticated User, without remembering.
		unset($_POST["remember"]);
	/*48*/	$arr[]=assert($tests->login_error("admin_name","adminpwd",false)=="");
	/*49*/	$arr[]=assert($tests->login_valid("admin_name","adminpwd",false)==true);
		$cookies=$tests->login_cookies("admin_name","admin_pwd",false);
	/*50*/	$arr[]=assert( ( isset($cookies["username"])and isset($cookies["pwd"]) )==false );
		$session=$tests->login_session("admin_name","adminpwd",false);
	/*51*/	$arr[]=assert($session["id"]!=="");
	/*52*/	$arr[]=assert($session["username"]=="admin_name");
	/*53*/	$arr[]=assert($session["group"]=="admin");

	//Registration test
		$res=array("valid"=>false  , "error"=>"Ce nom d'utilisateur existe déjà<br>L'adresse mail entrée n'est pas valide<br>"   );
	/*54*/	$arr[]=assert($tests->registration("admin_name","a","a","a","a")==$res);
		$res=array("valid"=>false  , "error"=>"Ce nom d'utilisateur existe déjà<br>"   );
	/*55*/	$arr[]=assert($tests->registration("admin_name","a","a","a@a.fr","a")==$res);
		$res=array("valid"=>false  , "error"=>"Les mots de passe entrés ne sont pas identiques !<br>"   );
	/*56*/	$arr[]=assert($tests->registration("admin_name_new","pwd1","pwd2","a@a.fr","a")==$res);
		$res=array("valid"=>false  , "error"=>"Tous les champs sont obligatoires !<br>"   );
	/*57*/	$arr[]=assert($tests->registration("admin_name_new","","","a@a.fr","a")==$res);

		ob_end_clean();
		return var_dump($arr);	
	}
}
$resultats= new Results();
echo $resultats->test_results();


?>
