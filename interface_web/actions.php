<!--Script affichant les boutons d'actions pour chaque user 
Une variable "page_to_load" est envoyé en GET à index.php 
cette variable aura pour valeur le nom de l'action-->

<?php 
$user = $_SESSION["group"];

$rights = array(
'administrateur' => array('Modifier les droits'),
'secretaire' => array('Scanner'),
'correcteur' => array('Corriger')
);	
	//Mise en place du style 
	echo"
	<div class='col-md-4'>
		<h2>Actions de $user</h2>
		<form action='index.php' method='get'>
		<div class='list-group'>
	";
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
