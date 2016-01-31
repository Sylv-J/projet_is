<?php

/*
liste des tâches possibles, avec adresse de redirection associés
*******************TO DO*******************
une fois les pages crées, remplacer convenablement les noms de pages dans la
destination
*******************************************
*/
$tasksDirectories = array(
  'Scanner' => 'secretaire/ajoutercopie.php',
  'Corriger' => 'correcteur/bandeau_correction.php',
  'Voir mes Stats' => 'correcteur/bandeau_stats_correcteur.php',
  'Statistiques' => 'chairman/statschairman.php',
  'Stats Correcteur' => 'chairman/bandeau_stats_chairman.php',
  'Assignation des copies' => 'chairman/assignationcopies.php',
  'Ajouter concours' => 'administrateur/formulaire_ajout_table.php',
  'Ajouter Eleve' => 'chairman/formulaire_ajout_eleve.php',
  'register' => '../users/registration.php',
  'logout' => '../users/logout.php',
  'Créer un compte' => '../users/registration.php',
  'Gestion' => 'chairman/gestion.php',
  'Modifier les droits' => 'administrateur/gestion_droits.php'
  );

?>
