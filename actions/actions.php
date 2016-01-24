<!--Script affichant les boutons d'actions pour chaque user
Une variable "page_to_load" est envoyé en POST à index.php
cette variable aura pour valeur le nom de l'action-->

<?php
$user = $_SESSION["group"];

/*
	Liste des droits d'accès pour chaque classe d'utilisateur
	*******************TO DO*******************
	Mettre à jour les différents types d'utilisateurs et leurs droits ici.
	ATTENTION : l'ajout ou la modification du noms des droits ici implique une action équivalente dans le fichier "tasksDirectories"
	*******************************************
	*/
$rights = array(
'administrateur' => array('Modifier les droits'),
'secretaire' => array('Scanner'),
'correcteur' => array('Corriger', 'Voir mes Stats'),
'chairman' => array('Statistiques','Assignation des copies', 'Stats Correcteur','Ajouter concours')
);

	//Mise en place du style
	echo"
	<div class='col-md-6 '>
		<h2>Actions de $user</h2>
		<form action='index.php' method='post'>
		<div class='list-group'>
	";

    //Messages de confirmation
    //Confirmation apparaissant lorsqu'un correcteur valide une note
    if(isset($_POST["mark_submit"]) and $user == "correcteur"){
        include_once("correcteur/bandeau_validation_note.php");
    }
	//Les boutons utilisables
	foreach($rights[$user] as $actions){
		echo "
		<button type='submit' name='page_to_load' value='$actions' class='btn'>$actions</button>
		";
	}
	//fermeture des balises
	echo"

	</div></form></div>
	";
?>
