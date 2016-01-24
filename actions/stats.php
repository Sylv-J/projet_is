<body>
	
    <div class="jumbotron">
        <div class="container">


			<?php
			//Connection à la db
			include_once("../master_db.php");

			$db = masterDB::getDB();
			if(!isset($_SESSION)){session_start();}



			function MoyennePersoGlissante($nb_copies, $nom_exo){
				$db = masterDB::getDB();
				$req = $db->prepare("SELECT mark FROM units WHERE id_father = $nom_exo AND id_corrector = ? ORDER BY date_modif DESC ");
				$req->execute(array($_SESSION["id"]));
				
				
				$compteur = 0;
				$indice = 0;
				$res = $req->fetch();
				
				if ($res[0]!= ''){
					$compteur += $res[0];
					$indice += 1 ;
					while($res = $req->fetch() and $indice < $nb_copies){
						//echo $res[0];
						$compteur += $res[0];
						$indice += 1 ;
					}
					return $compteur / $indice ;
				}
				return "le nom de l'exercice est incorrect, veuillez ressayer";
			}

			function MoyennePersoGlobale($nom_exo){
				return MoyennePersoGlissante(INF, $nom_exo);
			}
			
			
			function MoyenneGlobale($nom_exo){
				$db = masterDB::getDB();
				$req = $db->prepare("SELECT mark FROM units WHERE id_father = $nom_exo");
				$req->execute(array($_SESSION["id"]));
				
				$res = $req->fetch();
				$compteur = 0;
				$indice = 0;
				
				if ($res[0]!= ''){
					$compteur += $res[0];
					$indice += 1 ;
					
									
					while($res = $req->fetch()){
						//echo $res[0];
						$compteur += $res[0];
						$indice += 1 ;
					}
					return $compteur / $indice ;
				}
				return " le nom de l'exo est incorrect, veuillez reessayer";
			}
			function MoyenneTresGlobale(){
				$db = masterDB::getDB();
				$req = $db->prepare("SELECT id_corrector FROM units");
				$req->execute(array($_SESSION["id"]));
				
				$compteur = 0;
				$indice = 0;
						
								
				while($res = $req->fetch()){
					//echo $res[0];
					$compteur += MoyenneCorrecteur($res[0]);
					$indice += 1 ;
				}
				return $compteur / $indice ;
			}
			function MoyenneCorrecteur($nom_correcteur){
				$db = masterDB::getDB();
				$req = $db->prepare("SELECT mark FROM units WHERE id_corrector = $nom_correcteur");
				$req->execute(array($_SESSION["id"]));
				
				
				$compteur = 0;
				$indice = 0;
				
				$res = $req->fetch();
				
				if ($res[0]!= ''){
					$compteur += $res[0];
					$indice += 1 ;
								
					while($res = $req->fetch()){
						//echo $res[0];
						$compteur += $res[0];
						$indice += 1 ;
					}
					return $compteur / $indice ;
				}
				return "le correcteur est inconnu, veuillez reessayer";
			}
			function MoyenneCorrecteurExo($nom_correcteur, $nom_exo){
				$db = masterDB::getDB();
				$req = $db->prepare("SELECT mark FROM units WHERE id_corrector = $nom_correcteur AND id_father = $nom_exo");
				$req->execute(array($_SESSION["id"]));
				
				
				$compteur = 0;
				$indice = 0;
				
				$res = $req->fetch();
				
				if ($res[0]!= ''){
					$compteur += $res[0];
					$indice += 1 ;
								
					while($res = $req->fetch()){
						//echo $res[0];
						$compteur += $res[0];
						$indice += 1 ;
					}
					return $compteur / $indice ;
				}
				return " le correcteur est inconnu ou le nom de l'exercice n'est pas valide, veuillez reessayer ";
			}
						
			?>


			<?php // CORRECTEUR //traitement si moyenne globale demandée
					if(isset($_POST["id_exo"])){?>
						
						
						<p> <?php
						echo "Votre moyenne globale sur l'exercice " ;
						echo $_POST["id_exo"];
						echo " est de : " ;
						echo MoyennePersoGlobale($_POST["id_exo"]);?>
						
						<p>
						<?php
						echo "\n La moyenne globale sur l'exercice " ;
						echo $_POST["id_exo"];
						echo " est de : " ;
						echo MoyenneGlobale($_POST["id_exo"]);?> 
						
						<?php
					}?>	

			<?php // CORRECTEUR //traitement si moyenne glissante demandée
					if(isset($_POST["id_exo2"]) AND isset($_POST["nb_copies"])){?>
						
						<p> <?php
						echo "Votre moyenne glissante sur l'exercice " ;
						echo $_POST["id_exo2"];
						echo " est de : " ;
						echo MoyennePersoGlissante($_POST["nb_copies"], $_POST["id_exo2"]);
						?>
						
						<p>
						<?php
						echo "\n La moyenne globale des correcteurs sur l'exercice " ;
						echo $_POST["id_exo2"];
						echo " est de : " ;
						echo MoyenneGlobale($_POST["id_exo2"]);
						
						
						?> 
						
						<?php
					}
									
								?>	
								
				<?php 
				
				//Création du graph de la moyenne glissante
				if (isset($_POST['id_exo2'])){
					$req = $db->prepare("SELECT mark FROM units WHERE id_father = ? AND id_corrector = ? ORDER BY date_modif DESC ");
					$req->execute(array($_POST['id_exo2'], $_SESSION["id"]));
					
					while($res = $req->fetch()){
						//echo $res[0];
						$notes[] = $res[0];
					}
					if (isset($notes)){
						$moyenneGlissante = [];
						for ($i = 0; $i < sizeof($notes); $i++) {
							if ($i+$_POST["nb_copies"]<=sizeof($notes)){
								$sum = 0;
								for ($j = 0; $j <$_POST["nb_copies"]; $j++){
									$sum += $notes[$i+$j];
								}
							}
							$moyenneGlissante[] = $sum/$_POST["nb_copies"];
						}
						echo "<br/>";
						echo '<img src="../actions/graph.php?ydata1='.urlencode(serialize($moyenneGlissante)).'" border="0" alt="img.php" align="left">';
					}
					
				}
				?>
				
				<?php // CHAIRMAN //traitement si moyenne globale correcteur demandée
					if(isset($_POST["id_correcteur"])){?>
						
						<p> <?php
						echo "Le moyenne globale du correcteur " ;
						echo $_POST["id_correcteur"];
						echo " est de : " ;
						echo MoyenneCorrecteur($_POST["id_correcteur"]);
						?>
						
						<p>
						<?php
						echo "\n La moyenne globale de tous les correcteurs est de : " ;
						echo MoyenneTresGlobale();?> 
						
						<?php
					}
									
								?>		
			<?php // CHAIRMAN//traitement si moyenne sur exo precis demandée
					if(isset($_POST["id_exo3"]) AND isset($_POST["id_correcteur2"])){?>
						
						<p> <?php
						echo "Le moyenne de ";
						echo $_POST["id_correcteur2"];
						echo " sur l'exercice " ;
						echo $_POST["id_exo3"];
						echo " est de : " ;
						echo MoyenneCorrecteurExo($_POST["id_correcteur2"], $_POST["id_exo3"]);
						?>
						
						<p>
						<?php
						echo "\n La moyenne globale des correcteurs sur l'exercice " ;
						echo $_POST["id_exo3"];
						echo " est de : " ;
						echo MoyenneGlobale($_POST["id_exo3"]);?> 
						
						<?php
					}
									
								?>						
					
			
			
        </div>
    </div>
</body>
					