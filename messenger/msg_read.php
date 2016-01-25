<body>
	<div class="jumbotron">
		<div class="container">
		<style>
		<?php include_once("css/messenger.css") ;?>
		</style>
		<?php
		include_once("../users/user_context.php");
		if(!isset($_SESSION["id"])){
			include("err.php");
		}else{
			$mid = isset($_POST["mid"]) ? $_POST["mid"] : "err";
			$mid = preg_match("#^[0-9]+$#", $mid) ? $mid : 0;
			$lid = isset($_POST["lid"]) ? $_POST["lid"] : "err";
			$lid = preg_match("#^[0-9]+$#", $lid) ? $lid : 0;
			$req = $db->prepare("SELECT * FROM msg_link WHERE (mfrom = :user OR mto = :user) AND id_msg = :mid AND id = :lid");
			$req->execute(array(
				"user" => $_SESSION["username"],
				"mid" => $mid,
				"lid" => $lid
			));
			if(!$req->fetch()){
				include("err_nomsg.php");
			}else{
				$req = $db->prepare("SELECT * FROM msg WHERE id = ?");
				$req->execute(array($mid));
				$data = $req->fetch();
				$req = $db->prepare("SELECT * FROM msg_link WHERE id = ?");
				$req->execute(array($lid));
				$d2 = $req->fetch();
				?>
				<div class="msgbox">
						<div>
						<h3>
							<?php echo $data["object"]; ?>
						</h3>
						<i>De :</i>
						<?php echo $d2["mfrom"]; ?>
						<i> &nbsp; À :</i>
						<?php echo $d2["dest"] ?>
					</div>
						<table>
							<tr>
								<td colspan="2">
									<textarea  rows="10" cols="100" style="width:100%" name="msg_content" readonly="readonly" style="" > <?php echo $data["body"] ?></textarea>
								</td>
							</tr>
							<tr>
								<td>
									<form action="../interface_web/index.php" method="post">
										<input type="hidden" name="sendbox" value="sendbox">
										<input type="hidden" name="msg_object_res" value=<?php echo("Re : ".$data["object"]); ?>>
										<input type="hidden" name="msg_dests_res" value=<?php echo($d2["mfrom"]); ?>>
										<button  name="respond"  type="submit" > Répondre</button>
									</form>
									<form>
										<input type="hidden" name="sendbox" value="sendbox">
										<input type="hidden" name="msg_object_res" value=<?php echo("Re : ".$data["object"]); ?>>
										<input type="hidden" name="msg_dests_res" value=<?php echo($d2["mfrom"].";".$d2["dest"]); ?>>
										<button name="respondToAll" type="submit" style="padding-left:5px">Répondre à tous</button>
									</form>
									</td>
								</tr>
							</table>
					</div>
					<?php }} ?>
				</div>
			</div>
		</body>
