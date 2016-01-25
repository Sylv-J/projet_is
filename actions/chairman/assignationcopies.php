<div class ="jumbotron">
    <div class = "container">
      <?php
      include("../../paquets_copies/paquets_copies.php");
      ?>
      <h2> Assignation Copies </h2>

      <!--Assignation des correcteurs sur une épreuve -->
      <div class="col-md-3">
        <form method="post" action="../interface_web/index.php">
          <select name="unitsType">
              <?php
                $list = array("Maths1","Physique");
                //$list = getSubjects();
                for ($i=0;$i<=sizeof($list);$i++) {
                  $value = $list[$i];
                  echo "<option value=$value>$value</option>" ;
                }
              ?>

          </select>
          <input type="hidden" name="page_to_load" value="Assignation des copies"/>
          <input type="submit" value="Assigner" title="Valider pour assigner" />
        </form>
      </div>
      
      <!--Tableau affichant les udc assigné aux correcteurs-->
      <div class="col-md-6">
        <table class="table">
          <thead>
            <tr>
              <th>Correcteur</th>
              <th>Nombre de copies assignées</th>
              <th>Matière</th>
            </tr>
          </thead>
          <tbody>
            
            <?php 
            //include("../../paquets_copies/paquets_copies.php");
            //$list = listCorrectors();
            
            //for ($i=0;$i <= sizeof($list);$i++){
              //$corrector = $list[$i];
              
              //echo "<tr> <td>$corrector</td> <td></td> <td></td></tr>"; } ?>
          </tbody>

        </table>

      </div>
    </div>
</div>



<!-- 2 listes déroulantes : une recuperant les ids des correcteurs, l'autre récupérant les unités à assigner -->