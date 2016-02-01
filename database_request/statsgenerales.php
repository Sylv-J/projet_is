<?php
  include_once("../master_db.php");
  $db = masterDB::getDB();
  $req = $db->query("SELECT units_remaining FROM users WHERE user_group = 'correcteur' ");
  $req2 = $db->query("SELECT id_sons FROM units WHERE id_sons is NULL AND id_corrector is NULL");
  $nbcopies=0;
  $nbassignes=0;
  while($donnees = $req->fetch()){
       $nbcopies = $nbcopies + $donnees['units_remaining'];
  }
  while($donnees2 = $req2->fetch()){
       $nbassignes = $nbassignes + $donnees['id_sons'];
  }
  echo "<tr> <td>".$nbcopies."</td> <td>".$nbassignes."</td> </tr>";

  $req->closeCursor();
  $req2->closeCursor();
?>