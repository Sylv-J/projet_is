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

	<!-- Titre de la page -->
	<title>Secrétaire</title>

	<!-- Alignement des cases sur la page-->
	<style type="text/css">
	form  { display: table;      }
	p     { display: table-row;  }
	label { display: table-cell; }
	input { display: table-cell; }
	</style>

	<!-- Bootstrap core CSS -->
	<link href="css/bootstrap.min.css" rel="stylesheet">


	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>

<body>
	<!-- barre de navigation -->
	<?php include("navbar.php") ;?>

	<!-- Bandeau contenant 3 champs : Epreuve, Nombre d'exercices, Identifiant de la copie -->
	<div class="jumbotron">
		<div class="container">
			<form action= "multiplefile_upload.php" method="post" enctype="multipart/form-data" target="upload_iframe">
				<p>
					<br/>
					<label for="fileToUpload">Ajouter image :</label><br/>
					<input id="file" type="file" name="my_files[]" multiple><br/>
				</p>
				<p>
					<label for="input_epreuve">Epreuve : </label><br/>
					<input id="input_epreuve" type="text" name="epreuve"><br/>
				</p>
				<p>
					<label for="input_nb_exo">Nombre d'exercices: </label><br/>
					<input id="input_nb_exo" type="text" name="nb_exo"><br/>
				</p>
				<p>
					<label for="input_id_copie">Identifiant de la copie : </label><br/>
					<input id="input_id_copie" type="text" name="id_copie"><br/><br/>
				</p>
				<p>
					<input formmethod="post" name="submit" type="submit" value="Soumettre" align ="right"><br>
				</p>
			</form>
			<!--  Créer un iframe (sorte de page intégrée dans la page actuelle) pour
      			éxecuter le code php sans avoir à rafraichir la page.
			-->
			<script language="Javascript" type="text/Javascript">
				var ifrm = document.createElement("IFRAME");
				ifrm.setAttribute("id","upload_iframe");
				ifrm.setAttribute("name","upload_iframe");
				// les dimensions (ci-dessous) de l'iframe sont mise toutes à 0 pour
				// que cette iframe ne soit pas visible à l'utilisateur (la secrétaire)
				ifrm.style.width = "0px";
				ifrm.style.height = "0px";
				ifrm.style.border = "0";
				// On ajoute cet iframe à la page actuelle.
				document.body.appendChild(ifrm);
			</script>
		</div>
	</div>


	<!-- Bootstrap core JavaScript
	================================================== -->
	<!-- Placed at the end of the document so the pages load faster -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="js/vendor/jquery.min.js"><\/script>')</script>
	<script src="js/bootstrap.min.js"></script>
	<!-- le script Javascript permettant l'affichage de splashscreen-->
	<script src="./js/notify.js"></script>
	<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
	<script src="js/ie10-viewport-bug-workaround.js"></script>
</body>

</html>
