-------- Système de messagerie -------
Ce dossier contient tout les fichiers permettant le fonctionnement de la messagerie interne
- inbox.php affiche la boîte de réception d'un utilisateur, et gère elle-même les erreurs qui pourraient survenir.
- msg_read.php gère l'affichage d'un message en particulier, et de même gère automatiquement les erreurs
- err_nommsg.php et err.php sont des pages d'erreurs utilisées par les pages précédentes avec un message et une redirection automatique
- send_interface.php est l'interface d'envoi de messages, avec gestion des erreurs, mais elle n'envoie pas le message elle même,
ceci est géré par send_script.php On peut l'appeler avec des paramètres pour pré-remplir certains champs de l'interface.
- send_script.php est le script qui envoit effectivement les messages, et peut être appelé depuis l'éxtérieur pour envoyer un message automatiquement si les paramètres appropriés sont fournis

---- Envoi assisté de message ------
Pour pré-remplir certain champ de send_interface.php automatiquement, il suffit d'appeller la page en remplissant les variables $_POST["msg_object_res"] pour l'objet du message et $_POST["msg_dests_res"]
pour remplir les destinataires du message automatiquement

---- Envoi automatique de message -----
Pour envoyer automatiquement un message, il faut apppeler la page send_script.php et fournir les paramètres suivants : $_POST["msg_object"] pour l'objet, $_POST["msg_dests"] pour les destinataires et
$_POST["msg_content"] pour le contenu du message
