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
								
				while($res = $req->fetch() and $indice < $nb_copies){
					//echo $res[0];
					$compteur += $res[0];
					$indice += 1 ;
				}
				return $compteur / $indice ;
			}

			function MoyennePersoGlobale($nom_exo){
				return MoyennePersoGlissante(INF, $nom_exo);
			}
						
			function MoyenneGlobale($nom_exo){
				$db = masterDB::getDB();
				$req = $db->prepare("SELECT mark FROM units WHERE id_father = $nom_exo");
				$req->execute(array($_SESSION["id"]));
				
				
				$compteur = 0;
				$indice = 0;
								
				while($res = $req->fetch()){
					//echo $res[0];
					$compteur += $res[0];
					$indice += 1 ;
				}
				return $compteur / $indice ;
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
								
				while($res = $req->fetch()){
					//echo $res[0];
					$compteur += $res[0];
					$indice += 1 ;
				}
				return $compteur / $indice ;
			}
			function MoyenneCorrecteurExo($nom_correcteur, $nom_exo){
				$db = masterDB::getDB();
				$req = $db->prepare("SELECT mark FROM units WHERE id_corrector = $nom_correcteur AND id_father = $nom_exo");
				$req->execute(array($_SESSION["id"]));
				
				
				$compteur = 0;
				$indice = 0;
								
				while($res = $req->fetch()){
					//echo $res[0];
					$compteur += $res[0];
					$indice += 1 ;
				}
				return $compteur / $indice ;
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
						echo MoyenneGlobale($_POST["id_exo2"]);?> 
						
						<?php
					}
									
								?>	
								
				<?php // CHAIRMAN //traitement si moyenne globale correcteur demandée
					if(isset($_POST["id_correcteur"])){?>
						
						<p> <?php
						echo "Le moyenne globale de " ;
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
					