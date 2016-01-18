

<?php
//Connection à la db
include_once("../master_db.php");
$db = masterDB::getDB();


function MoyennePersoGlissante($nb_copies){
	$db = masterDB::getDB();
	$req = $db->prepare("SELECT mark FROM units WHERE id_corrector = ? ORDER BY date_modif DESC ");
	$req->execute(array($_SESSION["id"]));
	
	
	$compteur = 0;
	$indice = 0;
					
	while($res = $req->fetch() and $indice < $nb_copies){
		echo $res[0];
		$compteur += $res[0];
		$indice += 1 ;
	}
	return $compteur / $indice ;
}

function MoyennePersoGlobale(){
	return MoyennePersoGlissante(INF);
}
			
			
			
			
?>