<body>
	<div class="jumbotron">
		<div class="container">
<?php
include_once("../users/user_context.php");
if(!isset($_SESSION["id"])){
  include("../messenger/err.php");
}else{
$box = isset($_POST["box"]) ? $_POST["box"] : "inbox";
$names = array("inbox" => "Boîte de réception", "sendbox" => "Boîte d'envoi");
$requests = array(
  "inbox" => "SELECT m.id mid, l.id lid, l.mfrom c1, m.object obj, DATE_FORMAT(m.date,'%d/%m/%Y %H:%i') dated FROM msg_link l JOIN msg m ON m.id = l.id_msg WHERE l.mto = ? ORDER BY dated DESC",
  "sendbox" => "SELECT DISTINCT m.id id, l.id lid, l.dest c1, m.object obj, DATE_FORMAT(m.date,'%d/%m/%Y %H:%i') dated FROM msg_link l JOIN msg m ON m.id = l.id_msg WHERE l.mfrom = ? ORDER BY dated DESC"
);

//SQl requests
$req = $db->prepare($requests[$box]);
$req->execute(array($_SESSION["username"]));
?>
<style>
	<?php include_once("css/messenger.css") ;?>
</style>
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
