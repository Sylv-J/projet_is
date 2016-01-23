###########################################################################################################
###########################################################################################################
#######################		UniteDeCorrection.php	###################################################
###########################################################################################################
#######################		18/01/2016		###################################################
###########################################################################################################

# OBJECTIF

Ce sont des classes qui permettent l'implémentation des différents éléments (épreuves, parties, 
exercices...) qui constituent les concours.

Note : la classe ne sert que d'intermédiaire entre l'utilisateur et le serveur, à chaque appel du
constructeur, tout est uploadé sur le serveur

# CREER UNE UDC

<<<<<<< HEAD
Quatre choix ici :
* $udc = UniteDeCorrection::fromId('id de l'�l�ve') cr�e une entr�e sur la database � partir de l'ID en se
basant sur le bar�me existant. Il g�n�re donc l'arbre associ�.
* $udc = UniteDeCorrection::fromData($arrayData) cr�e une entr�e sur la BDD et en local � partir d'un 
ensemble de donn�es ($arrayData). Le format doit �tre le suivant :
=======
Trois choix ici :
* $udc = UniteDeCorrection::fromId('id de l'élève') crée une entrée sur la database à partir de l'ID en se
basant sur le barême existant. Il génère donc l'arbre associé.
* $udc = UniteDeCorrection::fromData($arrayData) crée une entrée sur la BDD et en local à partir d'un 
ensemble de données ($arrayData). Le format doit être le suivant :
>>>>>>> e50b4775559c73a47a62e030f15e04fcbe5ccc42
$arrayData = array('id_father'=>'lolfather',
		'id'=>'lolme',
		'id_sons'=>'lol_son1,lol_son2,lol_son3',
		'level'=>2,
		'mark'=>3,
		'max_mark'=>5,
		'date_modif'=>'23-01-2016 15:09:12',
		'id_corrector'=>'lolcorrector');
<<<<<<< HEAD
* POUR LA SECRETAIRE : $udc = UniteDeCorrection::fromData($arrayData,true) (notez l'importance du true !!)
 o� le format de $arrayData est :
$arrayData = array(
		'id'=>'id de l'�l�ve',
		'annee'=>'2016',
		'concours'=>'Mines-Ponts,
		'filiere'=>'MP',
		'epreuve'=>'Maths1');
* $udc = UniteDeCorrection::getUnitById('id � trouver') va chercher l'unit� sur la BDD en fonction
=======
* $udc = UniteDeCorrection::getUnitById('id à trouver') va chercher l'unité sur la BDD en fonction
>>>>>>> e50b4775559c73a47a62e030f15e04fcbe5ccc42
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
