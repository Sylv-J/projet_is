<?php
include_once("../projet_is/master_db.php");
$db = masterDB::getDB();
function updateDB($idUnite){
  global $db;
  $req = $db->prepare('INSERT INTO units VALUES(:id, :id_father, :id_sons, :data, :level, :mark, :id_corrector, :date_modif)');
  $res = $req->execute(array(
    'id' => $idUnite,
    'id_father' => NULL,
    'id_sons' => NULL,
    'data' => NULL,
    'level' => NULL,
    'mark' => NULL,
    'id_corrector' => NULL,
    'date_modif' => NULL
  ));

}
function find_unset_entry(){
  global $db;
  /*
  $req = $db->prepare('SELECT id FROM units ORDER BY id ASC LIMIT 1 ') or die( 'Invalid MySQL query :'.mysql_error());
  $res = $req->execute();
  var_dump($res);*/
  $id =0;
  echo "find_unset_entry :<br/>";
  $rq = 'SELECT id FROM units ORDER BY id ASC LIMIT 1 ';
  $req = $db->prepare($rq);
  $data = $req->execute();
  while ($data)
  {
    $req = $db->prepare('SELECT id FROM units WHERE id='.$id);
    var_dump($req);
    $req->execute();
    $data = $req->fetch();

    var_dump($data);
    $id++;
  }
  return (int)$id-1;
}

?>
