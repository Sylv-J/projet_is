<?php
include_once("../../master_db.php");
$db = masterDB::getDB();


$nomconcours = $_POST['nom_concours'];
$tableuser = $nomconcours.'users';
$correcteurs = $_POST['correc'];
$epreuves = $_POST['epreuv'];
$tabcorr = preg_split("/[\n]/",$correcteurs); // convertit les données rentrées en une table triée par lignes.
$tabepr = preg_split("/[\n]/",$epreuves);



for($i =0 ; $i<count($tabcorr) ; $i++){         //on parcourt notre tableau contenant chaque lignes
	// on a le nom d'une table (correspondant à une épreuve) ainsi qu'une liste de noms de correcteurs.
	// commençons par introduire le correcteur dans la tableusers
	$correcteurname = trim($tabcorr[$i]); // on supprime les espaces qui gènent lors des requêtes sql

	$test = $db->query("SELECT id FROM $tableuser WHERE username = '$correcteurname' ");

	$testid = $test->fetch(PDO::FETCH_ASSOC)['id'];

	if ($testid == null) {
		$db->query("INSERT INTO $tableuser SELECT * FROM users WHERE username = '$correcteurname' ");
	}
	
	//on récupère les épreuves déjà allouées pour y ajouter les nouvelles
	$resobjet = $db->query("SELECT epreuves FROM $tableuser WHERE username = '$correcteurname' ");  
	$res = $resobjet->fetch(PDO::FETCH_ASSOC)['epreuves'];
	if($res !=null){
		$res = $res.';';
	}
	
	// on parcourt nos épreuves qu'on ajoute à notre liste d'épreuves : $res
	for($k = 0 ; $k<count($tabepr) ; $k++){
		if($k != (count($tabepr) -1)){
			$ajoutepreuve = trim($tabepr[$k]);
			$res = $res.$ajoutepreuve.';';
		}
		else {
			$ajoutepreuve = trim($tabepr[$k]);
			$res = $res.$ajoutepreuve;
		}
	}
	
	// on update les epreuves avec la liste mise à jour
	$db->query("UPDATE $tableuser SET epreuves = '$res' WHERE username = '$correcteurname'");

	
}
	echo("Les épreuves ont bien été ajoutées à ces correcteurs");
	

?>