<?php
include_once("user_context.php");
if(isset($_SESSION["id"])){
	?>
	<p>
	Vous �tes connect� avec le nom <?php echo $_SESSION["username"] ?>
	et vous �tes un <?php echo $_SESSION["group"] ?>
	</p>
<?php
}
else {
	echo "Vous n'�tes pas connect�";
}
?>
