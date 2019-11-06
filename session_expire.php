<?php


$expiry = 60*30 ; // la session expire après 1h d'inactivité
if (isset($_SESSION['LAST']) && (time() - $_SESSION['LAST'] > $expiry)) { // si l'heure actuelle moins l'heure stockée est supérieur à 1h alors déconnexion
    session_unset();
    session_destroy();
    echo "<script>window.location.href = '/connexion.php';</script>";

}
$_SESSION['LAST'] = time(); // stocke l'heure actuelle à chaque clique sur un onglet qui a besoin de db_conn.php