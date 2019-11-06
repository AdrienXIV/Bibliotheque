<?php
try {
    require "db_conn.php";
      if (isset($_SESSION['id']) and !empty($_SESSION['id']) and isset($_GET['profil'])) {



    // echo $_GET['profil'];
    if (isset($_GET['categorie'])) {
      $query = $db->prepare("SELECT identifiants.id AS identifiants_id, collections.auteur, collections.categorie, collections.titre, collections.image, collections.temps, collections.email, collections.id FROM identifiants INNER JOIN collections ON identifiants.email = collections.email WHERE identifiants.id = ? AND collections.categorie = ?;");
      $query->execute(array($_SESSION['id'], $_GET['categorie']));

      if($_GET['categorie'] == "serie"){
        echo '<div class="jumbotron">';
        echo '<h1 class="display-5">' . 'séries' . '</h1>';
        echo '</div>';
      }else{
        echo '<div class="jumbotron">';
        echo '<h1 class="display-5">' . $_GET['categorie'] . 'S' . '</h1>';
        echo '</div>';
      }
      

      echo "<div>";
      $j = 0;
      while ($donnee = $query->fetch()) {
        $j++; // en début de boucle pour pouvoir entrer dans la condition == 1 
        if ($j == 1) {
          
          echo "<div class='card-block'>";
        }
        require "card.php";

        if ($j == 4) {
          echo "</div>";
          $j = 0;
        }
      }
      echo "</div>";
    }
  }

  require "modal_affiche.php";
} catch (PDOException $e) {
  print "Erreur !: " . $e->getMessage() . "<br/>";
  die();
}
?>

<script>
  var cardAffiche = document.getElementsByClassName('card-affiche');

  for (let i = 0; i < cardAffiche.length; i++) {
    cardAffiche[i].onclick = () => {
      let titre = document.getElementsByClassName('card-header')[i].textContent;
      let auteur = document.getElementsByClassName('card-title')[i].textContent;
      let image = document.getElementsByClassName('card-image')[i].src;
      let date = document.getElementsByClassName('card-footer')[i].textContent;

      let options = {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: 'numeric',
        minute: 'numeric',
        second: 'numeric'
      };

      date = new Date(date);
      date = date.toLocaleDateString('fr-FR', options)

      document.getElementById('exampleModalCenterTitle').innerText = titre;
      document.getElementById('auteur').innerText = auteur;
      document.getElementById('image').src = image;
      document.getElementById('date').innerText = "Ajouté le "+ date;
    };

  }
</script>