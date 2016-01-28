Dernière mise à jour le 28/01/2016 par Mathilde

Ce dossier /paquet_copies permet de gérer l'allocation des unités de correction pour les correcteurs.

Concis, mais efficace ? #Julien

Pour distribuer les unités non assignées:
- Utiliser la fonction assignUnits($unitType) pour assigner toutes les unités de type $unitType aux correcteurs concernés.
- Utiliser la fonction punctualAssignment($id_corrector, $unitType) pour assigner une unité de type $unitType à un correcteur spécifique (identifié par $id_corrector).

Fonctions utiles:
getSubjects() : récupérer un tableau contenant toutes les matières
listCorrectors() : tableau contenant tous les correcteurs auxquels on a assigné au moins une unité
freeCorrectors(): réinitialiser le champ id_corrector de toutes les unités de la table units


