<?php
include_once("../users/user_context.php");
if(!isset($_SESSION["id"])){
  include("err.php");
}else{
$box = isset($_POST["box"]) ? $_POST["box"] : "inbox";
$names = array("inbox" => "Boîte de réception", "sendbox" => "Boîte de réception");
?>

<!DOCTYPE html>
<head>
  <meta charset="utf-8">
  <title><?php echo($names[$box])?></title>
</head>
<body>
  <table id="mailbox" style="width:100%">
    <!-- TODO Add the function to read the messages and display them!-->
    test
  </table>
</body>
<?php } ?>
