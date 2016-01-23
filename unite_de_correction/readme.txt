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
* $udc = UniteDeCorrection::fromId('id de l'�l�ve') cr�e une entr�e sur la database � partir de l'ID en se
basant sur le bar�me existant. Il g�n�re donc l'arbre associ�.
* $udc = UniteDeCorrection::fromData($arrayData) cr�e une entr�e sur la BDD et en local � partir d'un 
ensemble de donn�es ($arrayData). Le format doit �tre le suivant :
$arrayData = array('id_father'=>'lolfather',
		'id'=>'lolme',
		'id_sons'=>'lol_son1,lol_son2,lol_son3',
		'level'=>2,
		'mark'=>3,
		'max_mark'=>5,
		'date_modif'=>'23-01-2016 15:09:12',
		'id_corrector'=>'lolcorrector');
* $udc = UniteDeCorrection::getUnitById('id � trouver') va chercher l'unit� sur la BDD en fonction
de son ID.
 
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

# EXEMPLE D'UTILISATION 

Cr�ation et upload :

$udc = UniteDeCorrection::fromId('nouvelEleve');
$udc->setNote(12);

$udc->upload();

Depuis un formulaire :

$arrayData['id'] = $_GET['id'];
$arrayData['data']=$_GET['data'];
...

$udc = UniteDeCorrection::fromData($arrayData);

	A noter que dans le cas pr�sent, on n'a rien modifi� � l'objet. Il est donc inutile (mais pas
	une erreur pour autant) d'appeler la m�thode upload(), qui est appel�e au moment de la cr�ation
	de l'objet.