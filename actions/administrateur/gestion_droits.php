<?php
  include_once("../master_db.php");
  $db = masterDB::getDB();
  
  //exécution de la requête:
  $req = $db->query( "SELECT user_group FROM users") ;
 
  //affichage des données:
  if( $result = mysql_fetch_object( $requete ) ) {
  <form action='gestion_droits.php' method='post'>
    <select name=Droit>
      <OPTION value=Administrateur>Administrateur</OPTION>
      <OPTION value=Correcteur>Correcteur</OPTION>
      <OPTION value=Chairman>Chairman</OPTION>
      <OPTION value=Secrétaire>Secrétaire</OPTION>
      </select><input type='submit' value='Valider'>
  </form>
  }
?>