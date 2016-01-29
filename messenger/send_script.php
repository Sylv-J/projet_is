
<?php
include_once("../master_db.php");
$db = masterDB::getDB();
?>
<!DOCTYPE html>
<html>
<body>
  <!-- Encodage de la page-->
  <meta charset="utf-8">
  <!-- Chargement du Javascript pour pouvoir l'utiliser après -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
</body>
</html>
<?php
// Si l'utilisateur clique sur le boutton envoyer on procède à l'envoi des messages
if(isset($_POST["submit"])){
  //header('P3P: CP="CAO PSA OUR"'); // Décommenter cette ligne si y a eu que le navigateur n'arrive pas à ouvrir une autre session.
  // Puisque ce script s'éxecute dans une iframe on doit "redémarrer" la session php.
  session_start();

  $check = 1; // Variable de controle si une erreur survient
  $date = date("Y-m-d H:i:s"); // On doit daté l'envoi/réception de messages
  $msg_object = $_POST["msg_object"];
  // Vérification si l'utilisateur a saisi un objet pour son message
  if(strlen($msg_object)==0){;
    $check = 0;
    ?>
    <script>
    $( document ).ready(function() {
      window.parent.$.notify("Veuillez spécifier un objet pour votre message ", "warn");
    });
    </script>
    <?php
  }
  $msg_dests = $_POST["msg_dests"];
  // Vérification si l'utilisateur a saisi au moins un destinataire dans le champs dédié
  if(strlen($msg_dests)==0){;
    $check = 0;
    ?>
    <script>
    $( document ).ready(function() {
      window.parent.$.notify("Veuillez spécifier au moins un destinataire pour votre message ", "warn");
    });
    </script>
    <?php
    exit();
  }
  else{
    // On enlève les eventuels espaces entrés par l'utilisateur lors de la
    // saisie des destinataires.
    $msg_dests = str_replace(' ', '', $msg_dests);
    $msg_dests_array = explode(";",$msg_dests);
    $nb_dest = count($msg_dests_array);
    // On parcourt la liste des destinataires et on vérifie qu'ils sont bien
    // dans notre base de données.
    for($i=0;$i<$nb_dest;$i++){
      $req = $db->prepare('SELECT username FROM `users` WHERE username=?');
      $req->execute(array($msg_dests_array[$i]));
      if(!$req->fetch()){
        $error_msg = "L'utilisateur '".$msg_dests_array[$i]."' est introuvable";
        $check = 0;
        ?>
        <script>
        $( document ).ready(function() {
          window.parent.$.notify("<?php echo $error_msg; ?>", "error");
        });
        </script>
        <?php
      }
    }
  }

  $msg_cc =$_POST["msg_cc"];
  // Si il y a des destinatiares en CC (Copie Carbone) on vérifie que ceux-ci
  // sont bien dans notre base de données
  if(strlen($msg_cc)!=0){
    $msg_cc = str_replace(' ', '', $msg_cc);
    $msg_cc_array = explode(";", $msg_cc);
    $nb_cc = count($msg_cc_array);
    // On parcourt la liste des destinatiares en CC
    for($i=0;$i<$nb_cc;$i++){
      $req = $db->prepare('SELECT username FROM `users` WHERE username=?');
      $req->execute(array($msg_cc_array[$i]));
      if(!$req->fetch()){
        $error_msg = "L'utilisateur '".$msg_cc_array[$i]."' est introuvable";
        $check = 0;
        ?>
        <script>
        $( document ).ready(function() {
          window.parent.$.notify("<?php echo $error_msg; ?>", "error");
        });
        </script>
        <?php
      }
    }
}else{
    $nb_cc = 0;
}


  $msg_content = $_POST["msg_content"];
  // Vérification si le message et non vide
  if($msg_content==""){;
    $check = 0;
    ?>
    <script>
    $( document ).ready(function() {
      window.parent.$.notify("Veuillez saisir un message ", "warn");
    });
    </script>
    <?php
    exit();
  }
  if($check){
    // On commence par insérer le message dans la table dédiée à contenir les messages
    $req = $db->prepare('INSERT INTO msg(object, body, date) VALUES(:object, :body, :date)');
    $res=$req->execute(array(
      'object' => $msg_object,
      'body' =>$msg_content ,
      'date'=>$date,
    ));
    if(!$res){
      $check = 0;
      $error_msg = "L'envoi du message a échoué. Merci de Réessayer.";
      exit();
      ?>
      <script>
      $( document ).ready(function() {
        window.parent.$.notify("<?php echo $error_msg;?>", "error");
      });
      </script>
      <?php
    }
    else{
      // On récupère l'indice du message qu'on vient d'insérer dans la table 'msg'
      $msg_id = $db->lastInsertId();
      // Boucle pour l'envoi aux utilisateurs en CC (Copie Carbone)
      for($i=0;$i<$nb_cc;$i++){
        $req = $db->prepare('INSERT INTO msg_link(mfrom, mto, dest, id_msg) VALUES(:mfrom, :mto, :dest, :id_msg)');
        $res = $req->execute(array(
          'mfrom' => $_SESSION["username"] ,
          'mto' =>$msg_cc_array[$i],
          'dest'=>$msg_cc,
          'id_msg'=>$msg_id,
        ));
        // Si un erreur parvient lors de l'envoi du message à un destinataire particulier on le signale à l'utilisateur
        if(!$res){
          $check = 0;
          $error_msg = "Une erreur est survenue lors de l'envoi à".$msg_cc_array[$i].". Merci de Réessayer.";
          ?>
          <script>
          $( document ).ready(function() {
            window.parent.$.notify("<?php echo $error_msg;?>", "error");
          });
          </script>
          <?php
        }
      }
      // Boucle pour l'envoi aux utilisateurs en CCi (Copie Carbone invisible)
      for($i=0;$i<$nb_dest;$i++){
        $req = $db->prepare('INSERT INTO msg_link(mfrom, mto, dest, id_msg) VALUES(:mfrom, :mto, :dest, :id_msg)');
        $res = $req->execute(array(
          'mfrom' => $_SESSION["username"] ,
          'mto' =>$msg_dests_array[$i],
          'dest'=>$msg_dests.';'.$msg_cc,
          'id_msg'=>$msg_id,
        ));
        // Si un erreur parvient lors de l'envoi du message à un destinataire particulier on le signale à l'utilisateur
        if(!$res){
          $check = 0;
          $error_msg = "Une erreur est survenue lors de l'envoi à".$msg_dests_array[$i].". Merci de Réessayer.";
          ?>
          <script>
          $( document ).ready(function() {
            window.parent.$.notify("<?php echo $error_msg;?>", "error");
          });
          </script>
          <?php
        }
      }
    }
    // Si on arrive à ce point c'est que les messages ce sont bien envoyés
    // Alors on fait un petit message pour notre utilisateur
    ?>
    <script>
    $( document ).ready(function() {
      window.parent.$.notify("Votre message a été bien envoyé !", "success");
    });
    </script>
    <?php
  }
}
?>
