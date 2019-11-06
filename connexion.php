<?php
session_start();

$temps = (60 * 60 * 12);

try {

    require "db_conn.php";

    if (isset($_POST['button_annuler'])) {
        header("Location: /");
    }
    if (isset($_POST['button'])) {
        if (!empty($_POST['email']) and !empty($_POST['password'])) {

            $email = htmlspecialchars($_POST['email']); // htmlspecialchars = evite l'injection d'html dans la variable
            $password = sha1($_POST['password']); // encode le mdp

            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

                $requser = $db->prepare("SELECT * FROM identifiants WHERE email = ? AND `password` = ? ");
                $requser->execute(array($email, $password));

                $user_existe = $requser->rowCount();

                //vérification si l'utilisateur existe dans la bdd
                if ($user_existe == 1) {
                    $user_info = $requser->fetch();

                    $_SESSION['id'] = $user_info['id']; // stocke l'id de la bdd correspondant à l'utilisateur dans une variable session
                    $_SESSION['email'] = $user_info['email']; // stocke le mail
                    $_SESSION['prenom'] = $user_info['prenom'];
                    $_SESSION['nom'] = $user_info['nom'];
                    $_SESSION['photo'] = $user_info['photo'];

                    $_SESSION['LAST'] = time(); // stocke l'heure actuelle à chaque clique sur un onglet qui a besoin de db_conn.php

                    $prenom = $user_info['prenom'];
                    $nom = $user_info['nom'];
                    setcookie("id", $user_info['id'], time() + $temps);
                    setcookie("email", $user_info['email'], time() + $temps);
                    setcookie("nom", $user_info['nom'], time() + $temps);
                    setcookie("prenom", $user_info['prenom'], time() + $temps);

                    header("Location: /accueil.php?profil=$prenom%20$nom");
                } else {
                    $erreur = "Courriel ou mot de passe invalides !";
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

<body>
    <!-- MENU -->
    <?php require 'menu.php'; ?>

    <!--   FORMULAIRE DE CHOIX   -->
    <div class="container center-div" id="form_connexion">
        <form action="" method="POST">
            <fieldset>
                <legend>Connexion</legend>
                <div class="form-group">
                    <label for="email">Courriel</label>
                    <input required type="text" name="email" id="email" class="form-control" placeholder="azerty@gmail.com" value="<?php if (isset($_COOKIE['email'])) {
                                                                                                                                        echo $_COOKIE['email'];
                                                                                                                                    } ?>">
                </div>
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input required type="password" name="password" id="password" class="form-control">
                    <?php if (isset($_POST['button']) and isset($erreur)) {
                        echo "<div class='div-erreur'>$erreur</div>";
                    }
                    ?>
                </div>
                <div class="margin-top"></div>
                <div style="display: flex; align-items: center;
  justify-content: center; margin-bottom:2.5%">
                    <input type="submit" value="Valider" name="button" class="btn btn-outline-success">
                    <a class='nav-link' href='/'>Annuler<span class='sr-only'></span></a>
                </div>
                <a class='link' href='/inscription.php' style="float:right;">S'inscrire<span class='sr-only'></span></a>
            </fieldset>
        </form>
    </div>

</body>

</html>