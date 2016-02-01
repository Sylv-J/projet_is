<body>
  <div class="jumbotron">
    <div class="container">
    <h2> Changement de droits </h2>
      <?php
        include_once("../master_db.php");
        $db = masterDB::getDB();
  
        $res = $db->query('SELECT id FROM users') ;
        $res = array();
         // while($units = $result->fetch()){
           // foreach(array_unique($units) as $unit) {
            //  $res = array_unique($res);
           // }
         // }
       // return $res;
      ?>
      
      <div class="col-md-3">
        <form method="post" action="../interface_web/index.php">
          <select name="user">
              <?php
                $id = $res;
                foreach($id as $id):
                      echo "<option value=$id>$id</option>" ;
                endforeach;
              ?>

          </select>
          <input type="hidden" name="page_to_load" value="Test Admin"/>
        </form>
      </div>
      
    <form action="../interface_web/index.php" method="post">
    
    <select name="Droits">
    <option value=""> ----- Choisir ----- </option>
    <option value="administrateur"> Administrateur </option>
    <option value="chairman"> Chairman </option>
    <option value="correcteur"> Correcteur </option>
    <option value="secretaire"> Secrétaire </option>
    </select>
    <input type="submit" value="Modifier" title="Valider pour assigner" />
  </form>
    <?php
      echo $_POST['user'];
      echo $_POST['Droits'];
    ?> 
  </div>
  </div>
</body>