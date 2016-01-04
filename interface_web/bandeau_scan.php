<body>
	<!-- Bandeau contenant 3 cases : avancement, actions persos, gestion du compte -->
	<div class="jumbotron">
		<div class="container">
			<!-- Scan de copies -->
			<div class="col-md-6">
				<h2>Scanner</h2>
				<form method="post" action="index.php">
					<input type="file" accept="image/*" name="copie[]" multiple>
					<br>
					<input type="text" placeholder="Epreuve">
					<input type="text" placeholder="Nombre d'exercices">
					<button type="submit">GO</button>
				</form>
			</div>
			
		</div>
	</div>
</body>

