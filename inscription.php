<?php
session_start();

$temps = (60 * 60 * 12);
try {
    require "db_conn.php";
    $import = false;
    if (isset($_POST['button'])) {
        if (!empty($_POST['email']) and !empty($_POST['password']) and !empty($_POST['nom']) and !empty($_POST['prenom'])) {
            $email = htmlspecialchars($_POST['email']);
            $prenom = htmlspecialchars($_POST['prenom']);
            $nom = htmlspecialchars($_POST['nom']);
            $password = sha1($_POST['password']); // encodage

            date_default_timezone_set('Europe/Paris');

            $date = date('Y-m-d H:i:s');
            $imagePath = "public/images/default.png";

            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $reqmail = $db->prepare('SELECT * FROM identifiants WHERE email=?');
                $reqmail->execute(array($email));
                $mail_existe = $reqmail->rowCount();

                if ($mail_existe == 0) {
                    if (isset($_FILES['image']) and !empty($_FILES['image'])) {
                        $tailleMax = 2097152; //2Mo
                        $extensionsValides = array('jpg', 'jpeg', 'png');
                        $extensionUpload = strtolower(substr(strrchr($_FILES['image']['name'], '.'), 1)); // renvoie l'extension avec le point donc substr enleve le point. strtolower met tout en minuscule

                        if ($_FILES['image']['size'] <= $tailleMax) {
                            if (in_array($extensionUpload, $extensionsValides)) {
                                $imagePath = "public/images/profils/" . $nom . "_" . $prenom . '_' . date('d-m-Y') . "_" . date('H-i-s') . "." . $extensionUpload;

                                if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
                                    $import = true;
                                } else {
                                    $erreur = "Erreur lors de l'importation de l'image.";
                                }
                            }
                        }
                    }

                    $query = $db->prepare("INSERT INTO identifiants (nom, prenom, email, `password`, `date`, photo) VALUES (?,?,?,?,?,?)");
                    $query->execute(array($nom, $prenom, $email, $password, $date, $imagePath));
                    $query->closeCursor();
                    $_SESSION['email'] = $email;

                    $req = $db->prepare("SELECT * FROM identifiants WHERE email=?;");
                    $req->execute(array($email));
                    $donnee = $req->fetch();
                    $_SESSION['id'] = $donnee['id'];
                    $_SESSION['nom'] = $donnee['nom'];
                    $_SESSION['prenom'] = $donnee['prenom'];
                    $_SESSION['photo'] = $donnee['photo'];

                    $_SESSION['LAST'] = time(); // stocke l'heure actuelle à chaque clique sur un onglet qui a besoin de db_conn.php

                    setcookie("id", $donnee['id'], time() + $temps);
                    setcookie("email", $email, time() + $temps);
                    setcookie("nom", $donnee['nom'], time() + $temps);
                    setcookie("prenom", $donnee['prenom'], time() + $temps);

                    if ($import) {
                        $erreur = "Profil crée avec succès !";
                    } else {
                        $erreur = "Profil avec une photo par défaut crée avec succès !";
                    }
                    header("refresh: 2; URL=/profil.php?profil=$prenom%20$nom");
                } else {
                    $erreur = "Adresse électronique déjà utilisée !";
                }
            } else {
                $erreur = "Email invalide !";
            }
        } else {
            $erreur = "Tous les champs doivent être complétés !";
        }
    }
} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
}
?>
<!DOCTYPE html>
<html lang="fr">

<!-- HEAD -->
<?php require "head.php"; ?>

<!-- MENU -->
<?php require "menu.php"; ?>

<!--   FORMULAIRE DE CHOIX   -->
<div class="container" id="form_inscription">
    <form action="" method="POST" enctype="multipart/form-data">
        <fieldset>
            <legend>Inscription</legend>
            <div class="form-group">
                <label for="nom">Nom</label>
                <input required type="text" name="nom" id="nom" class="form-control" placeholder="Votre nom" value="<?php if (isset($_SESSION['nom'])) {
                                                                                                                        echo $_SESSION['nom'];
                                                                                                                    } ?>">
            </div>
            <div class="form-group">
                <label for="prenom">Prénom</label>
                <input required type="text" name="prenom" id="prenom" class="form-control" placeholder="Votre prénom" value="<?php if (isset($_SESSION['prenom'])) {
                                                                                                                                    echo $_SESSION['prenom'];
                                                                                                                                } ?>">
            </div>
            <div class="form-group">
                <label for="email">Courriel</label>
                <input required type="text" name="email" id="email" class="form-control" placeholder="azerty@gmail.com" value="<?php if (isset($_SESSION['email'])) {
                                                                                                                                    echo $_SESSION['email'];
                                                                                                                                } ?>">
            </div>
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input required type="password" name="password" id="password" class="form-control">

            </div>
            <div class="form-group">
                <label for="image">Choisir un fichier</label>
                <input type="file" class="form-control-file" id="imgImp" name="image" aria-describedby="fileHelp">
                <small id="fileHelp" class="form-text text-muted">Formats : jpg, jpeg, png</small>
                <div style='width:100%; text-align:center;'>
                    <img src='#' id='blah' style='width:20%;  margin-top:1%; margin-bottom:1%;' />
                </div>
            </div>
            <div class="margin-top"></div>
            <?php if (isset($_POST['button']) and isset($erreur)) {
                echo "<div class='div-erreur'>$erreur</div>";
            }
            ?>
            <div style="display: flex; align-items: center;
      justify-content: center;">
                <input type="submit" value="Valider" name="button" class="btn btn-outline-success">
                <a class='nav-link' href='/'>Annuler<span class='sr-only'></span></a>
            </div>
        </fieldset>
    </form>
</div>

 <!-- SCRIPT -->
 <?php require "script.php"; ?>

<script>
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#blah').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#imgImp").change(function() {
        readURL(this);
    });
</script>


<?php require "footer.php"; ?>