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
  // utile si le nom est sous la forme 'Eleve_Maths1_Part1'
  /*
  $name = explode('_', $unitType);
  $exam = $name[1];
  $exam = preg_replace('/[0-9]+/', '', $exam);
  */
  $sql = "SELECT id FROM units WHERE id_corrector IS NULL AND id LIKE '%{$unitType}%'";
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
  return $list;
}

/*
///////////TEST//////////////////////////////////////////////
$test1 = getUnitsUnassigned('Maths');
echo implode(' ', $test1);
//////////////////////////////////////////////////////////////
*/

function assignSons($localCorrector, $id_unit) {
  $db = masterDB::getDB();
	$result = $db->query('SELECT id_sons FROM units WHERE id ="'.$id_unit.'"');
	while($sons = $result->fetch()){
    foreach(array_unique($sons) as $son) {
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
  $db->query('UPDATE users SET current_units = "'.$id_unit.'" WHERE id="'.$localCorrector.'"');

  // appliquer les changements aux fils
	assignSons($localCorrector, $id_unit);

  // appliquer les changements au père et au père du père etc... jusqu'à ce qu'on arrive au plus au niveau (plus de père)
  $unit = $id_unit;
  //$father = $db->query('SELECT id_father FROM units WHERE id = "'.$unit.'"') -> fetch();
  //$father = array_unique($father);
  //$father = implode($father);
  //do{
  //  assignFathers($localCorrector, $unit);
  //  $unit = findFather($unit);
  //} while ($unit);
}

/*
//////TEST//////////////////////////////////
updateDB('test3', 5);
updateDB('test2', 6);
///////////////////////////////////////////
*/

// On suppose que unitType est de la forme "Maths" ou "Physics"...
function assignUnits($unitType) {
  $db = masterDB::getDB();

	$listUNA = getUnitsUnassigned($unitType);
  // utile si unitType est de la forme 'Eleve_Maths1_Part1' (et dans ce cas il faut remplacer unitType par exam dans la requête sql)
  /*
  $name = explode('_', $unitType);
  $exam = $name[1];
  $exam = preg_replace('/[0-9]+/', '', $exam);
  */
  $res = $db->query("SELECT id FROM users WHERE user_group LIKE '%{$unitType}%'");
  $list = array();
  while($correctors = $res->fetch()){
    array_push($list, $correctors[0]);
  }

  while(isset($listUNA[0])){
      $localCorrector = randomCorrector($list);
      echo $listUNA[0].' :'.$localCorrector;
      updateDB($listUNA[0], $localCorrector);
      $listUNA = getUnitsUnassigned($unitType);
    }
}

/*
///////////TEST////////////////////////////
assignUnits('Maths');
//////////////////////////////////////////
*/

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
punctualAssignment(2, 'Maths')
/////////////////////////////////
*/

?>