<?php
include_once("../master_db.php");
$db = masterDB::getDB();


//Pas de Check ici. On demande de rentrer un nom de concours, le check d'existence se fait dans action_ajout_table.php


	{
?>
<!-- HMTL code for the registration form -->
<form action=<?php echo "../utils/action_ajout_table.php" ?> method="post">
<h3>Inscription : </h3>
<p>
	Nom concours : <input type="text" name="NomConcours" required><br><br>
	

	</select><br><br>
	<input type="submit" name='page_to_load' value='register'><br>
</p>
</form>

