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





#Philippe et Marion le 25:01:2016 aprem
sur le stats.php : 


fonctions déclarées :
 
function MoyennePersoGlissante($nb_copies, $nom_exo)
	prends l'id_father et le nombre de copies à prendre en compte et calcule la moyenne de celles-ci (triées par date décroissante)

function MoyennePersoGlobale($nom_exo)
	prend l'id_father d'un exo et calcule la moyenne des notes données par le correcteur (qui appelle la fonction) sur cet exo

function MoyenneGlobale($nom_exo)
	prend l'id_father d'un exo et calcule la moyenne des notes données par tous les correcteurs confondus sur cet exo

function MoyenneTresGlobale()
	rien en entrée_ calcule la moyenne des notes de tous les exercices et de tous les correcteurs

function MoyenneCorrecteur($nom_correcteur)
	entrée : id du correcteur
	sortie : moyenne des notes données par ce correcteur (tous les exercices confondus)

function MoyenneCorrecteurExo($nom_correcteur, $nom_exo)
	entrée : id du correcteur et id_father d'un exo
	sortie : moyenne des notes données par ce correcteur sur l'exo donné


leur utilisation : 

pour le chairman : 
function MoyenneCorrecteurExo($nom_correcteur, $nom_exo)
	permet de comparer la notataion d'un correcteur sur un exo par rapport aux notations des autres correcteurs (donnée par la fonction suivante)
function MoyenneGlobale($nom_exo)
	permet de comparer la notataion d'un correcteur sur un exo par rapport aux notations des autres correcteurs (donnée par la fonction précédende)
function MoyenneCorrecteur($nom_correcteur)
	permet de comparer la notataion d'un correcteur,tous les exo confondus, par rapport aux notations des autres correcteurs (donnée par la fonction suivante)
function MoyenneTresGlobale()
	permet de comparer la notataion d'un correcteur,tous les exo confondus, par rapport aux notations des autres correcteurs (donnée par la fonction précédente)

pour le correcteur :
function MoyennePersoGlissante($nb_copies, $nom_exo)
	permet au correcteur de voir l'évolution de sa notation et de repérer d'éventuels biais dûs au temps
function MoyennePersoGlobale($nom_exo)
	permet au correcteur de comparer sa notation globale sur un exo donnée à celles des autres correcteurs (donnée par la suivante fonction)
function MoyenneGlobale($nom_exo)
	permet au correcteur de comparer sa notation globale sur un exo donnée à celles des autres correcteurs (donnée par la précedente fonction)


