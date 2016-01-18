###########################################################################################################
###########################################################################################################
#######################		UniteDeCorrection.php	###################################################
###########################################################################################################
#######################		18/01/2016		###################################################
###########################################################################################################

# OBJECTIF

Ce sont des classes qui permettent l'impl�mentation des diff�rents �l�ments (�preuves, parties, 
exercices...) qui constituent les concours.

Note : la classe ne sert que d'interm�diaire entre l'utilisateur et le serveur, � chaque appel du
constructeur, tout est upload� sur le serveur

# CREER UNE UDC

Deux choix ici :
* On utilise l'ID de l'�l�ve, et le constructeur g�n�re tous ses fils en se basant sur le bar�me.
* On utilise une requ�te de r�cup�ration sur le serveur (getUnitById()).
 
# AJOUT DE FILS

D�pr�ci�. En r�alit�, le constructeur fait tout le travail lui-m�me en fonction de l'ID de l'�l�ve
et du bar�me.

# UPLOAD/DOWNLOAD

Pour r�cup�rer une UdC sur le serveur et en cr�er une instance locale, il suffit d'utiliser la fonction 
getUnitById().
Une fois les diff�rentes modifications apport�es, on utilise alors la fonction "upload()" qui cr�e une
instance de l'UdC sur le serveur. Si elle existe d�j�, upload() met � jour l'entr�e.

# DESTRUCTION

On peut d�truire une UdC ainsi que tous ses fils sur le serveur en appelant la fonction deleteAll().