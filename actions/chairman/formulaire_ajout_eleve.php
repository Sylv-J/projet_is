<?php
include_once("../../master_db.php");
$db = masterDB::getDB();


//Pas de Check ici. On demande de rentrer un nom de concours, le check d'existence se fait dans action_ajout_table.php


	{
?>
<!-- HMTL code for the registration form -->
<form action=<?php echo "../../actions/chairman/action_ajout_eleve.php" ?> method="post">
<h3> : </h3>
<p>

		Nom Concours de forme 2016MinesMP: <input type="text" name="nom_concours" required><br><br>
		Id élève : <input type="text" name="id_eleve" required><br><br>
       <label for="eleve">Veuillez inscrire l'élève à ses épreuves en respectant les consignes</label><br />
       <textarea name="eleve" rows="10" cols="50"></textarea>
	   <input type="submit" value="Envoyer" ></code>
   </p>
</form>
<?php
}
?>
