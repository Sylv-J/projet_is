
<?php
include_once("udc_chairman.php");
$db = masterDB::getDB();

$texte = $_POST['bareme'];

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

echo($texte);


//
//
//
//Fin de traitement

try
{
udc_chairman::generateBareme($texte);
} catch(Exception $e){
	//echo "Erreur au moment de la création du barême :  \n".$e->getTraceAsString();
	echo($e->getMessage());
}

?>