
<html>
<head>
  <!--
  Changer l'encodage en utf-8 pour que les caractères spéciaux s'affichent.
-->
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<!--  -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1/jquery.js"></script>
<!-- le javascript permettant l'affichage de splashscreen-->
<script src="./js/notify.js"></script>
</head>
<!-- INSERT INTO `projetis`.`units` (`id`, `id_father`, `id_sons`, `data`, `level`, `mark`, `id_corrector`, `date_modif`) VALUES ('52', NULL, NULL, NULL, NULL, NULL, NULL, NULL); -->

<?php
// elements nécessaires pour faire la mise à jour de la base de données
include_once("../master_db.php");
include_once("./multiplefile_upload_DB.php");

$acf =  $_POST ["anneeconcoursfiliere"];
$epreuve = $_POST["epreuve"];

$dir = "../images/$acf/$epreuve/"; // $dir contient le nom du dossier où seront uploader les images.
if(!is_dir($dir)){ // On fait un test pour savoir si le dossier existe déjà
  $msg = "Il y a un problème avec l'inscritption de cet élève.";
        ?>
        <script>
        // notifier la secrétaire que le dossier n'existe pas (càd que cet élève n'est pas inscrit à ce concours/epreuve.
        $( document ).ready(function() {
          window.parent.$.notify("<?php echo $msg; ?>", "error");
        });
        </script>
        <?php
}
if (isset($_FILES['my_files'])) {
  $myFiles = $_FILES['my_files'];
  $fileCount = count($myFiles["name"]);
}

for ($i = 0; $i < $fileCount; $i++) {

  $file = $dir . basename($myFiles["name"][$i]);
  $uploadOk = 1; // Variable pour controler l'upload des fichiers
  $imageFileType = $myFiles["type"][$i];

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
  else{
    $check = getimagesize($myFiles["tmp_name"][$i]);
    /* Pour des raisons de sécurité on vérifie que le fichier sélectionner est bien
    une image avec la fonction getimagesize() qui retourne les dimensions ainsi que
    le type de l'image.
    */
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
      $id_copie= $_POST ["id_copie"];

      $fileinfo = pathinfo($file);
      // On cherche une entrée libre sur la table "units" dans notre BDD et
      // on récupère son id
      // on ajoute i afin de differencier les differentes copies !
      $idUnite = $id_copie.$i;
      // On fait en sorte que l'id soit le nom de l'unité sur le dossier /Images
      $uploadImageID = $dir.$idUnite.'.'.$fileinfo['extension'];
      if(!file_exists($uploadImageID)){
        // si le fichier n'existe pas sur le serveur on procède à l'upload.
        if (move_uploaded_file($myFiles["tmp_name"][$i], $uploadImageID)) {
          // On met à jour la base de donnée en ajoutant l'unité à l'id récupéré
          // par find_unset_entry
          updateDB($idUnite);
          
          $udc = UniteDeCorrection::fromID($idUnite); //on crée en local l'unité de correction correspondant au barême qui vient d'être crée
          $udc->upload(); // on l'upload sur la BDD 

          $msg = "le fichier ". $fileinfo["filename"]. " a été bien envoyé.";
          ?>
          <script>
          // notifier la secrétaire du succès de l'upload du fichier.
          $( document ).ready(function() {
            window.parent.$.notify("<?php echo $msg; ?>", "success");
          });
          </script>
          <?php
        } else {
          $msg = "Désolé, le fichier ".$fileinfo["filename"]." n'a pas pu être envoyé.
          Veuillez réessayer";
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
        // notifier la secrétaire que le fichier existe déjà
        $( document ).ready(function() {
          window.parent.$.notify("<?php echo $msg; ?>", "error");
        });
        </script>
        <?php
      }
    }
  }
}
?>
</html>
