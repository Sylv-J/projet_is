###########################################################################################################
###########################################################################################################
#######################		UniteDeCorrection.php	###################################################
###########################################################################################################
#######################		18/01/2016		###################################################
###########################################################################################################

# OBJECTIF

Ce sont des classes qui permettent l'implÃ©mentation des diffÃ©rents Ã©lÃ©ments (Ã©preuves, parties, 
exercices...) qui constituent les concours.

Note : la classe ne sert que d'intermÃ©diaire entre l'utilisateur et le serveur, Ã  chaque appel du
constructeur, tout est uploadÃ© sur le serveur

# CREER UNE UDC

Quatre choix ici :
* $udc = UniteDeCorrection::fromId('id de l'élève') crée une entrée sur la database à partir de l'ID en se
basant sur le barême existant. Il génère donc l'arbre associé.
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
		'id'=>'id de l'élève',
		'annee'=>'2016',
		'concours'=>'Mines-Ponts,
		'filiere'=>'MP',
		'epreuve'=>'Maths1');
* $udc = UniteDeCorrection::getUnitById('id à trouver') va chercher l'unité sur la BDD en fonction
de son ID.
 
# AJOUT DE FILS

DÃ©prÃ©ciÃ©. En rÃ©alitÃ©, le constructeur fait tout le travail lui-mÃªme en fonction de l'ID de l'Ã©lÃ¨ve
et du barÃªme.

# UPLOAD/DOWNLOAD

Pour rÃ©cupÃ©rer une UdC sur le serveur et en crÃ©er une instance locale, il suffit d'utiliser la fonction 
getUnitById().
Une fois les diffÃ©rentes modifications apportÃ©es, on utilise alors la fonction "upload()" qui crÃ©e une
instance de l'UdC sur le serveur. Si elle existe dÃ©jÃ , upload() met Ã  jour l'entrÃ©e.

# DESTRUCTION

On peut dÃ©truire une UdC ainsi que tous ses fils sur le serveur en appelant la fonction deleteAll().

# EXEMPLE D'UTILISATION 

CrÃ©ation et upload :

$udc = UniteDeCorrection::fromId('nouvelEleve');
$udc->setNote(12);

$udc->upload();

Depuis un formulaire :

$arrayData['id'] = $_GET['id'];
$arrayData['data']=$_GET['data'];
...

$udc = UniteDeCorrection::fromData($arrayData);

	A noter que dans le cas prÃ©sent, on n'a rien modifiÃ© Ã  l'objet. Il est donc inutile (mais pas
	une erreur pour autant) d'appeler la mÃ©thode upload(), qui est appelÃ©e au moment de la crÃ©ation
	de l'objet.
