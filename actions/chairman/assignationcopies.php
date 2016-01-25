<div class ="jumbotron">
    <div class = "container">
      <h2> Assignation Copies </h2>
      <form method="post" action="../interface_web/index.php">
      <p>
      <select name="unitsType">
          <?php
            include(../paquets_copies/paquets_copies.php)
            $list = getUnits();
            for (i=0;sizeof($list);i++) {
              $value = $list[i];
              echo "<option value=$value>$value</option>" ;
            }
            
          ?>
         
      </select>

      <input type="submit" value="Assigner" title="Valider pour assigner" />

</p>
</form>
        </form>
    </div>
</div>



<!-- 2 listes déroulantes : une recuperant les ids des correcteurs, l'autre récupérant les unités à assigner -->