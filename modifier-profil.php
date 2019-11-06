<?php
session_start();
try {
    require "db_conn.php";

    if (isset($_POST['button'])) {

        if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $reqmail = $db->prepare('SELECT * FROM identifiants WHERE email=?');
            $reqmail->execute(array($_POST['email']));
            $donnee_photo = $reqmail->fetch();
            $mail_existe = $reqmail->rowCount();
            date_default_timezone_set('Europe/Paris');

            $date = date('Y-m-d H:i:s');

            if(isset($donnee_photo['photo'])){
                $imagePath = $donnee_photo['photo'];
            }
            if(isset($_POST['password']) and !empty($_POST['password'])){
                $password = sha1($_POST['password']); // encodage
            }else{
                $password = $donnee_photo['password'];
            }
            if ($mail_existe <= 1) {

                if (isset($_FILES['image']) and !empty($_FILES['image'])) {
                    if($donnee_photo['photo'] != "public/images/default.png"){
                        unlink(strval($donnee_photo['photo']));
                    }
                    $tailleMax = 2097152; //2Mo
                    $extensionsValides = array('jpg', 'jpeg', 'png');
                    $extensionUpload = strtolower(substr(strrchr($_FILES['image']['name'], '.'), 1)); // renvoie l'extension avec le point donc substr enleve le point. strtolower met tout en minuscule

                    if ($_FILES['image']['size'] <= $tailleMax) {
                        if (in_array($extensionUpload, $extensionsValides)) {
                            $imagePath = "public/images/profils/" . $_POST['nom'] . "_" . $_POST['prenom'] . '_' . date('d-m-Y') . "_" . date('H-i-s') . "." . $extensionUpload;

                            if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
                                $import = true;
                            } else {
                                $erreur = "Erreur lors de l'importation de l'image.";
                            }
                        }
                    }
                }
                
                $query = $db->prepare("UPDATE identifiants SET nom = ?, prenom=?, email=?, `password`=?, photo=? WHERE id = ?");
                $query->execute(array($_POST['nom'], $_POST['prenom'], $_POST['email'], $password, $imagePath, $_SESSION['id']));
                $query->closeCursor();

                $req = $db->prepare("SELECT * FROM identifiants WHERE email=?;");
                $req->execute(array($_POST['email']));
                $donnee = $req->fetch();
                $_SESSION['id'] = $donnee['id'];
                $_SESSION['nom'] = $donnee['nom'];
                $_SESSION['prenom'] = $donnee['prenom'];
                $_SESSION['photo'] = $donnee['photo'];
                
                $erreur = "Modification réussi !";

                header("refresh: 2; URL=/profil.php?profil=" . $donnee['prenom'] . "%20" . $donnee['nom'] . "");
            } else {
                $erreur = "Adresse électronique déjà utilisée !";
            }
        } else {
            $erreur = "Email invalide !";
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
            <legend>Modifier le profil</legend>
            <div class="form-group">
                <label for="nom">Nom</label>
                <input type="text" name="nom" id="nom" class="form-control" placeholder="Votre nom" value="<?php if (isset($_SESSION['nom'])) {
                                                                                                                echo $_SESSION['nom'];
                                                                                                            } ?>">
            </div>
            <div class="form-group">
                <label for="prenom">Prénom</label>
                <input type="text" name="prenom" id="prenom" class="form-control" placeholder="Votre prénom" value="<?php if (isset($_SESSION['prenom'])) {
                                                                                                                        echo $_SESSION['prenom'];
                                                                                                                    } ?>">
            </div>
            <div class="form-group">
                <label for="email">Courriel</label>
                <input type="text" name="email" id="email" class="form-control" placeholder="azerty@gmail.com" value="<?php if (isset($_SESSION['email'])) {
                                    echo $_SESSION['email'];
                                }?>">
            </div>
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" name="password" id="password" class="form-control">

            </div>
            <div class="form-group">
                <label for="image">Choisir un fichier</label>
                <input type="file" class="form-control-file" id="imgImp" name="image" aria-describedby="fileHelp" value="">
                <small id="fileHelp" class="form-text text-muted">Formats : jpg, jpeg, png</small>
                <div style='width:100%; text-align:center;'>
                    <img src="<?php if (isset($_SESSION['email'])) {
                                    echo $_SESSION['photo'];
                                }else echo '#'; ?>" id='blah' style='width:20%;  margin-top:1%; margin-bottom:1%;' />
                </div>
            </div>
            <?php if (isset($_POST['button']) and isset($erreur)) {
                echo "<div class='div-erreur'>$erreur</div>";
            }
            ?>
            <div class="margin-top"></div>
            <div style="display: flex; align-items: center;
  justify-content: center;">
                <input type="submit" value="Valider" name="button" class="btn btn-outline-success">
                <a class='nav-link' <?php echo "href='/profil.php?profil=" . $_SESSION['prenom'] . "%20" . $_SESSION['nom'] . "'"; ?>>Annuler<span class='sr-only'></span></a>
            </div>
        </fieldset>
    </form>
</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

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