<div class="container">
    <!-- Example row of columns -->
    <?php
	$percentTotalCorrected = 0;
	$percentCorrectedByUser = 0;
	
    if ($_SESSION['group'] == 'correcteur'){
        $req = $db->prepare("SELECT COUNT(mark) FROM units WHERE id_corrector = ?");
        $req->execute(array($_SESSION["id"]));
        $res = $req->fetch();
        $unit_corrected=$res[0];

        $req = $db->prepare("SELECT units_remaining FROM users WHERE id = ?");
        $req->execute(array($_SESSION["id"]));
        $res = $req->fetch();
        $unit_remaining = $res[0];

        $percentCorrectedByUser = round($unit_corrected / ($unit_corrected+$unit_remaining)*100, 1);

        $req = $db->prepare("SELECT COUNT(mark) FROM units");
        $req->execute(array($_SESSION["id"]));
        $res = $req->fetch();
        $total_unit_corrected=$res[0];


        $req = $db->prepare("SELECT SUM(units_remaining) FROM users");
        $req->execute(array($_SESSION["id"]));
        $res = $req->fetch();
        $total_unit_remaining = $res[0];

        $percentTotalCorrected = round($total_unit_corrected / ($total_unit_corrected+$total_unit_remaining)*100, 1);

    }
    ?>
    <div class="row">
		<?php if ($_SESSION['group'] == 'correcteur'){ ?>
			<div class="col-md-4">
				<h2>Progression</h2>
				<h3>personnelle :</h3>
				<div class="progress">
					<div class="progress-bar" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $percentCorrectedByUser ?>%;">
						<?php echo $percentCorrectedByUser ?>%
					</div>
				</div>
				<h3>totale :</h3>
				<div class="progress">
					<div class="progress-bar" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $percentTotalCorrected ?>%;">
						<?php echo $percentTotalCorrected ?>%
					</div>
				</div>
			</div>
		 <?php } ?>
        <div class="col-md-4">
            <h2>Informations</h2>
            <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
            <p><a class="btn btn-default" href="#" role="button">View details &raquo;</a></p>
        </div>
        <div class="col-md-4">
            <h2>Dates clés</h2>
            <div class="list-group">
                <a href="#" class="list-group-item active">
                    <h4 class="list-group-item-heading">Mise en ligne des copies</h4>
                    <p class="list-group-item-text">O6/06/2017</p>
                </a>
                <a href="#" class="list-group-item">
                    <h4 class="list-group-item-heading">Achèvement des corrections</h4>
                    <p class="list-group-item-text">O6/06/2016</p>
                </a>
            </div>
        </div>
    </div>

    <hr>

    <footer>
        <p>&copy; 2015 Mines Nancy</p>
    </footer>
</div> <!-- /container -->
