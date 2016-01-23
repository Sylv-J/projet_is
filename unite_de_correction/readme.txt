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

Deux choix ici :
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