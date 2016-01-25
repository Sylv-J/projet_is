<?php
include_once("../master_db.php");
$db = masterDB::getDB();
$tableFile = fopen("../utils/tables_struct","r");
$nomtableusers = $_POST['NomConcours']."users";
$nomtableunits = $_POST['NomConcours']."units";



while($line = fgets($tableFile)){
	$db->query("CREATE TABLE IF NOT EXISTS $nomtableusers LIKE users");  // si mon_nom_de_tableuser existe pas je le créé avec les même champs que ceux de la table users
	$db->query("CREATE TABLE IF NOT EXISTS $nomtableunits LIKE units");  // si mon_nom_de_tableunits existe pas je le créé ...
	
}
mkdir("../images/".$_POST['NomConcours']);
$db->query("INSERT INTO $nomtableunits (id, id_father) VALUES ('0','-1')");
header('Location: ../interface_web/index.php')

?>
