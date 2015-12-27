<?php
include_once("user_context.php");
if(isset($_SESSION["id"])){
	?>
	<p>
	Vous êtes connecté avec le nom <?php echo $_SESSION["username"] ?>
	et vous êtes un <?php echo $_SESSION["group"] ?>
	</p>
<?php
}
else {
	echo "Vous n'êtes pas connecté";
}
?>
