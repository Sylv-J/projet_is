<?php
include_once("../master_db.php");
$db = masterDB::getDB();
function updateDB($idUnite){
  global $db;
  $req = $db->prepare('INSERT INTO minesunits(id, id_father, id_sons, data, level, mark, max_mark,id_corrector, date_modif) VALUES(:id, :id_father, :id_sons, :data, :level, :mark, :max_mark, :id_corrector, :date_modif)');
  $req->execute(array(
    'id' => $idUnite,
    'id_father' => NULL,
    'id_sons' => NULL,
    'data' => NULL,
    'level' => NULL,
    'mark' => NULL,
    'max_mark' => NULL,
    'id_corrector' => NULL,
    'date_modif' => NULL
  ));


}
function find_unset_entry(){
  global $db;
  // requête pour se mettre en haut de la table 'units'
  $rq = 'SELECT id FROM units ORDER BY id ASC LIMIT 1 ';
  // on prépare la requête
  $req = $db->prepare($rq);
  // on l'exécute
  $data = $req->execute();
  // on parcourt la table du début jusqu'à trouver une entrée libre (id non assigné)
  for($id=0;$data;$id++){
    $req = $db->prepare('SELECT id FROM units WHERE id='.$id);
    $req->execute();
    // Récupérer l'entrée actuelle sur la table 'units'
    $data = $req->fetch();
  }
  return (int)$id-1;
}

?>
