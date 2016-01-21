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

// Pour le moment, ne prend pas en compte l'épreuve. Retourne juste les unités non assignées.
function getUnitsUnassigned($unitType){
  $db = masterDB::getDB();
  $sql = "SELECT id FROM units WHERE id_corrector IS NULL";
  $req = $db->query($sql);
  $list = array();
  while($donnees = $req->fetch()){
    array_push($list, $donnees[0]);
  }
  
  // pas utilisé pour le moment car tests avec petit nombre d'unités
  /*
  $nbUnits = sizeof($list); 
  if($nbUnits<=10){
    return null;
  }else{
    return array_slice($list, 0, $nbUnits-10);
  }  
  */
  //return implode(' ', $list);   
  return $list;       
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
  // ou 'SELECT id_sons FROM units WHERE id ="'.$id_unit.'"AND id_corrector IS NULL' ?
	while($sons = $result->fetch()){
    echo implode($sons);
    foreach(array_unique($sons) as $son) {
      echo $son;
      if(strpos($son,'|')){
        $list = explode('|', $son);
        foreach($list as $element){
        	$db->query('UPDATE units SET id_corrector = "'.$localCorrector.'" WHERE id ="'.$element.'"');
          $dateModif = date('Y/m/d h:i:s');
          $db->query('UPDATE units SET date_modif = "'.$dateModif.'" WHERE id ="'.$element.'"');
        }

      } else {

			// pas besoin de concaténer pour les fils : un seul correcteur a priori
			$db->query('UPDATE units SET id_corrector = "'.$localCorrector.'" WHERE id ="'.$son.'"');
      $dateModif = date('Y/m/d h:i:s');
      $db->query('UPDATE units SET date_modif = "'.$dateModif.'" WHERE id ="'.$son.'"');
      }
	  }
  }
}

/*
//////////TEST///////////////////////////////////////////////////
assignSons(2, "test");
assignSons(2, "test1");
/////////////////////////////////////////////////////////////////
*/

function assignFathers($localCorrector, $id_unit) {
	$db = masterDB::getDB();
	$result = $db->query('SELECT id_father FROM units WHERE id = "'.$id_unit.'"') ;
	while($fathers = $result->fetch()){
	  //if (sizeof($fathers) == 0)  { return false ;}
    foreach(array_unique($fathers) as $father) {
      $req = $db->query('SELECT id_corrector FROM units WHERE id = "'.$father.'"');
      while($correctors = $req->fetch()){
        foreach(array_unique($correctors) as $correctorlist) {
          $list = $correctorlist;
        }
      } 
      // Remarque : il faut changer le type du paramètre id_corrector en VARCHAR(255) (car possibilité de plusieurs correcteurs)
      // ici, on suppose que les id des correcteurs sont séparés par le caractère '|'
      if(isset($list)&&strpos($list,'|')){
        $correctors = explode('|', $list);
        array_push($correctors, $localCorrector);
        $correctors = array_unique($correctors);
        $res = implode('|',$correctors);
      } else if(isset($list)&&$list!=NULL&&$list!=$localCorrector){
        $res = $list."|".$localCorrector;
      } else {
        $res = $localCorrector;
      }
			$db->query('UPDATE units SET id_corrector = "'.$res.'" WHERE id = "'.$father.'"');
      $dateModif = date('Y/m/d h:i:s');
      $db->query('UPDATE units SET date_modif = "'.$dateModif.'" WHERE id = "'.$father.'"');
	  }
  }
	//return true;
}

/*
/////TEST////////////////////////////////
assignFathers(3, "test2");
assignFathers(3, "test3");
assignFathers(4, "test2");
////////////////////////////////////////
*/

function findFather($id_unit){
  $db = masterDB::getDB();
	$result = $db->query('SELECT id_father FROM units WHERE id = "'.$id_unit.'"') ;
	while($fathers = $result->fetch()){
    foreach(array_unique($fathers) as $father) {
      return $father;
    }
  }
  return false;
}

/*
/////////TEST////////////////////////////
echo findFather('test3');
///////////////////////////////////////
*/

function updateDB($id_unit, $localCorrector) {
	$db = masterDB::getDB();
  $db->query('UPDATE units SET id_corrector = "'.$localCorrector.'" WHERE id ="'.$id_unit.'"');
  $dateModif = date('Y/m/d h:i:s');
  $db->query('UPDATE units SET date_modif = "'.$dateModif.'" WHERE id ="'.$id_unit.'"');
  
  // appliquer les changements aux fils
	assignSons($localCorrector, $id_unit); 
  
  // appliquer les changements au père et au père du père etc... jusqu'à ce qu'on arrive au plus au niveau (plus de père)
  $unit = $id_unit;
  $father = $db->query('SELECT id_father FROM units WHERE id = "'.$unit.'"') -> fetch();
  $father = array_unique($father);
  $father = implode($father);
  do{
    assignFathers($localCorrector, $unit);
    $unit = findFather($unit);
  } while ($unit);
}

/*
//////TEST//////////////////////////////////
updateDB('test3', 5);
updateDB('test2', 6);
///////////////////////////////////////////
*/

function assignUnits($unitType) {
  $db = masterDB::getDB();

	$listUNA = getUnitsUnassigned($unitType);
  $name = explode('_', $unitType);
  $exam = $name[1];
	$res = $db->query("SELECT id FROM users WHERE user_group LIKE '%{$exam}%'");
  $list = array();
  while($correctors = $res->fetch()){
    array_push($list, $correctors[0]);
  }
  /////////TEST
  //echo implode(' ', $list);
  
	foreach ($listUNA as $id_unit) {		
		$localCorrector = randomCorrector($list);
    //////////////TEST
    //echo $id_unit.' :'.$localCorrector;
		updateDB($id_unit, $localCorrector);
	}
}

///////////TEST////////////////////////////  
//assignUnits('Eleve_Maths1_Part2');
//////////////////////////////////////////


function punctualAssignment($id_corrector, $unitType) {
	$db = masterDB::getDB();
  $req = $db->query('SELECT id FROM units WHERE id_corrector IS NULL');
  $list = array();
	while($donnees = $req->fetch()){
    array_push($list, $donnees[0]);
  }
  $nbUnits = sizeof($list); 

  if ($nbUnits >=0) {
    $unit = $list[0];
    echo $unit;
    updateDB($unit, $id_corrector);
 	}

}

/*
//////////TEST///////////////////
punctualAssignment(2, 'Eleve_Maths1_Part2')
/////////////////////////////////
*/ 
?>