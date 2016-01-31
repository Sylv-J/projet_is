<?php
include_once("../../master_db.php");
$db = masterDB::getDB();


//Pas de Check ici. On demande de rentrer un nom de concours, le check d'existence se fait dans action_ajout_table.php


	{
?>
<!-- HMTL code for the registration form -->
<form action=<?php echo "../../actions/chairman/action_ajout_correcteur.php" ?> method="post">
<h3> : </h3>
<p>

		Nom Concours de forme 2016MinesMP: <input type="text" name="nom_concours" required><br><br>
       <label for="correc">Veuillez inscrire les correcteurs à l'épreuves en respectant les consignes</label><br />
       <textarea name="correc" rows="10" cols="50"></textarea>
	   <label for="epreuv">Veuillez inscrire la liste des épreuves concernant les correcteurs en respectant les consignes</label><br />
       <textarea name="epreuv" rows="10" cols="50"></textarea>
	   <input type="submit" value="Envoyer" ></code>
   </p>
</form>
<?php
}
?>
