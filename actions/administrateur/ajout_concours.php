<body>
	<!-- Bandeau contenant le formulaire de scan des copies de la secrétaire -->
	<div class="jumbotron">
		<div class="container">

			<!-- Scan de copies -->
			<div class="col-md-6">

				<h2>Ajout d'un concours</h2>

				<!-- Formulaire, il faut completer les noms des inputs   -->
				<form method="post" action="index.php" role="form">
					<div class="form-group has-feedback">
						<input class="form-control" type="text" name="name" placeholder="Nom du concours">
						<i class="glyphicon glyphicon-education form-control-feedback"></i>
					</div>

					<div class="form-group has-feedback">
						<input class="form-control" type="text" name="nbre_epreuves" placeholder="Nombre d'épreuves">
						<i class="glyphicon glyphicon-pencil form-control-feedback"></i>
					</div>

					<div class="form-group">
						<button class="btn" type="submit">
							<span class="glyphicon glyphicon-search"></span> GO
						</button>
					</div>
					<input type="hidden" name="page_to_load" value="Scanner">

				</form>
			</div>

		</div>
	</div>
</body>
