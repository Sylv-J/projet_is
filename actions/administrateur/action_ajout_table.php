<?php
function generateRandomString($length = 6) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
include_once("../../master_db.php");
$db = masterDB::getDB();
$tableFile = fopen("../../utils/tables_struct","r");
$nomtableusers = $_POST['NomConcours']."users";
$nomtableunits = $_POST['NomConcours']."units";


while($line = fgets($tableFile)){
	$db->query("CREATE TABLE IF NOT EXISTS $nomtableusers LIKE users");  // si mon_nom_de_tableuser existe pas je le créé avec les même champs que ceux de la table users
	$db->query("CREATE TABLE IF NOT EXISTS $nomtableunits LIKE units");  // si mon_nom_de_tableunits existe pas je le créé ...
}
mkdir("../../images/".$_POST['NomConcours']);
$db->query("INSERT INTO $nomtableunits (id, id_father) VALUES ('0','-1')");


$pwdGenerated=generateRandomString();
$req = $db->prepare("INSERT INTO users (username, pwd, mail, user_group) VALUES(?,?,?,?)");
$req->execute(array(
			strip_tags($_POST['NomConcours']),
			sha1($pwdGenerated),
			$_POST["mail"],
			"chairman"
		));
  $CreationAuto=1;
    // On prévient l'utilisateur en lui envoyant un email contenant ses identifiants.

$to      = 	$_POST["mail"];
$subject = 'le sujet';
$message =
'Bonjour,
Madame, Monsieur,
Noous sommes ravis de vous acceuilir parmi nous.
Veuillez récupérer vos identifiants pour la correction du concours.
Votre login :'.$_POST['NomConcours'].'
Mot de passe:' .$pwdGenerated.'
à bientôt,

Administrateur Correction Concours.
ProjetIS
Mines Nancy';
$headers = 'From: noreply@mines-nancy.fr' . "\r\n" .
'Reply-To: noreply@mines-nancy.fr' . "\r\n" .
'X-Mailer: PHP/' . phpversion();
mail($to, $subject, $message, $headers);
header('Location: ../../interface_web/index.php');
?>
