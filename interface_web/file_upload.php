
<html>
<head>
  <!--
  Changer l'encodage en utf-8 pour que les caractères spéciaux s'affichent.
-->
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta name="author" content="SAHLI Ayoub">
<!--  -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1/jquery.js"></script>
</head>
</html>

<?php
$dir = "../Images/"; // $dir contient le nom du dossier où seront uploader les images.
if(!is_dir($dir)){ // On fait un test pour savoir si le dossier exist déjà
  mkdir($dir); // S'il n'éxiste pas on le crée avec mkdir()
}
$file = $dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1; // Variable pour controler l'upload des fichiers
$imageFileType = $_FILES["fileToUpload"]["type"];
// Autoriser quelques types d'images seulement
if($imageFileType != "image/jpg" && $imageFileType != "image/png"
&& $imageFileType != "image/jpeg" && $imageFileType != "image/gif" ) {
  ?>
  <script>
  // Encapsulation du script dans $(document).ready(function()){} pour qu'il
  // s'exécute presque instantanément. Car par défaut les scripts en Javascript
  // ne s'exécute qu'après le chargement total de la page.
  // Cette remarque reste valable pour tous les scripts en Javascript qui suivent
  $( document ).ready(function() {
    // On utilise window.parent.$.notify pour afficher la notification sur
    // la page de la secrétaire et non sur l'iframe où s'exécute ce script php.
    // Cette remarque reste valable pour toute les notifications qui suivent
    // dans les différents cas.
    // Afficher le splashscreen pour notifier la secrétaire des formats acceptés
    window.parent.$.notify("Seul les fichiers de type image sont autorisé (JPG, JPEG, PNG et GIF)", "warn");
  });
  </script>
  <?php
  $uploadOk = 0; //Une erreur survient donc on met la variable de controle à zéro.
}
if(isset($_POST["submit"])) {
  $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
  /* Pour des raisons de sécurité on vérifie que le fichier sélectionner est bien
  une image avec la fonction getimagesize() qui retourne les dimensions ainsi que
  le type de l'image.
  */
  if($check == false) {
    ?>
    <script>
    $( document ).ready(function() {
      // afficher le splashscreen pour notifier la secrétaire des formats acceptés
      window.parent.$.notify("Seul les fichiers de type image sont autorisé (JPG, JPEG, PNG et GIF)", "warn");
    });
    </script>
    <?php
    $uploadOk = 0;
  }
  if ($uploadOk == 0) {
    ?>
    <script>
    $( document ).ready(function() {
      // afficher le splashscreen pour notifier la secrétaire que le fichier ne peut pas être envoyé.
      window.parent.$.notify("Désolé, votre fichier ne peut pas être envoyé !", "error");
    });
    </script>
    <?php
    // Si le fichier selectionner est bien une image on passe à la phase d'upload
  } else {
    /*
    move_uploaded_file permet de déplacer le fichier uploadé du dossier temp
    (premier paramètre passé à la fonction) vers le chemin passé en deuxième
    paramètre. Si une erreur survient la fonction retourne false.
    */
    // On test si le fichier existe déjà sur le serveur.
    $fileinfo = pathinfo($file);
    $uploadImageID = $dir . $_POST['epreuve']."_".$_POST['nb_exo']."_".$_POST['id_copie'].'.'.$fileinfo['extension'];
    if(!file_exists($uploadImageID)){
      // si le fichier n'existe pas sur le serveur on procède à l'upload.

      if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $uploadImageID)) {
        $msg = "Le fichier ". $fileinfo["filename"]. " a été bien envoyé.";
        ?>
        <script>
        // notifier la secrétaire du succès de l'upload du fichier.
        $( document ).ready(function() {
          window.parent.$.notify("<?php echo $msg; ?>", "success");
        });
        </script>
        <?php
      } else {
        $msg = "Désolé, le fichier ".$fileinfo["filename"]." n'a pas pu être envoyé. Veuillez réessayer";
        ?>
        <script>
        // notifier la secrétaire qu'il y a eu une erreur lors de l'upload.
        $( document ).ready(function() {
          window.parent.$.notify("<?php echo $msg; ?>", "error");
        });
        </script>
        <?php
      }
    }
    else{
      $msg = "Le fichier ". $fileinfo["filename"]. " existe déjà. Veuillez sélectionner un autre fichier";
      ?>
      <script>
      // notifier la secrétaire du succès de l'upload du fichier.
      $( document ).ready(function() {
        window.parent.$.notify("<?php echo $msg; ?>", "error");
      });
      </script>
      <?php
    }
  }
}
?>
