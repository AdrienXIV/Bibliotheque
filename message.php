<?php session_start();

try {
  require "db_conn.php";
  if (isset($_SESSION['id']) and !empty($_SESSION['id']) and isset($_GET['profil'])) {

    $query = $db->prepare("SELECT 
    messages.nom AS nom,
    messages.prenom AS prenom,
    identifiants.email AS email, 
    identifiants.photo AS photo, 
    messages.message AS `message`,
    messages.email_destinataire AS destinataire, 
    messages.email_expediteur AS expediteur,
    messages.date AS `date`, 
    messages.id AS id 
    FROM identifiants INNER JOIN messages ON identifiants.email = messages.email_destinataire WHERE identifiants.id = ? ORDER BY messages.id DESC;");
    $query->execute(array($_SESSION['id']));

    //$query->closeCursor();

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
  <div class="container bloc-profil">
    <div class="row ">
      <article class="col-sm-12 col-md col-xl col-lg">
        <div class="photo-profil" style="width:5rem; height:5rem;"></div>
        <h5 style="text-align:center;">Messages</h5>
        <?php
        if(isset($_POST['button']) and isset($_POST['texte']) and !empty($_POST['texte'])){
          echo "Message envoyé.";
        }
          $email_old = "";
          while ($donnee = $query->fetch()) {
            require "message-texte.php";
          }
          ?>
      </article>
    </div>
  </div>
</div>


<?php } else header("Location: connexion.php"); ?>

<!-- Modal -->
<div class="modal fade" id="modalAffiche" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
      </div>
    </div>
  </div>
</div>

<?php
if (isset($_POST['button']) and isset($_POST['texte']) and !empty($_POST['texte'])) {
 echo $_POST['texte'];
 echo $donnee['expediteur'];
 echo $_SESSION['email'];
 echo  $_SESSION['nom'];
 echo $_SESSION['prenom'];

}


?>

<?php require "message-modal.php"; ?>
<!-- FOOTER -->
<?php require "footer.php"; ?>

<?php require "script.php"; ?>

<script>
  var messageTexte = document.getElementsByClassName('message-texte');

  for (let i = 0; i < messageTexte.length; i++) {
    messageTexte[i].onclick = () => {
      //let photo = document.getElementsByClassName('photo-profil')[i].textContent;
      let destinataire = document.getElementsByClassName('destinataire')[i].textContent;
      let message = document.getElementsByClassName('message')[i].textContent;
      //let date = document.getElementsByClassName('card-footer')[i].textContent;

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

      document.getElementById('exampleModalCenterDestinataire').innerText = destinataire;
      document.getElementById('message').innerText = message;
      document.getElementById('image').src = image;
      document.getElementById('date').innerText = "Ajouté le " + date;

    };

    document.getElementById('fermer').onclick = () => {
      document.getElementById('textarea').value = "";
    }
    document.getElementById('croix').onclick = () => {
      document.getElementById('textarea').value = "";
    }
  }
</script>