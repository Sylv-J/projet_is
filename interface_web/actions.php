<!--Script php décidant du choix de a page perso à afficher -->
<?php
/*TEST
choisir un nombre entre 1 et 4 pour chnager l'affichage de la page
1 : Administrateur
2 : Secretaire
3 : Correcteur
4 : Chairman

$test=1;
switch ($test) {
    case 1:
        include("roles/administrateur.php");
        break;
    case 2:
        include("roles/secretaire.php");
        break;
    case 3:
        include("roles/correcteur.php");
        break;
	case 4:
		include("roles/chairman.php");
		break;
}
*/
?>
<?php 
	$rights=array(
		"administrateur" => array("Creer compte","associer des notes","réinitialiser mdp"),
		"secretaire" => array("scanner des copies","gestion des erreurs","mail de rappel","inviter des correcteurs"),
		"correcteur" => array("Corriger","Revoir","Demander", "Statistiques")
	);
	
	$user="administrateur";
	
	//Mise en place du style 
	echo"
	<div class='col-md-4'>
		<h2>Actions de $user</h2>
		<div class='list-group'>
	";
	//Les boutons utilisables
	foreach($rights[$user] as $actions){
		echo "
		<a href='#' class='list-group-item'>$actions</a>
		";
	}
	//fermeture des balises
	echo"
	</div></div>
	";
?>
