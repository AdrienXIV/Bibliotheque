<?php session_start();

try {

    require "db_conn.php";

    if (isset($_SESSION['id']) and !empty($_SESSION['id'])) {

        date_default_timezone_set('Europe/Paris');

        $query = $db->prepare("SELECT * FROM identifiants;");
        $query->execute();
        if (isset($_POST['button_search']) and $_POST['email']) {
            $query_nom = $db->prepare("SELECT * FROM identifiants WHERE email = ?;");
            $query_nom->execute(array($_POST['email']));
            $req = $query_nom->fetch();
            $query_nom->closeCursor();
        }

        if (isset($_POST['button']) and isset($_POST['texte']) and !empty($_POST['texte']) and !empty($_POST['email_destinataire'])) {

            $date = date('Y-m-d H:i:s');
            $texte = htmlspecialchars($_POST['texte']);
            $msg = $db->prepare("INSERT INTO messages (nom, prenom,email_destinataire, email_expediteur,`message`, `date`, vu) VALUES (?,?,?,?,?,?,?);");
            $msg->execute(array($_SESSION['nom'], $_SESSION['prenom'], $_POST['email_destinataire'], $_SESSION['email'], $texte, $date, 0));
            $msg->closeCursor();
            echo "<script>alert('Message envoyé !');</script>";
            echo "<script>window.location.href = '/messagerie.php?profil=" . $_SESSION['prenom'] . "%20" . $_SESSION['nom'] . "';</script>";
        }
    }
} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
}

?>

<!-- HEAD -->
<?php require "head.php"; ?>

<!-- MENU -->
<?php require "menu.php"; ?>

<?php if (isset($_SESSION['id']) and !empty($_SESSION['id']) and isset($_GET['profil'])) { ?>


    <!--   FORMULAIRE DE CHOIX   -->
    <div class="container" id="form_inscription">
        <?php if (!isset($_POST['button_search'])) { ?>
            <form action="" method="POST">
                <fieldset>
                    <legend>Envoyer un message</legend>
                    <label for="nom">Destinataire</label>
                    <div class="form-group" style="display:flex; align-items:center;">
                        <select class="form-control" name="email">
                            <?php while ($donnee = $query->fetch()) {
                                        if ($donnee['email'] != $_SESSION['email'])
                                            echo "<option value='" . $donnee['email'] . "'>" . $donnee['prenom'] . " " . $donnee['nom'] . "</option>";
                                    } ?>
                        </select>
                        <input type="submit" value="Rechercher" name="button_search" class="btn btn-success" style="margin-left:1%; padding:10px 20px 10px 20px">
                        <?php echo "<a style='cursor:pointer;' class='nav-link btn btn-link' class='sr-only' href='/messagerie.php?profil=" . $_SESSION['prenom'] . "%20" . $_SESSION['nom'] . "'>Annuler</a>"; ?>

                    </div>
                </fieldset>
            </form>

        <?php }
            if (isset($_POST['button_search'])) { ?>
            <form action="" method="POST">
                <fieldset>
                    <div class="form-group">
                        <label for="nom">Destinataire</label>
                        <input class="form-control" type="text" readonly="readonly" id="nom" name="nom" value="<?= $req['prenom'] ?> <?= $req['nom'] ?>">
                    </div>
                    <div class="form-group" style="margin-top:2.5%;width:100%;">
                        <input class='form-control' readonly='readonly' name='email_destinataire' value=<?= $_POST['email'] ?>>
                    </div>
                    <div class="form-group">
                        <label for="message">Message</label>
                        <textarea required cols="30" rows="10" type="text" name="texte" id="message" class="form-control" placeholder="Votre message"></textarea>
                    </div>
                    <div style="display: flex; align-items: center;
  justify-content: center;">
                        <input type="submit" value="Valider" name="button" class="btn btn-outline-success">
                        <?php echo "<a class='nav-link' class='sr-only' href='/envoyer-message.php?profil=" . $_SESSION['prenom'] . "%20" . $_SESSION['nom'] . "'>Annuler</a>"; ?>

                    </div>
                </fieldset>
            </form>
        <?php } ?>
    </div>


<?php } else header("Location: connexion.php"); ?>
<!-- FOOTER -->
<?php require "footer.php"; ?>