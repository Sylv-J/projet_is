Par :          Ilias BENJELLOUN
Editer le :    31 / 01 / 2016


******************************************
*************** COMPILATION **************
******************************************

Pour compiler le projet (avec QtCreator):

- Installer Qt 5.5 (lien de téléchargement : http://www.qt.io/download-open-source/)
- Installer Opencv 3.0.0
- Ouvrir QtCreator
- Cliquer sur "Ouvrir projet..." et ouvrir le fichier ScanCopie.pro
- Cliquer sur la fleche verte en bas a gauche de QtCreator


********************************************
*************** DOCUMENTATION **************
********************************************


La structure de l'application est globalement la suivante :


                      MyWindow est la fenetre principal                                               Displayer gère l'affichage de tout sauf les bouttons
 _____________________________________________________________________________            (les deux QVBoxLayout sont bien sur contenus dans une QHBoxLayout principale)
|  _________________________________________________________________________  |                 _______________________________________________________________
| |                          QHBoxLayout principal                          | |                |  _____________________________________   __________________   |
| |  ____________     ___________________________________________________   | |                | |   QVBoxLayout 2 contenant :         | |                  |  |
| | |            |   |                    Displayer                      |  | |                | |   ______________________________    | |                  |  |
| | |QVBoxLayout |   |             (qui hérite de QWidget)               |  | |                | |  |QLabel d'infos à l'utilisateur|   | |                  |  |
| | |     1      |   |                                                   |  | |                | |  |______________________________|   | |  QVBoxLayout 3   |  |
| | | contenant  |   |                                                   |  | |                | |   ______________________________    | |  contenant des   |  |
| | |    les     |   |                                                   |  | |                | |  |                              |   | |     QLabels      |  |
| | |  bouttons  |   |                                                   |  | |            __  | |  |         QGraphicsView        |   | |    affichant     |  |
| | |            |   |                                 __________________|__|_|____________\ \ | |  |     pour la visualisation    |   | |  des infos sur   |  |
| | |            |   |                                                   |  | |            /_/ | |  |          des images          |   | |   la division    |  |
| | |            |   |                                                   |  | |                | |  |                              |   | |    des images    |  |
| | |            |   |                                                   |  | |                | |  |                              |   | |                  |  |
| | |            |   |                                                   |  | |                | |  |                              |   | |                  |  |
| | |            |   |                                                   |  | |                | |  |                              |   | |                  |  |
| | |            |   |                                                   |  | |                | |  |                              |   | |                  |  |
| | |            |   |                                                   |  | |                | |  |                              |   | |                  |  |
| | |            |   |                                                   |  | |                | |  |                              |   | |                  |  |
| | |            |   |                                                   |  | |                | |  |                              |   | |                  |  |
| | |            |   |                                                   |  | |                | |  |______________________________|   | |                  |  |
| | |            |   |                                                   |  | |                | |   ______________________________    | |                  |  |
| | |            |   |                                                   |  | |                | |  |QLabel dossier de sauvegarde  |   | |                  |  |
| | |____________|   |___________________________________________________|  | |                | |  |______________________________|   | |                  |  |
| |                                                                         | |                | |_____________________________________| |__________________|  |
| |_________________________________________________________________________| |                |_______________________________________________________________|
|_____________________________________________________________________________|


________________________
La classe MyWindow      |
________________________|

Il y a trois boutons, contenus dans le QVBoxLayout 1 : "Choix des images"/"Nouveau choix", "Enregistrer" et "Fermer".

- Le premier fait appel au slot chooseImages de la classe, ce qui ouvre une boite de dialogue permettant à l'utilisateur de choisir les images a traiter. Les images sont chargees, analysees
par reconnaissance d'image et immediatement affichees avec le resultat de l'analyse.

- Le deuxieme lance la procedure de division puis d'enregistrement des images divisees. La procedure enregistre les images dans le dossier <dossier_de_lancement_du_programme>/images/images_xx,
xx correspondant au nombre de dossier se trouvant deja dans le dossier images. S'il y a deja 99 dossiers dans images, l'enregistrement s'effectue dans images/temp, dossier temporaire qui
est supprime a chaque nouveau lancement de l'application. Si les dossiers n'existent pas, ils sont crees.

- Le troisieme ferme simplement l'application.


Les boutons apparaissent/disparaissent selon les étapes et les actions de l'utilisateur, en changeant eventuellement de nom.


_______________________
La classe Displayer    |
_______________________|

Cette classe gere la zone d'affichage des images selon les differentes situations :
A l'ouverture de l'application, ou lorsque le traitement d'un ensemble d'image est fini, elle affiche une zone blanche vide.
Lorsque des images sont chargees et analysees dans MyWindow, les methodes addImages et drawDivLines de Displayer sont appelees depuis MyWindow, et Displayer ajoute les images ainsi que les lignes
de division a la MyQGraphicsScene et affiche le resultat dans la QGraphicsView.
Les lignes correspondent a des MyQGraphicsLineItem. Les classes MyQGraphicsScene et MyQGraphicsLineItem herite respectivement de QGraphicsScene et QGraphicsLineItem, mais reimplemante certaines fonctions
pour permettre a l'utilisateur d'interagir avec la scene a l'aide de la souris.
Cela permet en particulier d'ajouter des lignes a la scene, d'en enlever et de deplacer les lignes existantes a l'aide des boutons de la souris.

A la fin de addImages, le texte du label d'info est modifie, et le label du dossier de sauvegarde apparait et affiche le dossier dans lequel seront sauvegardees les images si l'utilisateur
appuie sur le bouton enregistrer (le dossier est determine par la methode findSaveDir).
Le nombre de lignes trouvees s'affichent a droite et s'actualise a chaque fois que l'utilisateur ajoute ou efface des lignes. La position du curseur de la souris s'affiche egalement lors du
deplacement d'une ligne par l'utilisateur.

Lorsque l'utilisateur appuie sur Enregistrer, et que les images sont divisees par la methode split de MyWindow puis enregistrees, MyWindow appelle la methode clean de Displayer pour permettre
le chargement de nouvelles images.
