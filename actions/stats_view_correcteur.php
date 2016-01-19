
<body>
	
    <div class="jumbotron">
        <div class="container">

			
			<h2> Stats globales, veuillez indiquer le nom de l'exercice </h2>
					<form method="post" action = "../actions/stats.php" role="form">

                        <div class="form-group">
                            <input type="number" placeholder="No de l'exercice" name="id_exo" class="form-control" required>
                        </div>
                        <input type="submit" value="Valider" class="btn btn-success">
						
                    </form>
					
			<h2> Stats glissantes, veuillez indiquer le nom de l'exercice et le nombres de copies à prendre en compte </h2>
					<form method="post" action = "../actions/stats.php" role="form">

                        <div class="form-group">
                            <input type="number" placeholder="No de l'exercice" name="id_exo2" class="form-control" required>
                        </div>
						<div class="form-group">
                            <input type="number" placeholder="Nb de copies" name="nb_copies" class="form-control" required>
                        </div>
                        <input type="submit" value="Valider" class="btn btn-success">
						
                    </form>
					
					
			
			
        </div>
    </div>
</body>
