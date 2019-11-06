<?php
try {
    require "db_conn.php";

    if (isset($_POST['id'])) {

        $vu = $db->prepare("UPDATE messages SET vu = 1 WHERE id = ?;");
        $vu->execute(array($_POST['id']));
        $vu->closeCursor();
    }
} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
}
