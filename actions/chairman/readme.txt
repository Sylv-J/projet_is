Bonjour ! 			Fait par Alban le 25/01/2016




///////README formulaire Ajout �l�ve\\\\\\\\\\\\\\\\\\\\\\\\


Pour l'ajour d'un bareme remplir le texte comme suit :

Math
*Math1
**Math1_sousepreuve1@
**Math1_sousepreuve2@
*Math2@
Fran�ais@
Physique
*Physique1@

ainsi que les champs anneeconcoursfiliere et id �l�ves

Les petites �toiles nous servent � savoir o� vous vous placez dans la hierarchie, et les @ indiquent les dossiers primaires
c�d ceux qui n'ont contiendront directement des fichiers et sans autres sous dossier dedans.

Cela cr�� DANS LE DOSSIER projet_is dans cet ordre :
 
projet_is > anneeconcoursfiliere > id eleve > Math > Math1 > Math1_sousepreuve1
							   > Math1_sousepreuve2
						    > Math2 
					    > Fran�ais 
					    > Physique > Physique1


######################## Marion et Philippe le 25/01/2016
dans bandeau_stats_chairman : 
	premier formulaire : rentrer l'id du correcteur
	deuxi�me partie : rentrer id du correcteur et l'id_father de l'exo 


###################### Gr�goire, le 31/01/2016
udc_chairman est un copier-coller du code disponible dans Unit�s de Corrections (afin de pouvoir int�grer les bons chemins lors de l'utilisation de action_bareme_bis)
Formulaire_bareme_concours et action_bareme_bis permettent de cr�er dans la database units le bar�me du concours, c'est � dire l'�l�ve 0 avec ses dossiers fils. La syntaxe � entrer est la m�me Ajout �l�ve