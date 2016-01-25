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
<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<link rel= "stylesheet" type="text/css" href="css/messenger.css">
	<style>
		<?php include_once("css/messenger.css") ;?>
	</style>

	<title><?php echo($names[$box]);?></title>
</head>
<body>
<h1><?php echo($names[$box]); ?></h1>
<table id="mailbox" style="width:60%">
  <tr>
    <th id="mailbox_label"><?php echo($box = "inbox" ? "De" : "À:"); ?></th>
    <th id="mailbox_label">Objet</th>
    <th id="mailbox_label">Date</th>
  </tr>
  <?php if($data = $req->fetch()){
    do{?>
    <tr id="mailbox_row" onclick="location.href='../messenger/msg_read.php?<?php echo("mid=".$data["mid"]."&lid=".$data["lid"]); ?>'" onmouseover="style='background-color:#3498db'" onmouseout="style='background-color=#a0f9f9'">
      <td id="mailbox_case"><?php echo $data["c1"];  ?></td>
      <td id="mailbox_case"><?php  echo $data["obj"]; ?></td>
      <td id="mailbox_case"><?php  echo $data["dated"]; ?></td>
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
