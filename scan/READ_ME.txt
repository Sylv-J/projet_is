Pour compiler le programme (sous windows), il faut :
- installer la derniere version d'Opencv (3.1.0)
- creer une nouvelle variable d'environnement OPENCV_DIR dont la valeur est (dossier d'installation d'opencv)\build\x64\vc14 (ex : C:\opencv\build\x64\vc14)
- editer la variable d'environnement PATH en lui ajoutant (dossier d'installation d'opencv)\build\x64\vc14\bin
- dans un IDE, creer un nouveau projet C++ (sous Visual Studio : application console win32, projet vide), et ajouter le fichier source main.cpp
- dans les proprietes C/C++ du projet : ajouter un autre repertoire pour les include : $(OPENCV_DIR)\..\..\include
- dans les propriétés de l'éditeur de liens : ajouter un repertoire de bibliotheques supplementaires : $(OPENCV_DIR)\lib
                                              ajouter une dependance supplementaire : opencv_world310.lib pour une config RELEASE, et opencv_world310d.lib pour une config DEBUG

                                              
Ce programme ne fonctionne pour l'instant que sur une plateforme sous windows disposant de la librairie opencv (version 3.1.0) et respectant la configuration ci-dessus.