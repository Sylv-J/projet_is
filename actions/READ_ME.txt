25/01/16
 
#AJOUTER UNE ACTION 
  - Dans le fichier "action.php" : ajouter l'action désirée dans la liste des actions associeés à chaque rôle.
      Ex : pour ajouter "Parler" à "secrétaire" : 'secretaire' => array('Scanner', 'Parler')

  - Dans le fichier "taskDirectories.php" : ajouter le chemin de la page vers laquelle le bouton d'action associé redirigera.
      Ex : pour "Parler" à "secrétaire" : 'Parler' => 'secretaire/parler.php',

#ACTIONS CHAIRMAN
    - "statschairman.php" : Affiche les statistiques des différents correcteurs(nombres de copies restantes, id, nom, mail) 
    dans un tableau rempli à partir  de "remplirtableau.php" (situé dans le dossier 'database_request')
    
    - "assignationcopies.php" : Ouvre un formulaire qui permet d'assigner les copies.
    
    - "remplirtableau.php" : Séries de requêtes SQL allant chercher les différentes données dans la base 'units' et 'users'.
    
#ACTIONS SECRETAIRE
    - "ajoutercopie.php" : Permet d'ajouter une copie à l'aide d'un formulaire.


Personnes en charge de cette partie (CHAIRMAN/SECRETAIRE) : Antoine Jubin & Bao-Vi Defaux