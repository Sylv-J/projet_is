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
	<form>
		<p>
			<label for="msg_object">De  </label>
			<input type="text" cols="50" name="msg_object" readonly="readonly" value = "<?php echo $d2["mfrom"]; ?>"><br/>
		</p>
		<br/>
		<p>
			<label for="msg_object">Objet  </label>
			<input type="text" cols="50" name="msg_object" readonly="readonly" value = "<?php echo $data["object"]; ?>"><br/>
		</p>
	</br>
		<p>
			<label for="msg_dests">Destinataire(s)  </label>
			<input type="text" name="msg_dests" readonly="readonly" value="<?php echo $d2["dest"] ?>"><br/>
		</p>
	</br>
		<p>
			<label for="msg_content">Message </label><br/>
			<textarea rows="10" cols="50" name="msg_content" readonly="readonly" > <?php echo $data["body"] ?></textarea>
		</p>
	</form>
</div>
<?php }} ?>
</div>
</div>
</body>
