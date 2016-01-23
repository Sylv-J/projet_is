<?php
include_once("UniteDeCorrection.php");


$arrayData = array('id_father'=>'lolfather',
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

?>