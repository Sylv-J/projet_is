<body>
	<div class="jumbotron">
		<div class="container">
			<div class="col-md-6">
<form action=<?php echo "../actions/chairman/action_ajout_epreuve.php" ?> method="post">
				<h2>Ajout d'une épreuve</h2>

				<!-- Formulaire -->
				<form method="post" action="index.php" role="form">
<h3>Organisation des exercices :</h3>
					<div class="form-group has-feedback">

            <textarea name="bareme" rows="10" cols="50" placeholder="barème"></textarea><br><br>

						<i class="glyphicon glyphicon-pencil form-control-feedback"></i>
					</div>
					<div class="form-group">
						<button class="btn" type="submit">
							<span class="glyphicon glyphicon-check"></span> Créer
						</button>
					</div>
				</form>
			</div>
					<div class="col-md-6">
	            <h2>Exemple pour remplir le formulaire: </h2>
	            <p>Maths<br>
							*Partie 1<br>
							**exercice 1@<br>
							**exercice 2@<br>
							**exercice 1@<br>
							*Partie 2<br>
							**exercice 1@<br>
							**exercice 2@<br>
						</p>
	        </div>




		</div>
	</div>
</body>
