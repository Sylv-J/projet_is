<body>
	<!-- Bandeau contenant 3 cases : avancement, actions persos, gestion du compte -->
	<div class="jumbotron">
		<div class="container">
			<!-- Case action -- dépend du rôle -->
			<?php include("../actions/actions.php");?>

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
</body>
