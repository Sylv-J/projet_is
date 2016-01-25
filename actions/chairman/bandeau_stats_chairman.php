
<body>
	
    <div class="jumbotron">
        <div class="container">

			<h2> Stats Globales : veuillez entrer le nom du correcteur </h2>
					<form method="post" action = "index.php" role="form">

                        <div class="form-group">
                            <input type="number" placeholder="Nom correcteur" name="id_correcteur" class="form-control" required>
                        </div>
                        <input type="submit" value="Valider" class="btn btn-success">
						
                    </form>
					
			<h2> Stats ciblées, veuillez indiquer le nom de l'exercice et le nom du correcteur </h2>
					<form method="post" action = "index.php" role="form">

                        <div class="form-group">
                            <input type="number" placeholder="No de l'exercice" name="id_exo3" class="form-control" required>
                        </div>
						<div class="form-group">
                            <input type="number" placeholder="Nom du correcteur" name="id_correcteur2" class="form-control" required>
                        </div>
                        <input type="submit" value="Valider" class="btn btn-success">
						
                    </form>
					
					<?php 
			
				include("../actions/stats.php");

			?>	
			
			
        </div>
    </div>
</body>
