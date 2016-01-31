Bonjour ! 			Fait par Alban le 25/01/2016




///////README formulaire Ajout élève\\\\\\\\\\\\\\\\\\\\\\\\


Pour l'ajour d'un bareme remplir le texte comme suit :

Math
*Math1
**Math1_sousepreuve1@
**Math1_sousepreuve2@
*Math2@
Français@
Physique
*Physique1@

ainsi que les champs anneeconcoursfiliere et id élèves

Les petites étoiles nous servent à savoir où vous vous placez dans la hierarchie, et les @ indiquent les dossiers primaires
càd ceux qui n'ont contiendront directement des fichiers et sans autres sous dossier dedans.

Cela créé DANS LE DOSSIER projet_is dans cet ordre :
 
projet_is > anneeconcoursfiliere > id eleve > Math > Math1 > Math1_sousepreuve1
							   > Math1_sousepreuve2
						    > Math2 
					    > Français 
					    > Physique > Physique1


######################## Marion et Philippe le 25/01/2016
dans bandeau_stats_chairman : 
	premier formulaire : rentrer l'id du correcteur
	deuxième partie : rentrer id du correcteur et l'id_father de l'exo 


###################### Grégoire, le 31/01/2016
udc_chairman est un copier-coller du code disponible dans Unités de Corrections (afin de pouvoir intégrer les bons chemins lors de l'utilisation de action_bareme_bis)
Formulaire_bareme_concours et action_bareme_bis permettent de créer dans la database units le barême du concours, c'est à dire l'élève 0 avec ses dossiers fils. La syntaxe à entrer est la même Ajout élève