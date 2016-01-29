<?php
  <form action="" method="post">
   <div>
    <textarea name="nomcorrecteur"><?php echo htmlentities($_POST[$username]); ?></texarea>
   </div>
  </form>
  include_once("../actions/stats.php");
  MoyenneCorrecteur($username);
?>