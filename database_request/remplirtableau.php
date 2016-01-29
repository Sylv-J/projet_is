<?php
  include_once("../master_db.php");
  include_once("../actions/stats.php");
  $db = masterDB::getDB();
  $req = $db->query("SELECT id, username, mail, units_remaining FROM users WHERE user_group = 'correcteur' ");
  while($donnees = $req->fetch()) {
       $id = $donnees["id"];
       $mail = $donnees["mail"];
       $username = $donnees["username"];
       $nbcopiesrestantes =$donnees["units_remaining"];
       $lienstats = <form action="statsperso.php" method="post" role="form">
        <div>
          <textarea name="$username"></texarea><br /><input type="submit"/>
        </div>
       </form>
       <p><a href="statsperso.php">Stats correcteur</a></p>;
       echo "<tr> <td>$username</td> <td>$id</td> <td>$nbcopiesrestantes</td> <td>$mail</td><td> $lienstats </td></tr>";

  }

   $req->closeCursor();
?>
