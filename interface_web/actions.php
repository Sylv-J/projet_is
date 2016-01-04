<!--Script php décidant du choix de a page perso à afficher -->

<?php 
/*get group
get actions
pour chaque action, on fait un bouton
*/
	
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
