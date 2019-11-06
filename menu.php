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
                        <a class="nav-link" href="/ajout.php?profil=<?=$_SESSION['prenom']."%20".$_SESSION['nom']?>">Ajouter<span class="sr-only"></span></a>
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

    <?php require "rubriques.php"; ?>