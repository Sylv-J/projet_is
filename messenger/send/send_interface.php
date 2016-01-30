<head>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
</head>
<body>
	<div class="jumbotron">
		<div class="container">
			<?php
			include_once("../users/user_context.php");
			if(!isset($_SESSION["id"])){
				include("../error/err.php");
			}else{
				?>
				<center>
				<table class="sendbox">
				<form id="sendform" action="../messenger/send/send_script.php" method="post" enctype="multipart/form-data" target="send_script_frame">
					<tr>
						<td class="sendbox_titles">
							<h><b>Objet : </b></h>
						</td>
						<td class="sendbox_inputs">
							<input form="sendform" id="msg_object" type="text" name="msg_object">
						</td>
					</tr>
				<tr>
					<td class="sendbox_titles">
						<h>Destinataire(s) : </h>
					</td>
					<td class="sendbox_inputs">
						<input form="sendform" id="msg_dests" type="text" name="msg_dests" value="">
					</td>
				</tr>
			</br>
			<tr>
				<td colspan="3" class="sendbox_inputs">
					<textarea id="msg_content" rows="10" cols="130" style="width:100%" name="msg_content" placeholder=" Veuillez entrer votre message dans cette zone texte."onfocus="show_button()"></textarea>
				</td>
			</tr>
			<tr>
				<td>
				<button id ="submit" name="submit" type="submit" value="Envoyer" disabled=true>Envoyer</button>
			</td>
			</tr>
			<input type="hidden" form="sendform" name='usergroup' value="<?php echo $_SESSION['group'];?>">
		</form>
	</table>
</center>
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
		ifrm.style.width = "0px";
		ifrm.style.height = "0px";
		ifrm.style.border = "0";
		// On ajoute cet iframe à la page actuelle.
		document.body.appendChild(ifrm);

		// Fonction pour "activer" le boutton 'Envoyer'
		function show_button(){
			var elem = document.getElementById('submit');
			elem.disabled=false;
		}
		$(function(){
	    $('#msg_dests').tags({
				// requireData mis à true => l'utilisateur n'a pas droit d'entrer des
				// destinataires qui ne sont pas dans la liste data de la fonction
				// autofill ci-dessous (c.f. lignes 85-86).
				// unique : true => éviter la répetition des destinataires
				requireData:true,
				unique:true,

	    }).autofill({
				// data contient les destinataires que l'utilisateur peut entrer.
	      data:["admin","chairman","correcteurs","secretaire"],
	    });
	  });
		</script>
		<?php } ?>
	</div>
</div>
</body>
