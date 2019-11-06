<?php session_start();

try {
  require "db_conn.php";
  if (isset($_SESSION['id']) and !empty($_SESSION['id']) and isset($_GET['profil'])) {

    $query = $db->prepare("SELECT nom, prenom, email, photo FROM identifiants WHERE id = ?;");
    $query->execute(array($_SESSION['id']));
    $donnee = $query->fetch();

    $query->closeCursor();

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
  print "Erreur !: " . $e->getMessage() . "<br/>";
  die();
}

?>

<!-- HEAD -->
<?php require "head.php"; ?>

<!-- MENU -->
<?php require "menu.php"; ?>

<?php if (isset($_SESSION['id']) and !empty($_SESSION['id']) and isset($_GET['profil'])) { ?>

<div class="container-fluid">
  <div class="container bloc-profil" id="afficher-profil">
    <div class="row ">
      <article class="col-sm-12 col-md col-xl col-lg">
        <div id="photo">
          <a href="modifier-profil.php?profil=<?= $_SESSION['prenom'] ?>%20<?= $_SESSION['nom'] ?>">
            <img src="<?=$donnee['photo']?>" alt="">
          </a>
        </div>
        <h1><?= $donnee["prenom"] . " " . $donnee['nom']; ?></h1>
        <div class="modifier-profil">
          <?php if (isset($_SESSION['id']) and !empty($_SESSION['id']) and isset($_SESSION['prenom']) and isset($_SESSION['nom'])) {
              echo "<a href='/modifier-profil.php?profil=" . $_SESSION['prenom'] . "%20" . $_SESSION['nom'] . "'>Modifier le profil</a>";
            } else {
              header("Location: connexion.php");
            } ?>
        </div>
          <div class="separateur"></div>
          <h5 class="titre-profil-nombre">Nombres de livres, séries et films ajoutés</h5>
          <div style="display:flex;justify-content:center;align-items:flex-end;">
            <div class="bloc-rubrique"><img src="public/images/livre.png" alt=""><span><?= $livre; ?></span></div>
            <div class="bloc-rubrique"><img src="public/images/serie.png" alt=""><span><?= $serie; ?></span></div>
            <div class="bloc-rubrique"><img src="public/images/film.png" alt=""><span><?= $film; ?></span></div>
          </div>
        </div>
      </article>
    </div>
  </div>
</div>

<?php } else header("Location: connexion.php"); ?>
<!-- FOOTER -->
<?php require "footer.php"; ?>