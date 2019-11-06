<?php session_start();

try {
    require "db_conn.php";
    if (isset($_GET['id']) and !empty($_GET['id'])) {
        $query = $db->prepare("SELECT * FROM collections INNER JOIN identifiants ON collections.email = identifiants.email WHERE identifiants.id = ? ORDER BY collections.categorie ASC;");
        $query->execute(array($_GET['id']));
        $query_email = $query->fetch();

        $query_livre = $db->prepare("SELECT * FROM collections WHERE email = ? AND categorie = 'livre';");
        $query_livre->execute(array($query_email['email']));



        $query_serie = $db->prepare("SELECT * FROM collections WHERE email = ? and categorie = 'serie';");
        $query_serie->execute(array($query_email['email']));


        $query_film = $db->prepare("SELECT * FROM collections WHERE email = ? and categorie = 'film';");
        $query_film->execute(array($query_email['email']));
    }
} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br/>";
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
                        <?php if (isset($_SESSION['id']) and !empty($_SESSION['id']) and isset($_SESSION['prenom']) and isset($_SESSION['nom'])) {
                                echo "<a class='nav-link' href='/categorie.php?profil=" . $_SESSION['prenom'] . "%20" . $_SESSION['nom'] . "&amp;categorie=livre'>Mes Livres</a>";
                            } ?>
                    </li>
                    <li class="nav-item">
                        <?php if (isset($_SESSION['id']) and !empty($_SESSION['id']) and isset($_SESSION['prenom']) and isset($_SESSION['nom'])) {
                                echo "<a class='nav-link' href='/categorie.php?profil=" . $_SESSION['prenom'] . "%20" . $_SESSION['nom'] . "&amp;categorie=serie'>Mes Séries</a>";
                            } ?>
                    </li>
                    <li class="nav-item">
                        <?php if (isset($_SESSION['id']) and !empty($_SESSION['id']) and isset($_SESSION['prenom']) and isset($_SESSION['nom'])) {
                                echo "<a class='nav-link' href='/categorie.php?profil=" . $_SESSION['prenom'] . "%20" . $_SESSION['nom'] . "&amp;categorie=film'>Mes Films</a>";
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


    <div class="container-fluid" style="display:flex;padding:0;">
        <div class="index-profil" id="profil-utilisateur">
            <div class="profil-de">
                <h5>Profil de</h5>
            </div>
            <img src="<?= $query_email['photo'] ?>" alt=""><small><?= $query_email['prenom'] ?></small>
            <div class="index-choix">
                <img src=" public/images/message.png" alt="" data-target="#modalMessage" data-toggle="modal">
                <span data-target="#modalMessage" data-toggle="modal" class="btn-link" style="font-size:12px;cursor:pointer;">Envoyer un message</span>
            </div>
        </div>
        <div class="container bloc-profil" style="margin-left:0;" id="bibliotheque-utilisateur">
            <div class="row ">
                <article class="col-sm-12 col-md col-xl col-lg">
                    <h4 style="text-align:center; margin-top:2.5%;">Livres</h4>
                    <div style="text-align:center; padding:5px;">
                        <?php
                        while ($livre = $query_livre->fetch()) {
                            echo "<div class='article-bloc'><img src='" . $livre['image'] . "'></div>";
                        } ?>
                    </div>
                </article>
                <div class="separateur-utilisateur"></div>
                <article class="col-sm-12 col-md col-xl col-lg">
                    <h4 style="text-align:center; margin-top:2.5%;">Séries</h4>
                    <div style="text-align:center; padding:5px;">
                        <?php while ($serie = $query_serie->fetch()) {
                            echo "<div class='article-bloc'><img src='" . $serie['image'] . "'></div>";
                        } ?>
                    </div>
                </article>
                <div class="separateur-utilisateur"></div>
                <article class="col-sm-12 col-md col-xl col-lg">
                    <h4 style="text-align:center; margin-top:2.5%;">Films</h4>
                    <div style="text-align:center; padding:5px;">
                        <?php while ($film = $query_film->fetch()) {
                            echo "<div class='article-bloc'><img src='" . $film['image'] . "'></div>";
                        }
                        ?>
                    </div>
                </article>
            </div>
        </div>
    </div>




    <!-- SCRIPT -->
    <?php require "script.php"; ?>

    <!-- FOOTER -->
    <?php require "footer.php"; ?>

    <?php require "message-modal.php"; ?>