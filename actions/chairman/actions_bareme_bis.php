
<?php
include_once("udc_chairman.php");
include_once("../../users/user_context.php");
$nom_concours = $_SESSION["username"];
$db = masterDB::getDB();

$texte = $_POST['bareme'];
$tab = preg_split("/[\n]/",$texte); // convertit les données rentrées en une table triée par ligne. CETTE LIGNE EST AJOUTEE POUR ALBAN
//
//
//Traitement des données vers un format déja connu :
//
//

$table = preg_split("/[\n]/",$texte); // convertit les données rentrées en une table triée par ligne.

//print_r(array_values($table)); //test

//Cette fonction compte le nombre d'étoiles dans une chaîne de caractères.
function nombre($string)
{
	if(preg_match("/\*/",$string)){
		return strrpos($string,"*")+1;
	}
	else{
		return (int) 0;
	}
}
//echo(nombre($table[0]));

$nbre_lignes = count($table);

//La table des indices sert à indiquer dans quelle sous-partie va se situer l'exercice.
$indextable = array();
for($i=0;$i<$nbre_lignes;$i++){
	$indextable[]=nombre($table[$i]);
}

$table_pluspetiteunit = array(); //cette table indique si l'unité est une "plus petite unité" (i.e. si elle nécessite un barême).
for($l=0;$l<count($table);$l++){
	if($l==count($table)-1){
		$table_pluspetiteunit[]=true;
	}else{
		$table_pluspetiteunit[]=($indextable[$l]>=$indextable[$l+1]);
	}
}


//On convertit le texte en texte demandé vers une charte compatible avec udc_chairman
$id_father_table = array();
$id_father_table[]="0";
$texte = "_";

for($j=0;$j<$nbre_lignes;$j++){
	if(strlen($texte)!=0){
		$texte.='
		';
	}
	$mark = 0;
	$id = substr($table[$j],$indextable[$j]);  //On prend la fin de la ligne après les étoiles.
	$id_father_table[$indextable[$j]] = $id;
	if($table_pluspetiteunit[$j]){
		$double = explode("@",$id);
		$id = $double[0];
		$id_father_table[$indextable[$j]] = $id;
		$mark = $double[1];
		$ligne = "";
		for($m=0;$m<$indextable[$j]+1;$m++){
			$ligne.=$id_father_table[$m];
			$ligne.="_";
		}
		$ligne.="$mark";
		$texte.=$ligne;
	}
}

//echo($texte);


//
//
//
//Fin de traitement concernant ke barème
//DEBUT AJOUT ALBAN !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! Reutilisation de action ajout élèves
// Traitement gestion élèves
$elev = $_POST['eleves'];
$tabeleves = preg_split("/[\n]/",$elev); // convertit les données rentrées en une table triée par lignes.

$id = array("futurideleve");
$tab_ligne = array("futurideleve");
$db->query("INSERT INTO $nom_concours.units (id, id_father) VALUES ('$id[0]',-1)");



for($i =0 ; $i<count($tab) ; $i++){         //on parcourt notre tableau contenant chaque lignes
	$flag = 0;
	$count = -1;            // Je commmence à -1 pour adapter mon code à la notation de gregoire qui ajoutait une étoile de plus que moi

	$ligne = $tab[$i];
	$ligne = trim($ligne);

	for($k = 0; $k<strlen($tab[$i]) ; $k++){  // on parcourt chaque caractère de notre ligne
		if($tab[$i][$k] == "@"){
			$flag = 1;
			$ligne = rtrim ($ligne, "0..9");
			$ligne = rtrim ($ligne, "@");		//on supprime les @
			echo("flag 1 tkt");


		}
		if($tab[$i][$k] == "*"){
			$count++;
			$ligne = ltrim ($ligne, "*");		//on supprime les *

		}
	}										// maintenant on sait comment est notre ligne
	for($j = (count($tab_ligne)-1); $j>$count;$j--){
				//echo $tab_ligne[$j];
				//unset($tab_ligne[$j]);
	}

	$tab_ligne[$count+1] = $ligne;





	if($flag == 1){					//l'@ indique lorsque on est en dossier sans sous dossier
		for($z=0; $z<count($tabeleves);$z++){
			$tab_ligne[0] = trim($tabeleves[$z]);
			$path = "../../images/$nom_concours/";
			for($l = 0; $l<$count+2;$l++){

				$path = $path.$tab_ligne[$l].'/';
			}
		//echo $path;



		//echo $i;

			echo("je créé tkt");
			mkdir ($path, 0777, true );		// le true indique que le mkdir est récursif : créer les sous dossier implique création dossiers.
		}
	}
}




// FIN AJOUT ALBAN !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!


try
{
udc_chairman::generateBareme($texte);
} catch(Exception $e){
	echo "Erreur au moment de la création du barême :  \n".$e->getTraceAsString();
	echo($e->getMessage());
}

?>
