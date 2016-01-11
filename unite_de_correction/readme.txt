##########################################################################################################################################
###############################	UniteDeCorrection.php			##################################################################
###############################	11/01/2016				##################################################################
##########################################################################################################################################
##########################################################################################################################################

# OBJECTIF

Ce sont des classes qui permettent l'implémentation des différents éléments (épreuves, parties, exercices...) qui constituent les
concours

NOTE : Toute modification apportée à la classe est LOCALE. Pour que ces modifications prennent effectivement effet, il faut appeler
la méthode magique upload() qui gère la création/modification des UdC. Il n'est donc PAS NECESSAIRE de vérifier qu'une UdC existe
sur le serveur pour utiliser upload(), dans un cas d'utilisation normale. En effet, les constructeurs permettent systématiquement
de trouver un ID qui n'a pas été attribué sur le serveur.

# CREER UNE UDC

Pour ce faire, la méthode la plus indiquée reste encore d'utiliser le constructeur de base, qui va alors s'occuper de créer LOCALEMENT
un objet en se basant sur un ID qui n'est pas encore attribué sur le serveur.
Les autres constructeurs peuvent être utilisés quand :
* on souhaite créer une UdC comme fille d'une autre (encore une fois localement)
* on souhaite créer une UdC comme insérée dans le noeud d'un autre (localement)
Le dernier constructeur UniteDeCorrection($arrayData) est un constructeur particulier appelé pour recréer localement l'objet
que l'on est allé cherche sur le serveur (ie par un getUnitById).

# AJOUT DE FILS

On n'appelle en fait PRESQUE JAMAIS soi-même les constructeurs d'UniteDeCorrection. Lorsqu'une UdC existe, on appelle plutôt la méthode newSon() 
dans le cas où l'on souhaite créer un fils vierge (cas le plus commun).
Si, cependant, il semble nécessaire d'ajouter un fils existant, la méthode addSon() peut être appelée à ces fins.

# UPLOAD/DOWNLOAD

Pour récupérer une UdC sur le serveur et en créer une instance locale, il suffit d'utiliser la fonction getUnitById.
Une fois les différentes modifications apportées, on peut alors :
* Utiliser uploadAll() : cette fonction va uploader/modifier sur le serveur l'UdC ainsi que TOUS SES FILS.
* La fonction upload() : se contente d'uploader/modifier sur le serveur l'UdC

Si l'on souhaite par exemple ré-enregistrer un arbre entier d'UdC, il suffit donc de faire un $UdC->getRoot()->uploadAll(),
la fonction getRoot() permettant d'obtenir la PREMIERE UDC DE L'ARBRE 

# DESTRUCTION

Pour détruire une UdC, on peut créer une instance locale, puis appeler la méthode deleteAll(). Celle-ci détruit une UdC ainsi que
tous ses fils. Il N'EXISTE PAS DE DELETE() pour le moment car il semble étrange de vouloir détruire une UdC sans détruire ses fils,
plutôt que de vouloir la modifier. Si cela semble nécessaire, il est tout de même possible de réaliser la manipulation avec la fonction
insert() qui insère l'UdC comme fils d'une autre.