Bonjour ! 			



  //////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
 ///////README formulaire Ajout �l�ve\\\\\\\\\\\\\\\\\\\\\\\    MaJ par Alban le 01/02/2016
////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

Pour l'ajout d'un �l�ve remplir le texte comme suit :

Math
*Math_Math1
**Math_Math1_sousepreuve1@
**Math_Math1_sousepreuve2@
*Math_Math2@
Fran�ais@
Physique
*Physique_Physique1@

ainsi que les champs anneeconcoursfiliere et id �l�ves

Les petites �toiles nous servent � savoir o� vous vous placez dans la hierarchie, et les @ indiquent les dossiers primaires
c�d ceux qui n'ont contiendront directement des fichiers et sans autres sous dossier dedans.

Cela cr�� DANS LE DOSSIER projet_is dans cet ordre :
 
projet_is > images > anneeconcoursfiliere > id eleve > Math > Math_Math1 > Math_Math1_sousepreuve1
							  		 > Math_Math1_sousepreuve2
						   	    > Math_Math2 
					    	     > Fran�ais 
					    	     > Physique > Physique_Physique1

  //////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
 ///////README formulaire Ajout correcteur\\\\\\\\\\\\\\\\\\    Mis � jour par Alban le 31/01/2016
////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

Donnez le nom du concours sans faute. Il sert � trouver le nom de la database
Pour les correcteurs inserez un retour � la ligne entre chaque nom de correcteurs :
 ---------------
|jean 		|
|michel		|
|paul		|
 ---------------

De m�me pour les �preuves : 

 ---------------
|Mat_ex1	|
|Mat_ex2	|
|Physique	|
 ---------------

!!!!!Warning!!!!!! rien ici n'emp�che les �preuves d'apparaitre en double


  //////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
 ///////README bandeau stats chairman\\\\\\\\\\\\\\\\\\\\\\\
////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

######################## Marion et Philippe le 25/01/2016
dans bandeau_stats_chairman : 
	premier formulaire : rentrer l'id du correcteur
	deuxi�me partie : rentrer id du correcteur et l'id_father de l'exo 


###################### Gr�goire, le 31/01/2016,  Alban le 02/02/2016
udc_chairman est un copier-coller du code disponible dans Unit�s de Corrections (afin de pouvoir int�grer les bons chemins lors de l'utilisation de action_bareme_bis)
Formulaire_bareme_concours et action_bareme_bis permettent de cr�er dans la database units le bar�me du concours, c'est � dire l'�l�ve 0 avec ses dossiers fils. La syntaxe � entrer est la m�me Ajout �l�ve


Pour le champ bareme remplir comme suit :


*Math
**Math_Math1
***Math_Math1_sousepreuve1@10
***Math_Math1_sousepreuve2@15
**Math_Math2@7
*Fran�ais@45
*Physique
**Physique_Physique1@12

(c'est pareil que l'ajout d'�l�ve plus haut avec une �toile en plus devant et des notes max apr�s les @)
Les dossiers cr��s comme ceci : 


projet_is > images > anneeconcoursfiliere > id eleve > Math > Math_Math1 > Math_Math1_sousepreuve1
							  		 > Math_Math1_sousepreuve2
						   	    > Math_Math2 
					    	     > Fran�ais 
					    	     > Physique > Physique_Physique1