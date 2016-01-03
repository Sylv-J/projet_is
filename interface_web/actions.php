<!--Script php décidant du choix de a page perso à afficher -->
<?php
/*TEST
choisir un nombre entre 1 et 4 pour chnager l'affichage de la page
1 : Administrateur
2 : Secretaire
3 : Correcteur
4 : Chairman
*/
$test=3;
switch ($test) {
    case 1:
        include("roles/administrateur.php");
        break;
    case 2:
        include("roles/secretaire.php");
        break;
    case 3:
        include("roles/correcteur.php");
        break;
	case 4:
		include("roles/chairman.php");
		break;
}
?>
