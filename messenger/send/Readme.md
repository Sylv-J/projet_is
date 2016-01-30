# Send Messages

L'interface d'envoi est implantée dans le fichier **send_interface.php**
Le script permettant d'envoyer des messages est implantés dans le fichier **send_script.php**


## Interface d'envoi

Pour pouvoir utiliser javascript pour notifier l'utilisateur on importe Javascript par :
```
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
```

Si ils sont envoyés par méthode POST, les champs 'Objet, destinataires' sont remplis automatiquement. Et ce parle biais du script php de la ligne **48** à la ligne **61**.

### Autocomplétition

L'autocomplétition des destinataires est assurée par les deux fonctions Javascript suivante :

```
$(function(){
  $('#msg_dests').tags({
    requireData:true,
    unique:true,
  }).autofill({
    data:["admin","chairman","correcteurs","secretaire"],
  });
});
```
Le paramètre *requireData* permet de limiter les destinataires à ceux figurants dans la liste *data*. Le paramètre *unique* nous permet d'éviter les destinataires dupliqués. La liste *data* contient les autocomplétition qui seront proposées à l'utilisateur lors de la saisie des destinataires.

### Envoi du message

Le boutton 'Envoyer' est par défaut désactivé(on ne peut pas cliquer dessus). Il n'est activé qu'une fois le champs contenu du message ait été changé. L'activation du boutton se fait par la fonction javascript 'show_button()'.

En cliquant sur le boutton 'Envoyer', le script send_script.php est appelé dans un **iframe** invisible (height:0;width:0;border:0). Cet élément est créé entre la ligne *63* et la lgine *73*.


## Script d'envoi
 Dans ce script php, aussi, des fonctions javascript sont appelée à plusieurs reprises pour notifier l'utilisateur sur ce qui se passe. Donc on inclut encore le Javascript (*c.f lignes 29-34*).

### Variables requises
Les variables suivantes doivent être passée par méthode POST au script *send_script.php* pour qu'il y est envoi du message :
```
"submit", "msg_object", "msg_dests", "msg_content"
```
* *submit*: déclenche l'envoi du message.
* *msg_object*: l'objet du message.
* *msg_dests* : les destinataires du message. Les destintaires sont séparés par un '**;**' (*c.f send_interface.php ligne 91* pour les destinataires possibles)
* *msg_content*: le contenu du message.

### Gestion des listes de diffusiona
La fonction *parseMailingList($destArray)* permet de retrouver les noms d'utilisateur pour chaque liste de difusion qui est contentue dans l'array $destArray. Cette fonction fait appel au fonction dans le fichier **database_request/getUsers.php**

### Envoi du message

On commence par vérifier si l'utilisateur à bien saisi un objet pour son message(*cf. lignes 45-55*). On fait de même pour le champs des destintaires(*cf. lignes 57-68*). Si c'est deux tests passés, on vérifie si les différents destintaires saisis sont dans la base de donnée(*cf. lignes 71-94*). On vérifie alors si le contenu du message n'est pas vide(*cf. lignes 98-108*).

Si tous les champs sont conformes, on procède à l'envoi du message. On enregistre d'abord le message dans la table `msg`, en insérant 'objet','contenu','date' dans les colonnes 'object','body','date'. Si au cours de la requête un problème survient on prévient l'utilisateur et on quitte le script.

Si le message est bien enregistré, on link ce message au destinataires et expéditeur respectifs. Si l'expéditeur est un chairman on enregistre dans la table que l'expéditeur du message est 'chairman' et non le nom d'utilisateur. Ceci permet de préserver le username du chairman.

Si tout ce passe bien on affiche à l'utilisateur un petit message confirmant le bon envoi du message.


## Built With

* Atom

## Contributing

* **Corentin Jannier**

## Authors

* **Ayoub SAHLI** - *Mines Nancy*
