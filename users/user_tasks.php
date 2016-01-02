<?php
include_once("user_context.php");

/*
ce fichier créé des bouttons, associés à chaque têche à réaliser en fonction des
droits d'accès de l'utilisateur à la tâche en question
*/

/*
liste des tâches possibles, avec adresse de redirection associés
*******************TO DO*******************
une fois les pages crées, remplacer convenablement les noms de pages dans la
destination
*******************************************
*/
$tasksLinks = array(
  'Modifier les droits' => '__PageDeDestination__',
  'Scanner' => '__PageDeDestination__',
  'Corriger' => '__PageDeDestination__',
  'Revoir une copie' => '__PageDeDestination__'
  );


/*
Liste des droits d'accès pour chaque classe d'utilisateur
*******************TO DO*******************
Mettre à jour les différents types d'utilisateurs et leurs droits ici.
*******************************************
*/
$rights = array(
'administrateur' => array('Modifier les droits'),
'secrétaire' => array('Scanner'),
"correcteur" => array('Corriger', 'Revoir une copie')
);


/*
Création des bouttons
********Méthode générale dépendant des paramètres modifiables ci dessus********
*/
if(!isset($_SESSION["id"]))
{
  echo "Veuillez vous connecter pour accéder à vos fonctionnalitées.";
}
else
{
  foreach($rights[$_SESSION["group"]] as $tacheAutorisee)
  {
    echo "
    <form action=$tasksLinks[$tacheAutorisee]>
      <p><input type='submit' value=$tacheAutorisee></p>
    </form>
    ";
  }
}
?>
