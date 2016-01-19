<?php
  include_once("../master_db.php");
  $db = masterDB::getDB();
  $req = $db->query("SELECT id, username, mail, nbcopiesrestantes FROM users WHERE user_group = 'correcteur' ");
  while($donnees = $req->fetch()) {
       $id = $donnees["id"];
       $mail = $donnees["mail"];
       $username = $donnees["username"];
       $nbcopiesrestantes =$donnees["nbcopiesrestantes"];
       
       echo "<tr> <td>".$username."</td> <td>".$id."</td> <td>".$nbcopiesrestantes."</td> <td>".$mail."</td></tr>";
        
  }

   $req->closeCursor();
?>