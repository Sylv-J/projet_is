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
				$req->execute();
				
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
				$req = $db->prepare("SELECT mark FROM units");
				$req->execute();
				
				$compteur = 0;
				$indice = 0;
						
								
				while($res = $req->fetch()){
					$compteur += $res[0];
					$indice += 1 ;
				}
				if($indice != 0){
					return $compteur / $indice ;
				}else{return "pas d'exercice dans la banque de donnees";}
			}
			function MoyenneCorrecteur($nom_correcteur){
				$db = masterDB::getDB();
				$req = $db->prepare("SELECT mark FROM units WHERE id_corrector = $nom_correcteur");
				$req->execute();
				
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
				$req->execute();
				
				
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
			
			function getNombreCopies($totCopies){
				
				$nb_cop = $totCopies/5;
				$nb_cop = (int) $nb_cop;
				
				if ($nb_cop>10){
					return 10;
				}
				elseif($nb_cop == 0 and $totCopies!=0){return 1;}
				else{
					return $nb_cop ;
				}
			}

			function quartiles($values){

				sort($values);
				$count = count($values);
				$first = .25 * ( $count ) - 1;
				if (round($first, 0, PHP_ROUND_HALF_DOWN)==$first){
					$first = $values[$first];
				}
				else{
					$first=1/2*($values[$first]+$values[$first+1]);
				}
				$second = ($count % 2 == 0) ? ($values[($count / 2) - 1] + $values[$count / 2]) / 2 : $second = $values[($count) / 2];
				$third = .75 * ( $count );

				if (round($third, 0, PHP_ROUND_HALF_DOWN)==$third){
					$third = $values[$third];
				}
				else{
					$third=1/2*($values[$third]+$values[$third+1]);
				}
				return [$first,$third,$values[0],$values[$count-1],$second];
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
			
				if (isset($_POST['id_exo2'])){
			
					$db = masterDB::getDB();
					$req = $db->prepare("SELECT COUNT(mark) FROM units WHERE id_corrector = ? AND id_father = ?");
					$req->execute(array($_SESSION["id"], $_POST["id_exo2"]));
					$res = $req->fetch();
					
										
					$nb_copies = getNombreCopies($res[0]);
						
				
				
					?>
						
						
						<p> <?php
						echo "Votre moyenne glissante ";
						echo "sur ";
						echo $nb_copies;
						echo " copie(s) sur l'exercice " ;
						echo $_POST["id_exo2"];
						echo " est de : " ;
						echo MoyennePersoGlissante($nb_copies, $_POST["id_exo2"]);
						?>
						
						<p>
						<?php
						echo "\n La moyenne globale des correcteurs sur l'exercice " ;
						echo $_POST["id_exo2"];
						echo " est de : " ;
						echo MoyenneGlobale($_POST["id_exo2"]);?> 
						
						<?php
					
									
								?>	
				<?php 
				
				//Création du graph de la moyenne glissante
				
					$req = $db->prepare("SELECT mark FROM units WHERE id_father = ? AND id_corrector = ? ORDER BY date_modif DESC ");
					$req->execute(array($_POST['id_exo2'], $_SESSION["id"]));
					
					while($res = $req->fetch()){
						//echo $res[0];
						$notes[] = $res[0];
					}
					if (isset($notes)){
						$moyenneGlissante = [];
						for ($i = 0; $i < sizeof($notes); $i++) {
							$sum = 0;
							if ($i+$nb_copies<=sizeof($notes)){
								
								for ($j = 0; $j <$nb_copies; $j++){
									$sum += $notes[$i+$j];
								}
							}
							$moyenneGlissante[] = $sum/$nb_copies;
						}
						echo '<br/>';
						echo "Graphiques représentant votre moyenne glissante, la boîte à moustache caractérisant vos notes (au-dessus) et l'ensemble des notes (en-dessous) :";
						echo '<br/>';
						echo '<img src="../actions/graph.php?ydata1='.urlencode(serialize($moyenneGlissante)).'" border="0" alt="img.php" align="left">';
						//$test = [1,2,3,4,5,6,7,8,9,10,11,12];

						$req = $db->prepare("SELECT mark FROM units WHERE id_father = ? ORDER BY date_modif DESC ");
						$req->execute(array($_POST['id_exo2']));

						while($res = $req->fetch()){
							//echo $res[0];
							$notes_tot[] = $res[0];
						}

						$quartiles = array_merge([0,0,0,0,0], quartiles($notes), quartiles($notes_tot));

						echo '<img src="../actions/box.php?ydata1='.urlencode(serialize($quartiles)).'" border="0" alt="img.php" align="left">';

					}
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
						echo MoyenneTresGlobale();

						$req = $db->prepare("SELECT mark FROM units WHERE id_corrector = ? ORDER BY date_modif DESC ");
						$req->execute(array($_POST["id_correcteur"]));

						while($res = $req->fetch()){
							$notes[] = $res[0];
						}

						echo '<br/>';
						echo "Graphique représentant la boîte à moustache caractérisant les notes données par " .$_POST["id_correcteur"]. " (au-dessus) et l'ensemble des notes (en-dessous) :";
						echo '<br/>';


						$req = $db->prepare("SELECT mark FROM units ORDER BY date_modif DESC ");
						$req->execute();

						while($res = $req->fetch()){
							//echo $res[0];
							$notes_tot[] = $res[0];
						}

						$quartiles = array_merge([0,0,0,0,0], quartiles($notes), quartiles($notes_tot));

						echo '<img src="../actions/box.php?ydata1='.urlencode(serialize($quartiles)).'" border="0" alt="img.php" align="left">';



					?>

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
						echo MoyenneGlobale($_POST["id_exo3"]);

						$req = $db->prepare("SELECT mark FROM units WHERE id_father = ? AND id_corrector = ? ORDER BY date_modif DESC ");
						$req->execute(array($_POST["id_exo3"], $_POST["id_correcteur2"]));

						while($res = $req->fetch()){
							$notes[] = $res[0];
						}

						echo '<br/>';
						echo "Graphique représentant la boîte à moustache caractérisant les notes données par " .$_POST["id_correcteur2"]. " (au-dessus) et l'ensemble des notes (en-dessous) :";
						echo '<br/>';


						$req = $db->prepare("SELECT mark FROM units WHERE id_father = ? ORDER BY date_modif DESC ");
						$req->execute(array($_POST["id_exo3"]));

						while($res = $req->fetch()){
							$notes_tot[] = $res[0];
						}

						$quartiles = array_merge([0,0,0,0,0], quartiles($notes), quartiles($notes_tot));

						echo '<img src="../actions/box.php?ydata1='.urlencode(serialize($quartiles)).'" border="0" alt="img.php" align="left">';


						?>
						
						<?php
					}
									
								?>						
					
			
			
        </div>
    </div>
</body>
					