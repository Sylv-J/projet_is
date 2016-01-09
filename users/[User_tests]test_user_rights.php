<?php

	require_once "user_tasks.php";

	// déclaration des rôles
	$administrateur = 'administrateur';
	$secretaire = 'secretaire';
	$correcteur = 'correcteur';

	// déclaration des tâches
	$modifier = 'Modifier les droits';
	$scanner = 'Scanner';
  $corriger = 'Corriger';
  $revoir = 'Revoir une copie';

	// modifier les pages le cas échéant
	$pageDestModifier = '__PageDeDestination__';
	$pageDestScanner = '__PageDeDestination__';
	$pageDestCorriger = '__PageDeDestination__';
	$pageDestRevoir = '__PageDeDestination__';


	/*
	* VERIFICATION DE L'ATTRIBUTION DES DROITS
	*/

	// vérification des droits pour l'administrateur
	assert(in_array($modifier, $rights[$administrateur]));
	assert(!in_array($scanner, $rights[$administrateur]));
	assert(!in_array($corriger, $rights[$administrateur]));
	assert(!in_array($revoir, $rights[$administrateur]));


	// vérification des droits pour le secrétaire
	assert(!in_array($modifier, $rights[$secretaire]));
	assert(in_array($scanner, $rights[$secretaire]));
	assert(!in_array($corriger, $rights[$secretaire]));
	assert(!in_array($revoir, $rights[$secretaire]));

	// vérification des droits pour le correcteur
	assert(!in_array($modifier, $rights[$correcteur]));
	assert(!in_array($scanner, $rights[$correcteur]));
	assert(in_array($corriger, $rights[$correcteur]));
	// assert(in_array($revoir, $rights[$correcteur])); droit temporairement révoqué


	/*
	* VERIFICATION DES PAGES DE DESTINATION
	*/
	assert($pageDestModifier == $tasksLinks[$modifier]);
	assert($pageDestScanner == $tasksLinks[$scanner]);
	assert($pageDestCorriger == $tasksLinks[$corriger]);
	assert($pageDestRevoir == $tasksLinks[$revoir]);

	echo "Exécution du script <strong>".$_SERVER['PHP_SELF']."</strong> terminée - <strong>0 erreur</strong>.";
?>
