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
* On utilise l'ID de l'élève, et le constructeur génère tous ses fils en se basant sur le barême.
* On utilise une requête de récupération sur le serveur (getUnitById()).
 
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