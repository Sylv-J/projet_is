
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

		<title>Administrateur</title>

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
		<?php include("/navbar.php") ;?>
		
		<!-- Bandeau contenant 3 cases : avancement, actions persos, gestion du compte -->
		<div class="jumbotron">
			<div class="container">
				<!-- Case Avancement -- commune à tous -->
				<div class="col-md-4">
					<h2>Progression</h2>
					<h3>Exercices corrigés</h3>
					<div class="progress">
						<div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
							60%
						</div>
					</div>
					<h3>Élèves évalués</h3>
					<div class="progress">
						<div class="progress-bar" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%;">
							20%
						</div>
					</div>
				</div>
				
				<!-- Case action -- dépend du rôle -->
				<?php include("actions.php");?>
				
				<!-- Case mon_compte -- commune à tous -->
				<div class="col-md-4">
					<h2>Mon compte</h2>
					<div class="list-group">
						<a href="#" class="list-group-item">Mon profil</a>
						<a href="#" class="list-group-item">Calendrier</a>
						<a href="#" class="list-group-item">Messages</a>
						<a href="#" class="list-group-item">Modifier</a>
						<a href="#" class="list-group-item">Déconnexion</a>
					</div>
				</div>
				
			</div>
		</div>


		<!-- Bootstrap core JavaScript
		================================================== -->
		<!-- Placed at the end of the document so the pages load faster -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<script>window.jQuery || document.write('<script src="js/vendor/jquery.min.js"><\/script>')</script>
		<script src="js/bootstrap.min.js"></script>
		<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
		<script src="js/ie10-viewport-bug-workaround.js"></script>
	</body>
	
</html>
