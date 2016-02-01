<?php
require_once "Mail.php";
if(isset($_POST["mail"])){
	$from = "Administrateur CorrectionConcours<adm.projetis@gmail.com>";
	$to = "<".$_POST['mail'].">";
	$subject = "Bienvenue";
	$usr = $_POST["username"];
	$pwd = $_POST["pwd1"];

	$body = <<<EOD
	Bonjour,
	Madame, Monsieur,
	Noous sommes ravis de vous acceuilir parmi nous.
	Veuillez récupérer vos identifiants pour la correction du concours.
	Nom d'utilisateur : $usr
	Mot de passe: $pwd.
	à bientôt,

	Administrateur Correction Concours.
	ProjetIS
	Dep'InfoNancy
EOD;

	$host = "ssl://smtp.gmail.com";
	$port = "465";
	$username = "adm.projetis@gmail.com";
	$password = "mywspfuhzdffjutt";

	$headers = array ('From' => $from,
	'To' => $to,
	'Subject' => $subject);
	$smtp = Mail::factory('smtp',
	array ('host' => $host,
	'port' => $port,
	'auth' => true,
	'username' => $username,
	'password' => $password));

	$mail = $smtp->send($to, $headers, $body);

	if (PEAR::isError($mail)) {
		echo("<p>" . $mail->getMessage() . "</p>");
	}
	else {
		echo "<br/>Un email a été envoyé pour prévenir l'utilisateur de son inscription";
	}
}
?>
