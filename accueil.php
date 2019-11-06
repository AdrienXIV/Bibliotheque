<?php session_start();

try {
    require "db_conn.php";

    $query = $db->prepare("SELECT * FROM identifiants ORDER BY nom;");
    $query->execute();




    if (isset($_SESSION['id']) and !empty($_SESSION['id']) and isset($_GET['profil'])) {

        $req = $db->prepare("SELECT nom, prenom, email, photo FROM identifiants WHERE id = ?;");
        $req->execute(array($_SESSION['id']));
        $donnee = $req->fetch();

        $req->closeCursor();

        $query_livre = $db->prepare("SELECT * FROM collections WHERE email = ? AND categorie = 'livre';");
        $query_livre->execute(array($donnee['email']));
        $livre = $query_livre->rowCount();
        $query_livre->closeCursor();


        $query_serie = $db->prepare("SELECT categorie FROM collections WHERE email = ? and categorie = 'serie';");
        $query_serie->execute(array($donnee['email']));
        $serie = $query_serie->rowCount();
        $query_serie->closeCursor();

        $query_film = $db->prepare("SELECT categorie FROM collections WHERE email = ? and categorie = 'film';");
        $query_film->execute(array($donnee['email']));
        $film = $query_film->rowCount();
        $query_film->closeCursor();
    }
} catch (PDOException $e) {

    print "Erreur :". $e->getMessage() . "<br/>"; 

    die();
}

?>

<!-- HEAD -->
<?php require "head.php"; ?>

<!-- MENU -->

<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <?php if (isset($_SESSION['id']) and !empty($_SESSION['id']) and isset($_SESSION['prenom']) and isset($_SESSION['nom'])) {
            
            echo "<a class='navbar-brand' href='/profil.php?profil=" . $_SESSION['prenom'] . "%20" . $_SESSION['nom'] . "'>Mon profil</a>";
        } else {
            echo "<a class='navbar-brand' href='/accueil.php'>Accueil</a>";
        } ?>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarColor01">
            <ul class="navbar-nav mr-auto">
                <?php if (isset($_SESSION['id']) and !empty($_SESSION['id'])) { ?>
                    <li class="nav-item">
                        <?php echo "<a class='nav-link' href='/accueil.php?profil=" . $_SESSION['prenom'] . "%20" . $_SESSION['nom'] . "'" . ">Accueil<span class='sr-only'></span></a>"; ?>
                    </li>
                    <li class="nav-item">
                        <?php echo "<a class='nav-link' href='/messagerie.php?profil=" . $_SESSION['prenom'] . "%20" . $_SESSION['nom'] . "'" . ">Messagerie<span class='sr-only'></span></a>"; ?>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/ajout.php?profil=<?= $_SESSION['prenom'] . "%20" . $_SESSION['nom'] ?>">Ajouter<span class="sr-only"></span></a>
                    </li>
                    <li class="nav-item">
                        <?php if (isset($_SESSION['id']) and !empty($_SESSION['id']) and isset($_SESSION['prenom']) and isset($_SESSION['nom'])) {
                                echo "<a class='nav-link' href='/categorie.php?profil=" . $_SESSION['prenom'] . "%20" . $_SESSION['nom'] . "&amp;categorie=livre'>Livre</a>";
                            } ?>
                    </li>
                    <li class="nav-item">
                        <?php if (isset($_SESSION['id']) and !empty($_SESSION['id']) and isset($_SESSION['prenom']) and isset($_SESSION['nom'])) {
                                echo "<a class='nav-link' href='/categorie.php?profil=" . $_SESSION['prenom'] . "%20" . $_SESSION['nom'] . "&amp;categorie=serie'>Série</a>";
                            } ?>
                    </li>
                    <li class="nav-item">
                        <?php if (isset($_SESSION['id']) and !empty($_SESSION['id']) and isset($_SESSION['prenom']) and isset($_SESSION['nom'])) {
                                echo "<a class='nav-link' href='/categorie.php?profil=" . $_SESSION['prenom'] . "%20" . $_SESSION['nom'] . "&amp;categorie=film'>Film</a>";
                            } ?>
                    </li>
                <?php }
                ?>
            </ul>
            <?php if (!(isset($_SESSION['id']) and !empty($_SESSION['id']))) {
                echo "<a class='nav-link' href='/connexion.php'>Se connecter</a>";
                echo "<div class='compte'>";
                echo "<a class='nav-link' href='/inscription.php'>S'inscrire<span class='sr-only'></span></a>";
                echo "</div>";
            }
            ?>

            <div class="compte">
                <?php if (isset($_SESSION['id']) and !empty($_SESSION['id'])) {
                    echo "<a class='nav-link' href='/deconnexion.php'>Se déconnecter</a>";
                } else {
                    echo "<a class='nav-link' href='/connexion.php'>Se connecter</a>";
                }
                ?>
            </div>
        </div>
    </nav>

    <!-- Si la personne est connecté -->

    <?php if (isset($_SESSION['id']) and !empty($_SESSION['id'])) { ?>
        <div class="container-fluid" style="display:flex;padding:0;">
            <div class="index-profil">
                <a href="profil.php?profil=<?= $_SESSION['prenom'] ?>%20<?= $_SESSION['nom'] ?>">
                    <img src="<?= $donnee['photo'] ?>" alt=""> <small><?= $donnee['prenom'] ?></small>
                </a>
                <div class="index-choix">
                    <?php if (isset($_SESSION['id']) and !empty($_SESSION['id']) and isset($_SESSION['prenom']) and isset($_SESSION['nom'])) {
                            echo "<a href='/categorie.php?profil=" . $_SESSION['prenom'] . "%20" . $_SESSION['nom'] . "&amp;categorie=livre'>";
                            ?>
                        <img src="public/images/livre.png" alt=""><span><?= $livre; ?></span></a>
                    <?php } ?>
                    <?php if (isset($_SESSION['id']) and !empty($_SESSION['id']) and isset($_SESSION['prenom']) and isset($_SESSION['nom'])) {
                            echo "<a href='/categorie.php?profil=" . $_SESSION['prenom'] . "%20" . $_SESSION['nom'] . "&amp;categorie=serie'>";
                            ?>
                        <img src="public/images/serie.png" alt=""><span><?= $serie; ?></span></a>
                    <?php } ?>
                    <?php if (isset($_SESSION['id']) and !empty($_SESSION['id']) and isset($_SESSION['prenom']) and isset($_SESSION['nom'])) {
                            echo "<a href='/categorie.php?profil=" . $_SESSION['prenom'] . "%20" . $_SESSION['nom'] . "&amp;categorie=film'>";
                            ?>
                        <img src="public/images/film.png" alt=""><span><?= $film; ?></span></a>
                    <?php } ?>

                </div>
            </div>
            <div class="container bloc-profil" style="margin-left:0;">
                <div class="row ">
                    <article class="col-sm-12 col-md col-xl col-lg">
                        <h3 style="margin:2.5% 0 2.5% 0">Rechercher un utilisateur</h3>
                        <?php while ($donnee = $query->fetch())
                                if ($donnee['id'] != $_SESSION['id']) { { ?>
                                <div class="utilisateurs">
                                    <a style="width:100%;" href="/utilisateur.php?id=<?= $donnee['id'] ?>"><img src="<?= $donnee['photo'] ?>" alt="photo de profil"><span><?= $donnee['prenom'] ?> <?= $donnee['nom'] ?></span>
                                    </a>
                                </div> <?php }
                                            } ?>
                    </article>
                </div>
            </div>
        </div>

        <!-- Si la personne n'est pas connecté -->

    <?php } else { ?>
        <div class="container bloc-profil">
            <div class="row ">
                <article class="col-sm-12 col-md col-xl col-lg">
                    <h3 style="margin:2.5% 0 2.5% 0">Utilisateurs inscrits</h3>
                    <?php while ($donnee = $query->fetch()) { ?>
                        <div class="utilisateurs">
                            <a style="width:100%;" href="/utilisateur.php?id=<?= $donnee['id'] ?>"><img src="<?= $donnee['photo'] ?>" alt="photo de profil"><span><?= $donnee['prenom'] ?> <?= $donnee['nom'] ?></span>
                            </a>
                        </div>
                    <?php
                        } ?>
                </article>
            </div>

        </div>
        </div>
    <?php } ?>



    <!-- SCRIPT -->
    <?php require "script.php"; ?>

    <!-- FOOTER -->
    <?php require "footer.php"; ?>