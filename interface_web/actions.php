<!--Script php décidant du choix de a page perso à afficher -->

<?php 
/*get group
get actions
pour chaque action, on fait un bouton
*/
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
