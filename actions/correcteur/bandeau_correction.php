<!-- Ce bandeau s affiche lorsqu un correcteur clique sur le bouton corriger de sa page personnelle -->
<!-- Le but est d afficher une unité de correction pas encore assignée et de permettre au correcteur de l évaluer -->

<body>
    <div class="jumbotron">
        <div class="container">

            <?php
            //Connection à la db
            include_once("../master_db.php");
            $db = masterDB::getDB();


            //Recherche dans la db d'une unité de correction non assignée
            $req = $db->prepare('SELECT current_units FROM users WHERE id = "'.$_SESSION["id"].'"');
            $req->execute();
            $res = $req->fetch();

            //Si une unité n'est pas assignée...
            if ($res[0]!=''){

                $id = $res[0]; ?>


                <div class="col-md-6">

                    <h2>À corriger :</h2>


                    <?php
                    $path=explode('_',$id);
                    //Affichage de l'unité de correction
                    echo '<img src="../images/'.$path[0].'/'.$path[1].'/'.$path[2].'/'.$id.'.jpg" alt="'.$id.'" />';
                    ?>

                </div>

                <div class="col-md-4">

                    <!-- Affichage du formulaire d evaluation -->

                    <h2>Évaluation : </h2>

                    <form method="post" action= "index.php" role="form">

                        <div class="form-group">
                            <input type="number" placeholder="Note" name="mark_submit" class="form-control" required>
                        </div>
                        <?php
                            //Nécessaire pour mettre la note dans la db
                            echo '<input type="hidden" name="unit_id" value='.$id.'>';
                        ?>
                        <input type="submit" value="Valider" class="btn btn-success">

                    </form>

                </div>


                <?php
                //Mise à jour de la db avec l id du correcteur

                $req = $db->prepare("SELECT id FROM users WHERE username = ?");
                $req->execute(array($_SESSION["username"]));
                $res = $req->fetch();

                $req = $db->prepare("UPDATE units SET id_corrector = ? WHERE id = ?");
                $req->execute(array($res[0], $id));
            }

            //Si toutes les unités sont assignées...
            else{ ?>
                <div class="col-md-10">
                    <p> Toutes les unités sont déjà assignées. </p>
                </div>

            <?php
            }
            ?>

        </div>
    </div>
</body>
