<?php if (isset($_GET['categorie'])) { ?>

  <div class="card mb-3 card-affiche" data-target="#modalAffiche" data-toggle="modal" id="<?= $donnee['id'] ?>">
    <h3 class="card-header"><?= $donnee['titre'] ?></h3>
    <div class="card-body">
      <h5 class="card-title" style="display:none;"><?= $donnee['auteur']; ?></h5>
      <img class="card-image " src="<?= $donnee['image']; ?>" alt="image">
    </div>
    <div class="card-footer text-muted" style="text-align:right; font-size:12px; display:none;">
      <?= $donnee['temps']; ?>
    </div>
  </div>

<?php } ?>