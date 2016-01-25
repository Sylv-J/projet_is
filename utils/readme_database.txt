Salut !

La forme des bases de données a changé. Et ça complique un peu la chose. Les listes sont remplacées par des longs strings
current_units est de type VARCHAR(255) alors que l'on voulait une liste de VARCHAR. Ce que l'on fait c'est que l'on ajoute
le caractère * entre chaque units.

	Ex : ["Math1_exo1","Math1_exo2"] est en réalité de la forme "Math1_exo1;Math2_exo2" 



Idem pour id_sons qui devait être une liste d'entiers et qui est en réalité de type VARCHAR(255)

	Ex : ['5','7','10'] est en réalité "5;7;10"


///////Veuillez donc en tenir compte à l'ajout et l'utilisation de ces données svp///////


Pour voir la liste des différents types. Ouvrez projetis/utils/tables_struct
