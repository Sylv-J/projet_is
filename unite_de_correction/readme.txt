##########################################################################################################################################
###############################	UniteDeCorrection.php			##################################################################
###############################	11/01/2016				##################################################################
##########################################################################################################################################
##########################################################################################################################################

# OBJECTIF

Ce sont des classes qui permettent l'impl�mentation des diff�rents �l�ments (�preuves, parties, exercices...) qui constituent les
concours

NOTE : Toute modification apport�e � la classe est LOCALE. Pour que ces modifications prennent effectivement effet, il faut appeler
la m�thode magique upload() qui g�re la cr�ation/modification des UdC. Il n'est donc PAS NECESSAIRE de v�rifier qu'une UdC existe
sur le serveur pour utiliser upload(), dans un cas d'utilisation normale. En effet, les constructeurs permettent syst�matiquement
de trouver un ID qui n'a pas �t� attribu� sur le serveur.

# CREER UNE UDC

Pour ce faire, la m�thode la plus indiqu�e reste encore d'utiliser le constructeur de base, qui va alors s'occuper de cr�er LOCALEMENT
un objet en se basant sur un ID qui n'est pas encore attribu� sur le serveur.
Les autres constructeurs peuvent �tre utilis�s quand :
* on souhaite cr�er une UdC comme fille d'une autre (encore une fois localement)
* on souhaite cr�er une UdC comme ins�r�e dans le noeud d'un autre (localement)
Le dernier constructeur UniteDeCorrection($arrayData) est un constructeur particulier appel� pour recr�er localement l'objet
que l'on est all� cherche sur le serveur (ie par un getUnitById).

# AJOUT DE FILS

On n'appelle en fait PRESQUE JAMAIS soi-m�me les constructeurs d'UniteDeCorrection. Lorsqu'une UdC existe, on appelle plut�t la m�thode newSon() 
dans le cas o� l'on souhaite cr�er un fils vierge (cas le plus commun).
Si, cependant, il semble n�cessaire d'ajouter un fils existant, la m�thode addSon() peut �tre appel�e � ces fins.

# UPLOAD/DOWNLOAD

Pour r�cup�rer une UdC sur le serveur et en cr�er une instance locale, il suffit d'utiliser la fonction getUnitById.
Une fois les diff�rentes modifications apport�es, on peut alors :
* Utiliser uploadAll() : cette fonction va uploader/modifier sur le serveur l'UdC ainsi que TOUS SES FILS.
* La fonction upload() : se contente d'uploader/modifier sur le serveur l'UdC

Si l'on souhaite par exemple r�-enregistrer un arbre entier d'UdC, il suffit donc de faire un $UdC->getRoot()->uploadAll(),
la fonction getRoot() permettant d'obtenir la PREMIERE UDC DE L'ARBRE 

# DESTRUCTION

Pour d�truire une UdC, on peut cr�er une instance locale, puis appeler la m�thode deleteAll(). Celle-ci d�truit une UdC ainsi que
tous ses fils. Il N'EXISTE PAS DE DELETE() pour le moment car il semble �trange de vouloir d�truire une UdC sans d�truire ses fils,
plut�t que de vouloir la modifier. Si cela semble n�cessaire, il est tout de m�me possible de r�aliser la manipulation avec la fonction
insert() qui ins�re l'UdC comme fils d'une autre.