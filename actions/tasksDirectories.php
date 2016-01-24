<?php

/*
liste des tâches possibles, avec adresse de redirection associés
*******************TO DO*******************
une fois les pages crées, remplacer convenablement les noms de pages dans la
destination
*******************************************
*/
$tasksDirectories = array(
  'Modifier les droits' => '__PageDeDestination1__',
  'Scanner' => 'secretaire/ajoutercopie.php',
  'Corriger' => 'correcteur/bandeau_correction.php',
  'Voir mes Stats' => 'correcteur/bandeau_stats_correcteur.php',
  'Statistiques' => 'chairman/statschairman.php',
  'Stats Correcteur' => 'correcteur/bandeau_stats_correcteur.php',
  'Assignation des copies' => 'chairman/assignationcopies.php',
  'Ajouter concours' => 'chairman/formulaire_ajout_table.php',
  'register' => '../users/registration.php',
  'logout' => '../users/logout.php'
  );

?>
