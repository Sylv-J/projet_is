<?php
include_once("../master_db.php");

// fonction pour l'instant non utilisée. ça pourra nous servir après !
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

// fonction pour l'instant non utilisée. ça pourra nous servir après !
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

function getCorrectorsByConcours($concourstable){
	/*  Cette fonction retourne le 'username' du chairman associé au concours
	   dans la table $concourstable, passé en paramètre.
	  */
	  $db = masterDB::getDB();
	  $req = $db->prepare("SELECT username FROM $concourstable WHERE user_group LIKE 'correcteur'");
	  try {
	    $req->execute();
	    $res = $req->fetchAll(PDO::FETCH_NUM);
	    if($res){
	      // les éléments obtenus en réponse à l'éxecution de la requête ci-dessus
	      // forment un tableau de tableau . Donc on convertit ce résultat en un
        // simple tableau pour les futurs traitements.
        $nb_elem = count($res);
        for($i=0;$i<$nb_elem;$i++){
          $res[$i] = $res[$i][0];
        }
        return $res;
	    }
			else {
				return array('0'=>'',);
			}
	  } catch (Exception $e) {
	    echo 'Exception reçue : ',  $e->getMessage(), "\n";
	    $res = array('0' => '',);;
	  }
	  // On retourne le résultat.
	  return $res;
}

function getChairman($concourstable){
	/*  Cette fonction retourne le 'username' du chairman associé au concours
	   dans la table $concourstable, passé en paramètre.
	  */
	  $db = masterDB::getDB();
	  $req = $db->prepare("SELECT username FROM $concourstable WHERE user_group LIKE 'chairman'");
	  try {
	    $req->execute();
	    $res = $req->fetch(PDO::FETCH_NUM);
	    if($res){
	      // les éléments obtenus en réponse à l'éxecution de la requête ci-dessus
	      // forment un tableau . Donc on retourne $res[0] pour avoir
	      // le 'username' qu'on cherche.
	        return $res[0];
	    }
			else {
				return '';
			}
	  } catch (Exception $e) {
	    echo 'Exception reçue : ',  $e->getMessage(), "\n";
	    $res = '';
	  }
	  // On retourne le résultat.
	  return $res;
}

function getConcoursTablebyUser($username){
  // Fonction qui permet de retrouver la table du concours qu'un correcteur
  // corrige.
  $db = masterDB::getDB();
  $req = $db->prepare(
  " SELECT table_name FROM information_schema.tables
    WHERE table_schema='projetis' AND table_name REGEXP '.users$'"
  );
  $req->execute();
  $res =$req->fetchAll(PDO::FETCH_NUM);
  foreach ($res as $key => $value) {
    $res[$key]=$value[0];
  }
  $len = count($res);
	for($i=0;$i<$len;$i++){
		$req1 = $db->prepare(" SELECT * FROM `$res[$i]` WHERE username='$username'");
		$req1->execute();
		$res1 = $req1->fetch();
		if($res1){
			return $res[$i];
		}
	}
  // On retourne par défaut la table 'users' qui contient tout les utilisateurs.
	return 'users';
}

function getUsers(){
  /*  Cette fonction retourne la liste des utilisateurs du site
  */
  $db = masterDB::getDB();
  $req = $db->query(
        " SELECT username
          FROM users "
        );
  // On retourne le résultat.
  return $req;
  }

function changeRights($username,$rights){
  /* Cette fonction change les droits d'un utilisateur 
  */
  $db = masterDB::getDB();
  $req = $db->prepare(
              "UPDATE users SET user_group = :droits WHERE username = :name"
              );
              $req->bindParam(":droits",$rights, PDO::PARAM_STR);
              $req->bindParam(":name",$username,PDO::PARAM_STR);
              $req->execute(); 
}
?>
