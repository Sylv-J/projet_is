<?php

assert_options(ASSERT_ACTIVE, 1);
assert_options(ASSERT_WARNING, 1);
class Tests_Users { //These functions are used to simulate a User using the site
	
	public function login_error($username,$password,$remember) {
		//Simulation of login that gives the corresponding error
		$_POST["username"]=$username;
		$_POST["pwd"]=$password;
		if ($remember==true) {
			$_POST["remember"]=$remember;
		}
		include("login.php");
		return($error_msg);
	}
	public function login_valid($username,$password,$remember) {
		//Simulation of login that gives the validity of the request 
		$_POST["username"]=$username;
		$_POST["pwd"]=$password;
		if ($remember==true) {
			$_POST["remember"]=$remember;
		}
		include("login.php");
		return($valid);
	}
	public function login_cookies($username,$password,$remember) {
		//Simulation of login that gives the cookies obtained
		$_POST["username"]=$username;
		$_POST["pwd"]=$password;
		if ($remember==true) {
			$_POST["remember"]=$remember;
		}
		include("login.php");
		return($_COOKIE);
	}
	public function login_session($username,$password,$remember) {
		//Simulation of login that gives the state of the session
		$_POST["username"]=$username;
		$_POST["pwd"]=$password;
		if($remember==true){
			$_POST["remember"]=$remember;
		}
		include("login.php");
		if (isset($_SESSION)) {
			return($_SESSION);
		} else {
			return "";
		}
	}
	public function registration($username,$pwd1,$pwd2,$mail,$group){
		//Simulation of refistration that gives the corresponding error and the validity
		//of the request
		$_POST["username"]=$username;
		$_POST["pwd1"]=$pwd1;
		$_POST["pwd2"]=$pwd2;
		$_POST["mail"]=$mail;
		$_POST["group"]=$group;
		include("registration.php");
		$arr=array("valid"=>$valid,"error"=>$error_msg);
		return $arr ;
	}
	public function user_context(){
		include("user_context.php");
		return $_SESSION;
	}
	public function logout(){
		include("logout.php");
		$array=array("session"=>$_SESSION,"cookies"=>$_COOKIES);
		return $array;
	}
}
?>
