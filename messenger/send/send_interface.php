<body>
	<div class="jumbotron">
		<div class="container">

<?php
include_once("../users/user_context.php");
if(!isset($_SESSION["id"])){
  include("../error/err.php");
}else{
  ?>
  <!DOCTYPE html>
  <html lang="fr">
  <head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1/jquery.js"></script>
    <!-- le script Javascript permettant l'affichage de splashscreen-->
    <script src="../interface_web/js/notify.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="js/ie10-viewport-bug-workaround.js"></script>
  </head>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../interface_web/image/MinesNancy.png">
		<!-- Alignement des cases sur la page-->
		<link type="text/css" href="../messenger/css/messenger.css" rel="stylesheet">
    <!-- Titre de la page -->
    <title>Messagerie</title>

  </head>
  <body>
	</br>
    <form class="sendbox" action="../messenger/send/send_script.php" method="post" enctype="multipart/form-data" target="send_script_frame">
      <p>
        <label for="msg_object">Objet : </label>
        <input id="msg_object" type="text" cols="50" name="msg_object"><br/>
      </p>
    </br>
      <p>
        <label for="msg_dests">Destinataire(s) : </label>
        <input id="msg_dests" type="text" name="msg_dests"><br/>
      </p>
    </br>
      <p>
        <label for="msg_content">Message : </label><br/>
        <textarea id="msg_content" rows="10" cols="50" name="msg_content" onfocus="show_button()"> Veuillez entrer votre message dans cette zone texte.</textarea>
      </p>
      <p>
        <input id ="submit" formmethod="post" name="submit" type="submit" value="Envoyer" disabled=true><br>
      </p>
			<input type="hidden" name='usergroup' value="<?php echo $_SESSION['group'];?>">
    </form>
		<?php
			if(isset($_POST["msg_object_res"])){
		 ?>
		 <script>
		 	document.getElementById("msg_object").setAttribute('value', "<?php echo $_POST["msg_object_res"];?>");
		 </script>
		 <?php
	 }
	 if (isset($_POST["msg_dests_res"])) {
		 ?>
 		<script>
 		 document.getElementById("msg_dests").setAttribute('value', "<?php echo $_POST["msg_dests_res"];?>");
 		</script>
 		<?php
	 }
		  ?>
    <script language="Javascript" type="text/Javascript">
      var ifrm = document.createElement("IFRAME");
      ifrm.setAttribute("id","send_script_frame");
      ifrm.setAttribute("name","send_script_frame");
      // les dimensions (ci-dessous) de l'iframe sont mise toutes à 0 pour
      // que cette iframe ne soit pas visible à l'utilisateur
      ifrm.style.width = "3000px";
      ifrm.style.height = "300px";
      ifrm.style.border = "0";
      // On ajoute cet iframe à la page actuelle.
      document.body.appendChild(ifrm);

      // Fonction pour "activer" le boutton 'Envoyer'
      function show_button(){
        var elem = document.getElementById('submit');
        elem.disabled=false;
      }
    </script>

  </html>

<?php } ?>
</div>
</div>
</body>
