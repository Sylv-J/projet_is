<head>
	<link type="text/css" href="../messenger/css/messenger.css" rel="stylesheet">
</head>
<body>
	<div class="jumbotron">
		<div class="container">
			<?php
			include_once("../users/user_context.php");
			if(!isset($_SESSION["id"])){
				include("../messenger/error/err.php");
			}else{

				$limit = 7; // paramètre pour limiter l'affichage à 7 message par page
				$req = $db->query('SELECT * FROM msg_link l WHERE l.mto = '.'\''.$_SESSION['username'].'\'');
				$get_total = $req->rowCount(); // nombre total des message pour l'utilisateur qui est connecté
				$pages_count = ceil($get_total/$limit); // nombre total des pages
				if(isset($_POST["p"]) ){
					$page=$_POST["p"];
				}
				// on définit l'offset pour l'affichage en fonction de la page
				if($page<=0){
					$offset = 0;
				}
				else{
					$offset = ceil($page-1)*$limit;
				}
				$box = isset($_POST["box"]) ? $_POST["box"] : "inbox";
				$names = array("inbox" => "Boîte de réception", "sendbox" => "Boîte d'envoi");
				$requests = array(
					"inbox" => "SELECT m.id mid, l.id lid, l.mfrom c1, m.object obj, DATE_FORMAT(m.date,'%d/%m/%Y %H:%i') dated FROM msg_link l JOIN msg m ON m.id = l.id_msg WHERE l.mto = ? ORDER BY dated DESC LIMIT $offset,$limit",
					"sendbox" => "SELECT DISTINCT m.id id, l.id lid, l.dest c1, m.object obj, DATE_FORMAT(m.date,'%d/%m/%Y %H:%i') dated FROM msg_link l JOIN msg m ON m.id = l.id_msg WHERE l.mfrom = ? ORDER BY dated DESC LIMIT $offset,$limit"
				);

				//SQl requests
				$req = $db->prepare($requests[$box]);
				$req->execute(array($_SESSION["username"]));
				?>
				<h1><?php echo($names[$box]); ?></h1>
				<table class="mailbox" style="width:60%">
					<tr>
						<th ><?php echo($box = "inbox" ? "De" : "À:"); ?></th>
						<th >Objet</th>
						<th >Date</th>
					</tr>
					<?php if($data = $req->fetch()){
						do{?>
							<form id=<?php echo($data["mid"].$data["lid"]); ?> action="../interface_web/index.php" method="post">
								<input type="hidden" name="msg_read" value="msg_read">
								<input type="hidden" name="mid" value=<?php echo($data["mid"]); ?>>
								<input type="hidden" name="lid" value= <?php echo($data["lid"]); ?>>
							</form>
							<tr onclick="document.getElementById(<?php echo($data["mid"].$data["lid"]); ?>).submit()" onmouseover="style='background-color:#3498db'" onmouseout="style='background-color=#a0f9f9'">
								<td ><?php echo $data["c1"];  ?></td>
								<td ><?php  echo $data["obj"]; ?></td>
								<td ><?php  echo $data["dated"]; ?></td>
							</tr>
							<?php }while($data = $req->fetch());
							if($get_total>$limit){
								?>
								<tr>
									<td colspan="all">
										<div id='pages'>
											<?php
											for($i=1; $i<=$pages_count; $i++){
												?>
												<form class='pageform' action='index.php' method ='POST'>
													<input type='hidden' name='p' value="<?php echo $i ?>">
													<button  type='submit' name='inbox' value='inbox'><?php echo $i ?></button>
												</form>
												<?php
											}
											?>
										</div>
									</td>
								</tr>
								<?php
							}
						}else{ ?>
							<tr>
								<td colspan="3" style="text-align:center">Pas de messages !</td>
							</tr>
							<?php } ?>
						</table>
						<?php } ?>
					</div>
				</div>
			</body>
