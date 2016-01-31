<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="image/MinesNancy.png">

    <title>Concours Mines-Ponts</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<?php
  include_once("../users/user_context.php");

  // barre de navigation
  include("navbar.php") ;

  //Si on est pas connecté, sauf si on a cliqué sur "register"!
  if(!isset($_SESSION["id"]) && (!isset($_POST["page_to_load"]) ||$_POST["page_to_load"]!='register'))
	{
		include("bandeau_connexion.php"); //formulaire de connexion
	}

  //Si connecté
  else
	{
        //Assignation des copies  
        if(isset($_POST["unitsType"]) and $_SESSION["group"]=="chairman"){
            include_once("../paquets_copies/paquets_copies.php");
            assignUnits($_POST["unitsType"]);
            unset($_POST["unitsType"]);
        }
        elseif(isset($_POST["inbox"])){
            include_once("../messenger/inbox.php");
        }
        elseif(isset($_POST["sendbox"])){
            include_once("../messenger/send/send_interface.php");
        }elseif(isset($_POST["msg_read"])){
          include_once("../messenger/read/msg_read.php");
        }
		elseif(isset($_POST["id_exo"]) and $_SESSION["group"] == "correcteur"){
			include_once("../actions/correcteur/bandeau_stats_correcteur.php");
		}
		elseif(isset($_POST["id_exo2"]) and $_POST["nb_copies"] and $_SESSION["group"] == "correcteur"){
			include_once("../actions/correcteur/bandeau_stats_correcteur.php");
		}
		elseif(isset($_POST["id_correcteur"]) and $_SESSION["group"] == "chairman"){
			include_once("../actions/chairman/bandeau_stats_chairman.php");
		}
		elseif(isset($_POST["id_exo3"]) and isset($_POST["id_correcteur2"]) and $_SESSION["group"] == "chairman"){
			include_once("../actions/chairman/bandeau_stats_chairman.php");
		}
		//aucune tâche n'a été demandé, on charge la page perso
		elseif(!isset($_POST["page_to_load"]))
		{
			include("bandeau_page_perso.php");
			include("bandeau_exemple.php");
		} elseif(isset($_FILES["image"])) {
			include_once("../unite_de_correction/UniteDeCorrection.php");
			$arrayData = array();
			$arrayData['id'] = $_POST["id_eleve"];
			$arrayData['anneeconcoursfiliere'] = $_POST['anneeconcoursfiliere'];
			$arrayData['epreuve']=$_POST['epreuve'];

			UniteDeCorrection::fromData($arrayData,true); // On upload les images

		}
		else
		{
			include("../actions/tasksDirectories.php");
			include("../actions/".$tasksDirectories[$_POST["page_to_load"]]);
		}
	}
?>
<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="js/vendor/jquery.min.js"><\/script>')</script>
<script src="js/bootstrap.min.js"></script>
<script src="./js/notify.js"></script>
<script src="./js/autofill.js"></script>
<script src="./js/tags.js"></script>
<!-- Style pour le système de messagerie interne-->
<link type="text/css" href="./css/messenger.css" rel="stylesheet">
<!-- Style pour l'auto-completition des champs dans le système de messagerie-->
<link type="text/css" rel="stylesheet" href="./css/tags.css">

<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="js/ie10-viewport-bug-workaround.js"></script>
</body>
</html>
