<?php
/// tests en cours (pour le moment, tests locaux)
include_once("../master_db.php");

function randomCorrector($correctors){
  $rand = rand(0,sizeof($correctors)-1);
  return $correctors[$rand];
}
/*
///////////////TEST///////////////////////////////////////
$info = array('1546', '964', '8848', '166', '484', '54');
$test = randomCorrector($info);
echo $test;
/////////////////////////////////////////////////////////
*/

function getUnitsUnassigned($unitType){
  $db = masterDB::getDB();
  $sql = "SELECT id FROM units WHERE id_corrector IS NULL";
  $req = $db->query($sql);
  $list = array();
  while($donnees = $req->fetch()){
    array_push($list, $donnees[0]);
  }
  /*
  ///////////////////TEST//////////////////////////////////
  foreach ($list as $value){
      echo $value;
      echo "\n\r";
  }
  //////////////////////////////////////////////////////////
  */

  $nbUnits = sizeof($list); 
  if($nbUnits<=10){
    return null;
  }else{
    return array_slice($list, 0, $nbUnits-10);
  }   
           
}
/*
///////////TEST//////////////////////////////////////////////
$test1 = getUnitsUnassigned("Eleve_Maths1_Part3");
echo $test1;    
//////////////////////////////////////////////////////////////
*/

function assignSons($localCorrector, $id_unit) {
  $db = masterDB::getDB();
	$result = $db->query('SELECT id_sons FROM units WHERE id ="'.$id_unit.'"');
	while($sons = $result->fetch()){
    foreach(array_unique($sons) as $son) {
			// pas besoin de concaténer pour les fils : un seul correcteur a priori
			$db->query('UPDATE units SET id_corrector = "'.$localCorrector.'" WHERE id ="'.$son.'"');
      $dateModif = date('Y/m/d h:i:s');
      $db->query('UPDATE units SET date_modif = "'.$dateModif.'" WHERE id ="'.$son.'"');
	  }
  }
}

/*
//////////TEST///////////////////////////////////////////////////
assignSons(2, "test");
/////////////////////////////////////////////////////////////////
*/

function assignFathers($localCorrector, $id_unit) {
	$db = masterDB::getDB();
	$result = $db->query('SELECT id_father FROM units WHERE id = "'.$id_unit.'"') ;
	while($fathers = $result->fetch()){
	  if (sizeof($fathers) == 0)  { return false ;}
    foreach(array_unique($fathers) as $father) {
	    //if(!$db->query('SELECT id_corrector LOCATE(localCorrector, id_corrector) FROM units' )) {
			// besoin de concaténer pour les pères: plusieurs correcteurs possibles
      $req = $db->query('SELECT id_corrector FROM units WHERE id = "'.$father.'"');
      while($test = $req->fetch()){
        foreach(array_unique($test) as $testou) {
          $list = $testou;
        }
      } 
      //ne marche pas avec le caractère "|" (mais semble marcher sinon)
      //// EN COURS DE MODIFICATION ////
      if(strpos($list,'|')){
        $correctors = explode('|', $list);
        $correctors = array_unique($correctors);
        array_push($correctors, $localCorrector);
        $res = implode('|',$correctors);
        echo $res;
      } else if($list!=NULL&&$list!=$localCorrector){
        $res = $list."|".$localCorrector;
        echo $res;
      } else {
        $res = $localCorrector;
      }
			$db->query('UPDATE units SET id_corrector = "'.$res.'" WHERE id = "'.$father.'"');
      $dateModif = date('Y/m/d h:i:s');
      $db->query('UPDATE units SET date_modif = "'.$dateModif.'" WHERE id = "'.$father.'"');
	  }
  }
	return true;
}

/*
/////TEST////////////////////////////////
assignFathers(3, "test2");
assignFathers(3, "test3");
//assignFathers(4, "test2");
////////////////////////////////////////
*/
 
function updateDB($id_unit, $localCorrector) {
	$db = masterDB::getDB();
  $db->query('UPDATE units SET id_corrector = "'.$localCorrector.'" WHERE id ="'.$id_unit.'"');
  $dateModif = date('Y/m/d h:i:s');
  $db->query('UPDATE units SET date_modif = "'.$dateModif.'" WHERE id ="'.$id_unit.'"');
  
	assignSons($localCorrector, $id_unit); 
  assignFathers($localCorrector, $id_unit);
  // faire boucle pour remonter l'arbre (père du père...)
	/*	
	do {
		assignFathers($localCorrector, $id_unit);
	} while (assignFathers == true) ;
  */
}
/*
//////TEST//////////////////////////////////
updateDB('test', 2);
///////////////////////////////////////////
*/

/*
/// Pas encore testé
function assignUnits($unitType) {
  $db = masterDB::getDB();

	$listUNA = getUnitsUnassigned($unitType);
	$correctors = $db->query('SELECT id_actor FROM actors WHERE le type dunité est dans le contrat du correcteur');
  
	foreach ($listeUNA as $id_unit) {		
		$localCorrector = randomCorrector($correctors);
		updateDB($localCorrector, $id_unit);
	}
}
  
function punctualAssignment($id_corrector, $unitType) {
	$db = masterDB::getDB();
  $req = $db->query('SELECT id FROM units WHERE id_corrector IS NULL');
  $list = array();
	while($donnees = $req->fetch()){
    array_push($list, $donnees);
  }
  $nbUnits = sizeof($list); 

  if ($nbUnits >=0) {
    $unit = $list[0] ;
 	}
	updateDB($id_corrector, $unit) ;
}
*/
 
?>