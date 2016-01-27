<?php
include_once("../master_db.php");
$db = masterDB::getDB();

function getUserbyUnit($unit){
  /* TODO : retourne un tableau contenant les id d'utilisateur qui sont chargés
  de corriger une unité donnée (passée en paramètre $unit)*/
}

function getUserbySubject($subject){
  /* TODO : retourne un tableau contenant les id d'utilisateur qui sont chargés
  de corriger une epreuve donnée (passée en paramètre $subject)*/
}

function getChairman($epreuve,$concours=''){
  /*  Cette fonction retourne un 'array', indexé numériquement à partir de 0,
  des chairmans associé à l'épreuve passée en paramètre $epreuve et au
  concours $concours.
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
