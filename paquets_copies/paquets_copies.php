<?php

include_once("../master_db.php");
$separator = ';';

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
  global $separator;
  
  $result = $db->query('SELECT id_sons FROM units WHERE id ="'.$id_unit.'"');
  while($sons = $result->fetch()){
    foreach(array_unique($sons) as $son) {
      if(strpos($son,$separator)){
        $list = explode($separator, $son);
        foreach($list as $element){
          $db->query('UPDATE units SET id_corrector = "'.$localCorrector.'" WHERE id ="'.$element.'"');
          $dateModif = date('Y/m/d h:i:s');
          $db->query('UPDATE units SET date_modif = "'.$dateModif.'" WHERE id ="'.$element.'"');
          // assigner aux fils du fils...
          assignSons($localCorrector, $element);
        }

      } else {

      // pas besoin de concaténer pour les fils : un seul correcteur a priori
      $db->query('UPDATE units SET id_corrector = "'.$localCorrector.'" WHERE id ="'.$son.'"');
      $dateModif = date('Y/m/d h:i:s');
      $db->query('UPDATE units SET date_modif = "'.$dateModif.'" WHERE id ="'.$son.'"');
      // assigner aux fils du fils...
      assignSons($localCorrector, $son);
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
  global $separator;
  
  $result = $db->query('SELECT id_father FROM units WHERE id = "'.$id_unit.'"') ;
  while($fathers = $result->fetch()){
  foreach(array_unique($fathers) as $father) {
      $req = $db->query('SELECT id_corrector FROM units WHERE id = "'.$father.'"');
      while($correctors = $req->fetch()){
        foreach(array_unique($correctors) as $correctorlist) {
          $list = $correctorlist;
        }
      }
      // ici, on suppose que les id des correcteurs sont séparés par le caractère $separator
      if(isset($list)&&strpos($list,$separator)){
        $correctors = explode($separator, $list);
        array_push($correctors, $localCorrector);
        $correctors = array_unique($correctors);
        $res = implode($separator,$correctors);
      } else if(isset($list)&&$list!=NULL&&$list!=$localCorrector){
        $res = $list.$separator.$localCorrector;
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
  global $separator;
  
  $db->query('UPDATE units SET id_corrector = "'.$localCorrector.'" WHERE id ="'.$id_unit.'"');
  $dateModif = date('Y/m/d h:i:s');
  $db->query('UPDATE units SET date_modif = "'.$dateModif.'" WHERE id ="'.$id_unit.'"');
  
  // mettre à jour la liste des unités assignées au correcteur
  $req = $db->query('SELECT current_units FROM users WHERE id = "'.$localCorrector.'"');
  while($unit = $req->fetch()){
    foreach(array_unique($unit) as $assigned_unit) {
      $list = $assigned_unit;
    }
  }
  //////TEST//////////
  //echo $list;
  if(isset($list)&&strpos($list,$separator)){
    $units = explode($separator, $list);
    array_push($id_unit, $localCorrector);
    $units = array_unique($units);
    $res = implode($separator,$units);
  } else if(isset($list)&&$list!=NULL&&$list!=$id_unit){
        $res = $list.$separator.$id_unit;
  } else {
        $res = $id_unit;
  }
  $db->query('UPDATE users SET current_units = "'.$res.'" WHERE id="'.$localCorrector.'"');

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

// On suppose que $unitType est de la forme "Maths" ou "Physics"...
function assignUnits($unitType) {
  $db = masterDB::getDB();

  $listUNA = getUnitsUnassigned($unitType);
  // utile si unitType est de la forme 'Eleve_Maths1_Part1' (et dans ce cas il faut remplacer unitType par exam dans la requête sql)
  /*
  $name = explode('_', $unitType);
  $exam = $name[1];
  $exam = preg_replace('/[0-9]+/', '', $exam);
  */
  /*Modification de la requete sql car le champ epreuve n'existe pas, il faudra modifier la requete pour qu'elle 
  prenne en compte les matières que le correcteur à le droit d'utiliser
  signé antoine
  */
  /*Le champ "epreuves" existe (il a été rajouté récemment), tu peux le voir dans utils/tables_struct. Je pense qu'il faut que
  tu pull et que tu re-build tes bases de données pour l'avoir.
  Mathilde
  */
  $res = $db->query("SELECT id FROM users WHERE user_group LIKE '%corrector%' AND epreuves LIKE '%{$unitType}%'");
  //$res = $db->query("SELECT id FROM users WHERE user_group LIKE '%corrector%'");
  $list = array();
  while($correctors = $res->fetch()){
    array_push($list, $correctors[0]);
  }

  while(isset($listUNA[0])){
      $localCorrector = randomCorrector($list);
      //////TEST//////////////////////////
      //echo $listUNA[0].' :'.$localCorrector;
      updateDB($listUNA[0], $localCorrector);
      $listUNA = getUnitsUnassigned($unitType);
    }
}

/*
///////////TEST////////////////////////////
assignUnits('Maths');
assignUnits('Physics');
//////////////////////////////////////////
*/

// assigner une copie à un correcteur spécifique 
function punctualAssignment($id_corrector, $unitType) {
  $db = masterDB::getDB();
  $req = $db->query("SELECT id FROM units WHERE id_corrector IS NULL AND id LIKE '%{$unitType}%'");
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

// réinitialiser le champ id_corrector d'une unité
function freeUnit($id_unit){
  $db = masterDB::getDB();
  $db->query('UPDATE units SET id_corrector = NULL WHERE id="'.$id_unit.'"');
}

/*
///////////TEST//////////////////////
freeUnit('Eleve1_Maths1_Part1');
//////////////////////////////////////
*/

// réinitialiser le champ id_corrector de toutes les unités et, inversement, le champ current_units des correcteurs 
//(utile pour tests notamment)
function freeCorrectors(){
  $db = masterDB::getDB();
  $req = $db->query('SELECT id FROM units WHERE id_corrector IS NOT NULL');
  while($array = $req->fetch()){
    foreach(array_unique($array) as $element){
      freeUnit($element);
    }
  }
  $correctors = listCorrectors();
  foreach(array_unique($correctors) as $corrector){
      $db->query('UPDATE users SET current_units = NULL WHERE id="'.$corrector.'"');
  }
  
}

///////////TEST////////////////////////////
//freeCorrectors();
//////////////////////////////////////////

// récupérer les différentes matières d'une épreuve (sous forme de tableau)
function getSubjects(){
  $db = masterDB::getDB();
  
  $result = $db->query('SELECT id FROM units WHERE id IS NOT NULL') ;
  $res = array();
  while($units = $result->fetch()){
    foreach(array_unique($units) as $unit) {
      // on suppose que le nom de l'unité est de la forme "Eleve1_Maths1_Part1..." (la matière est donc après le premier '_')
      // Attention, si le nom de l'unité est d'une forme différente, il faut modifier la partie sélectionnée
      $name = explode('_', $unit);
      $exam = $name[1]; 
      //$exam = preg_replace('/[0-9]+/', '', $exam);
      array_push($res, $exam);
      $res = array_unique($res);
    }
  }
  return $res;
}

/*
////////TEST////////////////////////////
$test = getSubjects();
echo implode(' ', $test);
//////////////////////////////////////
*/

// assigner en masse toutes les unités non assignées (toutes les matières)
function assignAll() {
  $db = masterDB::getDB();
  $subjects = getSubjects();
  foreach($subjects as $subject){
    assignUnits($subject);  
  }
}

///////////TEST////////////////////////////
//assignAll();
//////////////////////////////////////////

// tableau des correcteurs ayant au moins une unité assignée
function listCorrectors(){
  $db = masterDB::getDB();
  $result = $db->query("SELECT id FROM users WHERE user_group LIKE '%corrector%' AND current_units IS NOT NULL") ;
  $res = array();
  while($correctors = $result->fetch()){
    $correctors = array_unique($correctors);
    foreach($correctors as $corrector){
      array_push($res, $corrector);
    }
  }
  return $res;
}

/*
////////TEST///////////////////////////////////
$liste_correcteurs = listCorrectors();
echo implode($liste_correcteurs);
///////////////////////////////////////////////
*/

// récupérer les unités d'un correcteur
function unitsCorrector($id_corrector){
  $db = masterDB::getDB();
  $result = $db->query('SELECT current_units FROM users WHERE id="'.$id_corrector.'"') ;
  $res = $result->fetch();
  return array_unique($res); 
  
}

/*
////////TEST///////////////////////////////////
$test = unitsCorrector(1);
$test1 = unitsCorrector(2);
$test2 = unitsCorrector(3);
echo implode($test);
echo implode($test1);
echo implode($test2);
///////////////////////////////////////////////
*/

?>
