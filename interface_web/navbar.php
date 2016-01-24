
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php"><img src="image/MinesNancy_Logo.png" alt="logo mines" /></a>
        </div>
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li class="active"><a href="index.php">Accueil <span class="sr-only">(current)</span></a></li>
            </ul>
            <ul class="nav navbar-nav navbar-left">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Messagerie<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li>
                          <form action='index.php' method='post'>
                          <div class='list-group'>
                          <button type='submit' name='inbox' value='inbox' class='btn'>Boite de reception</button>
                          </div></form>
                        </li>
                        <li>
                          <form action='index.php' method='post'>
                          <div class='list-group'>
                          <button type='submit' name='sendbox' value='sendbox' class='btn'>Envoyer message</button>
                          </div></form>
                        </li>
                    </ul>
                </ul>
            </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="http://www.depinfonancy.net/" target="_blank">Dep'Info</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Mon Compte<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="index.php">Ma page</a></li>
						<li><a href="index.php">Aide</a></li>
                        <li role="separator" class="divider"></li>
						<li><a href="index.php">Paramètres</a></li>
                        <li>
                          <form action='index.php' method='post'>
                          <div class='list-group'>
                          <button type='submit' name='page_to_load' value='logout' class='btn'>Déconnexion</button>
                          </div></form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div>
</nav>
