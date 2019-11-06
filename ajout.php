<?php
session_start();

require "function.php";

if (isset($_SESSION['id']) and !empty($_SESSION['id'])) {
    try {
        $import = false; // si true alors affichage d'une fenêtre importation dans la BDD réussi
        require "db_conn.php";

        if (isset($_POST['button'])) {
            if (!empty($_POST['categorie']) and !empty($_POST['titre']) and !empty($_POST['auteur'])) {
                date_default_timezone_set('Europe/Paris');

                $categorie = strtolower(replace_accents(htmlspecialchars($_POST['categorie'])));
                $titre = strtolower(htmlspecialchars($_POST['titre']));
                $auteur = strtolower(htmlspecialchars($_POST['auteur']));
                $date = date('Y-m-d H:i:s');
                $imagePath = "";

                if (isset($_FILES['image']) and !empty($_FILES['image'])) {
                    $tailleMax = 2097152; //2Mo
                    $extensionsValides = array('jpg', 'jpeg', 'png');
                    $extensionUpload = strtolower(substr(strrchr($_FILES['image']['name'], '.'), 1)); // renvoie l'extension avec le point donc substr enleve le point. strtolower met tout en minuscule

                    if ($_FILES['image']['size'] <= $tailleMax) {
                        if (in_array($extensionUpload, $extensionsValides)) {
                            if ($categorie == "livre") {
                                $imagePath = "public/images/livres/" . $categorie . "_" . $titre . '_' . $auteur . '_' . date('d-m-Y') . "_" . date('H-i-s') . "." . $extensionUpload;
                            }
                            if ($categorie == "serie") {
                                $imagePath = "public/images/series/" . $categorie . "_" . $titre . '_' . $auteur . '_' . date('d-m-Y') . "_" . date('H-i-s') . "." . $extensionUpload;
                            }
                            if ($categorie == "film") {
                                $imagePath = "public/images/films/" . $categorie . "_" . $titre . '_' . $auteur . '_' . date('d-m-Y') . "_" . date('H-i-s') . "." . $extensionUpload;
                            }

                            if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
                                $import = true;
                            } else {
                                $erreur = "Erreur lors de l'importation de l'image.";
                            }

                            $req = $db->prepare("INSERT INTO collections (categorie, titre, auteur,`image`, temps, email) VALUES (?, ?, ?, ?, ?, ?);");
                            $req->execute(array($categorie, $titre, $auteur, $imagePath, $date, $_SESSION['email']));
                            
                            $req->closeCursor();
                            if ($import) {
                                echo "<script>alert('Importation réussi !');</script>";
                                echo "<script>window.location.href = '/ajout.php?profil=" . $_SESSION['prenom'] . "%20" . $_SESSION['nom'] . "';</script>";
                            }
                        } else {
                            $erreur = "L'image doit être au format : jpg, jpeg, png";
                        }
                    } else {
                        $erreur = "L'image ne doit pas dépasser 2Mo !";
                    }
                } else {
                    $erreur = "Aucune image sélectionnée !";
                }
            } else {
                $erreur = "Tous les champs ne sont pas remplis !";
            }
        }
    } catch (PDOException $e) {
        print "Erreur !: " . $e->getMessage() . "<br/>";
        die();
    }


    ?>

    <!DOCTYPE html>
    <html>

    <!-- HEAD -->
    <?php require "head.php"; ?>

    <body>
        <!-- MENU -->
        <?php require "menu.php"; ?>


        <form id="form_ajout" action="" method="POST" enctype="multipart/form-data">
            <fieldset>
                <legend>Formulaire d'ajout</legend>
                <?php if (isset($erreur)) {
                        echo $erreur;
                        echo "<br>";
                    } ?>
                <div class="form-group">
                    <label for="categorie">Catégorie</label>
                    <select class="form-control" id="categorie" name="categorie">
                        <option>Livre</option>
                        <option>Série</option>
                        <option>Film</option>
                    </select>
                </div>
                <div class="form-group" style="margin-top:7.5%;">
                    <label for="titre">Titre</label>
                    <input type="text" class="form-control" id="titre" placeholder="Titre" name="titre" required>
                </div>
                <div class="form-group">
                    <label for="auteur">Auteur</label>
                    <input type="text" class="form-control" id="auteur" placeholder="Auteur" name="auteur" required>
                </div>
                <div class="form-group">
                    <label for="image">Choisir un fichier</label>
                    <input type="file" class="form-control-file" id="imgImp" name="image" aria-describedby="fileHelp" required>
                    <small id="fileHelp" class="form-text text-muted">Formats : jpg, jpeg, png</small>
                    <div style='width:100%; text-align:center;'>
                        <img src='#' id='blah' style='width:25%;  margin-top:1%; margin-bottom:1%;' />
                    </div>
                </div>
                <div style="display: flex; align-items: center;">
                    <input type="submit" class="btn btn-primary" name="button" data-toggle="modal" data-target="#exampleModalCenter" value="Ajouter">
                    <a class='nav-link' href="accueil.php?profil=<?= $_SESSION['prenom'] ?>%20<?= $_SESSION['nom'] ?>">Annuler<span class='sr-only'></span></a>

                </div>
            </fieldset>
        </form>

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

    <?php
    } else {
        header("Location: /connexion.php");
    } ?>
    </body>

    </html>