Pour compiler le projet (avec QtCreator):

- Installer Qt 5.5 (lien de téléchargement : http://www.qt.io/download-open-source/)
- Installer Opencv
- Ouvrir QtCreator
- Créer un nouveau projet : nouveau projet -> autre projet -> empty qmake Project, donner un nom, puis suivant -> suivant -> terminer
- Dans le fichier (nom_du_projet).pro, ajouter les lignes :
QT += widgets
QMAKE_LIBS += `pkg-config opencv --cflags pkg-config opencv --libs`

- Puis ajouter au projet les fichiers main.cpp, mywindow.cpp et mywindow.h : clic droit sur le projet -> ajouter des fichiers existants ...
- Lancer le programme en appuyant sur la flèche verte en bas à gauche
