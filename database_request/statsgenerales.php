<?php
  include_once("../master_db.php");
  $db = masterDB::getDB();
  $req = $db->query("SELECT COUNT (nbcopiesrestantes) FROM users WHERE user_group = 'correcteur' ");
  $req2 = $db->query("SELECT COUNT (id_sons) FROM inits WHERE id_sons is NULL");

  echo "<tr> <td>".$req."</td> <td>".$req2."</td> </tr>";

  $req->closeCursor();
  $req2->closeCursor();
?>