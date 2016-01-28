<?php

include_once("paquets_copies.php");
include_once("../unite_de_correction/UniteDeCorrection.php");
$separator = ';';

///////////////////////////////////////////////////
/////// INITIALISATION DE LA TABLE 'Units' ////////
///////////////////////////////////////////////////

//se rapporter à "../unite_de_correction/UniteDeCorrection.php"
$struct = "Eleve1_Maths1_Partie1_Exercice1_petita_0
Eleve1_Maths1_Partie1_Exercice1_petitb_0
Eleve1_Maths1_Partie1_Exercice2_0
Eleve1_Maths1_Partie1_Exercice3_0
Eleve1_Maths1_Partie2_Exercice1_0
Eleve1_Maths1_Partie2_Exercice2_0";
UniteDeCorrection::generateBareme($struct);


////////////////////////////////////////////////////
//////////// IMPLEMENTATION DES TESTS //////////////
////////////////////////////////////////////////////

echo "<br>";
// TEST UNITAIRE fonction getUnitsUnassigned()
echo "Test function getUnitsUnassigned()";
echo "</br>";
$epreuves = array('Maths', 'Physics');
foreach ($epreuves as $epreuve) {
  $unassignedCopies = getUnitsUnassigned($epreuve);
  echo "Liste des id des unites non assignees de type ".$epreuve." : ";
  echo implode(', ', $unassignedCopies);
  echo "</br>";
}

echo "<br>";
// TEST UNITAIRE fonction assignSonsTest()
echo "Test function assignSons()";
echo "</br>";
function assignSonsTest($expectedCorrector, $id_unit) {
  $db = masterDB::getDB();
  global $separator;

	$result = $db->query('SELECT id_sons FROM units WHERE id ="'.$id_unit.'"');
  // sélection des fils de l'unité
	while($sons = $result->fetch()){
    // $result->fetch() > 0 tant que le $result n'est pas nul
    foreach(array_unique($sons) as $son) {
    // array_unique supprime les doublons qui se fait une seule fois
      if(strpos($son,$separator)){
        $list = explode($separator, $son);
        foreach($list as $element){
          // element = id de chaque fils direct
          $req = $db->query('SELECT id_corrector FROM units WHERE id ="'.$element.'"');
          while($sonCorrector = $req->fetch()){
            foreach(array_unique($sonCorrector) as $test){
              if(assert($test, $expectedCorrector)){echo "OK";};
              echo "<br>";
              //assignSonsTest($expectedCorrector, $element);
            }
          } 
        }

      } else {
			// pas besoin de concaténer pour les fils : un seul correcteur a priori
      $req2 = $db->query('SELECT id_corrector FROM units WHERE id ="'.$son.'"');
      $sonCorrector = $req2->fetch();
      foreach(array_unique($sonCorrector) as $test) {
        if(assert($test, $expectedCorrector)){echo "OK";};
        echo "<br>";
        //assignSonsTest($expectedCorrector, $test);
      }
      }
	  }
  }
}
$units_list = array('Maths1_Partie2', 'Maths1_Partie1_Exercice1');
$correcteur = 0;
foreach ($units_list as $unit_test) {
  $correcteur++;
  assignSons($correcteur, $unit_test);
  assignSonsTest($correcteur, $unit_test);
}


echo "<br>";
// TEST UNITAIRE fonction assignFathers()
echo "Test function assignFathers()";
echo "</br>";
function assignFathersTest($expectedCorrector, $id_unit) {
	$db = masterDB::getDB();
  global $separator;

	$result = $db->query('SELECT id_father FROM units WHERE id = "'.$id_unit.'"') ;
	while($fathers = $result->fetch()){
    foreach(array_unique($fathers) as $father) {
      $req = $db->query('SELECT id_corrector FROM units WHERE id = "'.$father.'"');
      while($fatherCorrectors = $req->fetch()){
        foreach(array_unique($fatherCorrectors) as $father) {
          if(assert($father, $expectedCorrector)){echo "OK";};
          echo "<br>";
        }
      }
	  }
  }
}
$units_list = array('Maths1_Partie2', 'Maths1_Partie1_Exercice1');
$correcteur = 0;
foreach ($units_list as $unit_test) {
  $correcteur++;
  assignFathers($correcteur, $unit_test);
  assignFathersTest($correcteur, $unit_test);
}

echo "<br>"; 
// TEST UNITAIRE fonction findFather()
echo "Test function findFather()";
echo "</br>";   
if(assert(findFather("Maths1_Partie1") == "Maths1")&&
assert(findFather("Maths1_Partie2") == "Maths1")&&
assert(findFather("Maths1_Partie2_Exercice2") == 'Maths1_Partie2')&&
assert(findFather("Maths1_Partie1_Exercice3") == 'Maths1_Partie1')&&
assert(findFather("Maths1_Partie1_Exercice1_petita") == 'Maths1_Partie1_Exercice1')&&
assert(findFather("Maths1_Partie1_Exercice1_petitb") == 'Maths1_Partie1_Exercice1')&&
assert(findFather("Physics1_Partie1") == 'Physics1')){
echo "OK";
};
//////////////////////////// FIN TEST FINDFATHERS

echo "<br>";
echo "<br>";
// TEST UNITAIRE fonction updateDB()
echo "Test function updateDB()";
echo "</br>";
$id_unit_list = array('Maths1_Partie1', 'Maths1_Partie2');
$localCorrector = 1212;
foreach ($id_unit_list as $id_unit) {
  updateDB($id_unit, $localCorrector);
  assignSonsTest($id_unit, $localCorrector);
  assignFathersTest($id_unit, $localCorrector);
}

echo "<br>";
echo "End of tests";
echo "<br>";

// Pour réinitialiser la table rapidement (mettre le champ id_corrector à NULL pour toutes les unités)
/*
echo "Reinitialisation";
$db = masterDB::getDB();
$req = $db->query('SELECT id FROM units WHERE id_corrector IS NOT NULL');
while($array = $req->fetch()){
  foreach(array_unique($array) as $element){
    freeUnit($element);
  }
}
*/

?>
