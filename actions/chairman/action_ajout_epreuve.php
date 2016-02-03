<?php
header('Location: ../../interface_web/index.php');
include_once("../../master_db.php");
include_once("../../users/user_context.php");
$db = masterDB::getDB();


$nomconcours = $_SESSION["username"];
echo $nomconcours;
$tableauBareme = $_POST['bareme'];
$tab = preg_split("/[\n]/",$tableauBareme); // convertit les données rentrées en une table triée par ligne.


$id = 0;
$tab_ligne = array(0);



for($i =0 ; $i<count($tab) ; $i++){         //on parcourt notre tableau contenant chaque lignes
	$flag = 0;
	$count = 0;

	$ligne = trim($tab[$i]);
	$path = "../../images/"."$nomconcours"."/";
	for($k = 0; $k<strlen($tab[$i]) ; $k++){  // on parcourt chaque caractère de notre ligne
		if($tab[$i][$k] == "@"){
			$flag = 1;
			$ligne = rtrim ($ligne, "@");		//on supprime les @
		}

		if($tab[$i][$k] == "*"){
			$count++;
			$ligne = ltrim ($ligne, "*");		//on supprime les *
		}

	}										// maintenant on sait comment est notre ligne
	for($j = (count($tab_ligne)-1); $j>$count;$j--){
				//echo $tab_ligne[$j];
				unset($tab_ligne[$j]);
	}

	$tab_ligne[$count+1] = $ligne;

	$id[$count+1] = "$id[$count]"."$ligne";


	$idmoi = "$id[$count]"."$ligne";


	//$db->query("INSERT INTO minesunits (id, id_father) VALUES ('$idmoi','$id[$count]')");
	if($flag == 1){					//l'@ indique lorsque on est en dossier sans sous dossier
		for($l = 0; $l<$count+2;$l++){

			$path = $path.$tab_ligne[$l].'/';
		}
		//echo $path;

		//echo $i;
		echo ("<br>");
		echo ("creation du dossier");
		echo ("<br>");
		echo $path;
		echo ("<br>");
		mkdir ($path, 0777, true );		// le true indique que le mkdir est récursif : créer les sous dossier implique création dossiers.

	}
}
?>
