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
  'Voir mes Stats' => 'stats_view_correcteur.php',
  'Statistiques' => 'chairman/statschairman.php',
  'Stats Correcteur' => 'stats_view_chairman.php',
  'Assignation des copies' => 'chairman/assignationcopies.php',

  'register' => '../users/registration.php',
  'logout' => '../users/logout.php'
  );

?>
