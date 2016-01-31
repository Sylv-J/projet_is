<?php
include_once("../../master_db.php");
$db = masterDB::getDB();


//Pas de Check ici. On demande de rentrer un nom de concours, le check d'existence se fait dans action_ajout_table.php


	{
?>
<!-- HMTL code for the registration form -->
<form action=<?php echo "../chairman/actions_bareme_bis.php" ?> method="post">
<h3>Bareme : </h3>
<p>
       <label for="bareme">Veuillez inscrire le bareme en respectant les consignes</label><br />
       <textarea name="bareme" id="ameliorer" rows="10" cols="50"></textarea>
	   <input type="submit" value="Envoyer" ></code>
   </p>
</form>
<?php
}
?>
