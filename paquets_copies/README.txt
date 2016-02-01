Dernière mise à jour le 31/01/2016 par Mathilde

Ce dossier /paquet_copies permet de gérer l'allocation des unités de correction pour les correcteurs.

Concis, mais efficace ? #Julien

Pour distribuer les unités non assignées:
- Utiliser la fonction assignUnits($unitType) pour assigner toutes les unités de type $unitType aux correcteurs concernés
- Utiliser la fonction assignAll() pour assigner toutes les unités libres (toutes les matières)


Fonctions utiles:
getSubjects() : récupérer un tableau contenant toutes les matières
listCorrectors() : tableau contenant tous les correcteurs auxquels on a assigné au moins une unité
unitsCorrector($id_corrector) : récupérer les unités assignées à un correcteur (sous forme de tableau)
freeCorrectors(): réinitialiser le champ id_corrector de toutes les unités de la table units et le champ current_units des correcteur (désassigner toutes les unités) 
freeUnit() : désassigner une seule unité (mettre le champ id_corrector à NULL)


