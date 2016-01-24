
<body>
	
    <div class="jumbotron">
        <div class="container">

			
			<h2> Moyenne globale : veuillez entrer le nom du correcteur </h2>
					<form method="post" action = "../actions/stats.php" role="form">

                        <div class="form-group">
                            <input type="number" placeholder="Nom correcteur" name="id_correcteur" class="form-control" required>
                        </div>
                        <input type="submit" value="Valider" class="btn btn-success">
						
                    </form>
					
			<h2> Moyenne ciblée, veuillez indiquer le nom de l'exercice et le nom du correcteur </h2>
					<form method="post" action = "../actions/stats.php" role="form">

                        <div class="form-group">
                            <input type="number" placeholder="No de l'exercice" name="id_exo3" class="form-control" required>
                        </div>
						<div class="form-group">
                            <input type="number" placeholder="Nom du correcteur" name="id_correcteur2" class="form-control" required>
                        </div>
                        <input type="submit" value="Valider" class="btn btn-success">
						
                    </form>
					
					
			
			
        </div>
    </div>
</body>
