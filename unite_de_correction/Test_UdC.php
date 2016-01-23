<?php
include_once("UniteDeCorrection.php");


/*$arrayData = array('id_father'=>'lolfather',
		'id'=>'lolme',
		'id_sons'=>'lol_son1,lol_son2,lol_son3',
		'level'=>2,
		'mark'=>3,
		'max_mark'=>5,
		'date_modif'=>'23-01-2016 15:09:12',
		'id_corrector'=>'lolcorrector');

$udc = UniteDeCorrection::fromData($arrayData);

$udc->upload();

$udc->updateDate();
$udc->setNote(14);
$udc->upload();

$udc = UniteDeCorrection::getUnitById($udc->getId());

echo $udc->getId();
*/
try
{
UniteDeCorrection::generateBareme("Maths1_Partie1_Exercice1_petita_3
Maths1_Partie1_Exercice1_petitb_2
Maths1_Partie1_Exercice2_5
Maths1_Partie1_Exercice3_5
Maths1_Partie2_Exercice1_10
Maths1_Partie2_Exercice2_10");
} catch(Exception $e){
	echo "Erreur au moment de la création du barême :  \n".$e->getTraceAsString();
}

?>