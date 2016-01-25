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

// TEST UNITAIRE fonction randomCorrector()
echo "Test fonction randomCorrector()";
echo "</br>";
$correctors_list = array('1546', '964', '8848', '166', '484', '54');
for ( $i=0; $i < 10; $i++)
{
  $corrector_random = randomCorrector($correctors_list);
  assert(in_array($corrector_random, $correctors_list));
  echo randomCorrector($correctors_list);
  echo "<br>";
}

// TEST UNITAIRE fonction getUnitsUnassigned()
echo "Test function getUnitsUnassigned()";
echo "</br>";
$epreuves = array('Maths', 'Physics');
foreach ($epreuves as $epreuve) {
  $unassignedCopies = getUnitsUnassigned($epreuve);
  echo "Liste des id des unités non assignées de type " + $epreuve + " : ";
  echo implode(' ', $unassignedCopies);
  echo "</br>";
}

// TEST UNITAIRE fonction assignSonsTest()
echo "Test function assignSons()";
echo "</br>";
function assignSonsTest($expectedCorrector /*correcteur attendu pour l'unité*/,
$id_unit) {
  $db = masterDB::getDB();
  global $separator;

	$result = $db->query('SELECT id_sons FROM units WHERE id ="'.$id_unit.'"');
  // sélection des fils de l'unité
	while($sons /*liste*/ = $result->fetch()){
    // $result->fetch() > 0 tant que le $result n'est pas nul
    foreach(array_unique($sons) as $son) {
    // array_unique supprime les doublons qui se fait une seule fois
      if(strpos($son,$separator)){
        $list = explode($separator, $son);
        foreach($list as $element){
          // element = id de chaque fils direct
          $db->query('SELECT id_corrector FROM units WHERE id ="'.$element.'"');
          $sonCorrector = array_unique($db->fetch());
          assert($sonCorrector[0] == $expectedCorrector);
          assignSonsTest($expectedCorrector, $element);
        }

      } else {
			// pas besoin de concaténer pour les fils : un seul correcteur a priori
      $db->query('SELECT id_corrector FROM units WHERE id ="'.$son.'"');
      $sonCorrector = array_unique($db->fetch());
      assert($sonCorrector[0] == $expectedCorrector);
      assignSonsTest($expectedCorrector, $son);
      }
	  }
  }
}
$units_list = array('Eleve1_Maths1', 'Eleve1_Physics1_part1');
$correcteur = 0;
foreach ($units_list as $unit_test) {
  $correcteur++;
  assignSons($correcteur, $unit_test);
  assignSonsTest($correcteur, $unit_test);
}

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
      while($correctors = $req->fetch()){
        foreach(array_unique($correctors) as $correctorlist) {
          $list = $correctorlist;
        }
      }
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
      $db->query('SELECT id_corrector FROM units WHERE id ="'.$father.'"');
      $fatherCorrectors = array_unique($db->fetch());
      assert($fathers[0] == $expectedCorrector);
	  }
  }
}
$units_list = array('Eleve1_Maths1', 'Eleve1_Physics1_part1');
$correcteur = 0;
foreach ($units_list as $unit_test) {
  $correcteur++;
  assignFathers($correcteur, $unit_test);
  assignFathersTest($correcteur, $unit_test);
}

// TEST UNITAIRE fonction findFather()
echo "Test function findFather()"
echo "</br>";
assert(findFather("Maths1_Partie1") == "Maths1");
assert(findFather("Maths1_Partie2") == "Maths1");
echo findFather("Maths1_Partie2_Exercice2");
assert(findFather("Maths1_Partie2_Exercice2") == 'Maths1_Partie2');
assert(findFather("Maths1_Partie1_Exercice3") == 'Maths1_Partie1');
assert(findFather("Maths1_Partie1_Exercice1_petita") == 'Maths1_Partie1_Exercice1');
assert(findFather("Maths1_Partie1_Exercice1_petitb") == 'Maths1_Partie1_Exercice1');
//////////////////////////// FIN TEST FINDFATHERS

// TEST UNITAIRE fonction updateDB()
echo "Test function updateDB()"
echo "</br>";
$id_unit_list = array('12', '1515', '1212');
$localCorrector = "1212";
foreach ($id_unit_list as $id_unit) {
  updateDB($id_unit, $localCorrector);
  assignSonsTest($id_unit, $localCorrector);
  assignFathersTest($id_unit, $localCorrector);
}

echo "end of the tests";

?>
