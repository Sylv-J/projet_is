
<body>
	
    <div class="jumbotron">
        <div class="container">

			
			<h2> Moyenne globale </h2>
			
			<p>Veuillez indiquer le nom de l'exercice : </p>
					<form method="post" action = "index.php" role="form">

                        <div class="form-group">
                            <input type="number" placeholder="No de l'exercice" name="id_exo" class="form-control" required>
                        </div>
                        <input type="submit" value="Valider" class="btn btn-success">
						
                    </form>
					

					
			<h2> Moyenne glissante </h2>
			
			<p>Veuillez indiquer le nom de l'exercice et le nombres de copies à prendre en compte : </p>
			
					<form method="post" action = "../interface_web/index.php" role="form">

                        <div class="form-group">
                            <input type="number" placeholder="No de l'exercice" name="id_exo2" class="form-control" required>
                        </div>
						
						<input type="hidden" name="nb_copies" value="10">
                        <input type="submit" value="Valider" class="btn btn-success">
						
                    </form>
					
			<?php 
			
				include("../actions/stats.php");

				//$ydata = array(11,3,8,12,5,1,9,13,5,7);
				//include('img.php?ydata1=1');
				//echo '<img src="../actions/graph.php?ydata1='.urlencode(serialize($ydata)).'" border="0" alt="img.php" align="left">';
			?>			
			
			
        </div>
    </div>
</body>
