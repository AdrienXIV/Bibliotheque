<?php

try {
    require "db_conn.php";

    if (isset($_POST['button']) and isset($_POST['texte']) and !empty($_POST['texte']) and isset($_SESSION['email'])) {

        date_default_timezone_set('Europe/Paris');

        $date = date('Y-m-d H:i:s');
        $texte = htmlspecialchars($_POST['texte']);

        $query_msg = $db->prepare("INSERT INTO messages (nom, prenom,email_destinataire, email_expediteur,`message`, `date`, vu) VALUES (?,?,?,?,?,?,?);");
        $query_msg->execute(array($_SESSION['nom'], $_SESSION['prenom'], $query_email['email'], $_SESSION['email'], $texte, $date, 0));
        $query_msg->closeCursor();
        echo "<script>alert('Message envoyé !');</script>";
        echo "<script>window.location.href = '/utilisateur.php?id=".$query_email['id']."';</script>";
    }
} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
}
?>

<!-- Modal -->
<div class="modal fade" id="modalMessage" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width:90%; padding: 20px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterDestinataire"><small>Destinataire : </small> <span><?= $query_email['prenom']; ?> <?= $query_email['nom']; ?></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span id="croix" aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="" method="POST">
                <textarea name="texte" id="textarea" cols="30" rows="10" required placeholder="Votre message"></textarea>
                <div class="modal-footer">
                    <button class="btn btn-outline-success" type="submit" name="button" id="btn">Envoyer</button>
                    <button type="button" class="btn btn-secondary" id="fermer" data-dismiss="modal">Fermer</button>
                </div>
            </form>
        </div>
    </div>
</div>
