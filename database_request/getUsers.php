<?php
include_once("../master_db.php");

function getCorrectorbyUnit($unit,$concours=''){
  /*  Cette fonction retourne un 'array', indexé numériquement à partir de 0,
  des 'usernames' de correcteurs affectés à une unité de correction passée
  en paramètre $unit relative au concours $concours.
  L'argument $concours est optionnel. Si on ne spécifie pas de concours
  la fonction renvoie tout les correcteurs dont l'épreuve est $epreuve
  */
  $db = masterDB::getDB();
  $usersTable = $concours."users";
  $unitsTable = $concours."units";
  $req = $db->prepare(
        " SELECT username
          FROM $usersTable usr
          INNER JOIN $unitsTable unt
          ON usr.id = unt.id_corrector
          WHERE user_group LIKE :user_group AND epreuves LIKE :epreuves "
        );
  try {
    $req->execute(array(
      ':user_group' => 'correcteur' ,
      ':epreuves' => $epreuve
    ));
    $res = $req->fetchAll(PDO::FETCH_NUM);
    if($res){
      // les éléments obtenu en réponse à l'éxecution de la requête ci-dessus
      // forment un tableau de tableau. Cette structure est difficilement exploitable.
      // Donc prépare mets le résultats sous la forme d'un array (pour qu'il puisse
      // facilement être exploité ailleurs). On fait
      $nb_elem = count($res);
      for($i=0;$i<$nb_elem;$i++){
        $res[$i] = $res[$i][0];
      }
    }
  } catch (Exception $e) {
    echo 'Exception reçue : ',  $e->getMessage(), "\n";
    $res = array(''=>'');
  }
  // On retourne le résultat.
  return $res;
}

function getCorrectorbySubject($epreuve,$concours=''){
  /*  Cette fonction retourne un 'array', indexé numériquement à partir de 0,
  des 'usernames' de correcteurs associés à l'épreuve passée en paramètre
  $epreuve et au concours $concours.
  L'argument $concours est optionnel. Si on ne spécifie pas de concours
  la fonction renvoie tout les correcteurs dont l'épreuve est $epreuve
  */
  $db = masterDB::getDB();
  $table = $concours."users";
  $req = $db->prepare("SELECT username FROM $table WHERE user_group LIKE 'correcteur' AND epreuves LIKE :epreuves ");
  try {
    $req->execute(array(
      ':user_group' => 'chairman' ,
      ':epreuves' => $epreuve
    ));
    $res = $req->fetchAll(PDO::FETCH_NUM);
    if($res){
      // les éléments obtenu en réponse à l'éxecution de la requête ci-dessus
      // forment un tableau de tableau. Cette structure est difficilement exploitable.
      // Donc prépare mets le résultats sous la forme d'un array (pour qu'il puisse
      // facilement être exploité ailleurs). On fait
      $nb_elem = count($res);
      for($i=0;$i<$nb_elem;$i++){
        $res[$i] = $res[$i][0];
      }
    }
  } catch (Exception $e) {
    echo 'Exception reçue : ',  $e->getMessage(), "\n";
    $res = array(''=>'');
  }
  // On retourne le résultat.
  return $res;

}

function getChairmanbySubject($epreuve,$concours=''){
  /*  Cette fonction retourne un 'array', indexé numériquement à partir de 0,
  des 'usernames' de chairmans associé à l'épreuve passée en paramètre $epreuve
  et au concours $concours.
  L'argument $concours est optionnel. Si on ne spécifie pas de concours
  la fonction renvoie tout les chairmans dont l'epreuve est $epreuve
  */
  $db = masterDB::getDB();
  $table = $concours."users";
  $req = $db->prepare("SELECT username FROM $table WHERE user_group LIKE :user_group AND epreuves LIKE :epreuves ");
  try {
    $req->execute(array(
      ':user_group' => 'chairman' ,
      ':epreuves' => $epreuve
    ));
    $res = $req->fetchAll(PDO::FETCH_NUM);
    if($res){
      // les éléments obtenu en réponse à l'éxecution de la requête ci-dessus
      // forment un tableau de tableau. Cette structure est difficilement exploitable.
      // Donc prépare mets le résultats sous la forme d'un array (pour qu'il puisse
      // facilement être exploité ailleurs). On fait
      $nb_elem = count($res);
      for($i=0;$i<$nb_elem;$i++){
        $res[$i] = $res[$i][0];
      }
    }
  } catch (Exception $e) {
    echo 'Exception reçue : ',  $e->getMessage(), "\n";
    $res = array(''=>'');
  }
  // On retourne le résultat.
  return $res;
}
?>
