<!-- Bandeau contenant 3 champs : Epreuve, Nombre d'exercices, Identifiant de la copie -->
	<div class="jumbotron">
		<div class="container">
			<form action= "../database_request/multiplefile_upload.php" method="post" enctype="multipart/form-data" target="upload_iframe">
				<br/><br/><br/><br/>
				<p>
					<label for="fileToUpload">Ajouter image :</label>
					<input id="file" type="file" name="my_files[]" multiple><br/><br/><br/>
				</p>
				<p>
					<label for="input_epreuve">AnnéeConcoursFilière : </label>
					<input id="input_epreuve" type="text" name="anneeconcoursfiliere"><br/><br/><br/>
				</p>
				<p>
					<label for="input_epreuve">Epreuve : </label>
					<input id="input_epreuve" type="text" name="epreuve"><br/><br/><br/>
				</p>
				<p>
					<label for="input_nb_exo">Nombre d'exercices: </label>
					<input id="input_nb_exo" type="text" name="nb_exo"><br/><br/><br/>
				</p>
				<p>
					<label for="input_id_copie">Identifiant de la copie : </label>
					<input id="input_id_copie" type="int" name="id_copie"><br/><br/>
				</p>
				<p>
					<input formmethod="post" name="submit" type="submit" value="Soumettre" align ="right"><br>
				</p>
			</form>
			<!--  Créer un iframe (sorte de page intégrée dans la page actuelle) pour
      			éxecuter le code php sans avoir à rafraichir la page.
			-->
			<script language="Javascript" type="text/Javascript">
				var ifrm = document.createElement("IFRAME");
				ifrm.setAttribute("id","upload_iframe");
				ifrm.setAttribute("name","upload_iframe");
				// les dimensions (ci-dessous) de l'iframe sont mise toutes à 0 pour
				// que cette iframe ne soit pas visible à l'utilisateur (la secrétaire)
				ifrm.style.width = "0px";
				ifrm.style.height = "0px";
				ifrm.style.border = "0";
				// On ajoute cet iframe à la page actuelle.
				document.body.appendChild(ifrm);
			</script>
		</div>
	</div>
