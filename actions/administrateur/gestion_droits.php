<body>
  <div class="jumbotron">
    <div class="container">
    <h2> Changement de droits </h2>
      <?php
        include_once("../database_request/getUsers.php");
      ?>
      
      <div class="col-md-3">
        <form method="post" action="../interface_web/index.php">
          <select name="user">
              <?php
                $id = getUsers();
                foreach($id as $id):?>
                  <option value=<?=  $id['username']?> > <?= $id['username']?> </option>
                  
               <?php endforeach;?>

          </select>
          <select name="droits">
          <option value=""> ----- Choisir ----- </option>
          <option value="administrateur"> Administrateur </option>
          <option value="chairman"> Chairman </option>
          <option value="correcteur"> Correcteur </option>
          <option value="secretaire"> Secrétaire </option>
          </select>
          <input type="hidden" name="page_to_load" value="">
          <input type="submit" value="Modifier" title="Valider pour assigner" />
  </form> 
  </div>
  </div>
</body>