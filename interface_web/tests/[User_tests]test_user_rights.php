<?php

	require_once "../../actions/actions.php";
	require_once "../../actions/tasksDirectories.php";

	// déclaration des rôles
	$administrateur = 'administrateur';
	$secretaire = 'secretaire';
	$correcteur = 'correcteur';

	// déclaration des tâches
	$modifier = 'Modifier les droits';
	$scanner = 'Scanner';
  $corriger = 'Corriger';
	//$revoir = 'Revoir une copie'; droit récemment temporairement révoqué
	$register = 'register';
	$logout = 'logout';

	// modifier les pages le cas échéant
	$pageDestModifier = '__PageDeDestination1__';
	$pageDestScanner = '../../actions/secretaire/ajoutercopie.php';
	$pageDestCorriger = '../../actions/correcteur/bandeau_correction.php';
	$pageDestRevoir = '__PageDeDestination__';
	$pageDestRegister = '../../users/registration.php';
  $pageDestLogout = '../../users/logout.php';


	/*
	* VERIFICATION DE L'ATTRIBUTION DES DROITS
	*/

	// vérification des droits pour l'administrateur
	assert(in_array($modifier, $rights[$administrateur]));
	assert(!in_array($scanner, $rights[$administrateur]));
	assert(!in_array($corriger, $rights[$administrateur]));
	//assert(!in_array($revoir, $rights[$administrateur]));

	// vérification des droits pour le secrétaire
	assert(!in_array($modifier, $rights[$secretaire]));
	assert(in_array($scanner, $rights[$secretaire]));
	assert(!in_array($corriger, $rights[$secretaire]));
	//assert(!in_array($revoir, $rights[$secretaire]));

	// vérification des droits pour le correcteur
	assert(!in_array($modifier, $rights[$correcteur]));
	assert(!in_array($scanner, $rights[$correcteur]));
	assert(in_array($corriger, $rights[$correcteur]));
	// assert(in_array($revoir, $rights[$correcteur])); droit temporairement révoqué

	/*
	* VERIFICATION DES PAGES DE DESTINATION
	*/
	assert($pageDestModifier == $tasksDirectories[$modifier]);
	assert($pageDestScanner == $tasksDirectories[$scanner]);
	assert($pageDestCorriger == $tasksDirectories[$corriger]);
	//assert($pageDestRevoir == $tasksDirectories[$revoir]); page temporairement inexistante
	assert($pageDestRegister == $tasksDirectories[$register]);
	assert($pageDestLogout == $tasksDirectories[$logout]);

	echo "Exécution du script <strong>".$_SERVER['PHP_SELF']."</strong> terminée - <strong>0 erreur</strong>.";
?>
