<?php session_start();

try {
  require "db_conn.php";

  if (isset($_POST['supprimer']) and isset($_POST['checkbox'])) {

    $array = implode("','", $_POST['checkbox']);

    $sup = $db->prepare("DELETE FROM messages WHERE id IN ('" . $array . "');");
    $sup->execute();
    $sup->closeCursor();

    echo "<script>window.location.href = '/messagerie.php?profil=" . $_SESSION['prenom'] . "%20" . $_SESSION['nom'] . "';</script>";
  } else if (isset($_POST['supprimer'])) {
    echo "<script>window.location.href = '/messagerie.php?profil=" . $_SESSION['prenom'] . "%20" . $_SESSION['nom'] . "';</script>";
  }

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
    messages.id AS id,
    messages.vu AS vu
    FROM identifiants INNER JOIN messages ON identifiants.email = messages.email_destinataire WHERE identifiants.id = ? ORDER BY messages.vu, messages.date DESC;");
    $query->execute(array($_SESSION['id']));

    if (isset($_POST['button']) and !empty($_POST['texte']) and isset($_POST['expediteur'])) {
      date_default_timezone_set('Europe/Paris');

      $date = date('Y-m-d H:i:s');
      $texte = htmlspecialchars($_POST['texte']);

      $req = $db->prepare("INSERT INTO messages (nom, prenom,email_destinataire, email_expediteur,`message`, `date`, vu) VALUES (?,?,?,?,?,?,?);");
      $req->execute(array($_SESSION['nom'], $_SESSION['prenom'], $_POST['expediteur'], $_SESSION['email'], $texte, $date, 0));
      $req->closeCursor();
      echo "<script>alert('Message envoyé !');</script>";
      echo "<script>window.location.href = '/messagerie.php?profil=" . $_SESSION['prenom'] . "%20" . $_SESSION['nom'] . "';</script>";
    }
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
          <h5 style="text-align:center;margin-top:2.5%;">Messages</h5>


          <form action="" method="POST">
            <div id="div-supprimer" style="display: inline-flex;align-items: center;width: 100%;place-content: space-between;margin-bottom: 1.5%;">
              <a class='link' href="/envoyer-message.php?profil=<?= $_SESSION['prenom'] . "%20" . $_SESSION['nom'] ?>" style="cursor:pointer; width:max-content; float:right;">
                <img style="width:2rem" src=" public/images/message.png" alt=""><span style="font-size:12px;cursor:pointer;padding-left:5px;">Envoyer un message</span>
              </a>
              <input class="btn btn-link" type="submit" name="supprimer" value="Supprimer" style="padding:0">
            </div>
            <?php
              while ($donnee = $query->fetch()) {
                require "message-texte.php";
              }
              ?>
          </form>
        </article>
      </div>
    </div>
  </div>


<?php } else header("Location: connexion.php"); ?>

<!-- Modal -->
<div class="modal fade" id="modalMessagerie" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <form action="" method="POST">
        <fieldset>
          <div class="modal-header" style="display:flex; align-items:center; justify-content:flex-start;">
            <img class="card-image" src="" alt="image" id="modal-image">
            <h5 class="modal-title" id="destinataire" style="margin-left:2.5%;">Destinataire</h5>
          </div>
          <input type="email" style="display:none" id="modal-email" value="" name="expediteur">
          <div class="form-group" style="margin-top:0.5%;width:100%;padding:5px 15px 5px 15px;" id="message"></div>
          <div class="form-group">
            <textarea required cols="30" rows="5" type="text" name="texte" id="message" class="form-control" placeholder="Votre message ..."></textarea>
            <input style="margin: 2.5%;float: right;padding: 5px 10px 5px 10px;" type="submit" value="Répondre" name="button" class="btn btn-success">
          </div>
        </fieldset>
        <div class="modal-footer" style="align-items:center;">
          <div style="width:100%;">
            <legend id="modal-date" style="text-align:left; font-size:12px; margin-bottom:0 !important;">
            </legend>
          </div>
          <input type="number" style="display:none" id="modal-id" value="">
          <input type="submit" class="btn btn-secondary" id="btn-fermer" data-dismiss="modal" value="Fermer">
        </div>
      </form>
    </div>
  </div>
</div>

<?php require "script.php"; ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<script>
  var msg = document.getElementsByClassName('message-texte');

  for (let i = 0; i < msg.length; i++) {
    msg[i].onclick = () => {
      let message = document.getElementsByClassName('message')[i].textContent;
      let destinataire = document.getElementsByClassName('destinataire')[i].textContent;
      let photo = document.getElementsByClassName('photo-message')[i].src;
      let date = document.getElementsByClassName('message-date')[i].textContent;
      let email = document.getElementsByClassName('message-email')[i].textContent;
      let id = document.getElementsByClassName('message-texte')[i].id;

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

      document.getElementById('destinataire').innerText = destinataire;
      document.getElementById('message').innerText = message;
      document.getElementById('modal-image').src = photo;
      if (date != "Invalid Date") {
        document.getElementById('modal-date').innerText = "Envoyé le " + date;
      }
      document.getElementById('modal-email').value = email;
      document.getElementById('modal-id').value = id;
    }
  }

  $(document).ready(function() {
    $(".message-texte").click(function(event) {
      let id = $(this).attr("id");

      jQuery.ajax({
        method: "POST",
        data: {
          'id': id
        },
        url: "updAjax.php",
        cache: false,
        success: function(response) {
          $("#" + id + " .notif").css("display", "none");
          $("#" + id + " .message").attr("style", "color : rgb(124, 124, 124) !important");
        }
      });

    });

  });
</script>

<!-- FOOTER -->
<?php require "footer.php"; ?>