<?php
include_once("../master_db.php");
$db = masterDB::getDB();

function getUserbyUnit($unit){
  /* TODO : retourne un tableau contenant les id d'utilisateur qui sont chargés
            de corriger une unité donnée (passée en paramètre $unit)*/
}

function getUserbySubject($subject){
  /* TODO : retourne un tableau contenant les id d'utilisateur qui sont chargés
            de corriger une epreuve donnée (passée en paramètre $subject)*/
}

function getChairman($subject){
  /* TODO : retourne le chairman d'une epreuve donnée (passée en paramètre $subject)
            */
  SELECT id FROM users WHERE epreuves=$epreuve_donnee AND user_group='chairman'
}


?>
