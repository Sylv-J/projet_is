<!-- Ce bandeau apparait sur la page perso du correcteur après validation de la note -->
<!-- Permet de confirmer la prise en compte de la notation et de mettre à jour l unité de correction de la db -->

<body>
    <div class="jumbotron">
        <div class="container">

            <p>Votre notation a bien été prise en compte.</p>

            <?php
            include_once("../master_db.php");

            $db = masterDB::getDB();

            $req = $db->prepare("UPDATE units SET mark = ? WHERE id = ?");
            $req->execute(array($_POST["mark_submit"], $_POST["unit_id"]));

            ?>

        </div>
    </div>
</body>
