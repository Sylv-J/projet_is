Readme database_request.

Le code d'Ayoub n'a pas d'explication dans le readme mais est très commenté directement sur le code.
Voir donc directement sur les fichiers pour plus d'explications


Upload de fichier sur le serveur.

multiplefile_upload.php est le script appelé par le formulaire ajoutcopie.php (action de secrétaire).
Il a pour fonction (POUR LE MOMENT) d'uploader sur le serveur de multiples fichiers dans une arborescence spécifiée.

A TERME: le but est que la secrétaire sélectionne plusieurs fichiers constituant la copie,
qu'elles soient rassemblées en un fichier dont le nom respecte le format AnneeConcoursFiliere_Elève_Epreuve.
C'est ce fichier qui est ensuite uploadé dans le dossier /images/AnneeConcoursFilière/Elève/Epreuve (cette arborescence est crée par le chairman par ajouteleve).

POUR LE MOMENT: Les fichiers sont uploadés dans la bonne arborescence du moment que le premier fichier a son nom respectant le format.
Ils sont nommés Eleve0, Eleve1 etc...

POUR FACILITER L'INTEGRATION: tout mon code s'effectue à partir du fichier présent dans la variable $filetoupload.

Des explications sur le mécanisme sont présentes en commentaire du code.
