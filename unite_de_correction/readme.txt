###########################################################################################################
###########################################################################################################
#######################		UniteDeCorrection.php	###################################################
###########################################################################################################
#######################		25/01/2016		##########	Max BLASSIN		###########
###########################################################################################################

# OBJECTIF

Ce sont des classes qui permettent l'implémentation des différents éléments (épreuves, parties, 
exercices...) qui constituent les concours.

Note : la classe ne sert que d'intermédiaire entre l'utilisateur et le serveur, à chaque appel du
constructeur, tout est uploadé sur le serveur

# GENERER UN BAREME

Il existe une méthode statique genererBareme($struct) qui se charge de créer un barème sur la BDD. 
La variable $struct doit avoir la forme présentée dans "test_bareme.txt", à savoir :
"U1_V1_W1_NoteMax
U1_V1_W2_NoteMax
U1_V2_NoteMax"

Les éléments capitaux ici sont : * les "_" qui permettent de différentier les différentes parties/sous-parties
etc... de notre épreuve. Un "_" signale un lien de parenté "père_fils". Ex : "Partie1_Exercice1".
* Les sauts de ligne qui permettent d'identifier les différents plus petits éléments de notre unité de 
correction. C'est-à-dire que, lorsque notre fonction rencontre le caractère "\n", elle sait qu'elle vient
d'implémenter une des plus petites UdC, et recommence son parcours depuis la racine.
* le NoteMax, qui doit être de la forme d'un integer. Ex : "Partie1_Exercice1_2.5". Pas de confusion ici,
cette note est bien la note maximale de son père immédiat. Dans l'exemple précédent, d'Exercice1.

# CREER UNE UDC

Quatre choix ici :

* $udc = UniteDeCorrection::fromId('id de l'élève') crée une entrée sur la database à partir de l'ID en se
basant sur le barême existant. Il génère donc l'arbre associé. Les différentes unités ont pour ID :
"IdEleve[_PartieX[_ExerciceY[...]]]". Les crochets n'apparaissent pas dans l'ID des UdC, c'est une notation
que j'utilise ici pour signaler que ce qui est entre crochet n'est pas toujours présent.
Exemple :
L'unité de correction de la partie 1 de l'élève "Toto" est "Toto_Partie1", qui aura pour fils éventuels
"Toto_Partie1_Exercice1" et "Toto_Partie1_Exercice2", qui eux-même auront éventuellement pour fils 
"Toto_Partie1_Exercice1_petita" etc jusqu'au plus petit élément. La racine est donc "Toto".

* POUR LE CHAIRMAN : $plusieursUdc = UniteDeCorrection::getAllSmallest($id) va chercher sur le serveur toutes
les udc à corriger. $id est facultatif : il permet de discriminer par élève (on cherche les udc par élève qui
les a produites).

* $udc = UniteDeCorrection::fromData($arrayData) crée une entrée sur la BDD et en local à partir d'un 
ensemble de données ($arrayData). Le format doit être le suivant :
$arrayData = array('id_father'=>'lolfather',
		'id'=>'lolme',
		'id_sons'=>'lol_son1,lol_son2,lol_son3',
		'level'=>2,
		'mark'=>3,
		'max_mark'=>5,
		'date_modif'=>'23-01-2016 15:09:12',
		'id_corrector'=>'lolcorrector');
* POUR LA SECRETAIRE : $udc = UniteDeCorrection::fromData($arrayData,true) (notez l'importance du true !!)
où le format de $arrayData est :
$arrayData = array(
		'id'=>'id de l'Ã©lÃ¨ve',
		'annee'=>'2016',
		'concours'=>'Mines-Ponts,
		'filiere'=>'MP',
		'epreuve'=>'Maths1');
* $udc = UniteDeCorrection::getUnitById('id à trouver') va chercher l'unité sur la BDD en fonction
de son ID.
 
# AJOUT DE FILS

Déprécié. En réalité, le constructeur fait tout le travail lui-même en fonction de l'ID de l'élève
et du barême.

# UPLOAD/DOWNLOAD

Pour récupérer une UdC sur le serveur et en créer une instance locale, il suffit d'utiliser la fonction 
getUnitById().
Une fois les différentes modifications apportées, on utilise alors la fonction "upload()" qui crée une
instance de l'UdC sur le serveur. Si elle existe déjà, upload() met à jour l'entrée.

# DESTRUCTION

On peut détruire une UdC ainsi que tous ses fils sur le serveur en appelant la fonction deleteAll().

# EXEMPLE D'UTILISATION 

Création et upload :

$udc = UniteDeCorrection::fromId('nouvelEleve');
$udc->setNote(12);

$udc->upload();

Depuis un formulaire :

$arrayData['id'] = $_GET['id'];
$arrayData['data']=$_GET['data'];
...

$udc = UniteDeCorrection::fromData($arrayData);
	A noter que dans le cas présent, on n'a rien modifié à l'objet. Il est donc inutile (mais pas
	une erreur pour autant) d'appeler la méthode upload(), qui est appelée au moment de la création
	de l'objet.
	
# Les tests

Les tests se font à l'aide du fichier Test_UdC.php.
Par défaut, le fichier ne conduit aucun test car tout est en commentaire.
Il faut aller dans le fichier et enlever les commentaires (/* = début, */ = fin) du bloc correspondant pour le tester.

Le premier bloc crée une unité de correction à partir d'un tableau (fonction fromData).
Le deuxième bloc teste la génération du barême, la création d'une unité de correction avec ID, et la récupération d'une unité de correction à partir de son ID
