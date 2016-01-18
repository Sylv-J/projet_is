<?php
  include_once("../master_db.php");
  $db = masterDB::getDB();
  $req = $db->query("SELECT id, username, mail, nbcopiesrestantes FROM users WHERE user_group = 'correcteur' ");
  while($donnees = $req->fetch()) {
       $id = $donnees["id"];
       $mail = $donnees["mail"];
       $username = $donnees["username"];
       $nbcopiesrestantes =$donnees["nbcopiesrestantes"];
       
       echo "<tr>" .$username . "</tr><tr>" .$id."</tr>";
       
       ?>
       <tr>$username</tr>
       <tr>$id</tr>
       <tr> $nbcopiesrestantes </tr>
       <tr>$mail</tr>
       <?php    
  }
   $req->closeCursor();
?>