Bonjour ! 			



  //////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
 ///////README formulaire Ajout élève\\\\\\\\\\\\\\\\\\\\\\\    MaJ par Alban le 01/02/2016
////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

Pour l'ajout d'un élève remplir le texte comme suit :

Math
*Math_Math1
**Math_Math1_sousepreuve1@
**Math_Math1_sousepreuve2@
*Math_Math2@
Français@
Physique
*Physique_Physique1@

ainsi que les champs anneeconcoursfiliere et id élèves

Les petites étoiles nous servent à savoir où vous vous placez dans la hierarchie, et les @ indiquent les dossiers primaires
càd ceux qui n'ont contiendront directement des fichiers et sans autres sous dossier dedans.

Cela créé DANS LE DOSSIER projet_is dans cet ordre :
 
projet_is > images > anneeconcoursfiliere > id eleve > Math > Math_Math1 > Math_Math1_sousepreuve1
							  		 > Math_Math1_sousepreuve2
						   	    > Math_Math2 
					    	     > Français 
					    	     > Physique > Physique_Physique1

  //////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
 ///////README formulaire Ajout correcteur\\\\\\\\\\\\\\\\\\    Mis à jour par Alban le 31/01/2016
////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

Donnez le nom du concours sans faute. Il sert à trouver le nom de la database
Pour les correcteurs inserez un retour à la ligne entre chaque nom de correcteurs :
 ---------------
|jean 		|
|michel		|
|paul		|
 ---------------

De même pour les épreuves : 

 ---------------
|Mat_ex1	|
|Mat_ex2	|
|Physique	|
 ---------------

!!!!!Warning!!!!!! rien ici n'empêche les épreuves d'apparaitre en double


  //////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
 ///////README bandeau stats chairman\\\\\\\\\\\\\\\\\\\\\\\
////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

######################## Marion et Philippe le 25/01/2016
dans bandeau_stats_chairman : 
	premier formulaire : rentrer l'id du correcteur
	deuxième partie : rentrer id du correcteur et l'id_father de l'exo 


###################### Grégoire, le 31/01/2016,  Alban le 02/02/2016
udc_chairman est un copier-coller du code disponible dans Unités de Corrections (afin de pouvoir intégrer les bons chemins lors de l'utilisation de action_bareme_bis)
Formulaire_bareme_concours et action_bareme_bis permettent de créer dans la database units le barême du concours, c'est à dire l'élève 0 avec ses dossiers fils. La syntaxe à entrer est la même Ajout élève


Pour le champ bareme remplir comme suit :


*Math
**Math_Math1
***Math_Math1_sousepreuve1@10
***Math_Math1_sousepreuve2@15
**Math_Math2@7
*Français@45
*Physique
**Physique_Physique1@12

(c'est pareil que l'ajout d'élève plus haut avec une étoile en plus devant et des notes max après les @)
Les dossiers créés comme ceci : 


projet_is > images > anneeconcoursfiliere > id eleve > Math > Math_Math1 > Math_Math1_sousepreuve1
							  		 > Math_Math1_sousepreuve2
						   	    > Math_Math2 
					    	     > Français 
					    	     > Physique > Physique_Physique1